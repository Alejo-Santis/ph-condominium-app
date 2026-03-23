<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * GET /api/v1/units
     * Lista las unidades de la propiedad autenticada.
     * Filtros opcionales: tower_uuid, type, floor
     */
    public function index(Request $request): JsonResponse
    {
        $property = $request->attributes->get('api_property');

        $query = Unit::whereHas('tower', fn($q) => $q->where('property_id', $property->id))
            ->with('tower:id,uuid,name')
            ->orderBy('number');

        if ($request->filled('tower_uuid')) {
            $query->whereHas('tower', fn($q) => $q->where('uuid', $request->tower_uuid));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }

        $units = $query->get();

        return response()->json([
            'data' => $units->map(fn($u) => [
                'uuid'     => $u->uuid,
                'number'   => $u->number,
                'floor'    => $u->floor,
                'type'     => $u->type,
                'area_sqm' => $u->area_sqm ? (float) $u->area_sqm : null,
                'tower'    => [
                    'uuid' => $u->tower->uuid,
                    'name' => $u->tower->name,
                ],
            ]),
            'meta' => ['total' => $units->count()],
        ]);
    }
}
