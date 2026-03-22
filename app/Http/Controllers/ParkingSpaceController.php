<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpace;
use App\Models\Property;
use App\Models\Tower;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ParkingSpaceController extends Controller
{
    public function index()
    {
        $parkingSpaces = ParkingSpace::with('property', 'tower', 'unit')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('ParkingSpaces/Index', [
            'parkingSpaces' => $parkingSpaces,
        ]);
    }

    public function create()
    {
        return Inertia::render('ParkingSpaces/Create', [
            'properties' => Property::orderBy('name')->get(),
            'towers'     => Tower::with('property')->orderBy('name')->get(),
            'units'      => Unit::with('tower')->orderBy('number')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tower_id'    => 'nullable|exists:towers,id',
            'unit_id'     => 'nullable|exists:units,id',
            'code'        => [
                'required', 'string', 'max:20',
                Rule::unique('parking_spaces')->where('property_id', $request->property_id),
            ],
            'type'        => 'required|in:vehicle,motorcycle,bicycle',
            'status'      => 'required|in:available,assigned,blocked',
        ]);

        ParkingSpace::create($validated);

        return redirect()->route('parking-spaces.index')
            ->with('success', 'Parqueadero creado exitosamente.');
    }

    public function show(ParkingSpace $parkingSpace)
    {
        $parkingSpace->load('property', 'tower', 'unit.people');

        return Inertia::render('ParkingSpaces/Show', [
            'parkingSpace' => $parkingSpace,
        ]);
    }

    public function edit(ParkingSpace $parkingSpace)
    {
        return Inertia::render('ParkingSpaces/Edit', [
            'parkingSpace' => $parkingSpace->load('property', 'tower', 'unit'),
            'properties'   => Property::orderBy('name')->get(),
            'towers'       => Tower::with('property')->orderBy('name')->get(),
            'units'        => Unit::with('tower')->orderBy('number')->get(),
        ]);
    }

    public function update(Request $request, ParkingSpace $parkingSpace)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tower_id'    => 'nullable|exists:towers,id',
            'unit_id'     => 'nullable|exists:units,id',
            'code'        => [
                'required', 'string', 'max:20',
                Rule::unique('parking_spaces')
                    ->where('property_id', $request->property_id)
                    ->ignore($parkingSpace->id),
            ],
            'type'        => 'required|in:vehicle,motorcycle,bicycle',
            'status'      => 'required|in:available,assigned,blocked',
        ]);

        $parkingSpace->update($validated);

        return redirect()->route('parking-spaces.show', $parkingSpace)
            ->with('success', 'Parqueadero actualizado exitosamente.');
    }

    public function destroy(ParkingSpace $parkingSpace)
    {
        $parkingSpace->delete();

        return redirect()->route('parking-spaces.index')
            ->with('success', 'Parqueadero eliminado exitosamente.');
    }
}
