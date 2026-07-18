<?php

namespace App\Http\Controllers;

use App\Jobs\ExpressTradeJob;
use App\Models\Assets;
use App\Models\ExpressTrade;
use App\Services\PriceFeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpressTradeController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpressTrade::whereUserId(auth()->id())->with('asset');

        if ($request->filled('result') && $request->input('result') !== 'all') {
            $query->where('trade_status', $request->input('result'));
        }

        $trades = $query->latest()->paginate(20)->withQueryString();

        return view('express-trades.index', compact('trades'));
    }

    /**
     * Places a batch of express trades in one submission — one shared amount
     * across several assets/directions at once, each settling independently.
     */
    public function bulk(Request $request, PriceFeedService $priceFeed)
    {
        $validated = $request->validate([
            'trades' => 'required|array|min:1',
            'trades.*.asset_id' => 'required|integer|exists:assets,id',
            'trades.*.direction' => 'required|in:up,down',
            'trades.*.close_time' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        create_user_wallet($user->id);
        $walletSlug = $user->trade_wallet ?? 'qt_demo_usd';
        $amount = (float) $validated['amount'];
        $batchId = (string) Str::uuid();

        $created = [];
        $skipped = [];

        foreach ($validated['trades'] as $item) {
            $asset = Assets::find($item['asset_id']);
            if (!$asset) {
                $skipped[] = ['reason' => 'Asset not found'];
                continue;
            }
            if (!$priceFeed->isOnline($asset->symbol)) {
                $skipped[] = ['asset' => $asset->symbol, 'reason' => 'Asset is currently unavailable for trading'];
                continue;
            }
            $currentPrice = $priceFeed->getPrice($asset->symbol);
            if (null === $currentPrice) {
                $skipped[] = ['asset' => $asset->symbol, 'reason' => 'No live price available'];
                continue;
            }
            if (!debit_user($walletSlug, $amount, "Express Trade Order")) {
                $skipped[] = ['asset' => $asset->symbol, 'reason' => 'Insufficient wallet balance'];
                continue;
            }

            $percentageProfit = $asset->asset_profit_margin;
            $profitAmount = $percentageProfit * $amount;
            $closeSeconds = (int) $item['close_time'];

            $trade = ExpressTrade::create([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'trade_group_id' => (string) Str::uuid(),
                'trade_direction' => $item['direction'],
                'trade_type' => 'express_trade',
                'trade_amount' => $amount,
                'trade_close_time' => now()->addSeconds($closeSeconds)->toDateTimeString(),
                'trade_currency' => $asset->symbol,
                'start_price' => $currentPrice,
                'trade_wallet' => $walletSlug,
                'trade_profit' => $amount + $profitAmount,
                'trade_percentage' => $percentageProfit,
                'trade_status' => 'open',
                'trade_copied_count' => 0,
                'trade_extra_info' => ['batch_id' => $batchId],
            ]);

            ExpressTradeJob::dispatch($trade)->delay(now()->addSeconds($closeSeconds));
            $created[] = $trade;
        }

        if (empty($created)) {
            return response()->json([
                'status' => false,
                'message' => $skipped[0]['reason'] ?? 'No trades could be placed.',
                'skipped' => $skipped,
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => count($created) . ' trade(s) placed successfully!' . (count($skipped) ? ' ' . count($skipped) . ' skipped.' : ''),
            'trades' => $created,
            'skipped' => $skipped,
        ]);
    }
}
