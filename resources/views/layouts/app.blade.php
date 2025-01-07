<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradingView with WebSocket Data Feed</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- TradingView Widget Script -->
    <script type="text/javascript" src="//s3.tradingview.com/tv.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <style>
        body {
            background-image: url({{ asset('assets/images/bg.jpg') }})
        }

        /* Custom menu styling */
        .menu {
            display: flex;
            flex-direction: column;
            /* For desktop */
            justify-content: space-around;
            /* For mobile */
        }

        .right-menu-item {
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .right-menu-item:hover {
            background-color: #4B5563;
        }

        .right-menu-item .icon {
            font-size: 1.5rem;
        }

        .right-menu-item .text {
            font-size: 0.75rem;
        }

        #chart {
            width: 100%;
            height: 90%;
        }
    </style>
    @stack('css')
</head>

<body style="height: 100vh; overflow: hidden">
    @include('components.preloader')
    
    @include('layouts.header')
    
    <div class="w-full flex">
        @include('layouts.sidebar')
        @yield('content')
    </div>

    <form method="POST" action="{{ route('logout') }}" class="d-inline" id="UserLogoutForm">
        @csrf
    </form>


    <!-- Include jQuery -->
    <script src="//code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Custom JS files -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="//s3.tradingview.com/tv.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // input mask for time.
            $("#hs-trailing-icon").inputmask(
                "99:59:59", {
                    placeholder: "00:01:00",
                    insertMode: false,
                    showMaskOnHover: false,
                    definitions: {
                        '5': {
                            validator: "[0-5]",
                            cardinality: 1
                        }
                    }
                });


            let activeMenu = null; // Currently active menu element
            let isLoading = false; // Prevent multiple simultaneous requests
            const menuPanel = $('#hideShowMenu');

            // Load the previously active menu route from localStorage
            const _current_menu_route = localStorage.getItem('_current_menu_route');

            // If there's a stored route, load its content on page load
            if (_current_menu_route) {
                menuPanel.removeClass('hidden');
                loadMenuContent(_current_menu_route);
            }

            $('.right-menu-item').on('click', function() {
                const route = $(this).data('route'); // Get the route of the clicked menu

                // If the same menu is clicked, toggle the panel visibility
                if (activeMenu === this) {
                    menuPanel.toggleClass('hidden'); // Toggle visibility
                    if (menuPanel.hasClass('hidden')) {
                        activeMenu = null;
                        localStorage.removeItem('_current_menu_route'); // Clear cached route
                    }
                    return;
                }

                // If a different menu is clicked
                activeMenu = this;

                // If already loading or the route is the same, avoid duplicate requests
                if (isLoading || _current_menu_route === route) {
                    return;
                }

                // Update the current menu route in localStorage
                localStorage.setItem('_current_menu_route', route);

                // Show the panel and load content
                menuPanel.removeClass('hidden');
                loadMenuContent(route);
            });

            // Function to load menu content
            function loadMenuContent(route) {
                isLoading = true; // Set loading flag

                // Show a preloader (Blade-rendered or fallback HTML)
                const preloader = `{!! view('components.preloader-main')->render() !!}`;
                menuPanel.html("<div class='flex justify-center items-center h-full'>" + preloader +
                    "</div>");

                // Fetch content dynamically from the route
                $.get(route, function(data) {
                    menuPanel.html(data); // Replace preloader with fetched data
                    isLoading = false; // Reset loading flag
                }).fail(function() {
                    menuPanel.html('<p>Error loading content. Please try again.</p>');
                    isLoading = false; // Reset loading flag
                });
            }
        });

        // new TradingView.widget({
        //     "container_id": "tradingview_chart",
        //     "autosize": true,
        //     "symbol": "BTCUSDT",
        //     "interval": "1",
        //     "theme": "dark",
        //     "style": "2",
        //     "locale": "en",
        //     "toolbar_bg": "#f1f3f6",
        //     "enable_publishing": false,
        //     "hide_top_toolbar": true,
        //     "hide_side_toolbar": true,
        //     "allow_symbol_change": false,
        //     "studies": [
        //         "MAExp@tv-basicstudies"
        //     ]
        // });
    </script>

    @stack('js')
</body>

</html>
