<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopedToProperty;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    use ScopedToProperty;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = $this->applyPropertyScope(Property::query())
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
            'name'        => 'required|string|max:200',
            'nit'         => 'required|string|max:20|unique:properties,nit',
            'address'     => 'required|string|max:300',
            'city'        => 'required|string|max:100',
            'department'  => 'required|string|max:100',
            'admin_email' => 'required|email|max:150',
            'phone'       => 'nullable|string|max:20',
        ]);

        $property = Property::create($validated);

        return redirect()->route('properties.index')
            ->with('success', 'Propiedad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $this->authorizeProperty($property->id);
        $property->loadCount('towers');

        return Inertia::render('Properties/Show', [
            'property' => $property,
            'stats' => [
                'towers'         => $property->towers_count,
                'units'          => $property->units()->count(),
                'parking_spaces' => $property->parkingSpaces()->count(),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $this->authorizeProperty($property->id);
        return Inertia::render('Properties/Edit', [
            'property' => $property,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorizeProperty($property->id);
        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'nit'         => 'required|string|max:20|unique:properties,nit,' . $property->id,
            'address'     => 'required|string|max:300',
            'city'        => 'required|string|max:100',
            'department'  => 'required|string|max:100',
            'admin_email' => 'required|email|max:150',
            'phone'       => 'nullable|string|max:20',
        ]);

        $property->update($validated);

        return redirect()->route('properties.index')
            ->with('success', 'Propiedad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $this->authorizeProperty($property->id);
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Propiedad eliminada exitosamente.');
    }
}
