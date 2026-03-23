<?php

use App\Http\Controllers\Api\ChargeController;
use App\Http\Controllers\Api\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Portal PH
|--------------------------------------------------------------------------
| Autenticación: header X-Api-Key con una clave generada desde el admin.
| Todas las rutas están bajo /api/v1 y apuntan a la propiedad del API key.
*/

Route::prefix('v1')->middleware(['api.key', 'throttle:120,1'])->group(function () {

    // ─── Unidades ─────────────────────────────────────────────────────────────
    Route::get('units', [UnitController::class, 'index']);

    // ─── Cobros ───────────────────────────────────────────────────────────────
    Route::get('charges',                   [ChargeController::class, 'index']);
    Route::post('charges',                  [ChargeController::class, 'store']);
    Route::get('charges/{reference}',       [ChargeController::class, 'show']);
});
