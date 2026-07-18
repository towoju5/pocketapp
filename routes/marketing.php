<?php

use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'marketing.'], function () {
    Route::get('about', [MarketingController::class, 'about'])->name('about');
    Route::get('features', [MarketingController::class, 'features'])->name('features');
    Route::get('pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('faq', [MarketingController::class, 'faq'])->name('faq');
    Route::get('blog', [MarketingController::class, 'blog'])->name('blog');
    Route::get('contact', [MarketingController::class, 'contact'])->name('contact');
    Route::post('contact', [MarketingController::class, 'contactStore'])->name('contact.store');
});
