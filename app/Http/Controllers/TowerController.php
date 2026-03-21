<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TowerController extends Controller
{
    public function index()
    {
        $towers = Tower::with('property')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Towers/Index', [
            'towers' => $towers,
        ]);
    }

    public function create()
    {
        $properties = Property::all();
        return Inertia::render('Towers/Create', ['properties' => $properties]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:towers',
            'floors' => 'required|integer|min:1',
            'units_per_floor' => 'required|integer|min:1',
        ]);

        Tower::create($validated);

        return redirect()->route('towers.index')
            ->with('success', 'Torre creada exitosamente.');
    }

    public function show(Tower $tower)
    {
        $tower->load('property', 'units');
        return Inertia::render('Towers/Show', ['tower' => $tower]);
    }

    public function edit(Tower $tower)
    {
        $properties = Property::all();
        return Inertia::render('Towers/Edit', [
            'tower' => $tower,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, Tower $tower)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:towers,code,' . $tower->id,
            'floors' => 'required|integer|min:1',
            'units_per_floor' => 'required|integer|min:1',
        ]);

        $tower->update($validated);

        return redirect()->route('towers.show', $tower)
            ->with('success', 'Torre actualizada exitosamente.');
    }

    public function destroy(Tower $tower)
    {
        $tower->delete();

        return redirect()->route('towers.index')
            ->with('success', 'Torre eliminada exitosamente.');
    }
}
