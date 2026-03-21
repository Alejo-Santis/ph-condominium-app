<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TowerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ChargeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Properties
    Route::resource('properties', PropertyController::class);

    // Towers
    Route::resource('towers', TowerController::class);

    // Units
    Route::resource('units', UnitController::class);

    // Persons (Residents)
    Route::resource('persons', PersonController::class);

    // Charges
    Route::resource('charges', ChargeController::class);
});

require __DIR__.'/auth.php';
