<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopedToProperty;
use App\Models\Charge;
use App\Models\Unit;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChargeController extends Controller
{
    use ScopedToProperty;

    private function chargeQuery()
    {
        $query = Charge::with('unit', 'unit.tower', 'person');
        $pid   = $this->scopedPropertyId();
        if ($pid) {
            $query->whereHas('unit.tower', fn($q) => $q->where('property_id', $pid));
        }
        return $query;
    }

    public function index()
    {
        $charges = $this->chargeQuery()
            ->orderBy('billing_month', 'desc')
            ->paginate(15);

        return Inertia::render('Charges/Index', [
            'charges' => $charges,
        ]);
    }

    public function create()
    {
        $pid     = $this->scopedPropertyId();
        $units   = Unit::with('tower')
            ->when($pid, fn($q) => $q->whereHas('tower', fn($q2) => $q2->where('property_id', $pid)))
            ->orderBy('number')->get();
        $persons = Person::orderBy('last_name')->get();
        return Inertia::render('Charges/Create', [
            'units'   => $units,
            'persons' => $persons,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id'       => 'required|exists:units,id',
            'person_id'     => 'required|exists:people,id',
            'concept'       => 'required|string|max:200',
            'amount'        => 'required|numeric|min:0',
            'billing_month' => 'required|date',
            'origin'        => 'in:external_api,manual',
            'status'        => 'required|in:pending,link_generated,paid,overdue,cancelled',
        ]);

        $validated['origin'] = $validated['origin'] ?? 'manual';

        Charge::create($validated);

        return redirect()->route('charges.index')
            ->with('success', 'Cobro creado exitosamente.');
    }

    public function show(Charge $charge)
    {
        $charge->load([
            'unit.tower.property.paymentConfig',
            'paymentTransactions',
            'person',
        ]);

        $config = $charge->unit?->tower?->property?->paymentConfig;

        return Inertia::render('Charges/Show', [
            'charge'           => $charge,
            'canGenerateLink'  => $config?->is_active && ! in_array($charge->status, ['paid', 'cancelled']),
            'latestPaymentUrl' => $charge->latestTransaction?->payment_url,
        ]);
    }

    public function edit(Charge $charge)
    {
        $pid     = $this->scopedPropertyId();
        $units   = Unit::with('tower')
            ->when($pid, fn($q) => $q->whereHas('tower', fn($q2) => $q2->where('property_id', $pid)))
            ->orderBy('number')->get();
        $persons = Person::orderBy('last_name')->get();
        return Inertia::render('Charges/Edit', [
            'charge'  => $charge,
            'units'   => $units,
            'persons' => $persons,
        ]);
    }

    public function update(Request $request, Charge $charge)
    {
        $validated = $request->validate([
            'unit_id'       => 'required|exists:units,id',
            'person_id'     => 'required|exists:people,id',
            'concept'       => 'required|string|max:200',
            'amount'        => 'required|numeric|min:0',
            'billing_month' => 'required|date',
            'status'        => 'required|in:pending,link_generated,paid,overdue,cancelled',
        ]);

        $charge->update($validated);

        return redirect()->route('charges.show', $charge)
            ->with('success', 'Cobro actualizado exitosamente.');
    }

    public function destroy(Charge $charge)
    {
        $charge->delete();

        return redirect()->route('charges.index')
            ->with('success', 'Cobro eliminado exitosamente.');
    }
}
