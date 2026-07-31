<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by any App\Services\Payments\* gateway on a verification failure,
 * missing credentials, or a rejected API call. Callers must treat this as
 * "nothing happened" — never mark a Deposit/Payout as completed when this
 * is thrown.
 */
class PaymentGatewayException extends RuntimeException
{
}
