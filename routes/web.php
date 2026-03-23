<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TowerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\PaymentConfigController;
use App\Http\Controllers\GenerateLinkController;
use App\Http\Controllers\WompiWebhookController;
use App\Http\Controllers\ParkingSpaceController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\ResidentPortalController;
use App\Http\Controllers\InviteResidentController;
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

// ── Admin panel (requiere auth + rol admin) ───────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

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
    })->name('dashboard');

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

    // Generate Wompi payment link for a charge
    Route::post('/charges/{charge}/generate-link', GenerateLinkController::class)->name('charges.generate-link');

    // Parking Spaces
    Route::resource('parking-spaces', ParkingSpaceController::class);

    // API Keys (por propiedad)
    Route::prefix('properties/{property}/api-keys')->name('properties.api-keys.')->group(function () {
        Route::get('/',                     [ApiKeyController::class, 'index'])->name('index');
        Route::post('/',                    [ApiKeyController::class, 'store'])->name('store');
        Route::patch('/{apiKey}/toggle',    [ApiKeyController::class, 'toggle'])->name('toggle');
        Route::delete('/{apiKey}',          [ApiKeyController::class, 'destroy'])->name('destroy');
    });

    // Resident invitations
    Route::get('/persons/{person}/invite',  [InviteResidentController::class, 'create'])->name('residents.invite');
    Route::post('/persons/{person}/invite', [InviteResidentController::class, 'store'])->name('residents.invite.store');
});

// ── Portal del residente ──────────────────────────────────────────────────────
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/',              [ResidentPortalController::class, 'index'])->name('index');
    Route::get('/charges/{charge}', [ResidentPortalController::class, 'show'])->name('charges.show');
});

// Wompi webhook — public, no auth, no CSRF
Route::post('/webhooks/wompi', WompiWebhookController::class)->name('webhooks.wompi');

require __DIR__.'/auth.php';
