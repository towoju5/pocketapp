<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // TradeSeeder::class,
            // AssetSeeder::class — seeded the iqcent-sourced catalog; its
            // collector has been removed, so fresh installs no longer need
            // (or want) 158 assets with no way to ever get a live price.
            // Left in the codebase for reference, just not called.
            BrokeretAssetSeeder::class,
            BitgoSeeder::class,
            PaymentProviderSeeder::class,
            KycProviderSeeder::class,
            // CountryStateCityTableSeeder::class,
        ]);
    }
}
