<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@latest/dist/web/pusher.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <script>
        Echo.channel('trades')
            .listen('.TradeDataReceived', (e) => {
                console.log('New trade data:', e.tradeData);
                // Update your table with e.tradeData
            });
    </script>

</body>

</html>