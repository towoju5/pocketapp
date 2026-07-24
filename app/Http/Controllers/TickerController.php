<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Panther\Client;

class TickerController extends Controller
{
    public function webTick()
    {
        // 1. Initialize a Chrome Instance with Panther
        // We append standard stealth arguments to seamlessly blend past Cloudflare blocks
        $client = Client::createChromeClient(null, [
            '--no-sandbox',
            '--disable-blink-features=AutomationControlled',
            '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        ]);

        try {
            echo "🌐 Navigating to host domain to establish Cloudflare clearance...\n";

            // Load the base site first to collect Cloudflare sessions/cookies naturally
            $client->request('GET', 'https://iqcent.com');

            // Allow JavaScript and bot-checks a moment to clear
            sleep(5);

            echo "🛰️ Injecting custom JavaScript WebSocket Interceptor...\n";

            // 2. Inject raw JS to run side-by-side with the authenticated browser stack
            // This script establishes the connection, sends your EUR/USD payload, and queues ticks
            $jsInjection = <<<JS
        window.myScrapedTicks = [];
        
        const ws = new WebSocket("wss://iqcent.com/trade-api/public-ws/price");
        
        ws.onopen = function() {
            console.log("WebSocket connected from Panther scope.");
            const payload = {"id":"EUR/USD.X","param":"Option","operation":"SUBSCRIBE.TICK"};
            ws.send(JSON.stringify(payload));
        };
        
        ws.onmessage = function(event) {
            try {
                const parsed = JSON.parse(event.data);
                window.myScrapedTicks.push(parsed);
            } catch(e) {
                window.myScrapedTicks.push({raw: event.data});
            }
        };
        
        ws.onerror = function(err) {
            window.myScrapedTicks.push({error: "Socket encountered an error"});
        };
    JS;

            // Execute the payload inside the browser context
            $client->executeScript($jsInjection);
            echo "📥 Pipeline open. Streaming real-time ticks (Press Ctrl+C to stop)...\n\n";

            // 3. Infinite Scraping Loop
            // Periodically sweep the window variable array, flush it, and parse the data inside PHP
            while (true) {
                // Retrieve and atomically flush the shared array using an immediately invoked JS statement
                $ticks = $client->executeScript(<<<JS
            const data = window.myScrapedTicks;
            window.myScrapedTicks = []; // Clear array so we don't fetch duplicates next loop
            return data;
        JS);

                if (!empty($ticks)) {
                    foreach ($ticks as $tick) {
                        // If the array contains data, process it inside your PHP backend
                        echo "📈 Scraped Tick: " . json_encode($tick) . "\n";
                    }
                }

                // Wait 1 second before fetching the next batch of stream frames
                sleep(1);
            }
        } catch (\Exception $e) {
            echo "❌ Error occurred: " . $e->getMessage() . "\n";
        } finally {
            // Terminate browser safely on exit
            $client->quit();
        }
    }
}
