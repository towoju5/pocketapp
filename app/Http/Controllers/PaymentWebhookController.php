<?php

namespace App\Http\Controllers;

use App\Exceptions\PaymentGatewayException;
use App\Models\PaymentProvider;
use App\Services\Payments\PaymentGatewayResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Single entry point for every gateway's webhook — CSRF-exempt (see
     * routes/web.php), unauthenticated by nature (server-to-server).
     * Verification/crediting all happens inside the resolved gateway; this
     * only decides the HTTP response each provider's retry policy expects.
     */
    public function handle(string $slug, Request $request)
    {
        $provider = PaymentProvider::where('slug', $slug)->where('is_active', true)->first();

        if (! $provider) {
            return response('', 404);
        }

        try {
            PaymentGatewayResolver::resolve($provider)->handleWebhook($provider, $request);
        } catch (PaymentGatewayException $e) {
            // Verification/config failures — a real signal, not a transient
            // error. Non-2xx so this is visibly distinct from "handled".
            Log::warning("Payment webhook rejected ({$slug}): ".$e->getMessage());

            return response('', 400);
        } catch (\Throwable $e) {
            Log::error("Payment webhook error ({$slug}): ".$e->getMessage());
            report($e);

            return response('', 500);
        }

        return response('', 200);
    }
}
