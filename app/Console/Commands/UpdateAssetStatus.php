<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        // API endpoint and API key
        $apiKey = env("ZENROWS_API_KEY");
        $response = Http::get("https://api.zenrows.com/v1/?apikey={$apiKey}&url=https://iqcent.com/trade-api/api/ticks/current");
        $data     = $response->json();

        // Check if the response was successful
        if ($response->failed()) {
            $this->error('Failed to fetch data from the API. Status code: ' . $response->status());
            return Command::FAILURE;
        }

        // Decode the JSON response
        $data = $response->json();

        // Ensure the response is in the expected format
        if (!isset($data['data']) || !is_array($data['data'])) {
            $this->error('API response is not in the expected format.');
            return Command::FAILURE;
        }

        // Extract symbols from the API response
        $symbols = collect($data['data'])->pluck('symbol')->all();

        // Update the status of assets in the database
        DB::table('assets')
            ->whereIn('symbol', $symbols)
            ->update(['is_active' => true]);

        // Optionally, set the is_active to 0 for assets not in the API response
        DB::table('assets')
            ->whereNotIn('symbol', $symbols)
            ->update(['is_active' => false]);

        $this->info('Asset statuses updated successfully.');

        return Command::SUCCESS;
    }
}