<?php

namespace Database\Seeders;

use App\Models\Bitgo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BitgoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coins = [
            [
                'wallet_id' => '678eb345c4a19b24e803169034f30e5b',
                'wallet_name' => 'Bitcoin',
                'wallet_ticker' => 'TBTC',
                'type' => 'bitcoin',
                'require_memo' => false,
                'can_deposit' => true,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/1.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678ebacbb41ef5fb1036f1a9eb23b653',
                'wallet_name' => 'Holesky Testnet Ethereum',
                'wallet_ticker' => 'HTETH',
                'type' => 'ethereum',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/1027.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb3e4c4a19b24e8033763daf83f95',
                'wallet_name' => 'Testnet Eos',
                'wallet_ticker' => 'TEOS',
                'type' => 'tether',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => asset('svg/teos.svg'),
                'meta_data' => (['network' => 'eos']),
            ],
            [
                'wallet_id' => '678eb4cf13b9813a3e3ad0cdc6a6d74d',
                'wallet_name' => 'Ripple (XRP)',
                'wallet_ticker' => 'TXRP',
                'type' => 'ripple',
                'require_memo' => true,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/52.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb5331cbde2ceb5328f153a78ee93',
                'wallet_name' => 'Litecoin',
                'wallet_ticker' => 'TLTC',
                'type' => 'litecoin',
                'require_memo' => false,
                'can_deposit' => true,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/2.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb57bf843aae0747a1942c0c9b68a',
                'wallet_name' => 'Dash',
                'wallet_ticker' => 'TDASH',
                'type' => 'dash',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/131.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb5c2af2342aabad796c2806bb478',
                'wallet_name' => 'Zcash',
                'wallet_ticker' => 'TZEC',
                'type' => 'zcash',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/1437.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb61541dcfdde9cb82df46941a208',
                'wallet_name' => 'Bitcoin Cash',
                'wallet_ticker' => 'TBCH',
                'type' => 'bitcoin-cash',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/1831.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb65fdd625c55917e4b6e6db7aadd',
                'wallet_name' => 'Dogecoin',
                'wallet_ticker' => 'TDOGE',
                'type' => 'dogecoin',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/74.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb6c413b9813a3e3b3c59c11427c7',
                'wallet_name' => 'Cardano',
                'wallet_ticker' => 'TADA',
                'type' => 'cardano',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/2010.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb70cdd625c55917e6d8a0cf341bb',
                'wallet_name' => 'Stellar',
                'wallet_ticker' => 'TXLM',
                'type' => 'stellar',
                'require_memo' => true,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/512.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb77313b9813a3e3b627292746a35',
                'wallet_name' => 'Polkadot',
                'wallet_ticker' => 'TDOT',
                'type' => 'polkadot',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/6636.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb7c865174b4b36b3a8664d98c6e0',
                'wallet_name' => 'Solana',
                'wallet_ticker' => 'TSOL',
                'type' => 'solana',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/5426.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
            [
                'wallet_id' => '678eb849e787ed42be566340d23172b3',
                'wallet_name' => 'USD Coin on Solana (USDC)',
                'wallet_ticker' => 'TSOL:USDC',
                'type' => 'usdc',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/3408.png',
                'meta_data' => (['network' => 'erc20']),
            ],
            [
                'wallet_id' => '678eb899a85894b1e2bb271661b018ce',
                'wallet_name' => 'Avalanche P-Chain',
                'wallet_ticker' => 'TAVAXP',
                'type' => 'avalanche',
                'require_memo' => false,
                'can_deposit' => false,
                'can_payout' => true,
                'coin_logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/5805.png',
                'meta_data' => (['network' => 'mainnet']),
            ],
        ];


        \DB::table('bitgos')->truncate();
        foreach ($coins as $coin) {
            Bitgo::firstOrCreate([
                "wallet_name" => $coin['wallet_name'],
                "wallet_ticker" => $coin['wallet_ticker'],
            ],
            $coin);
        }
    }
}
