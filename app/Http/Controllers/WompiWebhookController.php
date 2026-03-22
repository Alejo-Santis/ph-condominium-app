<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WompiWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload  = $request->all();
        $checksum = $request->header('X-Event-Checksum', '');
        $reference = data_get($payload, 'data.transaction.reference');

        if (! $reference) {
            return response()->json(['message' => 'No reference in payload'], 200);
        }

        $transaction = PaymentTransaction::with('charge.unit.tower.property.paymentConfig')
            ->where('wompi_reference', $reference)
            ->first();

        if (! $transaction) {
            Log::warning("Wompi webhook: transaction not found for reference [{$reference}]");
            return response()->json(['message' => 'Not found'], 200);
        }

        $config = $transaction->charge->unit?->tower?->property?->paymentConfig;

        if (! $config) {
            Log::warning("Wompi webhook: no PaymentConfig for charge [{$transaction->charge_id}]");
            return response()->json(['message' => 'No config'], 200);
        }

        if (! $config->verifyWebhookSignature($payload, $checksum)) {
            Log::warning("Wompi webhook: invalid signature for reference [{$reference}]");
            return response()->json(['message' => 'Invalid signature'], 200);
        }

        $transaction->processWebhook($payload);

        return response()->json(['message' => 'OK'], 200);
    }
}
