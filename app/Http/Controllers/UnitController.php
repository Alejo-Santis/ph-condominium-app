<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Tower;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('tower', 'people')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Units/Index', [
            'units' => $units,
        ]);
    }

    public function create()
    {
        $towers = Tower::all();
        return Inertia::render('Units/Create', ['towers' => $towers]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tower_id' => 'required|exists:towers,id',
            'number'   => 'required|string|max:50',
            'floor'    => 'required|integer|min:1',
            'area_sqm' => 'nullable|numeric|min:0',
            'type'     => 'required|in:residential,commercial,mixed',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unidad creada exitosamente.');
    }

    public function show(Unit $unit)
    {
        $unit->load('tower', 'people', 'parkingSpaces');
        return Inertia::render('Units/Show', ['unit' => $unit]);
    }

    public function edit(Unit $unit)
    {
        $towers = Tower::all();
        return Inertia::render('Units/Edit', [
            'unit' => $unit,
            'towers' => $towers,
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'tower_id' => 'required|exists:towers,id',
            'number'   => 'required|string|max:50',
            'floor'    => 'required|integer|min:1',
            'area_sqm' => 'nullable|numeric|min:0',
            'type'     => 'required|in:residential,commercial,mixed',
        ]);

        $unit->update($validated);

        return redirect()->route('units.show', $unit)
            ->with('success', 'Unidad actualizada exitosamente.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unidad eliminada exitosamente.');
    }
}
