<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::with('units')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Persons/Index', [
            'persons' => $persons,
        ]);
    }

    public function create()
    {
        $units = Unit::all();
        return Inertia::render('Persons/Create', ['units' => $units]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:cc,ce,pasaporte,nit',
            'document_number' => 'required|string|max:50|unique:people',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:people',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'type' => 'required|in:owner,tenant,family_member',
        ]);

        Person::create($validated);

        return redirect()->route('persons.index')
            ->with('success', 'Persona creada exitosamente.');
    }

    public function show(Person $person)
    {
        $person->load('units', 'charges');
        return Inertia::render('Persons/Show', ['person' => $person]);
    }

    public function edit(Person $person)
    {
        $units = Unit::all();
        return Inertia::render('Persons/Edit', [
            'person' => $person,
            'units' => $units,
        ]);
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:cc,ce,pasaporte,nit',
            'document_number' => 'required|string|max:50|unique:people,document_number,' . $person->id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:people,email,' . $person->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'type' => 'required|in:owner,tenant,family_member',
        ]);

        $person->update($validated);

        return redirect()->route('persons.show', $person)
            ->with('success', 'Persona actualizada exitosamente.');
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('persons.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }
}
