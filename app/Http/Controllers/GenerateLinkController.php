<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GenerateLinkController extends Controller
{
    public function __invoke(Charge $charge)
    {
        $charge->load('unit.tower.property.paymentConfig', 'person');

        $config = $charge->unit?->tower?->property?->paymentConfig;

        if (! $config || ! $config->is_active) {
            return back()->with('error', 'Esta propiedad no tiene una configuración de Wompi activa. Configúrala primero en la sección de propiedades.');
        }

        if (in_array($charge->status, ['paid', 'cancelled'])) {
            return back()->with('error', 'No se puede generar un link para un cobro ' . ($charge->status === 'paid' ? 'ya pagado.' : 'cancelado.'));
        }

        $reference = 'PH-' . strtoupper(Str::random(10));

        $baseUrl = app()->isProduction()
            ? 'https://api.wompi.co/v1'
            : 'https://sandbox.wompi.co/v1';

        $response = Http::withToken($config->wompi_private_key)
            ->post("{$baseUrl}/payment_links", [
                'name'             => $charge->concept,
                'description'      => "Cobro #{$charge->id} — " . ($charge->billing_month?->format('F Y') ?? ''),
                'single_use'       => true,
                'collect_shipping' => false,
                'currency'         => $config->currency,
                'amount_in_cents'  => (int) round($charge->amount * 100),
                'redirect_url'     => url("/charges/{$charge->uuid}"),
                'customer_data'    => [
                    'customer_requires_email' => true,
                ],
            ]);

        if (! $response->successful()) {
            $reason = $response->json('error.reason') ?? $response->json('message') ?? 'Error desconocido';
            return back()->with('error', "Error al generar el link en Wompi: {$reason}");
        }

        $data = $response->json('data');

        DB::transaction(function () use ($charge, $data, $reference) {
            PaymentTransaction::create([
                'charge_id'       => $charge->id,
                'wompi_link_id'   => $data['id'],
                'wompi_reference' => $reference,
                'payment_url'     => $data['url'] ?? null,
                'wompi_status'    => null,
            ]);

            $charge->markLinkGenerated();
        });

        return back()->with('success', 'Link de pago generado exitosamente.');
    }
}
