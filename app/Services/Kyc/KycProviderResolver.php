<?php

namespace App\Services\Kyc;

use App\Contracts\KycProviderContract;
use App\Exceptions\KycVerificationException;
use App\Models\KycProvider;

class KycProviderResolver
{
    /** 'manual' is deliberately absent — it's handled directly by
     * KycController, not through this hosted-flow contract. */
    private const MAP = [
        'didit' => DiditProvider::class,
        'sumsub' => SumsubProvider::class,
        'persona' => PersonaProvider::class,
    ];

    public static function resolve(KycProvider $provider): KycProviderContract
    {
        $class = self::MAP[$provider->slug] ?? null;

        if (! $class) {
            throw new KycVerificationException("No KYC provider implementation registered for '{$provider->slug}'.");
        }

        return app($class);
    }
}
