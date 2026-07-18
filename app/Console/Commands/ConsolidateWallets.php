<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ConsolidateWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consolidate-wallets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fold the retired MT4/MT5/Shares wallets into the two USD accounts (real/demo) every customer keeps, moving any balance first so nothing is lost';

    /**
     * Slugs no longer offered — each user should only end up with
     * qt_real_usd and qt_demo_usd.
     */
    private const RETIRED_SLUGS = [
        'mt4_real_usd', 'mt4_demo_usd',
        'mt5_real_usd', 'mt5_demo_usd',
        'sh_real_usd', 'sh_demo_usd',
    ];

    public function handle(): int
    {
        $users = User::all();
        $this->info("Consolidating wallets for {$users->count()} user(s)...");

        foreach ($users as $user) {
            create_user_wallet($user->id);

            // The wallet's display name is only set once at creation time —
            // existing wallets predating this simplification still carry the
            // old "QT Real/Demo USD" labels and need refreshing explicitly.
            foreach (allowed_wallets() as $walletData) {
                $wallet = $user->getWallet($walletData['symbol']);
                if ($wallet && $wallet->name !== $walletData['name']) {
                    $wallet->name = $walletData['name'];
                    $wallet->save();
                }
            }

            foreach (self::RETIRED_SLUGS as $slug) {
                if (!$user->hasWallet($slug)) {
                    continue;
                }

                $wallet = $user->getWallet($slug);
                $balance = (float) ($wallet->balance ?? 0);

                if ($balance > 0) {
                    $targetSlug = str_contains($slug, 'demo') ? 'qt_demo_usd' : 'qt_real_usd';
                    $user->getWallet($targetSlug)->deposit($balance, [
                        'description' => "Consolidated from retired wallet {$slug}",
                    ]);
                    $this->line("  user #{$user->id}: moved {$balance} from {$slug} -> {$targetSlug}");
                }

                $wallet->transactions()->delete();
                $wallet->delete();
            }
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
