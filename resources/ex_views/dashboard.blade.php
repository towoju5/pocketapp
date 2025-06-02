@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@endsection


@push('js')

    <script src="//s3.tradingview.com/tv.js"></script>
    <!-- TradingView Widget Code -->
    <script type="text/javascript">
      new TradingView.widget({
        "container_id": "tradingview-widget",
        "autosize": true,
        "symbol": "BTCUSD",
        "interval": "1",
        "theme": "dark",
        "style": "1",
        "locale": "en",
        "toolbar_bg": "#f1f3f6",
        "enable_publishing": false,
        "hide_top_toolbar": true,
        "allow_symbol_change": true,
        "studies": [
          "MAExp@tv-basicstudies"
        ]
      });
    </script>
    <script>
        const eventSource = new EventSource('{{ route('chart.stream', "BTC-USD") }}');

        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            console.log(data); // Process the data
        };

        eventSource.onerror = function(error) {
            console.error('Error in SSE:', error);
        };
    </script>
@endpush
