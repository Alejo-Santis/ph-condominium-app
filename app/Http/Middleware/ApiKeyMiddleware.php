<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $plainKey = $request->header('X-Api-Key');

        if (! $plainKey) {
            return response()->json([
                'error' => 'API key requerida. Incluya el header X-Api-Key.',
            ], 401);
        }

        $apiKey = ApiKey::findByPlainKey($plainKey);

        if (! $apiKey) {
            return response()->json([
                'error' => 'API key inválida o inactiva.',
            ], 401);
        }

        // Attach property to request for use in controllers
        $request->merge(['_api_property' => $apiKey->property]);
        $request->attributes->set('api_key', $apiKey);
        $request->attributes->set('api_property', $apiKey->property);

        $apiKey->touch_usage();

        return $next($request);
    }
}
