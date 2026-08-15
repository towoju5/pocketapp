<?php

namespace Database\Seeders;

use App\Models\KycProvider;
use Illuminate\Database\Seeder;

class KycProviderSeeder extends Seeder
{
    /**
     * Seeds the fixed catalog of identity-verification methods. 'manual'
     * starts (and stays, on an existing install) active — it's the
     * pre-existing document-upload/admin-review flow, unchanged. The three
     * hosted providers start inactive with no credentials; an admin
     * configures and activates one from /admin/kyc-providers, which
     * automatically deactivates 'manual' (see
     * Admin\KycProviderController::update — only one provider is ever
     * active at once).
     *
     * Uses firstOrCreate (not updateOrCreate), same reasoning as
     * PaymentProviderSeeder: re-running this must never reset an admin's
     * own is_active/credentials/config back to these defaults.
     */
    public function run(): void
    {
        $providers = [
            ['slug' => 'manual', 'display_name' => 'Manual Review', 'is_active' => true],
            ['slug' => 'didit', 'display_name' => 'Didit', 'is_active' => false],
            ['slug' => 'sumsub', 'display_name' => 'Sumsub', 'is_active' => false],
            ['slug' => 'persona', 'display_name' => 'Persona', 'is_active' => false],
        ];

        foreach ($providers as $provider) {
            KycProvider::firstOrCreate(
                ['slug' => $provider['slug']],
                array_merge(['credentials' => [], 'config' => []], $provider)
            );
        }
    }
}
