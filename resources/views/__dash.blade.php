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

        .menu-item {
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .menu-item:hover {
            background-color: #4B5563;
        }

        .menu-item .icon {
            font-size: 1.5rem;
        }

        .menu-item .text {
            font-size: 0.75rem;
        }

        #tradingview_chart {
            width: 100%;
            height: 93%;
        }
    </style>
</head>

<body style="height: 100vh; overflow: hidden">
    {{-- @include('components.preloader') --}}
    <header class="w-full py-2 px-4 flex justify-between border-b border-[#293341] items-center">
        <div class="flex gap-3 justify-left items-center">
            <a href="{{ url('/') }}" class="home_url">
                <img src="{{ asset('assets/svg/logo.svg') }}" alt="Website Logo">
            </a>
            <a href="#" class="border rounded-lg p-2 border-[#293341]" onclick="alert('You clicked me')">
                <img src="{{ asset('assets/svg/star.svg') }}" alt="Favourites">
            </a>
            <div class="bg-lightHouse flex gap-2 rounded-lg py-1 px-3 items-center">
                //
                <div class="group text-lightHouse-text text-[10px]">
                    <p>In progress:</p>
                    <p>Top up your account</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3 justify-left items-center">
            <a href="{{ url('/') }}" class="home_url">
                <img src="{{ asset('assets/svg/logo.svg') }}" alt="Website Logo">
            </a>
            <!-- Dropdown Container -->
            <div class="relative text-white w-40">
                <!-- Dropdown Box -->
                <div class="relative border border-gray-600 rounded-lg bg-[#1c1f26]">
                    <!-- Replace Top Border with QT Real and USD -->
                    <div
                        class="absolute flex -top-3 left-1/2 -translate-x-1/2 whitespace-nowrap gap-2 bg-[#1c1f26] px-3">
                        <span class="text-sm text-gray-400">QT Real USD</span>
                    </div>

                    <!-- Dropdown Button -->
                    <button id="dropdownButton" class="w-full flex justify-between items-center p-3">
                        <span class="text-lg font-bold">0</span>
                        <svg id="dropdownArrow" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Dropdown Content -->
                <div id="dropdownContent"
                    class="hidden absolute top-full mt-2 w-80 -left-[50%] bg-[#1c1f26] rounded-lg border border-[#31353f] shadow-lg z-10">
                    <div class="p-4 space-y-4">
                        <!-- Quick Trading Real -->
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-300">Quick Trading Real</p>
                                <p class="text-xs text-gray-500">$0</p>
                            </div>
                            <button
                                class="px-2 py-1 text-xs text-gray-400 border border-gray-600 rounded-md">USD</button>
                        </div>

                        <!-- Top Up Button -->
                        <button
                            class="w-full py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Top up
                        </button>

                        <!-- Quick Trading Demo -->
                        <div>
                            <p class="text-sm text-gray-300">Quick Trading Demo</p>
                            <p class="text-xs text-gray-500">$49,993.60</p>
                        </div>

                        <!-- Forex Section -->
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-300">Forex</p>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- My Safe Section -->
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-300">My Safe</p>
                            <button class="px-3 py-1 text-xs bg-green-600 rounded-md hover:bg-green-700">Open</button>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#"
                class="bg-gradient-to-r from-[#047838] to-[#0a5c45] flex gap-3 rounded-lg py-2 px-3 items-center border-2 border-[#047838]">
                <i class="fas fa-wallet text-[#5aa86b]"></i>
                <span class="text-white uppercase text-[13px] font-bold">Top up</span>
            </a>
            <div class="relative inline-block  border border-yellow-500 text-yellow-500 p-1 rounded-full">
                <img class="inline-block size-[46px] rounded-full"
                    src="//images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                    alt="Avatar">
                <span
                    class="absolute top-0 end-0 block size-3 rounded-full ring-2 ring-white bg-yellow-400 dark:ring-neutral-900"></span>
            </div>
        </div>
    </header>

    {{-- // left sidebar --}}
    <div class="w-full flex">
        <div id="sideMenu" class="bg-[#1c1f26] w-20 min-h-screen lg:w-20 items-center">
            <!-- Menu Items -->
            <button class="menu-item p-3 w-full text-gray-400 hover:text-white" data-tooltip="Analytics">
                <i class="fa-solid fa-chart-line"></i>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <button class="menu-item p-3 w-full text-gray-400 hover:text-white" data-tooltip="Finance">
                <i class="fa-solid fa-dollar-sign"></i>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <button class="menu-item p-3 w-full text-gray-400 hover:text-white" data-tooltip="Profile">
                <i class="fa-solid fa-user"></i>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <button class="menu-item p-3 w-full text-gray-400 hover:text-white relative" data-tooltip="Cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <button class="menu-item p-3 w-full text-gray-400 hover:text-white relative" data-tooltip="Notifications">
                <i class="fa-regular fa-gem"></i>
                <span class="absolute top-2 right-2 bg-[#0c69a9] text-xs rounded-md text-white px-2 py-1">6</span>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <button class="menu-item p-3 w-full text-gray-400 hover:text-white relative" data-tooltip="Chat">
                <i class="fa-regular fa-comment"></i>
                <span class="absolute top-2 right-2 bg-[#0c69a9] text-xs rounded-md text-white px-2 py-1">6</span>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <button class="menu-item p-3 w-full text-gray-400 hover:text-white" data-tooltip="Help">
                <i class="fa-solid fa-circle-question"></i>
                <span class="hidden_text hidden lg:block">Trades</span>
            </button>

            <div class="bottom-3 fixed items-center mx-0 px-0 space-y-2">
                <button onclick="$('#UserLogoutForm').submit()"
                    class="menu-item py-4 w-full text-gray-400 hover:text-white" data-tooltip="Help">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="hidden_text hidden lg:block">Trades</span>
                </button>

                <button class="menu-item py-4 w-full text-gray-400 hover:text-white" data-tooltip="Help"
                    id="leftSideBarArrow">
                    <i class="fa-solid fa-arrow-right-long"></i>
                </button>
            </div>
        </div>

        <div class="lg:flex m-0 w-full">
            <div class="content m-0 w-full">
                <div id="tradingview_chart" class="w-full"></div>
            </div>
        </div>

        {{-- right sidebar --}}
        <div class="bg-gray-800 w-60 flex min-h-screen">
            <div class="column-1 w-40">
                {{-- // add form for trading --}}
                <form method="POST" action="/your-action-route" class="p-3 rounded-lg text-white space-y-4">
                    @csrf

                    <!-- Time Input -->
                    <div class="max-w-sm space-y-3">
                        <div>
                            <label for="hs-trailing-icon" class="block text-sm font-light mb-2">Time</label>
                            <div class="relative">
                                <input type="text" id="hs-trailing-icon" name="trading_time"
                                    class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"
                                    id="timeInput" maxlength="8" placeholder="00:00:00">
                                <div
                                    class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-10 border-l p-3 border-[#293341]">
                                    <i class="fa-regular fa-clock bg-[#23283b]"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Input -->
                    <div class="max-w-sm space-y-3">
                        <div>
                            <label for="hs-trailing-icon" class="block text-sm font-light mb-2">Amount</label>
                            <div class="relative">
                                <input type="text" id="hs-trailing-icon" name="trading_amount"
                                    class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"
                                    placeholder="1">
                                <div
                                    class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-10 border-l p-3 border-[#293341]">
                                    <svg class="currency-icon currency-icon--usd" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="11" stroke="currentColor"
                                            stroke-width="2"></circle>
                                        <path
                                            d="M15 9h-4a1 1 0 1 0 0 2h2a3 3 0 0 1 0 6v1a1 1 0 0 1-2 0v-1H9a1 1 0 0 1 0-2h4a1 1 0 0 0 0-2h-2a3 3 0 0 1 0-6V6a1 1 0 0 1 2 0v1h2a1 1 0 1 1 0 2Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payout Display -->
                    <div class="text-sm">
                        <label>Payout</label>
                        <div class="text-green-400 border border-dashed rounded-lg mb-3 border-[#293341] p-3 flex">
                            <span id="profit_percentage">+92% </span>
                            <span id="payout">$19.20</span>
                            </p>
                        </div>

                        <!-- Buy and Sell Buttons -->
                        <div class="gap-2 space-y-2">
                            <button type="submit" name="action" value="buy"
                                class="gap-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 w-full">
                                <i class="fas fa-arrow-up"></i>
                                BUY
                            </button>
                            <button type="submit" name="action" value="sell"
                                class="gap-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400 w-full">
                                <i class="fas fa-arrow-up"></i>
                                SELL
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        
        <div class="min-w-[20rem] p-2 hidden" id="hideShowMenu"></div>
        
        <div class="bg-[#1c1f26] w-20">
            <div class="menu-item py-2 w-full" data-route="/trades">
                <span class="icon">🔄</span>
                <span class="hidden_text hidden lg:block">Trades</span>
            </div>
            <div class="menu-item py-2 w-full" data-route="/signals">
                <span class="icon">📡</span>
                <span class="hidden_text hidden lg:block">Signals</span>
            </div>
            <div class="menu-item py-2 w-full" data-route="/social">
                <span class="icon">👥</span>
                <span class="hidden_text hidden lg:block">Social</span>
            </div>
            <div class="menu-item py-2 w-full" data-route="/express">
                <span class="icon">🎯</span>
                <span class="hidden_text hidden lg:block">Express</span>
            </div>
            <div class="menu-item py-2 w-full" data-route="/tournaments">
                <span class="icon">🏆</span>
                <span class="hidden_text hidden lg:block">Tournaments</span>
            </div>
            <div class="menu-item py-2 w-full" data-route="/pending">
                <span class="icon">⏳</span>
                <span class="hidden_text hidden lg:block">Pending</span>
            </div>
            <div class="menu-item py-2 w-full" data-route="/hotkeys">
                <span class="icon">⌨️</span>
                <span class="hidden_text hidden lg:block">Hotkeys</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="d-inline" id="UserLogoutForm">
        @csrf
    </form>


    <!-- Include jQuery -->
    <script src="//code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Custom JS files -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="//s3.tradingview.com/tv.js"></script>
    <script>
        $(document).ready(function() {
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

            $('.menu-item').on('click', function() {
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
                menuPanel.html("<div class='flex justify-center items-center h-full'>" + preloader + "</div>");

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

        new TradingView.widget({
            "container_id": "tradingview_chart",
            "autosize": true,
            "symbol": "BTCUSDT",
            "interval": "1",
            "theme": "dark",
            "style": "2",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "hide_top_toolbar": true,
            "hide_side_toolbar": true,
            "allow_symbol_change": false,
            "allow_symbol_title": true,
            "studies": [
                "MAExp@tv-basicstudies"
            ]
        });
    </script>




</body>

</html>
