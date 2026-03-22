<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Unit;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChargeController extends Controller
{
    public function index()
    {
        $charges = Charge::with('unit', 'unit.tower', 'person')
            ->orderBy('billing_month', 'desc')
            ->paginate(15);

        return Inertia::render('Charges/Index', [
            'charges' => $charges,
        ]);
    }

    public function create()
    {
        $units   = Unit::with('tower')->orderBy('number')->get();
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
        $charge->load('unit', 'paymentTransactions');
        return Inertia::render('Charges/Show', ['charge' => $charge]);
    }

    public function edit(Charge $charge)
    {
        $units   = Unit::with('tower')->orderBy('number')->get();
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
