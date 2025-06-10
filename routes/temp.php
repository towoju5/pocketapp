<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Necmicolak\YahooFinance\FinanceAsset;
use Necmicolak\YahooFinance\SearchAsset;

Route::get('s', function (Request $r) {
    $asset = SearchAsset::search($r->q);
    return response()->json($asset);
});

Route::get('m', function (Request $r) {
    $asset = new FinanceAsset($r->q);
    if($asset->getMeta() == null){
        return response()->json(["error" => "Asset not found"]);
    }
    return response()->json($asset->getMeta());
});

Route::get('c', function (Request $r) {
    $asset = $asset = new FinanceAsset($r->q);
    if($asset->getChart() == null){
        return response()->json(["error" => "Asset not found"]);
    }
    return response()->json($asset->getChart());
});

Route::get('chart/stream/{asset}', ['uses' => 'App\Http\Controllers\ChartController@stream'])->name('chart.stream');