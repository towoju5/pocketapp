<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Panther\Client;

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









## Code 2
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Panther\Client;

$client = Client::createChromeClient(null, [
    '--no-sandbox',
    '--disable-blink-features=AutomationControlled',
    '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
]);

// Full isolated asset payload matrix
$marketData = [
    ["symbol" => "EUR/GBP.X", "param" => "Option"],
    ["symbol" => "GS.US/USD", "param" => "Stock"],         
    ["symbol" => "TRUMP/GREENLAND.P", "param" => "Hype"], 
    ["symbol" => "XAU/USD.X", "param" => "Option"],
    ["symbol" => "UNI/USDT.X", "param" => "Option"],
    ["symbol" => "HKG.IDX/HKD.X", "param" => "Option"],
    ["symbol" => "USD/SEK.X", "param" => "Option"],
    ["symbol" => "EUR/CHF.X", "param" => "Option"],
    ["symbol" => "DBK.DE/EUR", "param" => "Stock"],
    ["symbol" => "DAI.DE/EUR", "param" => "Stock"],
    ["symbol" => "CSCO.US/USD", "param" => "Stock"],
    ["symbol" => "EUR/HKD.X", "param" => "Option"]
];

try {
    echo "🌐 Establishing Cloudflare clearance bypass...\n";
    $client->request('GET', 'https://iqcent.com');
    sleep(5);

    echo "🛰️ Spawning Isolated WebSocket Thread Runners...\n";
    $jsonAssets = json_encode($marketData);

    $jsInjection = <<<JS
        window.myScrapedTicks = [];
        const assetsToTrack = {$jsonAssets};
        
        const delay = ms => new Promise(res => setTimeout(res, ms));

        async function initializeMultiSockets() {
            for (let i = 0; i < assetsToTrack.length; i++) {
                const item = assetsToTrack[i];
                
                // FIXED: URL cleaned to correct format without double-prefixes
                const ws = new WebSocket("wss://://iqcent.com");
                
                ws.onopen = function() {
                    const payload = {
                        "id": item.symbol,
                        "param": item.param,
                        "operation": "SUBSCRIBE.TICK"
                    };
                    ws.send(JSON.stringify(payload));
                };
                
                ws.onmessage = function(event) {
                    try {
                        const parsed = JSON.parse(event.data);
                        window.myScrapedTicks.push({ asset: item.symbol, status: "SUCCESS", data: parsed });
                    } catch(e) {
                        window.myScrapedTicks.push({ asset: item.symbol, status: "RAW", data: event.data });
                    }
                };
                
                ws.onerror = function(err) {
                    window.myScrapedTicks.push({ asset: item.symbol, status: "ERROR", message: "Parameter variant rejected or closed." });
                };

                // Drip connection every 300ms to avoid hammering Cloudflare rate-limits
                await delay(300);
            }
        }

        initializeMultiSockets();
JS;

    $client->executeScript($jsInjection);
    echo "📥 Multi-Socket Pipeline Engaged. Gathering clean data frames...\n\n";

    // 4. Clean Backend Extraction Loop
    while (true) {
        $ticks = $client->executeScript(<<<JS
            const data = window.myScrapedTicks;
            window.myScrapedTicks = []; 
            return data;
JS);

        if (!empty($ticks)) {
            foreach ($ticks as $tick) {
                if ($tick['status'] === 'ERROR') {
                    echo "⚠️ Asset Dropped: [" . $tick['asset'] . "] - " . $tick['message'] . "\n";
                } else {
                    echo "📈 [" . date('H:i:s') . "] [" . $tick['asset'] . "] Data: " . json_encode($tick['data']) . "\n";
                }
            }
        }

        usleep(250000); // Poll browser buffer array every 250ms
    }

} catch (\Exception $e) {
    echo "❌ Execution Error: " . $e->getMessage() . "\n";
} finally {
    $client->quit();
}
