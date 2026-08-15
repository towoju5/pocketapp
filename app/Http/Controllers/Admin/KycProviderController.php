<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycProvider;
use Illuminate\Http\Request;

class KycProviderController extends Controller
{
    /**
     * Per-provider credential field names -> admin-facing labels. Mirrors
     * Admin\PaymentProviderController::CREDENTIAL_FIELDS — drives the
     * dynamic form in admin/kyc-providers/edit.blade.php.
     */
    private const CREDENTIAL_FIELDS = [
        'didit' => [
            'api_key' => 'API key',
            'webhook_secret' => 'Webhook signing secret',
        ],
        'sumsub' => [
            'app_token' => 'App token',
            'secret_key' => 'Secret key',
            'webhook_secret' => 'Webhook signing secret',
        ],
        'persona' => [
            'inquiry_template_id' => 'Inquiry template ID',
            'environment_id' => 'Environment ID',
            'webhook_secret' => 'Webhook signing secret',
        ],
    ];

    /** Non-secret settings stored alongside credentials. */
    private const CONFIG_FIELDS = [
        'didit' => [
            'workflow_id' => 'Workflow ID',
            'base_url' => 'API base URL (leave blank for default)',
        ],
        'sumsub' => [
            'level_name' => 'Verification level name',
            'base_url' => 'API base URL (leave blank for default)',
            'link_ttl_seconds' => 'Verification link TTL, seconds (default 1800)',
        ],
        'persona' => [],
    ];

    public function index()
    {
        $providers = KycProvider::orderBy('display_name')->get();

        return view('admin.kyc-providers.index', compact('providers'));
    }

    public function edit(KycProvider $kycProvider)
    {
        $credentialFields = self::CREDENTIAL_FIELDS[$kycProvider->slug] ?? [];
        $configFields = self::CONFIG_FIELDS[$kycProvider->slug] ?? [];
        $webhookUrl = $kycProvider->slug === 'manual' ? null : route('webhooks.kyc', ['slug' => $kycProvider->slug]);

        return view('admin.kyc-providers.edit', compact('kycProvider', 'credentialFields', 'configFields', 'webhookUrl'));
    }

    public function update(Request $request, KycProvider $kycProvider)
    {
        $validated = $request->validate([
            'credentials' => 'array',
            'config' => 'array',
        ]);

        $kycProvider->update([
            'credentials' => array_filter($validated['credentials'] ?? []),
            'config' => array_filter($validated['config'] ?? []),
        ]);

        // Only one provider is ever active at a time — activating this one
        // deactivates every other row. Unlike payment gateways (several can
        // run side by side), a platform verifies identity against a single
        // standard at once; KycController::create()/start() only ever look
        // at "the" active row.
        if ($request->boolean('is_active')) {
            KycProvider::where('id', '!=', $kycProvider->id)->update(['is_active' => false]);
            $kycProvider->update(['is_active' => true]);
        } else {
            $kycProvider->update(['is_active' => false]);
        }

        return redirect()->route('admin.kyc-providers.index')
            ->with('success', "{$kycProvider->display_name} updated.");
    }
}
