<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Person;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    /**
     * GET /api/v1/charges
     * Lista los cobros de la propiedad autenticada.
     * Filtros opcionales: status, billing_month (Y-m), unit_uuid
     */
    public function index(Request $request): JsonResponse
    {
        $property = $request->attributes->get('api_property');

        $query = Charge::whereHas('unit.tower', fn($q) => $q->where('property_id', $property->id))
            ->with('unit:id,uuid,number,floor', 'person:id,uuid,first_name,last_name,email')
            ->orderByDesc('billing_month');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('billing_month')) {
            [$year, $month] = explode('-', $request->billing_month);
            $query->whereYear('billing_month', $year)->whereMonth('billing_month', $month);
        }

        if ($request->filled('unit_uuid')) {
            $query->whereHas('unit', fn($q) => $q->where('uuid', $request->unit_uuid));
        }

        $charges = $query->paginate(50);

        return response()->json([
            'data'  => $charges->map(fn($c) => $this->formatCharge($c)),
            'meta'  => [
                'total'        => $charges->total(),
                'per_page'     => $charges->perPage(),
                'current_page' => $charges->currentPage(),
                'last_page'    => $charges->lastPage(),
            ],
        ]);
    }

    /**
     * POST /api/v1/charges
     * Crea un cobro nuevo desde un sistema externo (ERP, software contable).
     */
    public function store(Request $request): JsonResponse
    {
        $property = $request->attributes->get('api_property');

        $validated = $request->validate([
            'unit_uuid'          => 'required|string',
            'person_document'    => 'required|string',
            'concept'            => 'required|string|max:200',
            'amount'             => 'required|numeric|min:0',
            'billing_month'      => 'required|date_format:Y-m',
            'external_reference' => 'nullable|string|max:100|unique:charges,external_reference',
        ]);

        // Resolve unit by UUID scoped to this property
        $unit = Unit::whereHas('tower', fn($q) => $q->where('property_id', $property->id))
            ->where('uuid', $validated['unit_uuid'])
            ->first();

        if (! $unit) {
            return response()->json(['error' => 'Unidad no encontrada en esta propiedad.'], 422);
        }

        // Resolve person by document number
        $person = Person::where('document_number', $validated['person_document'])->first();

        if (! $person) {
            return response()->json(['error' => 'Persona no encontrada con ese número de documento.'], 422);
        }

        $charge = Charge::create([
            'unit_id'            => $unit->id,
            'person_id'          => $person->id,
            'concept'            => $validated['concept'],
            'amount'             => $validated['amount'],
            'billing_month'      => $validated['billing_month'] . '-01',
            'origin'             => 'external_api',
            'external_reference' => $validated['external_reference'] ?? null,
            'status'             => 'pending',
        ]);

        $charge->load('unit:id,uuid,number', 'person:id,uuid,first_name,last_name');

        return response()->json(['data' => $this->formatCharge($charge)], 201);
    }

    /**
     * GET /api/v1/charges/{reference}
     * Consulta un cobro por su referencia externa o UUID.
     */
    public function show(Request $request, string $reference): JsonResponse
    {
        $property = $request->attributes->get('api_property');

        $charge = Charge::whereHas('unit.tower', fn($q) => $q->where('property_id', $property->id))
            ->where(fn($q) => $q->where('external_reference', $reference)->orWhere('uuid', $reference))
            ->with('unit:id,uuid,number,floor', 'person:id,uuid,first_name,last_name,email', 'latestTransaction')
            ->first();

        if (! $charge) {
            return response()->json(['error' => 'Cobro no encontrado.'], 404);
        }

        return response()->json(['data' => $this->formatCharge($charge, withTransaction: true)]);
    }

    private function formatCharge(Charge $charge, bool $withTransaction = false): array
    {
        $data = [
            'uuid'               => $charge->uuid,
            'concept'            => $charge->concept,
            'amount'             => (float) $charge->amount,
            'billing_month'      => $charge->billing_month?->format('Y-m'),
            'status'             => $charge->status,
            'origin'             => $charge->origin,
            'external_reference' => $charge->external_reference,
            'unit'               => $charge->unit ? [
                'uuid'   => $charge->unit->uuid,
                'number' => $charge->unit->number,
                'floor'  => $charge->unit->floor,
            ] : null,
            'person'             => $charge->person ? [
                'uuid'       => $charge->person->uuid,
                'first_name' => $charge->person->first_name,
                'last_name'  => $charge->person->last_name,
                'email'      => $charge->person->email,
            ] : null,
            'created_at'         => $charge->created_at->toIso8601String(),
        ];

        if ($withTransaction && $charge->relationLoaded('latestTransaction') && $charge->latestTransaction) {
            $tx = $charge->latestTransaction;
            $data['latest_transaction'] = [
                'wompi_status' => $tx->wompi_status,
                'payment_url'  => $tx->payment_url,
                'amount_paid'  => $tx->amount_paid ? (float) $tx->amount_paid : null,
                'paid_at'      => $tx->paid_at?->toIso8601String(),
            ];
        }

        return $data;
    }
}
