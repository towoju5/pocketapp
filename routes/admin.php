<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AssetController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('assets', AssetController::class);
});
