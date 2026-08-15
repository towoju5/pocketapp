<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'deepseek' => [
        'api_key' => env('DEEPSEEK_API_KEY'),
        'url' => env('DEEPSEEK_API_URL', 'https://api.deepseek.com/chat/completions'),
        'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
    ],

    /*
    | Brokeret's live price feed (wss://feed.brokeret.com/ws). Unlike
    | iqcent, this endpoint isn't behind Cloudflare, so it's connected to
    | directly from the backend (app/Console/Commands/StreamBrokeretTicks.php)
    | over a plain WebSocket client — no headless-browser workaround needed.
    | Keeping the URL/key server-side (not exposed to the frontend) is the
    | whole point: the browser never talks to this feed directly.
    */
    'brokeret' => [
        'ws_url' => env('BROKERET_WS_URL', 'wss://feed.brokeret.com/ws'),
        'api_key' => env('BROKERET_API_KEY', 'demo'),
    ],

    /*
    | datafeedcl's live price feed (wss://datafeedcl.xyz/ws) plus its REST
    | API (api_url — GET /api/assets to enumerate symbols, GET
    | /api/assets/{symbol}/ticks?limit=N for recent ticks). Unlike Brokeret,
    | this feed requires an explicit {"type":"subscribe","symbol":...} frame
    | per symbol rather than pushing everything unsolicited — see
    | app/Console/Commands/StreamDataFeedClTicks.php. Deliberately its own
    | separate pipeline (app/Services/DataFeedClService.php) — see that
    | class's docblock.
    */
    'datafeedcl' => [
        'ws_url' => env('DATAFEEDCL_WS_URL', 'wss://datafeedcl.xyz/ws'),
        'api_url' => env('DATAFEEDCL_API_URL', 'https://datafeedcl.xyz'),
    ],

];
