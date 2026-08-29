<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadActivityController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffPerformanceController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [LeadController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {

    // -------------------------------------------------------------------------
    // Profile routes
    // -------------------------------------------------------------------------

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // -------------------------------------------------------------------------
    // Lead routes
    // -------------------------------------------------------------------------

    Route::get('/leads', [LeadController::class, 'index'])
        ->name('leads.index');


    Route::get('/leads/create', [LeadController::class, 'create'])
        ->middleware('role:admin')
        ->name('leads.create');


    Route::post('/leads', [LeadController::class, 'store'])
        ->middleware('role:admin')
        ->name('leads.store');


    // -------------------------------------------------------------------------
    // Lead Activities - Admin
    // IMPORTANT: This must come before /leads/{lead}
    // -------------------------------------------------------------------------

    Route::get('/leads/activities', [LeadActivityController::class, 'index'])
        ->middleware('role:admin')
        ->name('leads.activities.index');


    // -------------------------------------------------------------------------
    // Individual Lead routes
    // -------------------------------------------------------------------------

    Route::get('/leads/{lead}', [LeadController::class, 'show'])
        ->whereNumber('lead')
        ->name('leads.show');


    Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])
        ->whereNumber('lead')
        ->name('leads.edit');


    Route::put('/leads/{lead}', [LeadController::class, 'update'])
        ->whereNumber('lead')
        ->name('leads.update');


    // -------------------------------------------------------------------------
    // Store Lead Activity
    // -------------------------------------------------------------------------

    Route::post('/leads/{lead}/activities', [LeadActivityController::class, 'store'])
        ->whereNumber('lead')
        ->name('leads.activities.store');


    // -------------------------------------------------------------------------
    // Staff management routes
    // -------------------------------------------------------------------------

    Route::get('/staff', [StaffController::class, 'index'])
        ->middleware('role:admin')
        ->name('staff.index');


    Route::get('/staff/create', [StaffController::class, 'create'])
        ->middleware('role:admin')
        ->name('staff.create');


    Route::post('/staff', [StaffController::class, 'store'])
        ->middleware('role:admin')
        ->name('staff.store');


    Route::get('/staff/{user}/edit', [StaffController::class, 'edit'])
        ->middleware('role:admin')
        ->name('staff.edit');


    Route::put('/staff/{user}', [StaffController::class, 'update'])
        ->middleware('role:admin')
        ->name('staff.update');


    Route::get('/staff/{user}', [StaffController::class, 'show'])
        ->middleware('role:admin')
        ->name('staff.show');


    Route::get('/reports', [ReportController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('reports.index');


    Route::get('/staff-performance', [StaffPerformanceController::class, 'index'])
    ->middleware('auth')
    ->name('staff.performance');

});


require __DIR__.'/auth.php';