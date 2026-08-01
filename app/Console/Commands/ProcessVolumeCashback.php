<?php

namespace App\Console\Commands;

use App\Models\CashbackRule;
use App\Models\User;
use App\Services\CashbackService;
use Illuminate\Console\Command;

class ProcessVolumeCashback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashback:process-volume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Credits volume-based cashback to qualifying users for the current calendar month (safe to run daily — already-paid months are skipped).";

    /**
     * Execute the console command.
     */
    public function handle(CashbackService $cashbackService): int
    {
        if (!CashbackRule::where('type', 'volume')->where('active', true)->exists()) {
            return self::SUCCESS;
        }

        $paid = 0;

        User::query()->chunkById(200, function ($users) use ($cashbackService, &$paid) {
            foreach ($users as $user) {
                if ($cashbackService->applyVolumeCashback($user)) {
                    $paid++;
                }
            }
        });

        $this->info("Volume cashback processed — {$paid} wallet(s) credited.");

        return self::SUCCESS;
    }
}
