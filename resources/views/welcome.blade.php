<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <script>
        const socket = new Websocket('wss://ws-plus.olymptrade.com/connect');
        socket.addEventListener('open', (e) => {
            socket.send("Hello, server!");
        });

        socket.addEventListener('message', (e) => {
            console.log("M", e.data);
        });
    </script>
</body>
</html>