<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Resyncs `assets` to the broker-supplied catalog (assets.json) provided
 * 2026-08-19 — 183 symbols across Stock/Index/Cryptocurrency/Currency/
 * Commodity — the same list BrokeretAssetSeeder now seeds fresh installs
 * from, replacing the earlier 326-symbol snapshot from 2026-08-14 (see
 * sync_brokeret_asset_catalog).
 *
 * Scoped to price_source='brokeret' only — unlike the 2026-08-14 migration,
 * this app now also has the fully independent DataFeedCl pipeline
 * (price_source='datafeedcl', self-registered by DataFeedClService, no
 * static catalog), which must NOT be touched here.
 *
 *  1. Every price_source='brokeret' row whose symbol is NOT in the new
 *     canonical list gets soft-deleted (Assets uses SoftDeletes). Safe
 *     against BrokeretFeedService::ensureAssetRegistered() resurrecting a
 *     dropped symbol if Brokeret keeps streaming it: that call is a raw
 *     insertOrIgnore() against the unique `symbol` index, which still finds
 *     the (soft-deleted) row and skips re-inserting.
 *
 *  2. Every kept row gets name/asset_group/asset_profit_margin/is_otc/
 *     is_active/extra_data updated to this snapshot's values. is_active is
 *     reasserted from the broker's own per-symbol `active` flag (non-OTC
 *     instruments here are inactive outside their exchange's trading
 *     hours) rather than forced to true.
 */
