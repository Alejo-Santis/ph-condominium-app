<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfig;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentConfigController extends Controller
{
    public function edit(Property $property)
    {
        $config = $property->paymentConfig;

        return Inertia::render('PaymentConfig/Edit', [
            'property' => $property,
            'config'   => $config ? [
                'uuid'             => $config->uuid,
                'wompi_public_key' => $config->wompi_public_key,
                // Sensitive keys: only indicate if they are already set
                'has_private_key'  => ! empty($config->wompi_private_key),
                'has_secret'       => ! empty($config->wompi_secret),
                'has_webhook'      => ! empty($config->webhook_secret),
                'currency'         => $config->currency,
                'is_active'        => $config->is_active,
            ] : null,
        ]);
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'wompi_public_key'  => 'required|string|max:200',
            'wompi_private_key' => 'nullable|string|max:200',
            'wompi_secret'      => 'nullable|string|max:200',
            'webhook_secret'    => 'nullable|string|max:200',
            'currency'          => 'required|string|max:10',
            'is_active'         => 'boolean',
        ]);

        $config = $property->paymentConfig;

        if ($config) {
            // Only update sensitive fields if a new value was actually provided
            $data = ['wompi_public_key' => $validated['wompi_public_key'],
                     'currency'         => $validated['currency'],
                     'is_active'        => $validated['is_active'] ?? $config->is_active];

            if (! empty($validated['wompi_private_key'])) {
                $data['wompi_private_key'] = $validated['wompi_private_key'];
            }
            if (! empty($validated['wompi_secret'])) {
                $data['wompi_secret'] = $validated['wompi_secret'];
            }
            if (! empty($validated['webhook_secret'])) {
                $data['webhook_secret'] = $validated['webhook_secret'];
            }

            $config->update($data);
        } else {
            $property->paymentConfig()->create([
                'wompi_public_key'  => $validated['wompi_public_key'],
                'wompi_private_key' => $validated['wompi_private_key'] ?? null,
                'wompi_secret'      => $validated['wompi_secret'] ?? null,
                'webhook_secret'    => $validated['webhook_secret'] ?? null,
                'currency'          => $validated['currency'],
                'is_active'         => $validated['is_active'] ?? false,
            ]);
        }

        return redirect("/properties/{$property->uuid}")
            ->with('success', 'Configuración de Wompi guardada.');
    }
}
