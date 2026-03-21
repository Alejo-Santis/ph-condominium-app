<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ChargeController extends Controller
{
    public function index()
    {
        $charges = Charge::with('unit', 'unit.tower')
            ->orderBy('due_date', 'desc')
            ->paginate(15);

        return Inertia::render('Charges/Index', [
            'charges' => $charges,
        ]);
    }

    public function create()
    {
        $units = Unit::all();
        return Inertia::render('Charges/Create', ['units' => $units]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'charge_type' => 'required|in:administration,maintenance,special,utility',
        ]);

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
        $units = Unit::all();
        return Inertia::render('Charges/Edit', [
            'charge' => $charge,
            'units' => $units,
        ]);
    }

    public function update(Request $request, Charge $charge)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'charge_type' => 'required|in:administration,maintenance,special,utility',
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
