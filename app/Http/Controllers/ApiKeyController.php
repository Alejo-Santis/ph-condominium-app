<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApiKeyController extends Controller
{
    /**
     * Lista las API keys de una propiedad.
     */
    public function index(Property $property)
    {
        $keys = $property->apiKeys()
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('ApiKeys/Index', [
            'property' => $property,
            'apiKeys'  => $keys,
        ]);
    }

    /**
     * Genera una nueva API key para la propiedad.
     * La clave en texto plano se retorna UNA sola vez en la sesión flash.
     */
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        ['plain_key' => $plainKey] = ApiKey::generateFor($property, $request->name);

        return redirect()
            ->route('properties.api-keys.index', $property->uuid)
            ->with('new_api_key', $plainKey)
            ->with('success', 'API Key generada. Cópiala ahora — no se mostrará de nuevo.');
    }

    /**
     * Activa o desactiva una API key.
     */
    public function toggle(Property $property, ApiKey $apiKey)
    {
        $apiKey->update(['is_active' => ! $apiKey->is_active]);

        $msg = $apiKey->is_active ? 'API Key activada.' : 'API Key desactivada.';

        return back()->with('success', $msg);
    }

    /**
     * Elimina una API key.
     */
    public function destroy(Property $property, ApiKey $apiKey)
    {
        $apiKey->delete();

        return back()->with('success', 'API Key eliminada.');
    }
}
