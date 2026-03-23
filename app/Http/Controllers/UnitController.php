<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopedToProperty;
use App\Models\Unit;
use App\Models\Tower;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitController extends Controller
{
    use ScopedToProperty;

    private function unitQuery()
    {
        $query = Unit::with('tower', 'people');
        $pid   = $this->scopedPropertyId();
        if ($pid) {
            $query->whereHas('tower', fn($q) => $q->where('property_id', $pid));
        }
        return $query;
    }

    public function index()
    {
        $units = $this->unitQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Units/Index', [
            'units' => $units,
        ]);
    }

    public function create()
    {
        $towers = $this->applyPropertyScope(Tower::query(), 'property_id')->get();
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
        $this->authorizeProperty($unit->tower->property_id);
        $unit->load('tower', 'people', 'parkingSpaces');
        return Inertia::render('Units/Show', ['unit' => $unit]);
    }

    public function edit(Unit $unit)
    {
        $this->authorizeProperty($unit->tower->property_id);
        $towers = $this->applyPropertyScope(Tower::query(), 'property_id')->get();
        return Inertia::render('Units/Edit', [
            'unit' => $unit,
            'towers' => $towers,
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $this->authorizeProperty($unit->tower->property_id);
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
        $this->authorizeProperty($unit->tower->property_id);
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unidad eliminada exitosamente.');
    }
}
