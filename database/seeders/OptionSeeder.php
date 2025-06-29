<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                "autoload" => "yes",
                "option_name" => "min_deposit_for_bonus",
                "option_value" => 1000
            ],
            [
                "autoload" => "yes",
                "option_name" => "deposit_bonus",
                "option_value" => 10
            ],
            [
                "autoload" => "yes",
                "option_name" => "deposit_bonus_type",
                "option_value" => "float" // float or fixed
            ],
            [
                "autoload" => true,
                "aoption_name" => "bitgo_minimum_payout",
                "option_value" => 5
            ],
            [
                "autoload" => true,
                "aoption_name" => "bitgo_maximum_payout",
                "option_value" => 10000
            ],
            [
                "autoload" => true,
                "aoption_name" => "bitgo_fixed_withdrawal_charges",
                "option_value" => 1
            ],
            [
                "autoload" => true,
                "aoption_name" => "bitgo_float_withdrawal_charges",
                "option_value" => 0.5
            ],
            [
                "autoload" => true,
                "aoption_name" => "bitgo_maximum_daily_payout",
                "option_value" => 100000
            ]
        ];
    }
}            