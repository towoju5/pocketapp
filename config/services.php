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
    | datafeedcl.xyz. REST (GET /api/assets for the symbol catalog, GET
    | /api/tick?asset=&timestamp= for a point-in-time price) is called live,
    | on demand, by app/Services/DataFeedClService.php — no caching or
    | background daemon, see that class's docblock. Live ticks and their own
    | chart backfill come from ws_url, connected to directly by the browser
    | (resources/js/trading/dataFeedClFeed.js) — the backend never touches
    | the WebSocket at all.
    */
    'datafeedcl' => [
        'api_url' => env('DATAFEEDCL_API_URL', 'https://datafeedcl.xyz'),
        'ws_url' => env('DATAFEEDCL_WS_URL', 'wss://datafeedcl.xyz/ws'),
    ],

    /*
    | Auth for the Node real-time layer (node-services/). 'jwt_secret' signs
    | the key GET /realtime/token issues to the browser for the trade-socket
    | handshake (RealtimeAuthController) — deliberately not APP_KEY. 'api_secret'
    | is the shared secret Node presents (X-Internal-Secret) when calling into
    | routes/internal.php (trade relay, session-liveness heartbeat) — a
    | separate value because it authenticates a different direction of call
    | (Node -> Laravel, not browser -> Node).
    */
    'realtime' => [
        'jwt_secret' => env('REALTIME_JWT_SECRET'),
        'api_secret' => env('INTERNAL_API_SECRET'),
        // node-services/tradesocket's own HTTP listener (settlement push
        // endpoint only — trade placement itself flows the other direction,
        // Node calling into routes/internal.php). Empty/unset disables the
        // push entirely (TradeSettlementService::pushToTradeSocket no-ops),
        // which is the safe default until that service is actually deployed.
        'tradesocket_internal_url' => env('TRADESOCKET_INTERNAL_URL'),
        // Browser-facing URLs, distinct from the internal ones above — these
        // go through nginx/whatever's in front of the app, not straight to
        // the Node process. Empty/unset is the feature flag's off position:
        // dashboard-ui.blade.php only puts a URL in the page config (and
        // TradingDashboard.js only connects) when these are set. See
        // node-services/README.md for the rollout this gates.
        'trade_socket_public_url' => env('TRADESOCKET_PUBLIC_URL'),
        'pricefeed_public_url' => env('PRICEFEED_PUBLIC_URL'),
    ],

];
