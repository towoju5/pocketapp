<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class UpdateAssetStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of assets based on the API response';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! Schema::hasColumn('assets', 'is_active')) {
            Schema::table('assets', function ($table) {
                $table->boolean('is_active')->default(false);
            });
        }

        // Fetch data from the API
        $response = Http::get('https://iqcent.com/trade-api/api/ticks/current');
        $data     = $response->json();

        // Extract symbols from the API response
        $symbols = collect($data['data'])->pluck('symbol')->all();

        // Update the status of assets in the database
        DB::table('assets')
            ->whereIn('symbol', $symbols)
            ->update(['status' => true]);

        // Optionally, set the status to 0 for assets not in the API response
        DB::table('assets')
            ->whereNotIn('symbol', $symbols)
            ->update(['status' => false]);

        $this->info('Asset statuses updated successfully.');

        return Command::SUCCESS;
    }
}
