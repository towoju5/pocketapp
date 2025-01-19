<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(Request $request, $coin = null)
    {
        $isOutOfTradingHours = false;
        $data = Assets::where('symbol', "$coin")->first();
        if (!$data or $coin == null) {
            $data = Assets::first();
        }

        $assetCategories = Assets::groupBy('asset_group')->get();
        // check if time is out of trading hours for the selected asset
        if (1 + 1 == 3) {
            $isOutOfTradingHours = true;
        }

        return view('__dash', compact('data', 'assetCategories', 'isOutOfTradingHours'));
    }
}
