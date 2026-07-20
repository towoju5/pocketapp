<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trading - Polaris Option')</title>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/trading-shell.js', 'resources/js/trading-dashboard.js'])

    <style>
        html, body { height: 100%; margin: 0; overflow: hidden; background: #12182a; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #2a3350; border-radius: 3px; }
        .hidden { display: none !important; }
    </style>
    @stack('css')
</head>

<body class="text-[#d7dcea]" style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;">
    <div class="flex flex-col h-screen w-screen overflow-hidden" style="background:#12182a;font-size:13px;">
        @include('layouts.desktop.trading-topbar')

        <div class="flex flex-1 min-h-0">
            @include('layouts.desktop.trading-rail')

            <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                @yield('content')
            </div>
        </div>

        @include('layouts.desktop.trading-mobile-nav')
    </div>

    <form method="POST" action="{{ route('logout') }}" id="UserLogoutForm">
        @csrf
    </form>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#hs-trailing-icon").inputmask("99:59:59", {
                placeholder: "00:01:00",
                insertMode: false,
                showMaskOnHover: false,
                definitions: { '5': { validator: "[0-5]", cardinality: 1 } },
            });
        });

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{!! $error !!}");
            @endforeach
        @endif
        @if (session('error'))
            toastr.error("{!! session('error') !!}");
        @endif
        @if (session('success'))
            toastr.success("{!! session('success') !!}");
        @endif
    </script>

    @stack('js')
</body>

</html>
