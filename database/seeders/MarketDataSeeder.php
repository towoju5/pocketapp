<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('market_data')->insert([
            // Major Pairs
            ["symbol" => "frxAUDJPY", "name" => "AUD/JPY", "category" => "Forex"],
            ["symbol" => "frxAUDUSD", "name" => "AUD/USD", "category" => "Forex"],
            ["symbol" => "frxEURAUD", "name" => "EUR/AUD", "category" => "Forex"],
            ["symbol" => "frxEURCAD", "name" => "EUR/CAD", "category" => "Forex"],
            ["symbol" => "frxEURCHF", "name" => "EUR/CHF", "category" => "Forex"],
            ["symbol" => "frxEURGBP", "name" => "EUR/GBP", "category" => "Forex"],
            ["symbol" => "frxEURJPY", "name" => "EUR/JPY", "category" => "Forex"],
            ["symbol" => "frxEURUSD", "name" => "EUR/USD", "category" => "Forex"],
            ["symbol" => "frxGBPAUD", "name" => "GBP/AUD", "category" => "Forex"],
            ["symbol" => "frxGBPJPY", "name" => "GBP/JPY", "category" => "Forex"],
            ["symbol" => "frxGBPUSD", "name" => "GBP/USD", "category" => "Forex"],
            ["symbol" => "frxUSDCAD", "name" => "USD/CAD", "category" => "Forex"],
            ["symbol" => "frxUSDCHF", "name" => "USD/CHF", "category" => "Forex"],
            ["symbol" => "frxUSDJPY", "name" => "USD/JPY", "category" => "Forex"],

            // Minor Pairs
            ["symbol" => "frxAUDCAD", "name" => "AUD/CAD", "category" => "Forex"],
            ["symbol" => "frxAUDCHF", "name" => "AUD/CHF", "category" => "Forex"],
            ["symbol" => "frxAUDNZD", "name" => "AUD/NZD", "category" => "Forex"],
            ["symbol" => "frxEURNZD", "name" => "EUR/NZD", "category" => "Forex"],
            ["symbol" => "frxGBPCAD", "name" => "GBP/CAD", "category" => "Forex"],
            ["symbol" => "frxGBPCHF", "name" => "GBP/CHF", "category" => "Forex"],
            ["symbol" => "frxGBPNZD", "name" => "GBP/NZD", "category" => "Forex"],
            ["symbol" => "frxNZDJPY", "name" => "NZD/JPY", "category" => "Forex"],
            ["symbol" => "frxNZDUSD", "name" => "NZD/USD", "category" => "Forex"],
            ["symbol" => "frxUSDMXN", "name" => "USD/MXN", "category" => "Forex"],
            ["symbol" => "frxUSDPLN", "name" => "USD/PLN", "category" => "Forex"],

            // Indices
            ["symbol" => "OTC_US500", "name" => "US 500", "category" => "American Indices"],
            ["symbol" => "OTC_USTECH100", "name" => "US Tech 100", "category" => "American Indices"],
            ["symbol" => "OTC_WALLST30", "name" => "Wall Street 30", "category" => "American Indices"],
            ["symbol" => "OTC_AUS200", "name" => "Australia 200", "category" => "Asian Indices"],
            ["symbol" => "OTC_HK50", "name" => "Hong Kong 50", "category" => "Asian Indices"],
            ["symbol" => "OTC_JP225", "name" => "Japan 225", "category" => "Asian Indices"],
            ["symbol" => "OTC_EURO50", "name" => "Euro 50", "category" => "European Indices"],
            ["symbol" => "OTC_FR40", "name" => "France 40", "category" => "European Indices"],
            ["symbol" => "OTC_GER40", "name" => "Germany 40", "category" => "European Indices"],
            ["symbol" => "OTC_NETH25", "name" => "Netherlands 25", "category" => "European Indices"],
            ["symbol" => "OTC_SWISS20", "name" => "Swiss 20", "category" => "European Indices"],
            ["symbol" => "OTC_UK100", "name" => "UK 100", "category" => "European Indices"],

            // Cryptocurrencies
            ["symbol" => "cryBTCUSD", "name" => "BTC/USD", "category" => "Cryptocurrencies"],
            ["symbol" => "cryETHUSD", "name" => "ETH/USD", "category" => "Cryptocurrencies"],

            // Metals
            ["symbol" => "frxGOLDUSD", "name" => "Gold/USD", "category" => "Metals"],
            ["symbol" => "frxPALLADIUMUSD", "name" => "Palladium/USD", "category" => "Metals"],
            ["symbol" => "frxPLATINUMUSD", "name" => "Platinum/USD", "category" => "Metals"],
            ["symbol" => "frxSILVERUSD", "name" => "Silver/USD", "category" => "Metals"],
        ]);
    }
}
