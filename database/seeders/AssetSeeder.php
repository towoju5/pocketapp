<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            'forex' => [
                [
                    "description" => "Oanda GBP/NZD",
                    "displaySymbol" => "GBP/NZD",
                    "symbol" => "OANDA:GBP_NZD",
                ],
                [
                    "description" => "Oanda USD/PLN",
                    "displaySymbol" => "USD/PLN",
                    "symbol" => "OANDA:USD_PLN",
                ],
                [
                    "description" => "Oanda USD/DKK",
                    "displaySymbol" => "USD/DKK",
                    "symbol" => "OANDA:USD_DKK",
                ],
                [
                    "description" => "Oanda EUR/AUD",
                    "displaySymbol" => "EUR/AUD",
                    "symbol" => "OANDA:EUR_AUD",
                ],
                [
                    "description" => "Oanda EUR/USD",
                    "displaySymbol" => "EUR/USD",
                    "symbol" => "OANDA:EUR_USD",
                ],
                [
                    "description" => "Oanda GBP/USD",
                    "displaySymbol" => "GBP/USD",
                    "symbol" => "OANDA:GBP_USD",
                ],
            ],
            'commodities' => [
                [
                    "description" => "Oanda Silver/GBP",
                    "displaySymbol" => "XAG/GBP",
                    "symbol" => "OANDA:XAG_GBP",
                ],
                [
                    "description" => "Oanda Gold/CHF",
                    "displaySymbol" => "XAU/CHF",
                    "symbol" => "OANDA:XAU_CHF",
                ],
                [
                    "description" => "Oanda West Texas Oil",
                    "displaySymbol" => "WTICO/USD",
                    "symbol" => "OANDA:WTICO_USD",
                ],
                [
                    "description" => "Oanda Brent Oil",
                    "displaySymbol" => "BCO/USD",
                    "symbol" => "OANDA:BCO_USD",
                ],
            ],
            'indices' => [
                [
                    "description" => "Oanda UK 100",
                    "displaySymbol" => "UK100/GBP",
                    "symbol" => "OANDA:UK100_GBP",
                ],
                [
                    "description" => "Oanda France 40",
                    "displaySymbol" => "FR40/EUR",
                    "symbol" => "OANDA:FR40_EUR",
                ],
                [
                    "description" => "Oanda Germany 30",
                    "displaySymbol" => "DE30/EUR",
                    "symbol" => "OANDA:DE30_EUR",
                ],
                [
                    "description" => "Oanda US Wall St 30",
                    "displaySymbol" => "US30/USD",
                    "symbol" => "OANDA:US30_USD",
                ],
                [
                    "description" => "Oanda US Tech 100",
                    "displaySymbol" => "NAS100/USD",
                    "symbol" => "OANDA:NAS100_USD",
                ],
                [
                    "description" => "Oanda US SPX 500",
                    "displaySymbol" => "SPX500/USD",
                    "symbol" => "OANDA:SPX500_USD",
                ],
            ],
            'cryptocurrencies' => [
                [
                    "description" => "COINBASE FIS-USDT",
                    "displaySymbol" => "FIS-USDT",
                    "symbol" => "COINBASE:FIS-USDT"
                ],
                [
                    "description" => "COINBASE ROSE-USDT",
                    "displaySymbol" => "ROSE-USDT",
                    "symbol" => "COINBASE:ROSE-USDT"
                ],
                [
                    "description" => "COINBASE DYP-USD",
                    "displaySymbol" => "DYP-USD",
                    "symbol" => "COINBASE:DYP-USD"
                ],
                [
                    "description" => "COINBASE QSP-USDT",
                    "displaySymbol" => "QSP-USDT",
                    "symbol" => "COINBASE:QSP-USDT"
                ],
                [
                    "description" => "COINBASE SNX-GBP",
                    "displaySymbol" => "SNX-GBP",
                    "symbol" => "COINBASE:SNX-GBP"
                ],
                [
                    "description" => "COINBASE SHPING-USDT",
                    "displaySymbol" => "SHPING-USDT",
                    "symbol" => "COINBASE:SHPING-USDT"
                ],
                [
                    "description" => "COINBASE G/USD",
                    "displaySymbol" => "G/USD",
                    "symbol" => "COINBASE:G-USD"
                ],
                [
                    "description" => "COINBASE DOT-USDT",
                    "displaySymbol" => "DOT-USDT",
                    "symbol" => "COINBASE:DOT-USDT"
                ],
                [
                    "description" => "COINBASE HFT-USDT",
                    "displaySymbol" => "HFT-USDT",
                    "symbol" => "COINBASE:HFT-USDT"
                ],
                [
                    "description" => "COINBASE XYO-USDT",
                    "displaySymbol" => "XYO-USDT",
                    "symbol" => "COINBASE:XYO-USDT"
                ],
                [
                    "description" => "COINBASE NKN-GBP",
                    "displaySymbol" => "NKN-GBP",
                    "symbol" => "COINBASE:NKN-GBP"
                ],
                [
                    "description" => "COINBASE AXS-EUR",
                    "displaySymbol" => "AXS-EUR",
                    "symbol" => "COINBASE:AXS-EUR"
                ],
                [
                    "description" => "COINBASE BADGER-EUR",
                    "displaySymbol" => "BADGER-EUR",
                    "symbol" => "COINBASE:BADGER-EUR"
                ],
                [
                    "description" => "COINBASE ENS-USD",
                    "displaySymbol" => "ENS-USD",
                    "symbol" => "COINBASE:ENS-USD"
                ],
                [
                    "description" => "COINBASE GRT-GBP",
                    "displaySymbol" => "GRT-GBP",
                    "symbol" => "COINBASE:GRT-GBP"
                ],
                [
                    "description" => "COINBASE SPELL-USD",
                    "displaySymbol" => "SPELL-USD",
                    "symbol" => "COINBASE:SPELL-USD"
                ],
                [
                    "description" => "COINBASE WCFG-BTC",
                    "displaySymbol" => "WCFG-BTC",
                    "symbol" => "COINBASE:WCFG-BTC"
                ],
                [
                    "description" => "COINBASE COMP-USD",
                    "displaySymbol" => "COMP-USD",
                    "symbol" => "COINBASE:COMP-USD"
                ],
                [
                    "description" => "COINBASE FORTH-EUR",
                    "displaySymbol" => "FORTH-EUR",
                    "symbol" => "COINBASE:FORTH-EUR"
                ],
                [
                    "description" => "COINBASE HOPR-USDT",
                    "displaySymbol" => "HOPR-USDT",
                    "symbol" => "COINBASE:HOPR-USDT"
                ],
                [
                    "description" => "COINBASE CRPT-USD",
                    "displaySymbol" => "CRPT-USD",
                    "symbol" => "COINBASE:CRPT-USD"
                ],
                [
                    "description" => "COINBASE BICO-USDT",
                    "displaySymbol" => "BICO-USDT",
                    "symbol" => "COINBASE:BICO-USDT"
                ],
                [
                    "description" => "COINBASE HOPR-USD",
                    "displaySymbol" => "HOPR-USD",
                    "symbol" => "COINBASE:HOPR-USD"
                ],
                [
                    "description" => "COINBASE LINK-USD",
                    "displaySymbol" => "LINK-USD",
                    "symbol" => "COINBASE:LINK-USD"
                ],
                [
                    "description" => "COINBASE DAI-USD",
                    "displaySymbol" => "DAI-USD",
                    "symbol" => "COINBASE:DAI-USD"
                ],
                [
                    "description" => "COINBASE ERN-USD",
                    "displaySymbol" => "ERN-USD",
                    "symbol" => "COINBASE:ERN-USD"
                ],
                [
                    "description" => "COINBASE WBTC-USD",
                    "displaySymbol" => "WBTC-USD",
                    "symbol" => "COINBASE:WBTC-USD"
                ],
                [
                    "description" => "COINBASE FIDA-USD",
                    "displaySymbol" => "FIDA-USD",
                    "symbol" => "COINBASE:FIDA-USD"
                ],
                [
                    "description" => "COINBASE CTX-EUR",
                    "displaySymbol" => "CTX-EUR",
                    "symbol" => "COINBASE:CTX-EUR"
                ],
                [
                    "description" => "COINBASE ARKM/USD",
                    "displaySymbol" => "ARKM/USD",
                    "symbol" => "COINBASE:ARKM-USD"
                ],
                [
                    "description" => "COINBASE BTC-GBP",
                    "displaySymbol" => "BTC-GBP",
                    "symbol" => "COINBASE:BTC-GBP"
                ],
                [
                    "description" => "COINBASE PYUSD-USD",
                    "displaySymbol" => "PYUSD-USD",
                    "symbol" => "COINBASE:PYUSD-USD"
                ],
                [
                    "description" => "COINBASE INV-USD",
                    "displaySymbol" => "INV-USD",
                    "symbol" => "COINBASE:INV-USD"
                ],
                [
                    "description" => "COINBASE JUP-USD",
                    "displaySymbol" => "JUP-USD",
                    "symbol" => "COINBASE:JUP-USD"
                ],
                [
                    "description" => "COINBASE BAT-USD",
                    "displaySymbol" => "BAT-USD",
                    "symbol" => "COINBASE:BAT-USD"
                ],
                [
                    "description" => "COINBASE REQ-BTC",
                    "displaySymbol" => "REQ-BTC",
                    "symbol" => "COINBASE:REQ-BTC"
                ],
                [
                    "description" => "COINBASE BAND-BTC",
                    "displaySymbol" => "BAND-BTC",
                    "symbol" => "COINBASE:BAND-BTC"
                ],
                [
                    "description" => "COINBASE ADA-USDT",
                    "displaySymbol" => "ADA-USDT",
                    "symbol" => "COINBASE:ADA-USDT"
                ],
                [
                    "description" => "COINBASE NMR-GBP",
                    "displaySymbol" => "NMR-GBP",
                    "symbol" => "COINBASE:NMR-GBP"
                ],
                [
                    "description" => "COINBASE MKR-USD",
                    "displaySymbol" => "MKR-USD",
                    "symbol" => "COINBASE:MKR-USD"
                ],
                [
                    "description" => "COINBASE ASM-USD",
                    "displaySymbol" => "ASM-USD",
                    "symbol" => "COINBASE:ASM-USD"
                ],
                [
                    "description" => "COINBASE POLY-USDT",
                    "displaySymbol" => "POLY-USDT",
                    "symbol" => "COINBASE:POLY-USDT"
                ],
                [
                    "description" => "COINBASE CLV-USDT",
                    "displaySymbol" => "CLV-USDT",
                    "symbol" => "COINBASE:CLV-USDT"
                ],
                [
                    "description" => "COINBASE CTSI-BTC",
                    "displaySymbol" => "CTSI-BTC",
                    "symbol" => "COINBASE:CTSI-BTC"
                ],
                [
                    "description" => "COINBASE STX-USDT",
                    "displaySymbol" => "STX-USDT",
                    "symbol" => "COINBASE:STX-USDT"
                ],
                [
                    "description" => "COINBASE UPI-USDT",
                    "displaySymbol" => "UPI-USDT",
                    "symbol" => "COINBASE:UPI-USDT"
                ],
                [
                    "description" => "COINBASE USDT-GBP",
                    "displaySymbol" => "USDT-GBP",
                    "symbol" => "COINBASE:USDT-GBP"
                ],
                [
                    "description" => "COINBASE UNI-GBP",
                    "displaySymbol" => "UNI-GBP",
                    "symbol" => "COINBASE:UNI-GBP"
                ],
                [
                    "description" => "COINBASE 1INCH-GBP",
                    "displaySymbol" => "1INCH-GBP",
                    "symbol" => "COINBASE:1INCH-GBP"
                ],
                [
                    "description" => "COINBASE ZEC-USD",
                    "displaySymbol" => "ZEC-USD",
                    "symbol" => "COINBASE:ZEC-USD"
                ],
                [
                    "description" => "COINBASE SKL-BTC",
                    "displaySymbol" => "SKL-BTC",
                    "symbol" => "COINBASE:SKL-BTC"
                ],
                [
                    "description" => "COINBASE MOBILE/USD",
                    "displaySymbol" => "MOBILE/USD",
                    "symbol" => "COINBASE:MOBILE-USD"
                ],
                [
                    "description" => "COINBASE MINA-USDT",
                    "displaySymbol" => "MINA-USDT",
                    "symbol" => "COINBASE:MINA-USDT"
                ],
                [
                    "description" => "COINBASE AXS-USDT",
                    "displaySymbol" => "AXS-USDT",
                    "symbol" => "COINBASE:AXS-USDT"
                ],
                [
                    "description" => "COINBASE INJ-USD",
                    "displaySymbol" => "INJ-USD",
                    "symbol" => "COINBASE:INJ-USD"
                ],
                [
                    "description" => "COINBASE C98-USDT",
                    "displaySymbol" => "C98-USDT",
                    "symbol" => "COINBASE:C98-USDT"
                ],
                [
                    "description" => "COINBASE API3-USD",
                    "displaySymbol" => "API3-USD",
                    "symbol" => "COINBASE:API3-USD"
                ],
                [
                    "description" => "COINBASE KRL-EUR",
                    "displaySymbol" => "KRL-EUR",
                    "symbol" => "COINBASE:KRL-EUR"
                ],
                [
                    "description" => "COINBASE SUSHI-ETH",
                    "displaySymbol" => "SUSHI-ETH",
                    "symbol" => "COINBASE:SUSHI-ETH"
                ],
                [
                    "description" => "COINBASE VTHO-USD",
                    "displaySymbol" => "VTHO-USD",
                    "symbol" => "COINBASE:VTHO-USD"
                ],
                [
                    "description" => "COINBASE CRV-GBP",
                    "displaySymbol" => "CRV-GBP",
                    "symbol" => "COINBASE:CRV-GBP"
                ],
                [
                    "description" => "COINBASE ALEO/USD",
                    "displaySymbol" => "ALEO/USD",
                    "symbol" => "COINBASE:ALEO-USD"
                ],
                [
                    "description" => "COINBASE TRAC-USD",
                    "displaySymbol" => "TRAC-USD",
                    "symbol" => "COINBASE:TRAC-USD"
                ],
                [
                    "description" => "COINBASE A8/USD",
                    "displaySymbol" => "A8/USD",
                    "symbol" => "COINBASE:A8-USD"
                ],
                [
                    "description" => "COINBASE RLY-EUR",
                    "displaySymbol" => "RLY-EUR",
                    "symbol" => "COINBASE:RLY-EUR"
                ],
                [
                    "description" => "COINBASE WAXL-USD",
                    "displaySymbol" => "WAXL-USD",
                    "symbol" => "COINBASE:WAXL-USD"
                ],
                [
                    "description" => "COINBASE USDT-USD",
                    "displaySymbol" => "USDT-USD",
                    "symbol" => "COINBASE:USDT-USD"
                ],
                [
                    "description" => "COINBASE DDX-USDT",
                    "displaySymbol" => "DDX-USDT",
                    "symbol" => "COINBASE:DDX-USDT"
                ],
                [
                    "description" => "COINBASE BOBA-USDT",
                    "displaySymbol" => "BOBA-USDT",
                    "symbol" => "COINBASE:BOBA-USDT"
                ],
                [
                    "description" => "COINBASE OMG-USD",
                    "displaySymbol" => "OMG-USD",
                    "symbol" => "COINBASE:OMG-USD"
                ],
                [
                    "description" => "COINBASE SAND-USD",
                    "displaySymbol" => "SAND-USD",
                    "symbol" => "COINBASE:SAND-USD"
                ],
                [
                    "description" => "COINBASE ICP-USD",
                    "displaySymbol" => "ICP-USD",
                    "symbol" => "COINBASE:ICP-USD"
                ],
                [
                    "description" => "COINBASE ICP-GBP",
                    "displaySymbol" => "ICP-GBP",
                    "symbol" => "COINBASE:ICP-GBP"
                ],
                [
                    "description" => "COINBASE BAL-USD",
                    "displaySymbol" => "BAL-USD",
                    "symbol" => "COINBASE:BAL-USD"
                ],
                [
                    "description" => "COINBASE REQ-USD",
                    "displaySymbol" => "REQ-USD",
                    "symbol" => "COINBASE:REQ-USD"
                ],
                [
                    "description" => "COINBASE LINK-USDT",
                    "displaySymbol" => "LINK-USDT",
                    "symbol" => "COINBASE:LINK-USDT"
                ],
                [
                    "description" => "COINBASE REP-BTC",
                    "displaySymbol" => "REP-BTC",
                    "symbol" => "COINBASE:REP-BTC"
                ],
                [
                    "description" => "COINBASE CRV-EUR",
                    "displaySymbol" => "CRV-EUR",
                    "symbol" => "COINBASE:CRV-EUR"
                ],
                [
                    "description" => "COINBASE SKL-EUR",
                    "displaySymbol" => "SKL-EUR",
                    "symbol" => "COINBASE:SKL-EUR"
                ],
                [
                    "description" => "COINBASE BTC-USDT",
                    "displaySymbol" => "BTC-USDT",
                    "symbol" => "COINBASE:BTC-USDT"
                ],
                [
                    "description" => "COINBASE ATOM-BTC",
                    "displaySymbol" => "ATOM-BTC",
                    "symbol" => "COINBASE:ATOM-BTC"
                ],
                [
                    "description" => "COINBASE LTC-GBP",
                    "displaySymbol" => "LTC-GBP",
                    "symbol" => "COINBASE:LTC-GBP"
                ],
                [
                    "description" => "COINBASE LINK-EUR",
                    "displaySymbol" => "LINK-EUR",
                    "symbol" => "COINBASE:LINK-EUR"
                ],
                [
                    "description" => "COINBASE POWR-EUR",
                    "displaySymbol" => "POWR-EUR",
                    "symbol" => "COINBASE:POWR-EUR"
                ],
                [
                    "description" => "COINBASE RAI-USD",
                    "displaySymbol" => "RAI-USD",
                    "symbol" => "COINBASE:RAI-USD"
                ],
                [
                    "description" => "COINBASE AUCTION-USD",
                    "displaySymbol" => "AUCTION-USD",
                    "symbol" => "COINBASE:AUCTION-USD"
                ],
                [
                    "description" => "COINBASE ANKR-GBP",
                    "displaySymbol" => "ANKR-GBP",
                    "symbol" => "COINBASE:ANKR-GBP"
                ],
                [
                    "description" => "COINBASE NMR-EUR",
                    "displaySymbol" => "NMR-EUR",
                    "symbol" => "COINBASE:NMR-EUR"
                ],
                [
                    "description" => "COINBASE FOX-USDT",
                    "displaySymbol" => "FOX-USDT",
                    "symbol" => "COINBASE:FOX-USDT"
                ],
                [
                    "description" => "COINBASE BADGER-USDT",
                    "displaySymbol" => "BADGER-USDT",
                    "symbol" => "COINBASE:BADGER-USDT"
                ],
                [
                    "description" => "COINBASE MINA-USD",
                    "displaySymbol" => "MINA-USD",
                    "symbol" => "COINBASE:MINA-USD"
                ],
                [
                    "description" => "COINBASE XRP-USDT",
                    "displaySymbol" => "XRP-USDT",
                    "symbol" => "COINBASE:XRP-USDT"
                ],
                [
                    "description" => "COINBASE MCO2-USDT",
                    "displaySymbol" => "MCO2-USDT",
                    "symbol" => "COINBASE:MCO2-USDT"
                ],
                [
                    "description" => "COINBASE BCH-EUR",
                    "displaySymbol" => "BCH-EUR",
                    "symbol" => "COINBASE:BCH-EUR"
                ],
                [
                    "description" => "COINBASE PERP-USD",
                    "displaySymbol" => "PERP-USD",
                    "symbol" => "COINBASE:PERP-USD"
                ],
                [
                    "description" => "COINBASE AMP-USD",
                    "displaySymbol" => "AMP-USD",
                    "symbol" => "COINBASE:AMP-USD"
                ],
                [
                    "description" => "COINBASE AIOZ-USDT",
                    "displaySymbol" => "AIOZ-USDT",
                    "symbol" => "COINBASE:AIOZ-USDT"
                ],
                [
                    "description" => "COINBASE LRC-BTC",
                    "displaySymbol" => "LRC-BTC",
                    "symbol" => "COINBASE:LRC-BTC"
                ],
                [
                    "description" => "COINBASE RGT-USD",
                    "displaySymbol" => "RGT-USD",
                    "symbol" => "COINBASE:RGT-USD"
                ],
                [
                    "description" => "COINBASE KNC-BTC",
                    "displaySymbol" => "KNC-BTC",
                    "symbol" => "COINBASE:KNC-BTC"
                ],
                [
                    "description" => "COINBASE RLC-BTC",
                    "displaySymbol" => "RLC-BTC",
                    "symbol" => "COINBASE:RLC-BTC"
                ],
                [
                    "description" => "COINBASE CGLD-GBP",
                    "displaySymbol" => "CGLD-GBP",
                    "symbol" => "COINBASE:CGLD-GBP"
                ],
                [
                    "description" => "COINBASE NU-GBP",
                    "displaySymbol" => "NU-GBP",
                    "symbol" => "COINBASE:NU-GBP"
                ],
                [
                    "description" => "COINBASE ZEN-USD",
                    "displaySymbol" => "ZEN-USD",
                    "symbol" => "COINBASE:ZEN-USD"
                ],
                [
                    "description" => "COINBASE MPL-USD",
                    "displaySymbol" => "MPL-USD",
                    "symbol" => "COINBASE:MPL-USD"
                ],
                [
                    "description" => "COINBASE IO/USD",
                    "displaySymbol" => "IO/USD",
                    "symbol" => "COINBASE:IO-USD"
                ],
                [
                    "description" => "COINBASE BLAST/USD",
                    "displaySymbol" => "BLAST/USD",
                    "symbol" => "COINBASE:BLAST-USD"
                ],
                [
                    "description" => "COINBASE UPI-USD",
                    "displaySymbol" => "UPI-USD",
                    "symbol" => "COINBASE:UPI-USD"
                ],
                [
                    "description" => "COINBASE GUSD-USD",
                    "displaySymbol" => "GUSD-USD",
                    "symbol" => "COINBASE:GUSD-USD"
                ],
                [
                    "description" => "COINBASE XTZ-USD",
                    "displaySymbol" => "XTZ-USD",
                    "symbol" => "COINBASE:XTZ-USD"
                ],
                [
                    "description" => "COINBASE FORT-USD",
                    "displaySymbol" => "FORT-USD",
                    "symbol" => "COINBASE:FORT-USD"
                ],
                [
                    "description" => "COINBASE ORN-USDT",
                    "displaySymbol" => "ORN-USDT",
                    "symbol" => "COINBASE:ORN-USDT"
                ],
                [
                    "description" => "COINBASE MINA-EUR",
                    "displaySymbol" => "MINA-EUR",
                    "symbol" => "COINBASE:MINA-EUR"
                ],
                [
                    "description" => "COINBASE XRP-BTC",
                    "displaySymbol" => "XRP-BTC",
                    "symbol" => "COINBASE:XRP-BTC"
                ],
                [
                    "description" => "COINBASE HNT-USD",
                    "displaySymbol" => "HNT-USD",
                    "symbol" => "COINBASE:HNT-USD"
                ],
                [
                    "description" => "COINBASE BTC-USDC",
                    "displaySymbol" => "BTC-USDC",
                    "symbol" => "COINBASE:BTC-USDC"
                ],
                [
                    "description" => "COINBASE SOL-USDT",
                    "displaySymbol" => "SOL-USDT",
                    "symbol" => "COINBASE:SOL-USDT"
                ],
                [
                    "description" => "COINBASE INDEX-USD",
                    "displaySymbol" => "INDEX-USD",
                    "symbol" => "COINBASE:INDEX-USD"
                ],
                [
                    "description" => "COINBASE PERP-USDT",
                    "displaySymbol" => "PERP-USDT",
                    "symbol" => "COINBASE:PERP-USDT"
                ],
                [
                    "description" => "COINBASE CHZ-GBP",
                    "displaySymbol" => "CHZ-GBP",
                    "symbol" => "COINBASE:CHZ-GBP"
                ],
                [
                    "description" => "COINBASE APE-USD",
                    "displaySymbol" => "APE-USD",
                    "symbol" => "COINBASE:APE-USD"
                ],
                [
                    "description" => "COINBASE GAL-USD",
                    "displaySymbol" => "GAL-USD",
                    "symbol" => "COINBASE:GAL-USD"
                ],
                [
                    "description" => "COINBASE OMG-GBP",
                    "displaySymbol" => "OMG-GBP",
                    "symbol" => "COINBASE:OMG-GBP"
                ],
                [
                    "description" => "COINBASE ZEN-USDT",
                    "displaySymbol" => "ZEN-USDT",
                    "symbol" => "COINBASE:ZEN-USDT"
                ],
                [
                    "description" => "COINBASE PUNDIX-USD",
                    "displaySymbol" => "PUNDIX-USD",
                    "symbol" => "COINBASE:PUNDIX-USD"
                ],
                [
                    "description" => "COINBASE BCH-USD",
                    "displaySymbol" => "BCH-USD",
                    "symbol" => "COINBASE:BCH-USD"
                ],
                [
                    "description" => "COINBASE MATIC-BTC",
                    "displaySymbol" => "MATIC-BTC",
                    "symbol" => "COINBASE:MATIC-BTC"
                ],
                [
                    "description" => "COINBASE FARM-USDT",
                    "displaySymbol" => "FARM-USDT",
                    "symbol" => "COINBASE:FARM-USDT"
                ],
                [
                    "description" => "COINBASE STORJ-USD",
                    "displaySymbol" => "STORJ-USD",
                    "symbol" => "COINBASE:STORJ-USD"
                ],
                [
                    "description" => "COINBASE SUKU-USD",
                    "displaySymbol" => "SUKU-USD",
                    "symbol" => "COINBASE:SUKU-USD"
                ],
                [
                    "description" => "COINBASE XYO-BTC",
                    "displaySymbol" => "XYO-BTC",
                    "symbol" => "COINBASE:XYO-BTC"
                ],
                [
                    "description" => "COINBASE CRO-USD",
                    "displaySymbol" => "CRO-USD",
                    "symbol" => "COINBASE:CRO-USD"
                ],
                [
                    "description" => "COINBASE NEAR-USD",
                    "displaySymbol" => "NEAR-USD",
                    "symbol" => "COINBASE:NEAR-USD"
                ],
                [
                    "description" => "COINBASE WLUNA-USDT",
                    "displaySymbol" => "WLUNA-USDT",
                    "symbol" => "COINBASE:WLUNA-USDT"
                ],
                [
                    "description" => "COINBASE ANKR-USD",
                    "displaySymbol" => "ANKR-USD",
                    "symbol" => "COINBASE:ANKR-USD"
                ],
                [
                    "description" => "COINBASE DNT-USDC",
                    "displaySymbol" => "DNT-USDC",
                    "symbol" => "COINBASE:DNT-USDC"
                ],
                [
                    "description" => "COINBASE FIDA-USDT",
                    "displaySymbol" => "FIDA-USDT",
                    "symbol" => "COINBASE:FIDA-USDT"
                ],
                [
                    "description" => "COINBASE DASH-BTC",
                    "displaySymbol" => "DASH-BTC",
                    "symbol" => "COINBASE:DASH-BTC"
                ],
                [
                    "description" => "COINBASE CVX-USD",
                    "displaySymbol" => "CVX-USD",
                    "symbol" => "COINBASE:CVX-USD"
                ],
                [
                    "description" => "COINBASE OOKI-USD",
                    "displaySymbol" => "OOKI-USD",
                    "symbol" => "COINBASE:OOKI-USD"
                ],
                [
                    "description" => "COINBASE BAND-EUR",
                    "displaySymbol" => "BAND-EUR",
                    "symbol" => "COINBASE:BAND-EUR"
                ],
                [
                    "description" => "COINBASE BOND-USDT",
                    "displaySymbol" => "BOND-USDT",
                    "symbol" => "COINBASE:BOND-USDT"
                ],
                [
                    "description" => "COINBASE IDEX-USD",
                    "displaySymbol" => "IDEX-USD",
                    "symbol" => "COINBASE:IDEX-USD"
                ],
                [
                    "description" => "COINBASE LSETH-ETH",
                    "displaySymbol" => "LSETH-ETH",
                    "symbol" => "COINBASE:LSETH-ETH"
                ],
                [
                    "description" => "COINBASE APT-USDT",
                    "displaySymbol" => "APT-USDT",
                    "symbol" => "COINBASE:APT-USDT"
                ],
                [
                    "description" => "COINBASE MLN-USD",
                    "displaySymbol" => "MLN-USD",
                    "symbol" => "COINBASE:MLN-USD"
                ],
                [
                    "description" => "COINBASE SUSHI-EUR",
                    "displaySymbol" => "SUSHI-EUR",
                    "symbol" => "COINBASE:SUSHI-EUR"
                ],
                [
                    "description" => "COINBASE MANA-ETH",
                    "displaySymbol" => "MANA-ETH",
                    "symbol" => "COINBASE:MANA-ETH"
                ],
                [
                    "description" => "COINBASE LIT-USD",
                    "displaySymbol" => "LIT-USD",
                    "symbol" => "COINBASE:LIT-USD"
                ],
                [
                    "description" => "COINBASE XLM-USDT",
                    "displaySymbol" => "XLM-USDT",
                    "symbol" => "COINBASE:XLM-USDT"
                ],
                [
                    "description" => "COINBASE AAVE-EUR",
                    "displaySymbol" => "AAVE-EUR",
                    "symbol" => "COINBASE:AAVE-EUR"
                ],
                [
                    "description" => "COINBASE ZRX-EUR",
                    "displaySymbol" => "ZRX-EUR",
                    "symbol" => "COINBASE:ZRX-EUR"
                ],
                [
                    "description" => "COINBASE IOTX-USD",
                    "displaySymbol" => "IOTX-USD",
                    "symbol" => "COINBASE:IOTX-USD"
                ],
                [
                    "description" => "COINBASE BTRST-GBP",
                    "displaySymbol" => "BTRST-GBP",
                    "symbol" => "COINBASE:BTRST-GBP"
                ],
                [
                    "description" => "COINBASE TRB-USD",
                    "displaySymbol" => "TRB-USD",
                    "symbol" => "COINBASE:TRB-USD"
                ],
                [
                    "description" => "COINBASE SOL-ETH",
                    "displaySymbol" => "SOL-ETH",
                    "symbol" => "COINBASE:SOL-ETH"
                ],
                [
                    "description" => "COINBASE FOX-USD",
                    "displaySymbol" => "FOX-USD",
                    "symbol" => "COINBASE:FOX-USD"
                ],
                [
                    "description" => "COINBASE STG-USD",
                    "displaySymbol" => "STG-USD",
                    "symbol" => "COINBASE:STG-USD"
                ],
                [
                    "description" => "COINBASE ADA-USDC",
                    "displaySymbol" => "ADA-USDC",
                    "symbol" => "COINBASE:ADA-USDC"
                ],
                [
                    "description" => "COINBASE FORTH-BTC",
                    "displaySymbol" => "FORTH-BTC",
                    "symbol" => "COINBASE:FORTH-BTC"
                ],
                [
                    "description" => "COINBASE LOOM-USDC",
                    "displaySymbol" => "LOOM-USDC",
                    "symbol" => "COINBASE:LOOM-USDC"
                ],
                [
                    "description" => "COINBASE XRP-GBP",
                    "displaySymbol" => "XRP-GBP",
                    "symbol" => "COINBASE:XRP-GBP"
                ],
                [
                    "description" => "COINBASE AUCTION-USDT",
                    "displaySymbol" => "AUCTION-USDT",
                    "symbol" => "COINBASE:AUCTION-USDT"
                ],
                [
                    "description" => "COINBASE DIA-EUR",
                    "displaySymbol" => "DIA-EUR",
                    "symbol" => "COINBASE:DIA-EUR"
                ],
                [
                    "description" => "COINBASE CVC-USD",
                    "displaySymbol" => "CVC-USD",
                    "symbol" => "COINBASE:CVC-USD"
                ],
                [
                    "description" => "COINBASE LCX-USD",
                    "displaySymbol" => "LCX-USD",
                    "symbol" => "COINBASE:LCX-USD"
                ],
                [
                    "description" => "COINBASE BONK-USD",
                    "displaySymbol" => "BONK-USD",
                    "symbol" => "COINBASE:BONK-USD"
                ],
                [
                    "description" => "COINBASE BNT-USD",
                    "displaySymbol" => "BNT-USD",
                    "symbol" => "COINBASE:BNT-USD"
                ],
                [
                    "description" => "COINBASE AAVE-USD",
                    "displaySymbol" => "AAVE-USD",
                    "symbol" => "COINBASE:AAVE-USD"
                ],
                [
                    "description" => "COINBASE FIL-EUR",
                    "displaySymbol" => "FIL-EUR",
                    "symbol" => "COINBASE:FIL-EUR"
                ],
                [
                    "description" => "COINBASE MDT-USD",
                    "displaySymbol" => "MDT-USD",
                    "symbol" => "COINBASE:MDT-USD"
                ],
                [
                    "description" => "COINBASE DOT-EUR",
                    "displaySymbol" => "DOT-EUR",
                    "symbol" => "COINBASE:DOT-EUR"
                ],
                [
                    "description" => "COINBASE ZRX-USD",
                    "displaySymbol" => "ZRX-USD",
                    "symbol" => "COINBASE:ZRX-USD"
                ],
                [
                    "description" => "COINBASE ANKR-BTC",
                    "displaySymbol" => "ANKR-BTC",
                    "symbol" => "COINBASE:ANKR-BTC"
                ],
                [
                    "description" => "COINBASE SEAM-USD",
                    "displaySymbol" => "SEAM-USD",
                    "symbol" => "COINBASE:SEAM-USD"
                ],
                [
                    "description" => "COINBASE ADA-EUR",
                    "displaySymbol" => "ADA-EUR",
                    "symbol" => "COINBASE:ADA-EUR"
                ],
                [
                    "description" => "COINBASE C98-USD",
                    "displaySymbol" => "C98-USD",
                    "symbol" => "COINBASE:C98-USD"
                ],
                [
                    "description" => "COINBASE BIT-USDT",
                    "displaySymbol" => "BIT-USDT",
                    "symbol" => "COINBASE:BIT-USDT"
                ],
                [
                    "description" => "COINBASE ADA-GBP",
                    "displaySymbol" => "ADA-GBP",
                    "symbol" => "COINBASE:ADA-GBP"
                ],
                [
                    "description" => "COINBASE BICO-USD",
                    "displaySymbol" => "BICO-USD",
                    "symbol" => "COINBASE:BICO-USD"
                ],
                [
                    "description" => "COINBASE BAND-USD",
                    "displaySymbol" => "BAND-USD",
                    "symbol" => "COINBASE:BAND-USD"
                ],
                [
                    "description" => "COINBASE MTL-USD",
                    "displaySymbol" => "MTL-USD",
                    "symbol" => "COINBASE:MTL-USD"
                ],
                [
                    "description" => "COINBASE ROSE-USD",
                    "displaySymbol" => "ROSE-USD",
                    "symbol" => "COINBASE:ROSE-USD"
                ],
                [
                    "description" => "COINBASE RLY-GBP",
                    "displaySymbol" => "RLY-GBP",
                    "symbol" => "COINBASE:RLY-GBP"
                ],
                [
                    "description" => "COINBASE RARE-USD",
                    "displaySymbol" => "RARE-USD",
                    "symbol" => "COINBASE:RARE-USD"
                ],
                [
                    "description" => "COINBASE KAVA-USD",
                    "displaySymbol" => "KAVA-USD",
                    "symbol" => "COINBASE:KAVA-USD"
                ],
                [
                    "description" => "COINBASE DIMO-USD",
                    "displaySymbol" => "DIMO-USD",
                    "symbol" => "COINBASE:DIMO-USD"
                ],
                [
                    "description" => "COINBASE GNO-USDT",
                    "displaySymbol" => "GNO-USDT",
                    "symbol" => "COINBASE:GNO-USDT"
                ],
                [
                    "description" => "COINBASE BIT-USD",
                    "displaySymbol" => "BIT-USD",
                    "symbol" => "COINBASE:BIT-USD"
                ],
                [
                    "description" => "COINBASE MATIC-USDT",
                    "displaySymbol" => "MATIC-USDT",
                    "symbol" => "COINBASE:MATIC-USDT"
                ],
                [
                    "description" => "COINBASE FIL-USD",
                    "displaySymbol" => "FIL-USD",
                    "symbol" => "COINBASE:FIL-USD"
                ],
                [
                    "description" => "COINBASE AGLD-USDT",
                    "displaySymbol" => "AGLD-USDT",
                    "symbol" => "COINBASE:AGLD-USDT"
                ],
                [
                    "description" => "COINBASE ATOM-USDT",
                    "displaySymbol" => "ATOM-USDT",
                    "symbol" => "COINBASE:ATOM-USDT"
                ],
                [
                    "description" => "COINBASE ARB-USD",
                    "displaySymbol" => "ARB-USD",
                    "symbol" => "COINBASE:ARB-USD"
                ],
                [
                    "description" => "COINBASE MSOL-USD",
                    "displaySymbol" => "MSOL-USD",
                    "symbol" => "COINBASE:MSOL-USD"
                ],
                [
                    "description" => "COINBASE BOBA-USD",
                    "displaySymbol" => "BOBA-USD",
                    "symbol" => "COINBASE:BOBA-USD"
                ],
                [
                    "description" => "COINBASE MUSE-USD",
                    "displaySymbol" => "MUSE-USD",
                    "symbol" => "COINBASE:MUSE-USD"
                ],
                [
                    "description" => "COINBASE XCN-USD",
                    "displaySymbol" => "XCN-USD",
                    "symbol" => "COINBASE:XCN-USD"
                ],
                [
                    "description" => "COINBASE LCX-EUR",
                    "displaySymbol" => "LCX-EUR",
                    "symbol" => "COINBASE:LCX-EUR"
                ],
                [
                    "description" => "COINBASE ETH-BTC",
                    "displaySymbol" => "ETH-BTC",
                    "symbol" => "COINBASE:ETH-BTC"
                ],
                [
                    "description" => "COINBASE OP-USDT",
                    "displaySymbol" => "OP-USDT",
                    "symbol" => "COINBASE:OP-USDT"
                ],
                [
                    "description" => "COINBASE SOL-EUR",
                    "displaySymbol" => "SOL-EUR",
                    "symbol" => "COINBASE:SOL-EUR"
                ],
                [
                    "description" => "COINBASE TONE-USD",
                    "displaySymbol" => "TONE-USD",
                    "symbol" => "COINBASE:TONE-USD"
                ],
                [
                    "description" => "COINBASE APE-USDT",
                    "displaySymbol" => "APE-USDT",
                    "symbol" => "COINBASE:APE-USDT"
                ],
                [
                    "description" => "COINBASE ADA-ETH",
                    "displaySymbol" => "ADA-ETH",
                    "symbol" => "COINBASE:ADA-ETH"
                ],
                [
                    "description" => "COINBASE RARI-USD",
                    "displaySymbol" => "RARI-USD",
                    "symbol" => "COINBASE:RARI-USD"
                ],
                [
                    "description" => "COINBASE PERP-EUR",
                    "displaySymbol" => "PERP-EUR",
                    "symbol" => "COINBASE:PERP-EUR"
                ],
                [
                    "description" => "COINBASE MEDIA-USDT",
                    "displaySymbol" => "MEDIA-USDT",
                    "symbol" => "COINBASE:MEDIA-USDT"
                ],
                [
                    "description" => "COINBASE REQ-EUR",
                    "displaySymbol" => "REQ-EUR",
                    "symbol" => "COINBASE:REQ-EUR"
                ],
                [
                    "description" => "COINBASE OXT-USD",
                    "displaySymbol" => "OXT-USD",
                    "symbol" => "COINBASE:OXT-USD"
                ],
                [
                    "description" => "COINBASE IMX-USD",
                    "displaySymbol" => "IMX-USD",
                    "symbol" => "COINBASE:IMX-USD"
                ],
                [
                    "description" => "COINBASE RAD-USDT",
                    "displaySymbol" => "RAD-USDT",
                    "symbol" => "COINBASE:RAD-USDT"
                ],
                [
                    "description" => "COINBASE MANA-EUR",
                    "displaySymbol" => "MANA-EUR",
                    "symbol" => "COINBASE:MANA-EUR"
                ],
                [
                    "description" => "COINBASE SHPING-EUR",
                    "displaySymbol" => "SHPING-EUR",
                    "symbol" => "COINBASE:SHPING-EUR"
                ],
                [
                    "description" => "COINBASE WBTC-BTC",
                    "displaySymbol" => "WBTC-BTC",
                    "symbol" => "COINBASE:WBTC-BTC"
                ],
                [
                    "description" => "COINBASE POWR-USD",
                    "displaySymbol" => "POWR-USD",
                    "symbol" => "COINBASE:POWR-USD"
                ],
                [
                    "description" => "COINBASE NMR-BTC",
                    "displaySymbol" => "NMR-BTC",
                    "symbol" => "COINBASE:NMR-BTC"
                ],
                [
                    "description" => "COINBASE CHZ-EUR",
                    "displaySymbol" => "CHZ-EUR",
                    "symbol" => "COINBASE:CHZ-EUR"
                ],
                [
                    "description" => "COINBASE YFI-BTC",
                    "displaySymbol" => "YFI-BTC",
                    "symbol" => "COINBASE:YFI-BTC"
                ],
                [
                    "description" => "COINBASE DOT-USD",
                    "displaySymbol" => "DOT-USD",
                    "symbol" => "COINBASE:DOT-USD"
                ],
                [
                    "description" => "COINBASE COMP-BTC",
                    "displaySymbol" => "COMP-BTC",
                    "symbol" => "COINBASE:COMP-BTC"
                ],
                [
                    "description" => "COINBASE CHZ-USD",
                    "displaySymbol" => "CHZ-USD",
                    "symbol" => "COINBASE:CHZ-USD"
                ],
                [
                    "description" => "COINBASE FORT-USDT",
                    "displaySymbol" => "FORT-USDT",
                    "symbol" => "COINBASE:FORT-USDT"
                ],
                [
                    "description" => "COINBASE XTZ-BTC",
                    "displaySymbol" => "XTZ-BTC",
                    "symbol" => "COINBASE:XTZ-BTC"
                ],
                [
                    "description" => "COINBASE XYO-USD",
                    "displaySymbol" => "XYO-USD",
                    "symbol" => "COINBASE:XYO-USD"
                ],
                [
                    "description" => "COINBASE OCEAN-USD",
                    "displaySymbol" => "OCEAN-USD",
                    "symbol" => "COINBASE:OCEAN-USD"
                ],
                [
                    "description" => "COINBASE SUSHI-BTC",
                    "displaySymbol" => "SUSHI-BTC",
                    "symbol" => "COINBASE:SUSHI-BTC"
                ],
                [
                    "description" => "COINBASE DOT-BTC",
                    "displaySymbol" => "DOT-BTC",
                    "symbol" => "COINBASE:DOT-BTC"
                ],
                [
                    "description" => "COINBASE AAVE-GBP",
                    "displaySymbol" => "AAVE-GBP",
                    "symbol" => "COINBASE:AAVE-GBP"
                ],
                [
                    "description" => "COINBASE CLV-EUR",
                    "displaySymbol" => "CLV-EUR",
                    "symbol" => "COINBASE:CLV-EUR"
                ],
                [
                    "description" => "COINBASE EOS-BTC",
                    "displaySymbol" => "EOS-BTC",
                    "symbol" => "COINBASE:EOS-BTC"
                ],
                [
                    "description" => "COINBASE OSMO-USD",
                    "displaySymbol" => "OSMO-USD",
                    "symbol" => "COINBASE:OSMO-USD"
                ],
                [
                    "description" => "COINBASE SD/USD",
                    "displaySymbol" => "SD/USD",
                    "symbol" => "COINBASE:SD-USD"
                ],
                [
                    "description" => "COINBASE DESO-EUR",
                    "displaySymbol" => "DESO-EUR",
                    "symbol" => "COINBASE:DESO-EUR"
                ],
                [
                    "description" => "COINBASE SNT-USD",
                    "displaySymbol" => "SNT-USD",
                    "symbol" => "COINBASE:SNT-USD"
                ],
                [
                    "description" => "COINBASE TRB-BTC",
                    "displaySymbol" => "TRB-BTC",
                    "symbol" => "COINBASE:TRB-BTC"
                ],
                [
                    "description" => "COINBASE NU-USD",
                    "displaySymbol" => "NU-USD",
                    "symbol" => "COINBASE:NU-USD"
                ],
                [
                    "description" => "COINBASE 1INCH-USD",
                    "displaySymbol" => "1INCH-USD",
                    "symbol" => "COINBASE:1INCH-USD"
                ],
                [
                    "description" => "COINBASE GALA-USD",
                    "displaySymbol" => "GALA-USD",
                    "symbol" => "COINBASE:GALA-USD"
                ],
                [
                    "description" => "COINBASE BTRST-USDT",
                    "displaySymbol" => "BTRST-USDT",
                    "symbol" => "COINBASE:BTRST-USDT"
                ],
                [
                    "description" => "COINBASE ALCX-EUR",
                    "displaySymbol" => "ALCX-EUR",
                    "symbol" => "COINBASE:ALCX-EUR"
                ],
                [
                    "description" => "COINBASE PRQ-USDT",
                    "displaySymbol" => "PRQ-USDT",
                    "symbol" => "COINBASE:PRQ-USDT"
                ],
                [
                    "description" => "COINBASE HBAR-USDT",
                    "displaySymbol" => "HBAR-USDT",
                    "symbol" => "COINBASE:HBAR-USDT"
                ],
                [
                    "description" => "COINBASE DEGEN/USD",
                    "displaySymbol" => "DEGEN/USD",
                    "symbol" => "COINBASE:DEGEN-USD"
                ],
                [
                    "description" => "COINBASE SYLO-USD",
                    "displaySymbol" => "SYLO-USD",
                    "symbol" => "COINBASE:SYLO-USD"
                ],
                [
                    "description" => "COINBASE AXS-USD",
                    "displaySymbol" => "AXS-USD",
                    "symbol" => "COINBASE:AXS-USD"
                ],
                [
                    "description" => "COINBASE KRL-USDT",
                    "displaySymbol" => "KRL-USDT",
                    "symbol" => "COINBASE:KRL-USDT"
                ],
                [
                    "description" => "COINBASE STORJ-BTC",
                    "displaySymbol" => "STORJ-BTC",
                    "symbol" => "COINBASE:STORJ-BTC"
                ],
                [
                    "description" => "COINBASE MULTI-USD",
                    "displaySymbol" => "MULTI-USD",
                    "symbol" => "COINBASE:MULTI-USD"
                ],
                [
                    "description" => "COINBASE SNX-USD",
                    "displaySymbol" => "SNX-USD",
                    "symbol" => "COINBASE:SNX-USD"
                ],
                [
                    "description" => "COINBASE JTO-USD",
                    "displaySymbol" => "JTO-USD",
                    "symbol" => "COINBASE:JTO-USD"
                ],
                [
                    "description" => "COINBASE GRT-BTC",
                    "displaySymbol" => "GRT-BTC",
                    "symbol" => "COINBASE:GRT-BTC"
                ],
                [
                    "description" => "COINBASE IOTX-EUR",
                    "displaySymbol" => "IOTX-EUR",
                    "symbol" => "COINBASE:IOTX-EUR"
                ],
                [
                    "description" => "COINBASE TRU-BTC",
                    "displaySymbol" => "TRU-BTC",
                    "symbol" => "COINBASE:TRU-BTC"
                ],
                [
                    "description" => "COINBASE AXL-USD",
                    "displaySymbol" => "AXL-USD",
                    "symbol" => "COINBASE:AXL-USD"
                ],
                [
                    "description" => "COINBASE RNDR-EUR",
                    "displaySymbol" => "RNDR-EUR",
                    "symbol" => "COINBASE:RNDR-EUR"
                ],
                [
                    "description" => "COINBASE XRP-EUR",
                    "displaySymbol" => "XRP-EUR",
                    "symbol" => "COINBASE:XRP-EUR"
                ],
                [
                    "description" => "COINBASE ACH-USD",
                    "displaySymbol" => "ACH-USD",
                    "symbol" => "COINBASE:ACH-USD"
                ],
                [
                    "description" => "COINBASE ARPA-USDT",
                    "displaySymbol" => "ARPA-USDT",
                    "symbol" => "COINBASE:ARPA-USDT"
                ],
                [
                    "description" => "COINBASE NEST-USDT",
                    "displaySymbol" => "NEST-USDT",
                    "symbol" => "COINBASE:NEST-USDT"
                ],
                [
                    "description" => "COINBASE FIL-BTC",
                    "displaySymbol" => "FIL-BTC",
                    "symbol" => "COINBASE:FIL-BTC"
                ],
                [
                    "description" => "COINBASE TRU-USD",
                    "displaySymbol" => "TRU-USD",
                    "symbol" => "COINBASE:TRU-USD"
                ],
                [
                    "description" => "COINBASE GYEN-USD",
                    "displaySymbol" => "GYEN-USD",
                    "symbol" => "COINBASE:GYEN-USD"
                ],
                [
                    "description" => "COINBASE MASK-USDT",
                    "displaySymbol" => "MASK-USDT",
                    "symbol" => "COINBASE:MASK-USDT"
                ],
                [
                    "description" => "COINBASE PLU-USD",
                    "displaySymbol" => "PLU-USD",
                    "symbol" => "COINBASE:PLU-USD"
                ],
                [
                    "description" => "COINBASE DDX-USD",
                    "displaySymbol" => "DDX-USD",
                    "symbol" => "COINBASE:DDX-USD"
                ],
                [
                    "description" => "COINBASE SOL-BTC",
                    "displaySymbol" => "SOL-BTC",
                    "symbol" => "COINBASE:SOL-BTC"
                ],
                [
                    "description" => "COINBASE KRL-USD",
                    "displaySymbol" => "KRL-USD",
                    "symbol" => "COINBASE:KRL-USD"
                ],
                [
                    "description" => "COINBASE FX-USD",
                    "displaySymbol" => "FX-USD",
                    "symbol" => "COINBASE:FX-USD"
                ],
                [
                    "description" => "COINBASE MATIC-GBP",
                    "displaySymbol" => "MATIC-GBP",
                    "symbol" => "COINBASE:MATIC-GBP"
                ],
                [
                    "description" => "COINBASE ENS-USDT",
                    "displaySymbol" => "ENS-USDT",
                    "symbol" => "COINBASE:ENS-USDT"
                ],
                [
                    "description" => "COINBASE XTZ-GBP",
                    "displaySymbol" => "XTZ-GBP",
                    "symbol" => "COINBASE:XTZ-GBP"
                ],
                [
                    "description" => "COINBASE TRU-USDT",
                    "displaySymbol" => "TRU-USDT",
                    "symbol" => "COINBASE:TRU-USDT"
                ],
                [
                    "description" => "COINBASE XCN-USDT",
                    "displaySymbol" => "XCN-USDT",
                    "symbol" => "COINBASE:XCN-USDT"
                ],
                [
                    "description" => "COINBASE ATOM-GBP",
                    "displaySymbol" => "ATOM-GBP",
                    "symbol" => "COINBASE:ATOM-GBP"
                ],
                [
                    "description" => "COINBASE LQTY-USD",
                    "displaySymbol" => "LQTY-USD",
                    "symbol" => "COINBASE:LQTY-USD"
                ],
                [
                    "description" => "COINBASE SUKU-USDT",
                    "displaySymbol" => "SUKU-USDT",
                    "symbol" => "COINBASE:SUKU-USDT"
                ],
                [
                    "description" => "COINBASE REN-USD",
                    "displaySymbol" => "REN-USD",
                    "symbol" => "COINBASE:REN-USD"
                ],
                [
                    "description" => "COINBASE BCH-GBP",
                    "displaySymbol" => "BCH-GBP",
                    "symbol" => "COINBASE:BCH-GBP"
                ],
                [
                    "description" => "COINBASE ETH-USD",
                    "displaySymbol" => "ETH-USD",
                    "symbol" => "COINBASE:ETH-USD"
                ],
                [
                    "description" => "COINBASE CTX-USD",
                    "displaySymbol" => "CTX-USD",
                    "symbol" => "COINBASE:CTX-USD"
                ],
                [
                    "description" => "COINBASE RAD-BTC",
                    "displaySymbol" => "RAD-BTC",
                    "symbol" => "COINBASE:RAD-BTC"
                ],
                [
                    "description" => "COINBASE SWELL/USD",
                    "displaySymbol" => "SWELL/USD",
                    "symbol" => "COINBASE:SWELL-USD"
                ],
                [
                    "description" => "COINBASE ERN-USDT",
                    "displaySymbol" => "ERN-USDT",
                    "symbol" => "COINBASE:ERN-USDT"
                ],
                [
                    "description" => "COINBASE SEI-USD",
                    "displaySymbol" => "SEI-USD",
                    "symbol" => "COINBASE:SEI-USD"
                ],
                [
                    "description" => "COINBASE UMA-BTC",
                    "displaySymbol" => "UMA-BTC",
                    "symbol" => "COINBASE:UMA-BTC"
                ],
                [
                    "description" => "COINBASE SKL-USD",
                    "displaySymbol" => "SKL-USD",
                    "symbol" => "COINBASE:SKL-USD"
                ],
                [
                    "description" => "COINBASE GALA-USDT",
                    "displaySymbol" => "GALA-USDT",
                    "symbol" => "COINBASE:GALA-USDT"
                ],
                [
                    "description" => "COINBASE DASH-USD",
                    "displaySymbol" => "DASH-USD",
                    "symbol" => "COINBASE:DASH-USD"
                ],
                [
                    "description" => "COINBASE PRQ-USD",
                    "displaySymbol" => "PRQ-USD",
                    "symbol" => "COINBASE:PRQ-USD"
                ],
                [
                    "description" => "COINBASE RONIN/USD",
                    "displaySymbol" => "RONIN/USD",
                    "symbol" => "COINBASE:RONIN-USD"
                ],
                [
                    "description" => "COINBASE LINK-ETH",
                    "displaySymbol" => "LINK-ETH",
                    "symbol" => "COINBASE:LINK-ETH"
                ],
                [
                    "description" => "COINBASE LRC-USD",
                    "displaySymbol" => "LRC-USD",
                    "symbol" => "COINBASE:LRC-USD"
                ],
                [
                    "description" => "COINBASE POWR-USDT",
                    "displaySymbol" => "POWR-USDT",
                    "symbol" => "COINBASE:POWR-USDT"
                ],
                [
                    "description" => "COINBASE BIGTIME-USD",
                    "displaySymbol" => "BIGTIME-USD",
                    "symbol" => "COINBASE:BIGTIME-USD"
                ],
                [
                    "description" => "COINBASE IMX-USDT",
                    "displaySymbol" => "IMX-USDT",
                    "symbol" => "COINBASE:IMX-USDT"
                ],
                [
                    "description" => "COINBASE DOGE-EUR",
                    "displaySymbol" => "DOGE-EUR",
                    "symbol" => "COINBASE:DOGE-EUR"
                ],
                [
                    "description" => "COINBASE ORN-BTC",
                    "displaySymbol" => "ORN-BTC",
                    "symbol" => "COINBASE:ORN-BTC"
                ],
                [
                    "description" => "COINBASE RAD-GBP",
                    "displaySymbol" => "RAD-GBP",
                    "symbol" => "COINBASE:RAD-GBP"
                ],
                [
                    "description" => "COINBASE XLM-EUR",
                    "displaySymbol" => "XLM-EUR",
                    "symbol" => "COINBASE:XLM-EUR"
                ],
                [
                    "description" => "COINBASE SAND-USDT",
                    "displaySymbol" => "SAND-USDT",
                    "symbol" => "COINBASE:SAND-USDT"
                ],
                [
                    "description" => "COINBASE SHDW/USD",
                    "displaySymbol" => "SHDW/USD",
                    "symbol" => "COINBASE:SHDW-USD"
                ],
                [
                    "description" => "COINBASE DRIFT/USD",
                    "displaySymbol" => "DRIFT/USD",
                    "symbol" => "COINBASE:DRIFT-USD"
                ],
                [
                    "description" => "COINBASE USDC-GBP",
                    "displaySymbol" => "USDC-GBP",
                    "symbol" => "COINBASE:USDC-GBP"
                ],
                [
                    "description" => "COINBASE AXS-BTC",
                    "displaySymbol" => "AXS-BTC",
                    "symbol" => "COINBASE:AXS-BTC"
                ],
                [
                    "description" => "COINBASE TVK-USD",
                    "displaySymbol" => "TVK-USD",
                    "symbol" => "COINBASE:TVK-USD"
                ],
                [
                    "description" => "COINBASE GTC-USD",
                    "displaySymbol" => "GTC-USD",
                    "symbol" => "COINBASE:GTC-USD"
                ],
                [
                    "description" => "COINBASE EIGEN/USD",
                    "displaySymbol" => "EIGEN/USD",
                    "symbol" => "COINBASE:EIGEN-USD"
                ],
                [
                    "description" => "COINBASE TRAC-EUR",
                    "displaySymbol" => "TRAC-EUR",
                    "symbol" => "COINBASE:TRAC-EUR"
                ],
                [
                    "description" => "COINBASE ALGO-EUR",
                    "displaySymbol" => "ALGO-EUR",
                    "symbol" => "COINBASE:ALGO-EUR"
                ],
                [
                    "description" => "COINBASE ALICE-USD",
                    "displaySymbol" => "ALICE-USD",
                    "symbol" => "COINBASE:ALICE-USD"
                ],
                [
                    "description" => "COINBASE LPT-USD",
                    "displaySymbol" => "LPT-USD",
                    "symbol" => "COINBASE:LPT-USD"
                ],
                [
                    "description" => "COINBASE NKN-EUR",
                    "displaySymbol" => "NKN-EUR",
                    "symbol" => "COINBASE:NKN-EUR"
                ],
                [
                    "description" => "COINBASE DNT-USD",
                    "displaySymbol" => "DNT-USD",
                    "symbol" => "COINBASE:DNT-USD"
                ],
                [
                    "description" => "COINBASE CORECHAIN/USD",
                    "displaySymbol" => "CORECHAIN/USD",
                    "symbol" => "COINBASE:CORECHAIN-USD"
                ],
                [
                    "description" => "COINBASE FORTH-USD",
                    "displaySymbol" => "FORTH-USD",
                    "symbol" => "COINBASE:FORTH-USD"
                ],
                [
                    "description" => "COINBASE FLR-USD",
                    "displaySymbol" => "FLR-USD",
                    "symbol" => "COINBASE:FLR-USD"
                ],
                [
                    "description" => "COINBASE QUICK-USD",
                    "displaySymbol" => "QUICK-USD",
                    "symbol" => "COINBASE:QUICK-USD"
                ],
                [
                    "description" => "COINBASE ORN-USD",
                    "displaySymbol" => "ORN-USD",
                    "symbol" => "COINBASE:ORN-USD"
                ],
                [
                    "description" => "COINBASE NCT-EUR",
                    "displaySymbol" => "NCT-EUR",
                    "symbol" => "COINBASE:NCT-EUR"
                ],
                [
                    "description" => "COINBASE NEAR-USDT",
                    "displaySymbol" => "NEAR-USDT",
                    "symbol" => "COINBASE:NEAR-USDT"
                ],
                [
                    "description" => "COINBASE VGX-USD",
                    "displaySymbol" => "VGX-USD",
                    "symbol" => "COINBASE:VGX-USD"
                ],
                [
                    "description" => "COINBASE SHPING-USD",
                    "displaySymbol" => "SHPING-USD",
                    "symbol" => "COINBASE:SHPING-USD"
                ],
                [
                    "description" => "COINBASE BLZ-USD",
                    "displaySymbol" => "BLZ-USD",
                    "symbol" => "COINBASE:BLZ-USD"
                ],
                [
                    "description" => "COINBASE IDEX-USDT",
                    "displaySymbol" => "IDEX-USDT",
                    "symbol" => "COINBASE:IDEX-USDT"
                ],
                [
                    "description" => "COINBASE BAT-ETH",
                    "displaySymbol" => "BAT-ETH",
                    "symbol" => "COINBASE:BAT-ETH"
                ],
                [
                    "description" => "COINBASE CLV-USD",
                    "displaySymbol" => "CLV-USD",
                    "symbol" => "COINBASE:CLV-USD"
                ],
                [
                    "description" => "COINBASE DIA-USD",
                    "displaySymbol" => "DIA-USD",
                    "symbol" => "COINBASE:DIA-USD"
                ],
                [
                    "description" => "COINBASE WAMPL-USD",
                    "displaySymbol" => "WAMPL-USD",
                    "symbol" => "COINBASE:WAMPL-USD"
                ],
                [
                    "description" => "COINBASE AGLD-USD",
                    "displaySymbol" => "AGLD-USD",
                    "symbol" => "COINBASE:AGLD-USD"
                ],
                [
                    "description" => "COINBASE LSETH-USD",
                    "displaySymbol" => "LSETH-USD",
                    "symbol" => "COINBASE:LSETH-USD"
                ],
                [
                    "description" => "COINBASE RNDR-USD",
                    "displaySymbol" => "RNDR-USD",
                    "symbol" => "COINBASE:RNDR-USD"
                ],
                [
                    "description" => "COINBASE NKN-USD",
                    "displaySymbol" => "NKN-USD",
                    "symbol" => "COINBASE:NKN-USD"
                ],
                [
                    "description" => "COINBASE SUSHI-USD",
                    "displaySymbol" => "SUSHI-USD",
                    "symbol" => "COINBASE:SUSHI-USD"
                ],
                [
                    "description" => "COINBASE CELR-USD",
                    "displaySymbol" => "CELR-USD",
                    "symbol" => "COINBASE:CELR-USD"
                ],
                [
                    "description" => "COINBASE GNO-USD",
                    "displaySymbol" => "GNO-USD",
                    "symbol" => "COINBASE:GNO-USD"
                ],
                [
                    "description" => "COINBASE XTZ-EUR",
                    "displaySymbol" => "XTZ-EUR",
                    "symbol" => "COINBASE:XTZ-EUR"
                ],
                [
                    "description" => "COINBASE SUPER-USD",
                    "displaySymbol" => "SUPER-USD",
                    "symbol" => "COINBASE:SUPER-USD"
                ],
                [
                    "description" => "COINBASE EURC/EUR",
                    "displaySymbol" => "EURC/EUR",
                    "symbol" => "COINBASE:EURC-EUR"
                ],
                [
                    "description" => "COINBASE BADGER-USD",
                    "displaySymbol" => "BADGER-USD",
                    "symbol" => "COINBASE:BADGER-USD"
                ],
                [
                    "description" => "COINBASE CHZ-USDT",
                    "displaySymbol" => "CHZ-USDT",
                    "symbol" => "COINBASE:CHZ-USDT"
                ],
                [
                    "description" => "COINBASE ILV-USD",
                    "displaySymbol" => "ILV-USD",
                    "symbol" => "COINBASE:ILV-USD"
                ],
                [
                    "description" => "COINBASE ETC-USD",
                    "displaySymbol" => "ETC-USD",
                    "symbol" => "COINBASE:ETC-USD"
                ],
                [
                    "description" => "COINBASE XLM-USD",
                    "displaySymbol" => "XLM-USD",
                    "symbol" => "COINBASE:XLM-USD"
                ],
                [
                    "description" => "COINBASE NU-EUR",
                    "displaySymbol" => "NU-EUR",
                    "symbol" => "COINBASE:NU-EUR"
                ],
                [
                    "description" => "COINBASE OGN-BTC",
                    "displaySymbol" => "OGN-BTC",
                    "symbol" => "COINBASE:OGN-BTC"
                ],
                [
                    "description" => "COINBASE QI-USD",
                    "displaySymbol" => "QI-USD",
                    "symbol" => "COINBASE:QI-USD"
                ],
                [
                    "description" => "COINBASE ICP-BTC",
                    "displaySymbol" => "ICP-BTC",
                    "symbol" => "COINBASE:ICP-BTC"
                ],
                [
                    "description" => "COINBASE SUPER-USDT",
                    "displaySymbol" => "SUPER-USDT",
                    "symbol" => "COINBASE:SUPER-USDT"
                ],
                [
                    "description" => "COINBASE STG-USDT",
                    "displaySymbol" => "STG-USDT",
                    "symbol" => "COINBASE:STG-USDT"
                ],
                [
                    "description" => "COINBASE INDEX-USDT",
                    "displaySymbol" => "INDEX-USDT",
                    "symbol" => "COINBASE:INDEX-USDT"
                ],
                [
                    "description" => "COINBASE MEDIA-USD",
                    "displaySymbol" => "MEDIA-USD",
                    "symbol" => "COINBASE:MEDIA-USD"
                ],
                [
                    "description" => "COINBASE TNSR/USD",
                    "displaySymbol" => "TNSR/USD",
                    "symbol" => "COINBASE:TNSR-USD"
                ],
                [
                    "description" => "COINBASE LDO-USD",
                    "displaySymbol" => "LDO-USD",
                    "symbol" => "COINBASE:LDO-USD"
                ],
                [
                    "description" => "COINBASE ALEPH-USD",
                    "displaySymbol" => "ALEPH-USD",
                    "symbol" => "COINBASE:ALEPH-USD"
                ],
                [
                    "description" => "COINBASE PAX-USD",
                    "displaySymbol" => "PAX-USD",
                    "symbol" => "COINBASE:PAX-USD"
                ],
                [
                    "description" => "COINBASE NEON/USD",
                    "displaySymbol" => "NEON/USD",
                    "symbol" => "COINBASE:NEON-USD"
                ],
                [
                    "description" => "COINBASE MIR-USD",
                    "displaySymbol" => "MIR-USD",
                    "symbol" => "COINBASE:MIR-USD"
                ],
                [
                    "description" => "COINBASE MUSD-USD",
                    "displaySymbol" => "MUSD-USD",
                    "symbol" => "COINBASE:MUSD-USD"
                ],
                [
                    "description" => "COINBASE FORTH-GBP",
                    "displaySymbol" => "FORTH-GBP",
                    "symbol" => "COINBASE:FORTH-GBP"
                ],
                [
                    "description" => "COINBASE ETH-USDT",
                    "displaySymbol" => "ETH-USDT",
                    "symbol" => "COINBASE:ETH-USDT"
                ],
                [
                    "description" => "COINBASE MIR-BTC",
                    "displaySymbol" => "MIR-BTC",
                    "symbol" => "COINBASE:MIR-BTC"
                ],
                [
                    "description" => "COINBASE MIR-GBP",
                    "displaySymbol" => "MIR-GBP",
                    "symbol" => "COINBASE:MIR-GBP"
                ],
                [
                    "description" => "COINBASE SAFE/USD",
                    "displaySymbol" => "SAFE/USD",
                    "symbol" => "COINBASE:SAFE-USD"
                ],
                [
                    "description" => "COINBASE FLOW-USD",
                    "displaySymbol" => "FLOW-USD",
                    "symbol" => "COINBASE:FLOW-USD"
                ],
                [
                    "description" => "COINBASE OGN-USD",
                    "displaySymbol" => "OGN-USD",
                    "symbol" => "COINBASE:OGN-USD"
                ],
                [
                    "description" => "COINBASE ENJ-USD",
                    "displaySymbol" => "ENJ-USD",
                    "symbol" => "COINBASE:ENJ-USD"
                ],
                [
                    "description" => "COINBASE ETH-USDC",
                    "displaySymbol" => "ETH-USDC",
                    "symbol" => "COINBASE:ETH-USDC"
                ],
                [
                    "description" => "COINBASE GMT-USDT",
                    "displaySymbol" => "GMT-USDT",
                    "symbol" => "COINBASE:GMT-USDT"
                ],
                [
                    "description" => "COINBASE ASM-USDT",
                    "displaySymbol" => "ASM-USDT",
                    "symbol" => "COINBASE:ASM-USDT"
                ],
                [
                    "description" => "COINBASE ATA-USDT",
                    "displaySymbol" => "ATA-USDT",
                    "symbol" => "COINBASE:ATA-USDT"
                ],
                [
                    "description" => "COINBASE GALA-EUR",
                    "displaySymbol" => "GALA-EUR",
                    "symbol" => "COINBASE:GALA-EUR"
                ],
                [
                    "description" => "COINBASE ELA-USD",
                    "displaySymbol" => "ELA-USD",
                    "symbol" => "COINBASE:ELA-USD"
                ],
                [
                    "description" => "COINBASE BTRST-EUR",
                    "displaySymbol" => "BTRST-EUR",
                    "symbol" => "COINBASE:BTRST-EUR"
                ],
                [
                    "description" => "COINBASE AUDIO-USD",
                    "displaySymbol" => "AUDIO-USD",
                    "symbol" => "COINBASE:AUDIO-USD"
                ],
                [
                    "description" => "COINBASE BNT-BTC",
                    "displaySymbol" => "BNT-BTC",
                    "symbol" => "COINBASE:BNT-BTC"
                ],
                [
                    "description" => "COINBASE ZEC-BTC",
                    "displaySymbol" => "ZEC-BTC",
                    "symbol" => "COINBASE:ZEC-BTC"
                ],
                [
                    "description" => "COINBASE KNC-USD",
                    "displaySymbol" => "KNC-USD",
                    "symbol" => "COINBASE:KNC-USD"
                ],
                [
                    "description" => "COINBASE PRIME-USD",
                    "displaySymbol" => "PRIME-USD",
                    "symbol" => "COINBASE:PRIME-USD"
                ],
                [
                    "description" => "COINBASE ELA-USDT",
                    "displaySymbol" => "ELA-USDT",
                    "symbol" => "COINBASE:ELA-USDT"
                ],
                [
                    "description" => "COINBASE ARPA-EUR",
                    "displaySymbol" => "ARPA-EUR",
                    "symbol" => "COINBASE:ARPA-EUR"
                ],
                [
                    "description" => "COINBASE FLOW-USDT",
                    "displaySymbol" => "FLOW-USDT",
                    "symbol" => "COINBASE:FLOW-USDT"
                ],
                [
                    "description" => "COINBASE AAVE-BTC",
                    "displaySymbol" => "AAVE-BTC",
                    "symbol" => "COINBASE:AAVE-BTC"
                ],
                [
                    "description" => "COINBASE LINK-GBP",
                    "displaySymbol" => "LINK-GBP",
                    "symbol" => "COINBASE:LINK-GBP"
                ],
                [
                    "description" => "COINBASE USDT-EUR",
                    "displaySymbol" => "USDT-EUR",
                    "symbol" => "COINBASE:USDT-EUR"
                ],
                [
                    "description" => "COINBASE ZETA-USD",
                    "displaySymbol" => "ZETA-USD",
                    "symbol" => "COINBASE:ZETA-USD"
                ],
                [
                    "description" => "COINBASE ABT-USD",
                    "displaySymbol" => "ABT-USD",
                    "symbol" => "COINBASE:ABT-USD"
                ],
                [
                    "description" => "COINBASE RAD-EUR",
                    "displaySymbol" => "RAD-EUR",
                    "symbol" => "COINBASE:RAD-EUR"
                ],
                [
                    "description" => "COINBASE LQTY-USDT",
                    "displaySymbol" => "LQTY-USDT",
                    "symbol" => "COINBASE:LQTY-USDT"
                ],
                [
                    "description" => "COINBASE NMR-USD",
                    "displaySymbol" => "NMR-USD",
                    "symbol" => "COINBASE:NMR-USD"
                ],
                [
                    "description" => "COINBASE NCT-USD",
                    "displaySymbol" => "NCT-USD",
                    "symbol" => "COINBASE:NCT-USD"
                ],
                [
                    "description" => "COINBASE JASMY-USD",
                    "displaySymbol" => "JASMY-USD",
                    "symbol" => "COINBASE:JASMY-USD"
                ],
                [
                    "description" => "COINBASE ENJ-BTC",
                    "displaySymbol" => "ENJ-BTC",
                    "symbol" => "COINBASE:ENJ-BTC"
                ],
                [
                    "description" => "COINBASE STRK/USD",
                    "displaySymbol" => "STRK/USD",
                    "symbol" => "COINBASE:STRK-USD"
                ],
                [
                    "description" => "COINBASE MATH-USD",
                    "displaySymbol" => "MATH-USD",
                    "symbol" => "COINBASE:MATH-USD"
                ],
                [
                    "description" => "COINBASE COVAL-USDT",
                    "displaySymbol" => "COVAL-USDT",
                    "symbol" => "COINBASE:COVAL-USDT"
                ],
                [
                    "description" => "COINBASE POLY-USD",
                    "displaySymbol" => "POLY-USD",
                    "symbol" => "COINBASE:POLY-USD"
                ],
                [
                    "description" => "COINBASE PYR-USD",
                    "displaySymbol" => "PYR-USD",
                    "symbol" => "COINBASE:PYR-USD"
                ],
                [
                    "description" => "COINBASE VET-USD",
                    "displaySymbol" => "VET-USD",
                    "symbol" => "COINBASE:VET-USD"
                ],
                [
                    "description" => "COINBASE DOT-GBP",
                    "displaySymbol" => "DOT-GBP",
                    "symbol" => "COINBASE:DOT-GBP"
                ],
                [
                    "description" => "COINBASE 1INCH-BTC",
                    "displaySymbol" => "1INCH-BTC",
                    "symbol" => "COINBASE:1INCH-BTC"
                ],
                [
                    "description" => "COINBASE UMA-USD",
                    "displaySymbol" => "UMA-USD",
                    "symbol" => "COINBASE:UMA-USD"
                ],
                [
                    "description" => "COINBASE OP-USD",
                    "displaySymbol" => "OP-USD",
                    "symbol" => "COINBASE:OP-USD"
                ],
                [
                    "description" => "COINBASE ZRO/USD",
                    "displaySymbol" => "ZRO/USD",
                    "symbol" => "COINBASE:ZRO-USD"
                ],
                [
                    "description" => "COINBASE ALCX-USDT",
                    "displaySymbol" => "ALCX-USDT",
                    "symbol" => "COINBASE:ALCX-USDT"
                ],
                [
                    "description" => "COINBASE PRO-USD",
                    "displaySymbol" => "PRO-USD",
                    "symbol" => "COINBASE:PRO-USD"
                ],
                [
                    "description" => "COINBASE BTRST-BTC",
                    "displaySymbol" => "BTRST-BTC",
                    "symbol" => "COINBASE:BTRST-BTC"
                ],
                [
                    "description" => "COINBASE ETH-EUR",
                    "displaySymbol" => "ETH-EUR",
                    "symbol" => "COINBASE:ETH-EUR"
                ],
                [
                    "description" => "COINBASE GAL-USDT",
                    "displaySymbol" => "GAL-USDT",
                    "symbol" => "COINBASE:GAL-USDT"
                ],
                [
                    "description" => "COINBASE MASK-EUR",
                    "displaySymbol" => "MASK-EUR",
                    "symbol" => "COINBASE:MASK-EUR"
                ],
                [
                    "description" => "COINBASE RLY-USDT",
                    "displaySymbol" => "RLY-USDT",
                    "symbol" => "COINBASE:RLY-USDT"
                ],
                [
                    "description" => "COINBASE BAT-EUR",
                    "displaySymbol" => "BAT-EUR",
                    "symbol" => "COINBASE:BAT-EUR"
                ],
                [
                    "description" => "COINBASE MCO2-USD",
                    "displaySymbol" => "MCO2-USD",
                    "symbol" => "COINBASE:MCO2-USD"
                ],
                [
                    "description" => "COINBASE HFT-USD",
                    "displaySymbol" => "HFT-USD",
                    "symbol" => "COINBASE:HFT-USD"
                ],
                [
                    "description" => "COINBASE WLUNA-EUR",
                    "displaySymbol" => "WLUNA-EUR",
                    "symbol" => "COINBASE:WLUNA-EUR"
                ],
                [
                    "description" => "COINBASE ACX/USD",
                    "displaySymbol" => "ACX/USD",
                    "symbol" => "COINBASE:ACX-USD"
                ],
                [
                    "description" => "COINBASE UMA-EUR",
                    "displaySymbol" => "UMA-EUR",
                    "symbol" => "COINBASE:UMA-EUR"
                ],
                [
                    "description" => "COINBASE PAX-USDT",
                    "displaySymbol" => "PAX-USDT",
                    "symbol" => "COINBASE:PAX-USDT"
                ],
                [
                    "description" => "COINBASE LRC-USDT",
                    "displaySymbol" => "LRC-USDT",
                    "symbol" => "COINBASE:LRC-USDT"
                ],
                [
                    "description" => "COINBASE SOL-GBP",
                    "displaySymbol" => "SOL-GBP",
                    "symbol" => "COINBASE:SOL-GBP"
                ],
                [
                    "description" => "COINBASE BOND-USD",
                    "displaySymbol" => "BOND-USD",
                    "symbol" => "COINBASE:BOND-USD"
                ],
                [
                    "description" => "COINBASE KSM-USD",
                    "displaySymbol" => "KSM-USD",
                    "symbol" => "COINBASE:KSM-USD"
                ],
                [
                    "description" => "COINBASE TRAC-USDT",
                    "displaySymbol" => "TRAC-USDT",
                    "symbol" => "COINBASE:TRAC-USDT"
                ],
                [
                    "description" => "COINBASE CLV-GBP",
                    "displaySymbol" => "CLV-GBP",
                    "symbol" => "COINBASE:CLV-GBP"
                ],
                [
                    "description" => "COINBASE T-USD",
                    "displaySymbol" => "T-USD",
                    "symbol" => "COINBASE:T-USD"
                ],
                [
                    "description" => "COINBASE PNG-USD",
                    "displaySymbol" => "PNG-USD",
                    "symbol" => "COINBASE:PNG-USD"
                ],
                [
                    "description" => "COINBASE BAL-BTC",
                    "displaySymbol" => "BAL-BTC",
                    "symbol" => "COINBASE:BAL-BTC"
                ],
                [
                    "description" => "COINBASE LTC-BTC",
                    "displaySymbol" => "LTC-BTC",
                    "symbol" => "COINBASE:LTC-BTC"
                ],
                [
                    "description" => "COINBASE SHIB-USD",
                    "displaySymbol" => "SHIB-USD",
                    "symbol" => "COINBASE:SHIB-USD"
                ],
                [
                    "description" => "COINBASE ALGO-BTC",
                    "displaySymbol" => "ALGO-BTC",
                    "symbol" => "COINBASE:ALGO-BTC"
                ],
                [
                    "description" => "COINBASE MASK-GBP",
                    "displaySymbol" => "MASK-GBP",
                    "symbol" => "COINBASE:MASK-GBP"
                ],
                [
                    "description" => "COINBASE UST-EUR",
                    "displaySymbol" => "UST-EUR",
                    "symbol" => "COINBASE:UST-EUR"
                ],
                [
                    "description" => "COINBASE TRU-EUR",
                    "displaySymbol" => "TRU-EUR",
                    "symbol" => "COINBASE:TRU-EUR"
                ],
                [
                    "description" => "COINBASE DOGE-GBP",
                    "displaySymbol" => "DOGE-GBP",
                    "symbol" => "COINBASE:DOGE-GBP"
                ],
                [
                    "description" => "COINBASE UNI-BTC",
                    "displaySymbol" => "UNI-BTC",
                    "symbol" => "COINBASE:UNI-BTC"
                ],
                [
                    "description" => "COINBASE APE-EUR",
                    "displaySymbol" => "APE-EUR",
                    "symbol" => "COINBASE:APE-EUR"
                ],
                [
                    "description" => "COINBASE WELL/USD",
                    "displaySymbol" => "WELL/USD",
                    "symbol" => "COINBASE:WELL-USD"
                ],
                [
                    "description" => "COINBASE ACH-USDT",
                    "displaySymbol" => "ACH-USDT",
                    "symbol" => "COINBASE:ACH-USDT"
                ],
                [
                    "description" => "COINBASE REP-USD",
                    "displaySymbol" => "REP-USD",
                    "symbol" => "COINBASE:REP-USD"
                ],
                [
                    "description" => "COINBASE WLUNA-USD",
                    "displaySymbol" => "WLUNA-USD",
                    "symbol" => "COINBASE:WLUNA-USD"
                ],
                [
                    "description" => "COINBASE COW/USD",
                    "displaySymbol" => "COW/USD",
                    "symbol" => "COINBASE:COW-USD"
                ],
                [
                    "description" => "COINBASE CTSI-USD",
                    "displaySymbol" => "CTSI-USD",
                    "symbol" => "COINBASE:CTSI-USD"
                ],
                [
                    "description" => "COINBASE OMNI/USD",
                    "displaySymbol" => "OMNI/USD",
                    "symbol" => "COINBASE:OMNI-USD"
                ],
                [
                    "description" => "COINBASE MATIC-EUR",
                    "displaySymbol" => "MATIC-EUR",
                    "symbol" => "COINBASE:MATIC-EUR"
                ],
                [
                    "description" => "COINBASE DREP-USDT",
                    "displaySymbol" => "DREP-USDT",
                    "symbol" => "COINBASE:DREP-USDT"
                ],
                [
                    "description" => "COINBASE SNX-BTC",
                    "displaySymbol" => "SNX-BTC",
                    "symbol" => "COINBASE:SNX-BTC"
                ],
                [
                    "description" => "COINBASE ZEC-USDC",
                    "displaySymbol" => "ZEC-USDC",
                    "symbol" => "COINBASE:ZEC-USDC"
                ],
                [
                    "description" => "COINBASE cbETH-USD",
                    "displaySymbol" => "cbETH-USD",
                    "symbol" => "COINBASE:CBETH-USD"
                ],
                [
                    "description" => "COINBASE CRV-BTC",
                    "displaySymbol" => "CRV-BTC",
                    "symbol" => "COINBASE:CRV-BTC"
                ],
                [
                    "description" => "COINBASE ATOM-EUR",
                    "displaySymbol" => "ATOM-EUR",
                    "symbol" => "COINBASE:ATOM-EUR"
                ],
                [
                    "description" => "COINBASE VGX-USDT",
                    "displaySymbol" => "VGX-USDT",
                    "symbol" => "COINBASE:VGX-USDT"
                ],
                [
                    "description" => "COINBASE DAR-USD",
                    "displaySymbol" => "DAR-USD",
                    "symbol" => "COINBASE:DAR-USD"
                ],
                [
                    "description" => "COINBASE ALGO-USD",
                    "displaySymbol" => "ALGO-USD",
                    "symbol" => "COINBASE:ALGO-USD"
                ],
                [
                    "description" => "COINBASE ADA-USD",
                    "displaySymbol" => "ADA-USD",
                    "symbol" => "COINBASE:ADA-USD"
                ],
                [
                    "description" => "COINBASE cbETH-ETH",
                    "displaySymbol" => "cbETH-ETH",
                    "symbol" => "COINBASE:CBETH-ETH"
                ],
                [
                    "description" => "COINBASE MXC-USD",
                    "displaySymbol" => "MXC-USD",
                    "symbol" => "COINBASE:MXC-USD"
                ],
                [
                    "description" => "COINBASE POND-USD",
                    "displaySymbol" => "POND-USD",
                    "symbol" => "COINBASE:POND-USD"
                ],
                [
                    "description" => "COINBASE COVAL-USD",
                    "displaySymbol" => "COVAL-USD",
                    "symbol" => "COINBASE:COVAL-USD"
                ],
                [
                    "description" => "COINBASE WLUNA-GBP",
                    "displaySymbol" => "WLUNA-GBP",
                    "symbol" => "COINBASE:WLUNA-GBP"
                ],
                [
                    "description" => "COINBASE BAND-GBP",
                    "displaySymbol" => "BAND-GBP",
                    "symbol" => "COINBASE:BAND-GBP"
                ],
                [
                    "description" => "COINBASE GODS-USD",
                    "displaySymbol" => "GODS-USD",
                    "symbol" => "COINBASE:GODS-USD"
                ],
                [
                    "description" => "COINBASE WCFG-EUR",
                    "displaySymbol" => "WCFG-EUR",
                    "symbol" => "COINBASE:WCFG-EUR"
                ],
                [
                    "description" => "COINBASE DESO-USDT",
                    "displaySymbol" => "DESO-USDT",
                    "symbol" => "COINBASE:DESO-USDT"
                ],
                [
                    "description" => "COINBASE DEXT-USD",
                    "displaySymbol" => "DEXT-USD",
                    "symbol" => "COINBASE:DEXT-USD"
                ],
                [
                    "description" => "COINBASE GRT-USD",
                    "displaySymbol" => "GRT-USD",
                    "symbol" => "COINBASE:GRT-USD"
                ],
                [
                    "description" => "COINBASE ATOM-USD",
                    "displaySymbol" => "ATOM-USD",
                    "symbol" => "COINBASE:ATOM-USD"
                ],
                [
                    "description" => "COINBASE QNT-USDT",
                    "displaySymbol" => "QNT-USDT",
                    "symbol" => "COINBASE:QNT-USDT"
                ],
                [
                    "description" => "COINBASE CVC-USDC",
                    "displaySymbol" => "CVC-USDC",
                    "symbol" => "COINBASE:CVC-USDC"
                ],
                [
                    "description" => "COINBASE UST-USDT",
                    "displaySymbol" => "UST-USDT",
                    "symbol" => "COINBASE:UST-USDT"
                ],
                [
                    "description" => "COINBASE FIL-GBP",
                    "displaySymbol" => "FIL-GBP",
                    "symbol" => "COINBASE:FIL-GBP"
                ],
                [
                    "description" => "COINBASE ARPA-USD",
                    "displaySymbol" => "ARPA-USD",
                    "symbol" => "COINBASE:ARPA-USD"
                ],
                [
                    "description" => "COINBASE ALCX-USD",
                    "displaySymbol" => "ALCX-USD",
                    "symbol" => "COINBASE:ALCX-USD"
                ],
                [
                    "description" => "COINBASE SHIB-GBP",
                    "displaySymbol" => "SHIB-GBP",
                    "symbol" => "COINBASE:SHIB-GBP"
                ],
                [
                    "description" => "COINBASE MNDE-USD",
                    "displaySymbol" => "MNDE-USD",
                    "symbol" => "COINBASE:MNDE-USD"
                ],
                [
                    "description" => "COINBASE POL/USD",
                    "displaySymbol" => "POL/USD",
                    "symbol" => "COINBASE:POL-USD"
                ],
                [
                    "description" => "COINBASE LQTY-EUR",
                    "displaySymbol" => "LQTY-EUR",
                    "symbol" => "COINBASE:LQTY-EUR"
                ],
                [
                    "description" => "COINBASE ICP-EUR",
                    "displaySymbol" => "ICP-EUR",
                    "symbol" => "COINBASE:ICP-EUR"
                ],
                [
                    "description" => "COINBASE VARA-USD",
                    "displaySymbol" => "VARA-USD",
                    "symbol" => "COINBASE:VARA-USD"
                ],
                [
                    "description" => "COINBASE DIA-USDT",
                    "displaySymbol" => "DIA-USDT",
                    "symbol" => "COINBASE:DIA-USDT"
                ],
                [
                    "description" => "COINBASE POLS-USD",
                    "displaySymbol" => "POLS-USD",
                    "symbol" => "COINBASE:POLS-USD"
                ],
                [
                    "description" => "COINBASE ORCA-USD",
                    "displaySymbol" => "ORCA-USD",
                    "symbol" => "COINBASE:ORCA-USD"
                ],
                [
                    "description" => "COINBASE MKR-BTC",
                    "displaySymbol" => "MKR-BTC",
                    "symbol" => "COINBASE:MKR-BTC"
                ],
                [
                    "description" => "COINBASE ENS-EUR",
                    "displaySymbol" => "ENS-EUR",
                    "symbol" => "COINBASE:ENS-EUR"
                ],
                [
                    "description" => "COINBASE SNX-EUR",
                    "displaySymbol" => "SNX-EUR",
                    "symbol" => "COINBASE:SNX-EUR"
                ],
                [
                    "description" => "COINBASE ZEN-BTC",
                    "displaySymbol" => "ZEN-BTC",
                    "symbol" => "COINBASE:ZEN-BTC"
                ],
                [
                    "description" => "COINBASE 1INCH-EUR",
                    "displaySymbol" => "1INCH-EUR",
                    "symbol" => "COINBASE:1INCH-EUR"
                ],
                [
                    "description" => "COINBASE OMG-BTC",
                    "displaySymbol" => "OMG-BTC",
                    "symbol" => "COINBASE:OMG-BTC"
                ],
                [
                    "description" => "COINBASE REQ-GBP",
                    "displaySymbol" => "REQ-GBP",
                    "symbol" => "COINBASE:REQ-GBP"
                ],
                [
                    "description" => "COINBASE POLS-USDT",
                    "displaySymbol" => "POLS-USDT",
                    "symbol" => "COINBASE:POLS-USDT"
                ],
                [
                    "description" => "COINBASE METIS-USDT",
                    "displaySymbol" => "METIS-USDT",
                    "symbol" => "COINBASE:METIS-USDT"
                ],
                [
                    "description" => "COINBASE ETC-BTC",
                    "displaySymbol" => "ETC-BTC",
                    "symbol" => "COINBASE:ETC-BTC"
                ],
                [
                    "description" => "COINBASE UMA-GBP",
                    "displaySymbol" => "UMA-GBP",
                    "symbol" => "COINBASE:UMA-GBP"
                ],
                [
                    "description" => "COINBASE NEST-USD",
                    "displaySymbol" => "NEST-USD",
                    "symbol" => "COINBASE:NEST-USD"
                ],
                [
                    "description" => "COINBASE API3-USDT",
                    "displaySymbol" => "API3-USDT",
                    "symbol" => "COINBASE:API3-USDT"
                ],
                [
                    "description" => "COINBASE USDT-USDC",
                    "displaySymbol" => "USDT-USDC",
                    "symbol" => "COINBASE:USDT-USDC"
                ],
                [
                    "description" => "COINBASE MATIC-USD",
                    "displaySymbol" => "MATIC-USD",
                    "symbol" => "COINBASE:MATIC-USD"
                ],
                [
                    "description" => "COINBASE GMT-USD",
                    "displaySymbol" => "GMT-USD",
                    "symbol" => "COINBASE:GMT-USD"
                ],
                [
                    "description" => "COINBASE XYO-EUR",
                    "displaySymbol" => "XYO-EUR",
                    "symbol" => "COINBASE:XYO-EUR"
                ],
                [
                    "description" => "COINBASE ENJ-USDT",
                    "displaySymbol" => "ENJ-USDT",
                    "symbol" => "COINBASE:ENJ-USDT"
                ],
                [
                    "description" => "COINBASE HIGH-USD",
                    "displaySymbol" => "HIGH-USD",
                    "symbol" => "COINBASE:HIGH-USD"
                ],
                [
                    "description" => "COINBASE CRO-USDT",
                    "displaySymbol" => "CRO-USDT",
                    "symbol" => "COINBASE:CRO-USDT"
                ],
                [
                    "description" => "COINBASE XLM-BTC",
                    "displaySymbol" => "XLM-BTC",
                    "symbol" => "COINBASE:XLM-BTC"
                ],
                [
                    "description" => "COINBASE DREP-USD",
                    "displaySymbol" => "DREP-USD",
                    "symbol" => "COINBASE:DREP-USD"
                ],
                [
                    "description" => "COINBASE 00-USD",
                    "displaySymbol" => "00-USD",
                    "symbol" => "COINBASE:00-USD"
                ],
                [
                    "description" => "COINBASE AVAX-USD",
                    "displaySymbol" => "AVAX-USD",
                    "symbol" => "COINBASE:AVAX-USD"
                ],
                [
                    "description" => "COINBASE RENDER/USD",
                    "displaySymbol" => "RENDER/USD",
                    "symbol" => "COINBASE:RENDER-USD"
                ],
                [
                    "description" => "COINBASE BNT-EUR",
                    "displaySymbol" => "BNT-EUR",
                    "symbol" => "COINBASE:BNT-EUR"
                ],
                [
                    "description" => "COINBASE SUKU-EUR",
                    "displaySymbol" => "SUKU-EUR",
                    "symbol" => "COINBASE:SUKU-EUR"
                ],
                [
                    "description" => "COINBASE MANA-USD",
                    "displaySymbol" => "MANA-USD",
                    "symbol" => "COINBASE:MANA-USD"
                ],
                [
                    "description" => "COINBASE BTRST-USD",
                    "displaySymbol" => "BTRST-USD",
                    "symbol" => "COINBASE:BTRST-USD"
                ],
                [
                    "description" => "COINBASE SYLO-USDT",
                    "displaySymbol" => "SYLO-USDT",
                    "symbol" => "COINBASE:SYLO-USDT"
                ],
                [
                    "description" => "COINBASE AERO-USD",
                    "displaySymbol" => "AERO-USD",
                    "symbol" => "COINBASE:AERO-USD"
                ],
                [
                    "description" => "COINBASE EGLD-USD",
                    "displaySymbol" => "EGLD-USD",
                    "symbol" => "COINBASE:EGLD-USD"
                ],
                [
                    "description" => "COINBASE ACS-USD",
                    "displaySymbol" => "ACS-USD",
                    "symbol" => "COINBASE:ACS-USD"
                ],
                [
                    "description" => "COINBASE GRT-EUR",
                    "displaySymbol" => "GRT-EUR",
                    "symbol" => "COINBASE:GRT-EUR"
                ],
                [
                    "description" => "COINBASE EURC/USDC",
                    "displaySymbol" => "EURC/USDC",
                    "symbol" => "COINBASE:EURC-USDC"
                ],
                [
                    "description" => "COINBASE BAT-USDC",
                    "displaySymbol" => "BAT-USDC",
                    "symbol" => "COINBASE:BAT-USDC"
                ],
                [
                    "description" => "COINBASE BCH-BTC",
                    "displaySymbol" => "BCH-BTC",
                    "symbol" => "COINBASE:BCH-BTC"
                ],
                [
                    "description" => "COINBASE CGLD-USD",
                    "displaySymbol" => "CGLD-USD",
                    "symbol" => "COINBASE:CGLD-USD"
                ],
                [
                    "description" => "COINBASE LRDS/USD",
                    "displaySymbol" => "LRDS/USD",
                    "symbol" => "COINBASE:LRDS-USD"
                ],
                [
                    "description" => "COINBASE REN-BTC",
                    "displaySymbol" => "REN-BTC",
                    "symbol" => "COINBASE:REN-BTC"
                ],
                [
                    "description" => "COINBASE MIR-EUR",
                    "displaySymbol" => "MIR-EUR",
                    "symbol" => "COINBASE:MIR-EUR"
                ],
                [
                    "description" => "COINBASE AVAX-BTC",
                    "displaySymbol" => "AVAX-BTC",
                    "symbol" => "COINBASE:AVAX-BTC"
                ],
                [
                    "description" => "COINBASE POND-USDT",
                    "displaySymbol" => "POND-USDT",
                    "symbol" => "COINBASE:POND-USDT"
                ],
                [
                    "description" => "COINBASE TIA-USD",
                    "displaySymbol" => "TIA-USD",
                    "symbol" => "COINBASE:TIA-USD"
                ],
                [
                    "description" => "COINBASE HONEY-USD",
                    "displaySymbol" => "HONEY-USD",
                    "symbol" => "COINBASE:HONEY-USD"
                ],
                [
                    "description" => "COINBASE CRO-EUR",
                    "displaySymbol" => "CRO-EUR",
                    "symbol" => "COINBASE:CRO-EUR"
                ],
                [
                    "description" => "COINBASE DOGE-BTC",
                    "displaySymbol" => "DOGE-BTC",
                    "symbol" => "COINBASE:DOGE-BTC"
                ],
                [
                    "description" => "COINBASE AERGO-USD",
                    "displaySymbol" => "AERGO-USD",
                    "symbol" => "COINBASE:AERGO-USD"
                ],
                [
                    "description" => "COINBASE GST-USD",
                    "displaySymbol" => "GST-USD",
                    "symbol" => "COINBASE:GST-USD"
                ],
                [
                    "description" => "COINBASE WCFG-USDT",
                    "displaySymbol" => "WCFG-USDT",
                    "symbol" => "COINBASE:WCFG-USDT"
                ],
                [
                    "description" => "COINBASE DESO-USD",
                    "displaySymbol" => "DESO-USD",
                    "symbol" => "COINBASE:DESO-USD"
                ],
                [
                    "description" => "COINBASE MDT-USDT",
                    "displaySymbol" => "MDT-USDT",
                    "symbol" => "COINBASE:MDT-USDT"
                ],
                [
                    "description" => "COINBASE REQ-USDT",
                    "displaySymbol" => "REQ-USDT",
                    "symbol" => "COINBASE:REQ-USDT"
                ],
                [
                    "description" => "COINBASE ONDO-USD",
                    "displaySymbol" => "ONDO-USD",
                    "symbol" => "COINBASE:ONDO-USD"
                ],
                [
                    "description" => "COINBASE SHIB-EUR",
                    "displaySymbol" => "SHIB-EUR",
                    "symbol" => "COINBASE:SHIB-EUR"
                ],
                [
                    "description" => "COINBASE AIOZ-USD",
                    "displaySymbol" => "AIOZ-USD",
                    "symbol" => "COINBASE:AIOZ-USD"
                ],
                [
                    "description" => "COINBASE LTC-EUR",
                    "displaySymbol" => "LTC-EUR",
                    "symbol" => "COINBASE:LTC-EUR"
                ],
                [
                    "description" => "COINBASE KEEP-USD",
                    "displaySymbol" => "KEEP-USD",
                    "symbol" => "COINBASE:KEEP-USD"
                ],
                [
                    "description" => "COINBASE METIS-USD",
                    "displaySymbol" => "METIS-USD",
                    "symbol" => "COINBASE:METIS-USD"
                ],
                [
                    "description" => "COINBASE SKL-GBP",
                    "displaySymbol" => "SKL-GBP",
                    "symbol" => "COINBASE:SKL-GBP"
                ],
                [
                    "description" => "COINBASE NCT-USDT",
                    "displaySymbol" => "NCT-USDT",
                    "symbol" => "COINBASE:NCT-USDT"
                ],
                [
                    "description" => "COINBASE ETC-EUR",
                    "displaySymbol" => "ETC-EUR",
                    "symbol" => "COINBASE:ETC-EUR"
                ],
                [
                    "description" => "COINBASE CTX-USDT",
                    "displaySymbol" => "CTX-USDT",
                    "symbol" => "COINBASE:CTX-USDT"
                ],
                [
                    "description" => "COINBASE AVAX-USDT",
                    "displaySymbol" => "AVAX-USDT",
                    "symbol" => "COINBASE:AVAX-USDT"
                ],
                [
                    "description" => "COINBASE LOOM-USD",
                    "displaySymbol" => "LOOM-USD",
                    "symbol" => "COINBASE:LOOM-USD"
                ],
                [
                    "description" => "COINBASE MONA-USD",
                    "displaySymbol" => "MONA-USD",
                    "symbol" => "COINBASE:MONA-USD"
                ],
                [
                    "description" => "COINBASE MANA-USDC",
                    "displaySymbol" => "MANA-USDC",
                    "symbol" => "COINBASE:MANA-USDC"
                ],
                [
                    "description" => "COINBASE COTI-USD",
                    "displaySymbol" => "COTI-USD",
                    "symbol" => "COINBASE:COTI-USD"
                ],
                [
                    "description" => "COINBASE SPELL-USDT",
                    "displaySymbol" => "SPELL-USDT",
                    "symbol" => "COINBASE:SPELL-USDT"
                ],
                [
                    "description" => "COINBASE TRIBE-USD",
                    "displaySymbol" => "TRIBE-USD",
                    "symbol" => "COINBASE:TRIBE-USD"
                ],
                [
                    "description" => "COINBASE SWFTC-USD",
                    "displaySymbol" => "SWFTC-USD",
                    "symbol" => "COINBASE:SWFTC-USD"
                ],
                [
                    "description" => "COINBASE KSM-USDT",
                    "displaySymbol" => "KSM-USDT",
                    "symbol" => "COINBASE:KSM-USDT"
                ],
                [
                    "description" => "COINBASE PEPE/USD",
                    "displaySymbol" => "PEPE/USD",
                    "symbol" => "COINBASE:PEPE-USD"
                ],
                [
                    "description" => "COINBASE ETH-DAI",
                    "displaySymbol" => "ETH-DAI",
                    "symbol" => "COINBASE:ETH-DAI"
                ],
                [
                    "description" => "COINBASE ANKR-EUR",
                    "displaySymbol" => "ANKR-EUR",
                    "symbol" => "COINBASE:ANKR-EUR"
                ],
                [
                    "description" => "COINBASE AVAX-EUR",
                    "displaySymbol" => "AVAX-EUR",
                    "symbol" => "COINBASE:AVAX-EUR"
                ],
                [
                    "description" => "COINBASE FET-USD",
                    "displaySymbol" => "FET-USD",
                    "symbol" => "COINBASE:FET-USD"
                ],
                [
                    "description" => "COINBASE DAI-USDC",
                    "displaySymbol" => "DAI-USDC",
                    "symbol" => "COINBASE:DAI-USDC"
                ],
                [
                    "description" => "COINBASE ALGO-GBP",
                    "displaySymbol" => "ALGO-GBP",
                    "symbol" => "COINBASE:ALGO-GBP"
                ],
                [
                    "description" => "COINBASE EOS-USD",
                    "displaySymbol" => "EOS-USD",
                    "symbol" => "COINBASE:EOS-USD"
                ],
                [
                    "description" => "COINBASE GNT-USDC",
                    "displaySymbol" => "GNT-USDC",
                    "symbol" => "COINBASE:GNT-USDC"
                ],
                [
                    "description" => "COINBASE WIF/USD",
                    "displaySymbol" => "WIF/USD",
                    "symbol" => "COINBASE:WIF-USD"
                ],
                [
                    "description" => "COINBASE APT-USD",
                    "displaySymbol" => "APT-USD",
                    "symbol" => "COINBASE:APT-USD"
                ],
                [
                    "description" => "COINBASE CRV-USD",
                    "displaySymbol" => "CRV-USD",
                    "symbol" => "COINBASE:CRV-USD"
                ],
                [
                    "description" => "COINBASE USDC-EUR",
                    "displaySymbol" => "USDC-EUR",
                    "symbol" => "COINBASE:USDC-EUR"
                ],
                [
                    "description" => "COINBASE NU-BTC",
                    "displaySymbol" => "NU-BTC",
                    "symbol" => "COINBASE:NU-BTC"
                ],
                [
                    "description" => "COINBASE JASMY-USDT",
                    "displaySymbol" => "JASMY-USDT",
                    "symbol" => "COINBASE:JASMY-USDT"
                ],
                [
                    "description" => "COINBASE RPL-USD",
                    "displaySymbol" => "RPL-USD",
                    "symbol" => "COINBASE:RPL-USD"
                ],
                [
                    "description" => "COINBASE YFII-USD",
                    "displaySymbol" => "YFII-USD",
                    "symbol" => "COINBASE:YFII-USD"
                ],
                [
                    "description" => "COINBASE XRP-USD",
                    "displaySymbol" => "XRP-USD",
                    "symbol" => "COINBASE:XRP-USD"
                ],
                [
                    "description" => "COINBASE NKN-BTC",
                    "displaySymbol" => "NKN-BTC",
                    "symbol" => "COINBASE:NKN-BTC"
                ],
                [
                    "description" => "COINBASE BNT-GBP",
                    "displaySymbol" => "BNT-GBP",
                    "symbol" => "COINBASE:BNT-GBP"
                ],
                [
                    "description" => "COINBASE RAD-USD",
                    "displaySymbol" => "RAD-USD",
                    "symbol" => "COINBASE:RAD-USD"
                ],
                [
                    "description" => "COINBASE ZK/USD",
                    "displaySymbol" => "ZK/USD",
                    "symbol" => "COINBASE:ZK-USD"
                ],
                [
                    "description" => "COINBASE WAMPL-USDT",
                    "displaySymbol" => "WAMPL-USDT",
                    "symbol" => "COINBASE:WAMPL-USDT"
                ],
                [
                    "description" => "COINBASE QNT-USD",
                    "displaySymbol" => "QNT-USD",
                    "symbol" => "COINBASE:QNT-USD"
                ],
                [
                    "description" => "COINBASE ANT-USD",
                    "displaySymbol" => "ANT-USD",
                    "symbol" => "COINBASE:ANT-USD"
                ],
                [
                    "description" => "COINBASE FIS-USD",
                    "displaySymbol" => "FIS-USD",
                    "symbol" => "COINBASE:FIS-USD"
                ],
                [
                    "description" => "COINBASE ETH-GBP",
                    "displaySymbol" => "ETH-GBP",
                    "symbol" => "COINBASE:ETH-GBP"
                ],
                [
                    "description" => "COINBASE CGLD-BTC",
                    "displaySymbol" => "CGLD-BTC",
                    "symbol" => "COINBASE:CGLD-BTC"
                ],
                [
                    "description" => "COINBASE UNFI-USD",
                    "displaySymbol" => "UNFI-USD",
                    "symbol" => "COINBASE:UNFI-USD"
                ],
                [
                    "description" => "COINBASE WCFG-USD",
                    "displaySymbol" => "WCFG-USD",
                    "symbol" => "COINBASE:WCFG-USD"
                ],
                [
                    "description" => "COINBASE ZETACHAIN/USD",
                    "displaySymbol" => "ZETACHAIN/USD",
                    "symbol" => "COINBASE:ZETACHAIN-USD"
                ],
                [
                    "description" => "COINBASE FET-USDT",
                    "displaySymbol" => "FET-USDT",
                    "symbol" => "COINBASE:FET-USDT"
                ],
                [
                    "description" => "COINBASE FIDA-EUR",
                    "displaySymbol" => "FIDA-EUR",
                    "symbol" => "COINBASE:FIDA-EUR"
                ],
                [
                    "description" => "COINBASE GHST-USD",
                    "displaySymbol" => "GHST-USD",
                    "symbol" => "COINBASE:GHST-USD"
                ],
                [
                    "description" => "COINBASE HBAR-USD",
                    "displaySymbol" => "HBAR-USD",
                    "symbol" => "COINBASE:HBAR-USD"
                ],
                [
                    "description" => "COINBASE MANA-BTC",
                    "displaySymbol" => "MANA-BTC",
                    "symbol" => "COINBASE:MANA-BTC"
                ],
                [
                    "description" => "COINBASE BTC-USD",
                    "displaySymbol" => "BTC-USD",
                    "symbol" => "COINBASE:BTC-USD"
                ],
                [
                    "description" => "COINBASE SYN-USD",
                    "displaySymbol" => "SYN-USD",
                    "symbol" => "COINBASE:SYN-USD"
                ],
                [
                    "description" => "COINBASE AUCTION-EUR",
                    "displaySymbol" => "AUCTION-EUR",
                    "symbol" => "COINBASE:AUCTION-EUR"
                ],
                [
                    "description" => "COINBASE AURORA-USD",
                    "displaySymbol" => "AURORA-USD",
                    "symbol" => "COINBASE:AURORA-USD"
                ],
                [
                    "description" => "COINBASE UNI-USD",
                    "displaySymbol" => "UNI-USD",
                    "symbol" => "COINBASE:UNI-USD"
                ],
                [
                    "description" => "COINBASE RLY-USD",
                    "displaySymbol" => "RLY-USD",
                    "symbol" => "COINBASE:RLY-USD"
                ],
                [
                    "description" => "COINBASE MATH-USDT",
                    "displaySymbol" => "MATH-USDT",
                    "symbol" => "COINBASE:MATH-USDT"
                ],
                [
                    "description" => "COINBASE ERN-EUR",
                    "displaySymbol" => "ERN-EUR",
                    "symbol" => "COINBASE:ERN-EUR"
                ],
                [
                    "description" => "COINBASE BUSD-USD",
                    "displaySymbol" => "BUSD-USD",
                    "symbol" => "COINBASE:BUSD-USD"
                ],
                [
                    "description" => "COINBASE TIME-USDT",
                    "displaySymbol" => "TIME-USDT",
                    "symbol" => "COINBASE:TIME-USDT"
                ],
                [
                    "description" => "COINBASE OMG-EUR",
                    "displaySymbol" => "OMG-EUR",
                    "symbol" => "COINBASE:OMG-EUR"
                ],
                [
                    "description" => "COINBASE DOGE-USD",
                    "displaySymbol" => "DOGE-USD",
                    "symbol" => "COINBASE:DOGE-USD"
                ],
                [
                    "description" => "COINBASE GFI-USD",
                    "displaySymbol" => "GFI-USD",
                    "symbol" => "COINBASE:GFI-USD"
                ],
                [
                    "description" => "COINBASE LTC-USD",
                    "displaySymbol" => "LTC-USD",
                    "symbol" => "COINBASE:LTC-USD"
                ],
                [
                    "description" => "COINBASE ADA-BTC",
                    "displaySymbol" => "ADA-BTC",
                    "symbol" => "COINBASE:ADA-BTC"
                ],
                [
                    "description" => "COINBASE DYP-USDT",
                    "displaySymbol" => "DYP-USDT",
                    "symbol" => "COINBASE:DYP-USDT"
                ],
                [
                    "description" => "COINBASE TIME-USD",
                    "displaySymbol" => "TIME-USD",
                    "symbol" => "COINBASE:TIME-USD"
                ],
                [
                    "description" => "COINBASE QSP-USD",
                    "displaySymbol" => "QSP-USD",
                    "symbol" => "COINBASE:QSP-USD"
                ],
                [
                    "description" => "COINBASE DOGE-USDT",
                    "displaySymbol" => "DOGE-USDT",
                    "symbol" => "COINBASE:DOGE-USDT"
                ],
                [
                    "description" => "COINBASE YFI-USD",
                    "displaySymbol" => "YFI-USD",
                    "symbol" => "COINBASE:YFI-USD"
                ],
                [
                    "description" => "COINBASE SOL-USD",
                    "displaySymbol" => "SOL-USD",
                    "symbol" => "COINBASE:SOL-USD"
                ],
                [
                    "description" => "COINBASE KARRAT/USD",
                    "displaySymbol" => "KARRAT/USD",
                    "symbol" => "COINBASE:KARRAT-USD"
                ],
                [
                    "description" => "COINBASE VGX-EUR",
                    "displaySymbol" => "VGX-EUR",
                    "symbol" => "COINBASE:VGX-EUR"
                ],
                [
                    "description" => "COINBASE ETC-GBP",
                    "displaySymbol" => "ETC-GBP",
                    "symbol" => "COINBASE:ETC-GBP"
                ],
                [
                    "description" => "COINBASE RNDR-USDT",
                    "displaySymbol" => "RNDR-USDT",
                    "symbol" => "COINBASE:RNDR-USDT"
                ],
                [
                    "description" => "COINBASE SHIB-USDT",
                    "displaySymbol" => "SHIB-USDT",
                    "symbol" => "COINBASE:SHIB-USDT"
                ],
                [
                    "description" => "COINBASE BTC-EUR",
                    "displaySymbol" => "BTC-EUR",
                    "symbol" => "COINBASE:BTC-EUR"
                ],
                [
                    "description" => "COINBASE MAGIC-USD",
                    "displaySymbol" => "MAGIC-USD",
                    "symbol" => "COINBASE:MAGIC-USD"
                ],
                [
                    "description" => "COINBASE BLUR-USD",
                    "displaySymbol" => "BLUR-USD",
                    "symbol" => "COINBASE:BLUR-USD"
                ],
                [
                    "description" => "COINBASE LINK-BTC",
                    "displaySymbol" => "LINK-BTC",
                    "symbol" => "COINBASE:LINK-BTC"
                ],
                [
                    "description" => "COINBASE AST-USD",
                    "displaySymbol" => "AST-USD",
                    "symbol" => "COINBASE:AST-USD"
                ],
                [
                    "description" => "COINBASE RLC-USD",
                    "displaySymbol" => "RLC-USD",
                    "symbol" => "COINBASE:RLC-USD"
                ],
                [
                    "description" => "COINBASE GLM-USD",
                    "displaySymbol" => "GLM-USD",
                    "symbol" => "COINBASE:GLM-USD"
                ],
                [
                    "description" => "COINBASE BAT-BTC",
                    "displaySymbol" => "BAT-BTC",
                    "symbol" => "COINBASE:BAT-BTC"
                ],
                [
                    "description" => "COINBASE AKT/USD",
                    "displaySymbol" => "AKT/USD",
                    "symbol" => "COINBASE:AKT-USD"
                ],
                [
                    "description" => "COINBASE SUSHI-GBP",
                    "displaySymbol" => "SUSHI-GBP",
                    "symbol" => "COINBASE:SUSHI-GBP"
                ],
                [
                    "description" => "COINBASE VOXEL-USD",
                    "displaySymbol" => "VOXEL-USD",
                    "symbol" => "COINBASE:VOXEL-USD"
                ],
                [
                    "description" => "COINBASE CGLD-EUR",
                    "displaySymbol" => "CGLD-EUR",
                    "symbol" => "COINBASE:CGLD-EUR"
                ],
                [
                    "description" => "COINBASE ATA-USD",
                    "displaySymbol" => "ATA-USD",
                    "symbol" => "COINBASE:ATA-USD"
                ],
                [
                    "description" => "COINBASE PIRATE/USD",
                    "displaySymbol" => "PIRATE/USD",
                    "symbol" => "COINBASE:PIRATE-USD"
                ],
                [
                    "description" => "COINBASE DDX-EUR",
                    "displaySymbol" => "DDX-EUR",
                    "symbol" => "COINBASE:DDX-EUR"
                ],
                [
                    "description" => "COINBASE FARM-USD",
                    "displaySymbol" => "FARM-USD",
                    "symbol" => "COINBASE:FARM-USD"
                ],
                [
                    "description" => "COINBASE SUI-USD",
                    "displaySymbol" => "SUI-USD",
                    "symbol" => "COINBASE:SUI-USD"
                ],
                [
                    "description" => "COINBASE EURC/USD",
                    "displaySymbol" => "EURC/USD",
                    "symbol" => "COINBASE:EURC-USD"
                ],
                [
                    "description" => "COINBASE EOS-EUR",
                    "displaySymbol" => "EOS-EUR",
                    "symbol" => "COINBASE:EOS-EUR"
                ],
                [
                    "description" => "COINBASE UST-USD",
                    "displaySymbol" => "UST-USD",
                    "symbol" => "COINBASE:UST-USD"
                ],
                [
                    "description" => "COINBASE ICP-USDT",
                    "displaySymbol" => "ICP-USDT",
                    "symbol" => "COINBASE:ICP-USDT"
                ],
                [
                    "description" => "COINBASE VELO-USD",
                    "displaySymbol" => "VELO-USD",
                    "symbol" => "COINBASE:VELO-USD"
                ],
                [
                    "description" => "COINBASE ZRX-BTC",
                    "displaySymbol" => "ZRX-BTC",
                    "symbol" => "COINBASE:ZRX-BTC"
                ],
                [
                    "description" => "COINBASE LOKA-USD",
                    "displaySymbol" => "LOKA-USD",
                    "symbol" => "COINBASE:LOKA-USD"
                ],
                [
                    "description" => "COINBASE PLA-USD",
                    "displaySymbol" => "PLA-USD",
                    "symbol" => "COINBASE:PLA-USD"
                ],
                [
                    "description" => "COINBASE AVT-USD",
                    "displaySymbol" => "AVT-USD",
                    "symbol" => "COINBASE:AVT-USD"
                ],
                [
                    "description" => "COINBASE BICO-EUR",
                    "displaySymbol" => "BICO-EUR",
                    "symbol" => "COINBASE:BICO-EUR"
                ],
                [
                    "description" => "COINBASE STX-USD",
                    "displaySymbol" => "STX-USD",
                    "symbol" => "COINBASE:STX-USD"
                ],
                [
                    "description" => "COINBASE LCX-USDT",
                    "displaySymbol" => "LCX-USDT",
                    "symbol" => "COINBASE:LCX-USDT"
                ],
                [
                    "description" => "COINBASE UNI-EUR",
                    "displaySymbol" => "UNI-EUR",
                    "symbol" => "COINBASE:UNI-EUR"
                ],
                [
                    "description" => "COINBASE SPA-USD",
                    "displaySymbol" => "SPA-USD",
                    "symbol" => "COINBASE:SPA-USD"
                ],
                [
                    "description" => "COINBASE MASK-USD",
                    "displaySymbol" => "MASK-USD",
                    "symbol" => "COINBASE:MASK-USD"
                ],
                [
                    "description" => "COINBASE RBN-USD",
                    "displaySymbol" => "RBN-USD",
                    "symbol" => "COINBASE:RBN-USD"
                ]
                ],
        ];

        DB::table('assets')->truncate();
        foreach ($assets as $category => $items) {
            foreach ($items as $asset) {
                if($category == "cryptocurrencies"){
                    $asset['displaySymbol'] = str_replace("-", "", $asset['symbol']);
                }
                DB::table('assets')->insertOrIgnore([
                    'symbol' => $asset['symbol'],
                    'display_symbol' => $asset['displaySymbol'],
                    'name' => $asset['description'],
                    'asset_group' => $category,
                    'exchange_float_type' => "fixed",
                    'exchange_float' => 0,
                ]);
            }
        }
    }
}
