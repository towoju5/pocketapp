<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AssetController,
    UserController,
    CashbackRuleController,
    PromoCodeController,
    SignalController,
    WalletController,
    KycController,
    PlanController,
    PlanSubscriptionController,
    P2pOfferController,
    P2pTradeController,
    TaskController,
    TaskSubmissionController,
    ReferralController,
    ReferralCommissionRateController,
    SupportTicketController,
    SettingsController,
    PayoutController,
    DepositController,
    TradeController,
    ExpressTradeController,
    PaymentMethodController,
};

Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->as('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');

    Route::resources([
        'assets' => AssetController::class,
        'users' => UserController::class,
        'cashbacks' => CashbackRuleController::class,
        'promo-codes' => PromoCodeController::class,
        'signals' => SignalController::class,
        'plans' => PlanController::class,
        'tasks' => TaskController::class,
        'referral-rates' => ReferralCommissionRateController::class,
    ]);

    Route::post('signals/generate-ai', [SignalController::class, 'generateAi'])->name('signals.generate-ai');

    Route::resource('plan-subscriptions', PlanSubscriptionController::class)->only(['index', 'show']);
    Route::post('plan-subscriptions/{planSubscription}/cancel', [PlanSubscriptionController::class, 'cancel'])->name('plan-subscriptions.cancel');

    Route::resource('p2p-offers', P2pOfferController::class)->only(['index', 'show']);
    Route::resource('p2p-trades', P2pTradeController::class)->only(['index', 'show']);
    Route::post('p2p-trades/{p2pTrade}/resolve', [P2pTradeController::class, 'resolve'])->name('p2p-trades.resolve');

    Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::post('payment-methods/{paymentMethod}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');
    Route::delete('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

    Route::resource('task-submissions', TaskSubmissionController::class)->only(['index', 'show']);
    Route::post('task-submissions/{taskSubmission}/approve', [TaskSubmissionController::class, 'approve'])->name('task-submissions.approve');
    Route::post('task-submissions/{taskSubmission}/reject', [TaskSubmissionController::class, 'reject'])->name('task-submissions.reject');

    Route::get('referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::get('referrals/{user}', [ReferralController::class, 'show'])->name('referrals.show');

    Route::resource('support-tickets', SupportTicketController::class)->only(['index', 'show']);
    Route::post('support-tickets/{supportTicket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::patch('support-tickets/{supportTicket}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.status');

    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallets.index');
        Route::post('/{userId}/credit', [WalletController::class, 'credit'])->name('wallets.credit');
        Route::post('/{userId}/debit', [WalletController::class, 'debit'])->name('wallets.debit');
    });
    Route::post('{user}/wallet', [UserController::class, 'updateWalletAction'])->name('wallets.update');

    Route::prefix('kyc')->as('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::get('/{kyc}', [KycController::class, 'show'])->name('show');
        Route::post('/{kyc}/approve', [KycController::class, 'approve'])->name('approve');
        Route::post('/{kyc}/reject', [KycController::class, 'reject'])->name('reject');
    });

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('payouts', PayoutController::class)->only(['index', 'show']);
    Route::post('payouts/{payout}/approve', [PayoutController::class, 'approve'])->name('payouts.approve');
    Route::post('payouts/{payout}/reject', [PayoutController::class, 'reject'])->name('payouts.reject');

    Route::resource('deposits', DepositController::class)->only(['index', 'show']);

    Route::resource('trades', TradeController::class)->only(['index', 'show']);
    Route::post('trades/{trade}/force-win', [TradeController::class, 'forceWin'])->name('trades.force-win');
    Route::post('trades/{trade}/force-lose', [TradeController::class, 'forceLose'])->name('trades.force-lose');
    Route::post('trades/{trade}/void', [TradeController::class, 'void'])->name('trades.void');
    Route::resource('express-trades', ExpressTradeController::class)->only(['index', 'show']);
    Route::post('express-trades/{expressTrade}/force-win', [ExpressTradeController::class, 'forceWin'])->name('express-trades.force-win');
    Route::post('express-trades/{expressTrade}/force-lose', [ExpressTradeController::class, 'forceLose'])->name('express-trades.force-lose');
    Route::post('express-trades/{expressTrade}/void', [ExpressTradeController::class, 'void'])->name('express-trades.void');
});
