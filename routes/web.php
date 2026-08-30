<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadActivityController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffPerformanceController;
use App\Http\Controllers\FollowUpController;
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


    // -------------------------------------------------------------------------
    // Reports
    // -------------------------------------------------------------------------

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('role:admin')
        ->name('reports.index');


    // -------------------------------------------------------------------------
    // Staff Performance
    // -------------------------------------------------------------------------

    Route::get('/staff-performance', [StaffPerformanceController::class, 'index'])
        ->name('staff.performance');


    // -------------------------------------------------------------------------
    // Follow-Up Management - Admin
    // -------------------------------------------------------------------------

    Route::get('/follow-ups', [FollowUpController::class, 'index'])
        ->middleware('role:admin')
        ->name('follow-ups.index');


    // IMPORTANT:
    // This route must come before /follow-ups/{lead}/send
    // so "send-all" is not treated as a lead ID.

    Route::post('/follow-ups/send-all', [FollowUpController::class, 'sendAllReminders'])
        ->middleware('role:admin')
        ->name('follow-ups.send-all');


    Route::post('/follow-ups/{lead}/send', [FollowUpController::class, 'sendReminder'])
        ->whereNumber('lead')
        ->middleware('role:admin')
        ->name('follow-ups.send');


    Route::post('/follow-ups/{lead}/escalate', [FollowUpController::class, 'sendEscalation'])
        ->whereNumber('lead')
        ->middleware('role:admin')
        ->name('follow-ups.escalate');

});


require __DIR__.'/auth.php';

