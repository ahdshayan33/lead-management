<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadActivityController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [LeadController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Lead routes
    Route::get('/leads', [LeadController::class, 'index'])
        ->name('leads.index');

    Route::get('/leads/create', [LeadController::class, 'create'])
        ->middleware('role:admin')
        ->name('leads.create');

    Route::post('/leads', [LeadController::class, 'store'])
        ->middleware('role:admin')
        ->name('leads.store');

    Route::get('/leads/{lead}', [LeadController::class, 'show'])
        ->name('leads.show');

    Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])
        ->name('leads.edit');

    Route::put('/leads/{lead}', [LeadController::class, 'update'])
        ->name('leads.update');

    Route::post('/leads/{lead}/activities', [LeadActivityController::class, 'store'])
    ->name('leads.activities.store');
});

require __DIR__.'/auth.php';