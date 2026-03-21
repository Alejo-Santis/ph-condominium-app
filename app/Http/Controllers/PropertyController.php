<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Properties/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'common_areas' => 'nullable|string',
        ]);

        $property = auth()->user()->properties()->create($validated);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Propiedad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->load('towers', 'units', 'parkingSpaces');

        return Inertia::render('Properties/Show', [
            'property' => $property,
            'stats' => [
                'towers' => $property->towers_count ?? 0,
                'units' => $property->units_count ?? 0,
                'parking_spaces' => $property->parkingSpaces()->count(),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        return Inertia::render('Properties/Edit', [
            'property' => $property,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'common_areas' => 'nullable|string',
        ]);

        $property->update($validated);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Propiedad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Propiedad eliminada exitosamente.');
    }
}
