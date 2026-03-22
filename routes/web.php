<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TowerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\PaymentConfigController;
use App\Models\Property;
use App\Models\Tower;
use App\Models\Unit;
use App\Models\Person;
use App\Models\Charge;
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
    return Inertia::render('Dashboard', [
        'stats' => [
            'properties'       => Property::count(),
            'units'            => Unit::count(),
            'persons'          => Person::count(),
            'pending_charges'  => Charge::where('status', 'pending')->count(),
            'overdue_charges'  => Charge::where('status', 'overdue')->count(),
            'paid_this_month'  => Charge::where('status', 'paid')
                ->whereMonth('billing_month', now()->month)
                ->whereYear('billing_month', now()->year)
                ->sum('amount'),
        ],
        'recent_charges' => Charge::with('unit.tower', 'person')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(),
    ]);
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

    // Payment Config (per property)
    Route::get('/properties/{property}/payment-config', [PaymentConfigController::class, 'edit'])->name('payment-config.edit');
    Route::put('/properties/{property}/payment-config', [PaymentConfigController::class, 'update'])->name('payment-config.update');
});

require __DIR__.'/auth.php';
