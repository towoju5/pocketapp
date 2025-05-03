<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        for ($i = 1; $i <= 10; $i++) {
            $firstName    = fake()->firstName();
            $lastName     = fake()->lastName();
            $email        = fake()->unique()->safeEmail();
            $username     = Str::slug($firstName . $lastName . $i);
            $birthday     = fake()->date('Y-m-d', '2005-01-01');
            $phone        = fake()->phoneNumber();
            $address      = fake()->address();
            $avatar       = fake()->imageUrl(200, 200, 'people');
            $kycLevel     = rand(0, 2);
            $tradeLevel   = rand(1, 5);
            $wallet       = 'qt_demo_usd';
            $activeWallet = 'qt_live_usd';

            $users[] = [
                'first_name'         => $firstName,
                'last_name'          => $lastName,
                'birthday'           => $birthday,
                'email'              => $email,
                'username'           => $username,
                'email_verified_at'  => now(),
                'password'           => Hash::make('password'),
                'phone'              => $phone,
                'address'            => [
                    'line1' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->stateAbbr(),
                    'zip' => fake()->postcode(),
                    'country' => fake()->countryCode(),
                ],
                'avatar'             => $avatar,
                'google2fa_enabled'  => rand(0, 1),
                'google2fa_secret'   => Str::random(32),
                'kyc_level'          => $kycLevel,
                'trade_level'        => $tradeLevel,
                'trade_wallet'       => $wallet,
                'active_wallet_slug' => $activeWallet,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        User::insert($users);
    }
}