return new class extends Migration
{
    private const CATALOG = [
            ['symbol' => '#AAPL', 'name' => 'Apple', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#AAPL_otc', 'name' => 'Apple OTC', 'category' => 'STOCK', 'payout' => 82, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#AXP', 'name' => 'American Express', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#AXP_otc', 'name' => 'American Express OTC', 'category' => 'STOCK', 'payout' => 82, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#BA', 'name' => 'Boeing Company', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#BA_otc', 'name' => 'Boeing Company OTC', 'category' => 'STOCK', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#CSCO', 'name' => 'Cisco', 'category' => 'STOCK', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#CSCO_otc', 'name' => 'Cisco OTC', 'category' => 'STOCK', 'payout' => 88, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#FB', 'name' => 'FACEBOOK INC', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#FB_otc', 'name' => 'FACEBOOK INC OTC', 'category' => 'STOCK', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#INTC', 'name' => 'Intel', 'category' => 'STOCK', 'payout' => 25, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#INTC_otc', 'name' => 'Intel OTC', 'category' => 'STOCK', 'payout' => 37, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#JNJ', 'name' => 'Johnson & Johnson', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#JNJ_otc', 'name' => 'Johnson & Johnson OTC', 'category' => 'STOCK', 'payout' => 87, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#JPM', 'name' => 'JPMorgan Chase & Co', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#MCD', 'name' => 'McDonald\'s', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#MCD_otc', 'name' => 'McDonald\'s OTC', 'category' => 'STOCK', 'payout' => 61, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#MSFT', 'name' => 'Microsoft', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#MSFT_otc', 'name' => 'Microsoft OTC', 'category' => 'STOCK', 'payout' => 71, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#PFE', 'name' => 'Pfizer Inc', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#PFE_otc', 'name' => 'Pfizer Inc OTC', 'category' => 'STOCK', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#TSLA', 'name' => 'Tesla', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#TSLA_otc', 'name' => 'Tesla OTC', 'category' => 'STOCK', 'payout' => 51, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '#XOM', 'name' => 'ExxonMobil', 'category' => 'STOCK', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => '#XOM_otc', 'name' => 'ExxonMobil OTC', 'category' => 'STOCK', 'payout' => 75, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => '100GBP', 'name' => '100GBP', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => '100GBP_otc', 'name' => '100GBP OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'ADA-USD_otc', 'name' => 'Cardano OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 47, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AEDCNY_otc', 'name' => 'AED/CNY OTC', 'category' => 'CURRENCY', 'payout' => 82, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AEX25', 'name' => 'AEX 25', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'AMD_otc', 'name' => 'Advanced Micro Devices OTC', 'category' => 'STOCK', 'payout' => 29, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AMZN_otc', 'name' => 'Amazon OTC', 'category' => 'STOCK', 'payout' => 79, 'is_otc' => true, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AUDCAD', 'name' => 'AUD/CAD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'AUDCAD_otc', 'name' => 'AUD/CAD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AUDCHF', 'name' => 'AUD/CHF', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'AUDCHF_otc', 'name' => 'AUD/CHF OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AUDJPY', 'name' => 'AUD/JPY', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'AUDJPY_otc', 'name' => 'AUD/JPY OTC', 'category' => 'CURRENCY', 'payout' => 82, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AUDNZD_otc', 'name' => 'AUD/NZD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AUDUSD', 'name' => 'AUD/USD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'AUDUSD_otc', 'name' => 'AUD/USD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AUS200', 'name' => 'AUS 200', 'category' => 'INDEX', 'payout' => 37, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'AUS200_otc', 'name' => 'AUS 200 OTC', 'category' => 'INDEX', 'payout' => 67, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'AVAX_otc', 'name' => 'Avalanche OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BABA', 'name' => 'Alibaba', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => 'BABA_otc', 'name' => 'Alibaba OTC', 'category' => 'STOCK', 'payout' => 46, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BCHEUR', 'name' => 'BCH/EUR', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BCHGBP', 'name' => 'BCH/GBP', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BCHJPY', 'name' => 'BCH/JPY', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BHDCNY_otc', 'name' => 'BHD/CNY OTC', 'category' => 'CURRENCY', 'payout' => 73, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BITB_otc', 'name' => 'Bitcoin ETF OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BNB-USD_otc', 'name' => 'BNB OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 68, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BTCGBP', 'name' => 'BTC/GBP', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BTCJPY', 'name' => 'BTC/JPY', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BTCUSD', 'name' => 'Bitcoin', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'BTCUSD_otc', 'name' => 'Bitcoin OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'CAC40', 'name' => 'CAC 40', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'CADCHF', 'name' => 'CAD/CHF', 'category' => 'CURRENCY', 'payout' => 47, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'CADCHF_otc', 'name' => 'CAD/CHF OTC', 'category' => 'CURRENCY', 'payout' => 44, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'CADJPY', 'name' => 'CAD/JPY', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'CADJPY_otc', 'name' => 'CAD/JPY OTC', 'category' => 'CURRENCY', 'payout' => 79, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'CHFJPY', 'name' => 'CHF/JPY', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'CHFJPY_otc', 'name' => 'CHF/JPY OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'CHFNOK_otc', 'name' => 'CHF/NOK OTC', 'category' => 'CURRENCY', 'payout' => 91, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'CITI', 'name' => 'Citigroup Inc', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => 'CITI_otc', 'name' => 'Citigroup Inc OTC', 'category' => 'STOCK', 'payout' => 23, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'COIN_otc', 'name' => 'Coinbase Global OTC', 'category' => 'STOCK', 'payout' => 66, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'D30EUR', 'name' => 'D30/EUR', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'D30EUR_otc', 'name' => 'D30EUR OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'DASH_USD', 'name' => 'Dash', 'category' => 'CRYPTOCURRENCY', 'payout' => 25, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'DJI30', 'name' => 'DJI30', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'DJI30_otc', 'name' => 'DJI30 OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'DOGE_otc', 'name' => 'Dogecoin OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 60, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'DOTUSD_otc', 'name' => 'Polkadot OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 40, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'E35EUR', 'name' => 'E35EUR', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'E35EUR_otc', 'name' => 'E35EUR OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'E50EUR', 'name' => 'E50/EUR', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'E50EUR_otc', 'name' => 'E50EUR OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'ETHUSD', 'name' => 'Ethereum', 'category' => 'CRYPTOCURRENCY', 'payout' => 40, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'ETHUSD_otc', 'name' => 'Ethereum OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 39, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURAUD', 'name' => 'EUR/AUD', 'category' => 'CURRENCY', 'payout' => 33, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'EURCAD', 'name' => 'EUR/CAD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'EURCHF', 'name' => 'EUR/CHF', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'EURCHF_otc', 'name' => 'EUR/CHF OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURGBP', 'name' => 'EUR/GBP', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'EURGBP_otc', 'name' => 'EUR/GBP OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURHUF_otc', 'name' => 'EUR/HUF OTC', 'category' => 'CURRENCY', 'payout' => 63, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURJPY', 'name' => 'EUR/JPY', 'category' => 'CURRENCY', 'payout' => 58, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'EURJPY_otc', 'name' => 'EUR/JPY OTC', 'category' => 'CURRENCY', 'payout' => 90, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURNZD_otc', 'name' => 'EUR/NZD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURRUB_otc', 'name' => 'EUR/RUB OTC', 'category' => 'CURRENCY', 'payout' => 91, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURTRY_otc', 'name' => 'EUR/TRY OTC', 'category' => 'CURRENCY', 'payout' => 71, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'EURUSD', 'name' => 'EUR/USD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'EURUSD_otc', 'name' => 'EUR/USD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'F40EUR', 'name' => 'F40/EUR', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'F40EUR_otc', 'name' => 'F40EUR OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'FDX_otc', 'name' => 'FedEx OTC', 'category' => 'STOCK', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'GBPAUD', 'name' => 'GBP/AUD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'GBPAUD_otc', 'name' => 'GBP/AUD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'GBPCAD', 'name' => 'GBP/CAD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787112600, 'min_expiration' => 30],
            ['symbol' => 'GBPCHF', 'name' => 'GBP/CHF', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787112600, 'min_expiration' => 30],
            ['symbol' => 'GBPJPY', 'name' => 'GBP/JPY', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'GBPJPY_otc', 'name' => 'GBP/JPY OTC', 'category' => 'CURRENCY', 'payout' => 82, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'GBPUSD', 'name' => 'GBP/USD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'GBPUSD_otc', 'name' => 'GBP/USD OTC', 'category' => 'CURRENCY', 'payout' => 68, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'GME_otc', 'name' => 'GameStop Corp OTC', 'category' => 'STOCK', 'payout' => 86, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'H33HKD', 'name' => 'HONG KONG 33', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'IRRUSD_otc', 'name' => 'IRR/USD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'JODCNY_otc', 'name' => 'JOD/CNY OTC', 'category' => 'CURRENCY', 'payout' => 53, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'JPN225', 'name' => 'JPN225', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'JPN225_otc', 'name' => 'JPN225 OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'KESUSD_otc', 'name' => 'KES/USD OTC', 'category' => 'CURRENCY', 'payout' => 49, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'LBPUSD_otc', 'name' => 'LBP/USD OTC', 'category' => 'CURRENCY', 'payout' => 40, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'LINK_otc', 'name' => 'Chainlink OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'LNKUSD', 'name' => 'Chainlink', 'category' => 'CRYPTOCURRENCY', 'payout' => 15, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'LTCUSD_otc', 'name' => 'Litecoin OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 64, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'MADUSD_otc', 'name' => 'MAD/USD OTC', 'category' => 'CURRENCY', 'payout' => 71, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'MARA_otc', 'name' => 'Marathon Digital Holdings OTC', 'category' => 'STOCK', 'payout' => 84, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'MATIC_otc', 'name' => 'Polygon OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 50, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'NASUSD', 'name' => 'US100', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'NASUSD_otc', 'name' => 'US100 OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'NFLX', 'name' => 'Netflix', 'category' => 'STOCK', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787157300, 'min_expiration' => 30],
            ['symbol' => 'NFLX_otc', 'name' => 'Netflix OTC', 'category' => 'STOCK', 'payout' => 71, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'NGNUSD_otc', 'name' => 'NGN/USD OTC', 'category' => 'CURRENCY', 'payout' => 87, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'NZDJPY_otc', 'name' => 'NZD/JPY OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'NZDUSD_otc', 'name' => 'NZD/USD OTC', 'category' => 'CURRENCY', 'payout' => 55, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'OMRCNY_otc', 'name' => 'OMR/CNY OTC', 'category' => 'CURRENCY', 'payout' => 70, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'PLTR_otc', 'name' => 'Palantir Technologies OTC', 'category' => 'STOCK', 'payout' => 29, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'QARCNY_otc', 'name' => 'QAR/CNY OTC', 'category' => 'CURRENCY', 'payout' => 68, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'SARCNY_otc', 'name' => 'SAR/CNY OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'SMI20', 'name' => 'SMI 20', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'SOL-USD_otc', 'name' => 'Solana OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 69, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'SP500', 'name' => 'SP500', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787179500, 'min_expiration' => 30],
            ['symbol' => 'SP500_otc', 'name' => 'SP500 OTC', 'category' => 'INDEX', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'SYPUSD_otc', 'name' => 'SYP/USD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'TNDUSD_otc', 'name' => 'TND/USD OTC', 'category' => 'CURRENCY', 'payout' => 79, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'TON-USD_otc', 'name' => 'Toncoin OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 66, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'TRX-USD_otc', 'name' => 'TRON OTC', 'category' => 'CRYPTOCURRENCY', 'payout' => 84, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'UAHUSD_otc', 'name' => 'UAH/USD OTC', 'category' => 'CURRENCY', 'payout' => 76, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'UKBrent', 'name' => 'Brent Oil', 'category' => 'COMMODITY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'UKBrent_otc', 'name' => 'Brent Oil OTC', 'category' => 'COMMODITY', 'payout' => 80, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USCrude', 'name' => 'WTI Crude Oil', 'category' => 'COMMODITY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USCrude_otc', 'name' => 'WTI Crude Oil OTC', 'category' => 'COMMODITY', 'payout' => 80, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDARS_otc', 'name' => 'USD/ARS OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDBDT_otc', 'name' => 'USD/BDT OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDBRL_otc', 'name' => 'USD/BRL OTC', 'category' => 'CURRENCY', 'payout' => 88, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDCAD', 'name' => 'USD/CAD', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'USDCAD_otc', 'name' => 'USD/CAD OTC', 'category' => 'CURRENCY', 'payout' => 31, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDCHF', 'name' => 'USD/CHF', 'category' => 'CURRENCY', 'payout' => 42, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'USDCHF_otc', 'name' => 'USD/CHF OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDCLP_otc', 'name' => 'USD/CLP OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDCNH_otc', 'name' => 'USD/CNH OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDCOP_otc', 'name' => 'USD/COP OTC', 'category' => 'CURRENCY', 'payout' => 90, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDDZD_otc', 'name' => 'USD/DZD OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDEGP_otc', 'name' => 'USD/EGP OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDIDR_otc', 'name' => 'USD/IDR OTC', 'category' => 'CURRENCY', 'payout' => 59, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDINR_otc', 'name' => 'USD/INR OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDJPY', 'name' => 'USD/JPY', 'category' => 'CURRENCY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787104800, 'min_expiration' => 30],
            ['symbol' => 'USDJPY_otc', 'name' => 'USD/JPY OTC', 'category' => 'CURRENCY', 'payout' => 81, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDMXN_otc', 'name' => 'USD/MXN OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDMYR_otc', 'name' => 'USD/MYR OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDPHP_otc', 'name' => 'USD/PHP OTC', 'category' => 'CURRENCY', 'payout' => 41, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDPKR_otc', 'name' => 'USD/PKR OTC', 'category' => 'CURRENCY', 'payout' => 85, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDRUB_otc', 'name' => 'USD/RUB OTC', 'category' => 'CURRENCY', 'payout' => 31, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDSGD_otc', 'name' => 'USD/SGD OTC', 'category' => 'CURRENCY', 'payout' => 57, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDTHB_otc', 'name' => 'USD/THB OTC', 'category' => 'CURRENCY', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'USDVND_otc', 'name' => 'USD/VND OTC', 'category' => 'CURRENCY', 'payout' => 21, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'VISA_otc', 'name' => 'VISA OTC', 'category' => 'STOCK', 'payout' => 92, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'VIX_otc', 'name' => 'VIX OTC', 'category' => 'STOCK', 'payout' => 29, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XAGEUR', 'name' => 'XAG/EUR', 'category' => 'COMMODITY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XAGUSD', 'name' => 'Silver', 'category' => 'COMMODITY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XAGUSD_otc', 'name' => 'Silver OTC', 'category' => 'COMMODITY', 'payout' => 80, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XAUEUR', 'name' => 'XAU/EUR', 'category' => 'COMMODITY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XAUUSD', 'name' => 'Gold', 'category' => 'COMMODITY', 'payout' => 50, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XAUUSD_otc', 'name' => 'Gold OTC', 'category' => 'COMMODITY', 'payout' => 80, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XNGUSD', 'name' => 'Natural Gas', 'category' => 'COMMODITY', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XNGUSD_otc', 'name' => 'Natural Gas OTC', 'category' => 'COMMODITY', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XPDUSD', 'name' => 'Palladium spot', 'category' => 'COMMODITY', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XPDUSD_otc', 'name' => 'Palladium spot OTC', 'category' => 'COMMODITY', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XPTUSD', 'name' => 'Platinum spot', 'category' => 'COMMODITY', 'payout' => 45, 'is_otc' => false, 'is_active' => false, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'XPTUSD_otc', 'name' => 'Platinum spot OTC', 'category' => 'COMMODITY', 'payout' => 45, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'YERUSD_otc', 'name' => 'YER/USD OTC', 'category' => 'CURRENCY', 'payout' => 68, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
            ['symbol' => 'ZARUSD_otc', 'name' => 'ZAR/USD OTC', 'category' => 'CURRENCY', 'payout' => 29, 'is_otc' => true, 'is_active' => true, 'exp_time' => 1787184000, 'min_expiration' => 30],
    ];

    public function up(): void
    {
        $canonicalSymbols = array_column(self::CATALOG, 'symbol');

        DB::table('assets')
            ->where('price_source', 'brokeret')
            ->whereNull('deleted_at')
            ->whereNotIn('symbol', $canonicalSymbols)
            ->update(['deleted_at' => now()]);

        foreach (self::CATALOG as $entry) {
            DB::table('assets')
                ->where('symbol', $entry['symbol'])
                ->whereNull('deleted_at')
                ->update([
                    'name' => $entry['name'],
                    'asset_group' => $entry['category'],
                    'asset_profit_margin' => $entry['payout'] / 100,
                    'extra_data' => json_encode([
                        'expTime' => $entry['exp_time'],
                        'min_expiration' => $entry['min_expiration'],
                    ]),
                    'is_otc' => $entry['is_otc'],
                    'price_source' => 'brokeret',
                    'is_active' => $entry['is_active'],
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Restores whatever this migration soft-deleted. Doesn't attempt to
     * recall each row's pre-migration name/asset_group/margin individually
     * — this is a best-effort rollback, not a perfect inverse.
     */
    public function down(): void
    {
        $canonicalSymbols = array_column(self::CATALOG, 'symbol');

        DB::table('assets')
            ->where('price_source', 'brokeret')
            ->whereNotIn('symbol', $canonicalSymbols)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);
    }
};
