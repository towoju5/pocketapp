<?php

namespace App\Http\Controllers;

use App\Exceptions\KycVerificationException;
use App\Models\KycProvider;
use App\Services\Kyc\KycProviderResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KycWebhookController extends Controller
{
    /**
     * Single entry point for every hosted KYC provider's webhook —
     * CSRF-exempt (see routes/web.php), unauthenticated by nature
     * (server-to-server). Mirrors PaymentWebhookController exactly:
     * verification/settling all happens inside the resolved provider; this
     * only decides the HTTP response.
     */
    public function handle(string $slug, Request $request)
    {
        $provider = KycProvider::where('slug', $slug)->where('is_active', true)->first();

        if (! $provider) {
            return response('', 404);
        }

        try {
            KycProviderResolver::resolve($provider)->handleWebhook($provider, $request);
        } catch (KycVerificationException $e) {
            Log::warning("KYC webhook rejected ({$slug}): ".$e->getMessage());

            return response('', 400);
        } catch (\Throwable $e) {
            Log::error("KYC webhook error ({$slug}): ".$e->getMessage());
            report($e);

            return response('', 500);
        }

        return response('', 200);
    }
}
