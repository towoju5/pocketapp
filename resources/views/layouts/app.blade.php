<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trading Panel - Polaris Option')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- TradingView Widget Script -->
    <script type="text/javascript" src="//s3.tradingview.com/tv.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <style>
        #menuPanel {
            position: fixed;
            /* Fixes it to the viewport */
            right: 0;
            /* Aligns to the right edge of the viewport */
            top: 0;
            width: 300px;
            /* Adjust as necessary */
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            background-color: #1f1f1f;
            /* Optional for better visibility */
        }

        body {
            overflow-x: hidden;
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

        .container {
            margin: 2rem;
            margin-left: 8rem;
        }

        table {
            border-collapse: collapse;
        }

        table tr {
            width: auto;
        }

        table tr:not(:last-child) {
            border-bottom: 1px solid #292d4a;
            /* Light gray border */
        }

        @media (min-width: 1536px) {
            .container {
                max-width: 100vw;
            }
        }
    </style>
    @stack('css')

</head>

<body style="overflow: auto;">
    @include('components.preloader')

    @include('layouts.header')

    <div class="w-full flex" style="margin-top: 4rem">
        @include('layouts.sidebar')

        @yield('content')
    </div>

    <form method="POST" action="{{ route('logout') }}" class="d-inline" id="UserLogoutForm">
        @csrf
    </form>


    <!-- Include jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
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

            $('.right-menu-item').on('click', function(e) {
                e.preventDefault();
                const route = $(this).data('route');

                if (!route) {
                    console.warn('No route defined for this menu item.');
                    return;
                }

                // Hide if same route is clicked or is already open
                if ($(this).hasClass('is-open') || menuPanel.data('currentRoute') === route) {
                    $(this).removeClass('is-open').addClass('is-close');
                    menuPanel.hide(); // Hide the panel
                    activeMenu = null;
                    return;
                }

                // Handle switching to a different menu
                if (activeMenu && activeMenu !== route) {
                    $('.right-menu-item.is-open').removeClass('is-open').addClass('is-close');
                }

                // Mark the clicked item as active
                $(this).removeClass('is-close').addClass('is-open');
                activeMenu = route;
                menuPanel.data('currentRoute', route);

                // Load content and show the panel
                if (!isLoading) {
                    isLoading = true;
                    loadMenuContent(route).then(() => {
                        isLoading = false;
                        menuPanel.css({
                            display: 'block',
                            visibility: 'visible',
                            opacity: 1
                        });
                    }).catch((error) => {
                        isLoading = false;
                        console.error('Error loading menu content:', error);
                    });
                }
            });
            // Function to load menu content
            function loadMenuContent(route) {
                isLoading = true; // Set loading flag

                // Show a preloader (Blade-rendered or fallback HTML)
                const preloader = `{!! view('components.preloader-main')->render() !!}`;
                menuPanel.html("<div class='flex justify-center items-center h-full'>" + preloader + "</div>");

                // Fetch content dynamically from the route
                $.get(route, function(data) {
                    menuPanel.html(data); // Replace preloader with fetched data
                    isLoading = false; // Reset loading flag
                }).fail(function() {
                    menuPanel.html(
                        '<p class="text-white text-xl">Error loading content. Please try again.</p>');
                    isLoading = false; // Reset loading flag
                });
            }
        });
    </script>

    @stack('js')
</body>

</html>
