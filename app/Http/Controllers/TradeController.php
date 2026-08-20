<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Services\TradePlacementService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Necmicolak\YahooFinance\FinanceAsset;

class TradeController extends Controller
{
    public function __construct()
    {
        if(!Schema::hasColumn('trades', 'trade_percentage')) {
            Schema::table('trades', function(Blueprint $table) {
                $table->string('trade_percentage')->default(0.9);
            });
        }
    }
    
    public function index(Request $request)
    {
        $currentMode = is_demo_wallet(auth()->user()->trade_wallet ?? 'qt_demo_usd') ? 'demo' : 'real';
        $mode = in_array($request->input('mode'), ['demo', 'real']) ? $request->input('mode') : $currentMode;

        $query = Trade::whereUserId(auth()->id())->where('trade_wallet', 'like', "%{$mode}%");

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }
        if ($request->filled('asset')) {
            $query->where('trade_currency', $request->input('asset'));
        }
        if ($request->filled('result') && $request->input('result') !== 'all') {
            $query->where('trade_status', $request->input('result'));
        }

        $trades = $query->latest()->paginate(20)->withQueryString();
        $assets = Trade::whereUserId(auth()->id())->where('trade_wallet', 'like', "%{$mode}%")->select('trade_currency')->distinct()->orderBy('trade_currency')->pluck('trade_currency');

        return view('trades.index', compact('trades', 'assets', 'mode'));
    }

    public function placeTrade(Request $request, TradePlacementService $placementService)
    {
        $validator = Validator::make($request->all(), TradePlacementService::validationRules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $placementService->place(auth()->user(), $validator->validated());

        return response()->json($result['body'], $result['status']);
    }


    private function getMarketPrice($market)
    {
        $asset = new FinanceAsset($market);
        if ($asset->getMeta() == null) {
            return response()->json(["error" => "Asset not found"]);
        }
        $finalPrice = $asset->getMeta()?->regularMarketPrice;

    }

    public function show($id)
    {
        $trade = Trade::findOrFail($id);
        return view('trades.show', compact('trade'));
    }

    public function store(Request $request, TradePlacementService $placementService)
    {
        return $this->placeTrade($request, $placementService);
    }

    public function socialTrades()
    {
        return social_trades();
    }
}
