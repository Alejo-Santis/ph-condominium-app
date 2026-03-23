<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopedToProperty;
use App\Models\Tower;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TowerController extends Controller
{
    use ScopedToProperty;

    public function index()
    {
        $towers = $this->applyPropertyScope(Tower::with('property'), 'property_id')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Towers/Index', [
            'towers' => $towers,
        ]);
    }

    public function create()
    {
        $properties = $this->applyPropertyScope(Property::query())->get();
        return Inertia::render('Towers/Create', ['properties' => $properties]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'  => 'required|exists:properties,id',
            'name'         => 'required|string|max:200',
            'floors'       => 'required|integer|min:1',
            'has_elevator' => 'boolean',
            'has_parking'  => 'boolean',
            'description'  => 'nullable|string|max:500',
        ]);

        Tower::create($validated);

        return redirect()->route('towers.index')
            ->with('success', 'Torre creada exitosamente.');
    }

    public function show(Tower $tower)
    {
        $this->authorizeProperty($tower->property_id);
        $tower->load('property', 'units');
        return Inertia::render('Towers/Show', ['tower' => $tower]);
    }

    public function edit(Tower $tower)
    {
        $this->authorizeProperty($tower->property_id);
        $properties = $this->applyPropertyScope(Property::query())->get();
        return Inertia::render('Towers/Edit', [
            'tower' => $tower,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, Tower $tower)
    {
        $this->authorizeProperty($tower->property_id);
        $validated = $request->validate([
            'property_id'  => 'required|exists:properties,id',
            'name'         => 'required|string|max:200',
            'floors'       => 'required|integer|min:1',
            'has_elevator' => 'boolean',
            'has_parking'  => 'boolean',
            'description'  => 'nullable|string|max:500',
        ]);

        $tower->update($validated);

        return redirect()->route('towers.show', $tower)
            ->with('success', 'Torre actualizada exitosamente.');
    }

    public function destroy(Tower $tower)
    {
        $this->authorizeProperty($tower->property_id);
        $tower->delete();

        return redirect()->route('towers.index')
            ->with('success', 'Torre eliminada exitosamente.');
    }
}
