<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by any App\Services\Kyc\* provider on a signature/verification
 * failure, missing credentials, or a rejected API call. Mirrors
 * PaymentGatewayException — callers must not mark a KycVerification
 * verified/rejected when this is thrown.
 */
class KycVerificationException extends RuntimeException
{
}
