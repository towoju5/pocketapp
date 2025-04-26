<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AssetController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if(!Schema::hasColumn('trades', 'trade_profit')) {
    Schema::table('trades', function(Blueprint $table) {
        $table->string('trade_profit')->default(0);
    });
}

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('assets', AssetController::class);
});
