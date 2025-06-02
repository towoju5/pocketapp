<?php

namespace App\Http\Controllers;

use App\Events\ExpressTradeEvent;
use App\Models\ExpressTrade;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpressTradeController extends Controller
{
    public function index()
    {
        return view('express-trade');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [            
            'asset' => 'required|string',
            'direction' => 'required|in:up,down',
            'duration' => 'required|string' // assuming HH:ii:ss
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();
        $data = $validator->validated();
        $data['user_id'] = $user->id;
        if($express = ExpressTrade::create($data)) {
            broadcast(new ExpressTradeEvent($express));
            return response()->json([
                'status' => true, 
                'message' => 'Trade placed successfully!', 
                'trade' => $express,
                'html' => view("mini-pages.express-trade", compact(['trade' => $express]))->render()
            ]);
        }
        
        return response()->json(['status' => false, 'message' => 'Error placing trade']);
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'trades' => 'required|array|min:1',
            'trades.*.direction' => 'in:up,down',
            'trades.*.duration'  => 'integer|min:1',
        ]);

        return response()->json(['message' => "Endpoint development in progress"], 200);

        foreach ($validated['trades'] as $symbol => $trade) {
            Trade::create([
                'user_id'   => auth()->id(),
                'symbol'    => $symbol,
                'direction' => $trade['direction'],
                'duration'  => $trade['duration'],
            ]);
        }

        return back()->with('success', 'Trades executed!');
    }

}
