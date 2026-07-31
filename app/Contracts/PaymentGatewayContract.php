<?php

namespace App\Contracts;

use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;

interface PaymentGatewayContract
{
    /**
     * Start a hosted-checkout deposit. Returns ['redirect_url' => string, 'reference' => string]
     * — the caller stores `reference` and redirects the user to `redirect_url`.
     * Must never credit the wallet itself; only handleWebhook() does that.
     */
    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array;

    /**
     * Verify and process an incoming webhook for this provider: check the
     * signature, resolve the Deposit by its stored provider reference, and
     * credit the wallet exactly once (idempotent — safe to receive the same
     * event more than once). Throws on verification failure; the caller
     * decides the HTTP response.
     */
    public function handleWebhook(PaymentProvider $provider, Request $request): void;

    /**
     * Send money to the payout's recipient. Returns ['reference' => string,
     * 'status' => string]. Throws on failure — the caller must NOT mark the
     * payout completed if this throws.
     */
    public function sendPayout(PaymentProvider $provider, Payout $payout): array;
}
