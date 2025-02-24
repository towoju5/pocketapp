<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketDataSeeder extends Seeder
{
    public function run()
    {
        DB::table('market_data')->insert([
            // Major Pairs
            ["symbol" => "frxAUDJPY", "name" => "AUD/JPY", "category" => "Forex", "yahoo_ticker" => "AUDJPY=X"],
            ["symbol" => "frxAUDUSD", "name" => "AUD/USD", "category" => "Forex", "yahoo_ticker" => "AUDUSD=X"],
            ["symbol" => "frxEURAUD", "name" => "EUR/AUD", "category" => "Forex", "yahoo_ticker" => "EURAUD=X"],
            ["symbol" => "frxEURCAD", "name" => "EUR/CAD", "category" => "Forex", "yahoo_ticker" => "EURCAD=X"],
            ["symbol" => "frxEURCHF", "name" => "EUR/CHF", "category" => "Forex", "yahoo_ticker" => "EURCHF=X"],
            ["symbol" => "frxEURGBP", "name" => "EUR/GBP", "category" => "Forex", "yahoo_ticker" => "EURGBP=X"],
            ["symbol" => "frxEURJPY", "name" => "EUR/JPY", "category" => "Forex", "yahoo_ticker" => "EURJPY=X"],
            ["symbol" => "frxEURUSD", "name" => "EUR/USD", "category" => "Forex", "yahoo_ticker" => "EURUSD=X"],
            ["symbol" => "frxGBPAUD", "name" => "GBP/AUD", "category" => "Forex", "yahoo_ticker" => "GBPAUD=X"],
            ["symbol" => "frxGBPJPY", "name" => "GBP/JPY", "category" => "Forex", "yahoo_ticker" => "GBPJPY=X"],
            ["symbol" => "frxGBPUSD", "name" => "GBP/USD", "category" => "Forex", "yahoo_ticker" => "GBPUSD=X"],
            ["symbol" => "frxUSDCAD", "name" => "USD/CAD", "category" => "Forex", "yahoo_ticker" => "USDCAD=X"],
            ["symbol" => "frxUSDCHF", "name" => "USD/CHF", "category" => "Forex", "yahoo_ticker" => "USDCHF=X"],
            ["symbol" => "frxUSDJPY", "name" => "USD/JPY", "category" => "Forex", "yahoo_ticker" => "USDJPY=X"],

            // Minor Pairs
            ["symbol" => "frxAUDCAD", "name" => "AUD/CAD", "category" => "Forex", "yahoo_ticker" => "AUDCAD=X"],
            ["symbol" => "frxAUDCHF", "name" => "AUD/CHF", "category" => "Forex", "yahoo_ticker" => "AUDCHF=X"],
            ["symbol" => "frxAUDNZD", "name" => "AUD/NZD", "category" => "Forex", "yahoo_ticker" => "AUDNZD=X"],
            ["symbol" => "frxEURNZD", "name" => "EUR/NZD", "category" => "Forex", "yahoo_ticker" => "EURNZD=X"],
            ["symbol" => "frxGBPCAD", "name" => "GBP/CAD", "category" => "Forex", "yahoo_ticker" => "GBPCAD=X"],
            ["symbol" => "frxGBPCHF", "name" => "GBP/CHF", "category" => "Forex", "yahoo_ticker" => "GBPCHF=X"],
            ["symbol" => "frxGBPNZD", "name" => "GBP/NZD", "category" => "Forex", "yahoo_ticker" => "GBPNZD=X"],
            ["symbol" => "frxNZDJPY", "name" => "NZD/JPY", "category" => "Forex", "yahoo_ticker" => "NZDJPY=X"],
            ["symbol" => "frxNZDUSD", "name" => "NZD/USD", "category" => "Forex", "yahoo_ticker" => "NZDUSD=X"],
            ["symbol" => "frxUSDMXN", "name" => "USD/MXN", "category" => "Forex", "yahoo_ticker" => "USDMXN=X"],
            ["symbol" => "frxUSDPLN", "name" => "USD/PLN", "category" => "Forex", "yahoo_ticker" => "USDPLN=X"],

            // Cryptocurrencies
            ["symbol" => "cryBTCUSD", "name" => "BTC/USD", "category" => "Cryptocurrencies", "yahoo_ticker" => "BTC-USD"],
            ["symbol" => "cryETHUSD", "name" => "ETH/USD", "category" => "Cryptocurrencies", "yahoo_ticker" => "ETH-USD"],

            // Metals
            ["symbol" => "frxGOLDUSD", "name" => "Gold/USD", "category" => "Metals", "yahoo_ticker" => "GC=F"],
            ["symbol" => "frxPALLADIUMUSD", "name" => "Palladium/USD", "category" => "Metals", "yahoo_ticker" => "PA=F"],
            ["symbol" => "frxPLATINUMUSD", "name" => "Platinum/USD", "category" => "Metals", "yahoo_ticker" => "PL=F"],
            ["symbol" => "frxSILVERUSD", "name" => "Silver/USD", "category" => "Metals", "yahoo_ticker" => "SI=F"],
        ]);
    }
}
