<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading Dashboard</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <style>
        body {
            background-color: #0A0A23;
            /* Dark background color */
            color: #FFFFFF;
            /* White text color */
            font-family: 'Arial', sans-serif;
        }

        .bg-gray-800 {
            background-color: #1C1C3C;
            /* Darker grey for form area */
        }

        .bg-gray-900 {
            background-color: #14142A;
            /* Darkest grey for sidebars */
        }

        .text-gray-700 {
            color: #B0B3B8;
            /* Light grey for labels */
        }

        .border-gray-300 {
            border-color: #4A4A5F;
            /* Border color */
        }

        .bg-indigo-600 {
            background-color: #007BFF;
            /* Blue for buttons */
        }

        .hover\:bg-indigo-700:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }

        .bg-green-500 {
            background-color: #28A745;
            /* Green for BUY button */
        }

        .hover\:bg-green-600:hover {
            background-color: #218838;
            /* Darker green on hover */
        }

        .bg-red-500 {
            background-color: #DC3545;
            /* Red for SELL button */
        }

        .hover\:bg-red-600:hover {
            background-color: #C82333;
            /* Darker red on hover */
        }
    </style>
</head>

<body class="h-screen overflow-hidden">
    <!-- Full Width Top Menu -->
    <header class="bg-gray-900 text-white flex justify-between items-center p-4 fixed top-0 left-0 right-0 z-40">
        <div class="flex items-center">
            <img src="path/to/logo.png" alt="Logo" class="h-8 mr-2">
            <h1 class="text-xl font-bold">PocketOption</h1>
        </div>
        <nav class="flex space-x-4">
            <div class="flex items-center text-blue-400">
                <i class="fas fa-star"></i> <span class="ml-1">100%</span>
            </div>
            <a href="#" class="text-blue-400">TOP UP YOUR ACCOUNT</a>
            <a href="#" class="text-blue-400">TRADER'S BOX</a>
            <a href="#" class="text-blue-400">RECEIVE A RANDOM REWARD</a>
            <a href="#" class="text-blue-400">TOP UP</a>
        </nav>
    </header>

    <!-- Main Content Area -->
    <div class="flex h-full pt-1">
        <!-- Column 1: Left Sidebar (Fixed) -->
        <nav class="bg-gray-900 text-white w-64 fixed left-0 top-16 bottom-0 z-30">
            <ul class="p-4 space-y-2 overflow-y-auto h-full">
                <li><a href="#" class="block p-2 hover:bg-gray-700"><i class="fas fa-chart-line mr-2"></i>
                        Trading</a></li>
                <li><a href="#" class="block p-2 hover:bg-gray-700"><i class="fas fa-user mr-2"></i> Profile</a>
                </li>
                <li><a href="#" class="block p-2 hover:bg-gray-700"><i class="fas fa-shopping-cart mr-2"></i>
                        Market</a></li>
                <li><a href="#" class="block p-2 hover:bg-gray-700"><i class="fas fa-trophy mr-2"></i>
                        Achievements</a></li>
                <li><a href="#" class="block p-2 hover:bg-gray-700"><i class="fas fa-comments mr-2"></i> Chat</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content Wrapper -->
        <div class="flex flex-1 h-full pl-64">
            <!-- Column 2: Chart -->
            <div class="flex-grow h-full relative">
                <div id="chart" class="flex-grow w-full"></div>
                <div class="absolute top-0 left-0 p-2 bg-gray-900 text-white">
                    <span>AUS 200 OTC</span>
                    <span class="ml-4 text-green-400">+67%</span>
                </div>
            </div>

            <div class="flex gap-0 right-0 -mt-3 py-10">
                <!-- Column 3: Form Data -->
                <div class="w-64 h-full bg-gray-800 text-white">
                    <div class="p-4">
                        <form method="POST" action="{{ route('trade.store') }}" class="space-y-4">
                            @csrf
                            <div class="text-sm">
                                <label class="text-gray-700">Time</label>
                                <div class="flex items-center justify-between mt-1">
                                    <span>00:01:00</span>
                                    <input type="hidden" name="duration" value="00:01:00">
                                </div>
                            </div>
                            <div class="text-sm">
                                <label class="text-gray-700">Amount</label>
                                <div class="flex items-center justify-between mt-1">
                                    <span>1</span>
                                    <input type="hidden" name="amount" value="1">
                                </div>
                            </div>
                            <div class="text-sm">
                                <label class="text-gray-700">Payout</label>
                                <div class="flex items-center justify-between mt-1">
                                    <span>+85%</span>
                                    <span>$1.85</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button"
                                    class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">BUY</button>
                                <button type="button"
                                    class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">SELL</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Column 4: Hidden for Right Sidebar Menu Content -->
                <div id="contentArea" class="w-64 h-full bg-gray-800 hidden py-10">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold mb-4">Trades</h2>
                        <p>No opened trades</p>
                    </div>
                </div>

                <!-- Column 6: Right Sidebar (Fixed) -->
                <aside class="w-24 h-full bg-gray-900 text-white right-0">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold mb-4">Menu</h2>
                        <ul id="rightSidebarMenu" class="space-y-2">
                            <li><a href="#" data-content-id="trades" class="block p-2 hover:bg-gray-700">Trades</a></li>
                            <li><a href="#" data-content-id="signals" class="block p-2 hover:bg-gray-700">Signals</a></li>
                            <li><a href="#" data-content-id="socialTrading" class="block p-2 hover:bg-gray-700">SocialTradings</a></li>
                            <li><a href="#" data-content-id="expressTrading" class="block p-2 hover:bg-gray-700">Express Tradings</a></li>
                            <li><a href="#" data-content-id="tournaments" class="block p-2 hover:bg-gray-700">Tournaments</a></li>
                            <li><a href="#" data-content-id="pendingTrades" class="block p-2 hover:bg-gray-700">Pending Trades</a></li>
                            <li><a href="#" data-content-id="hotkeys" class="block p-2 hover:bg-gray-700">Hot keys</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
    @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentArea = document.getElementById('contentArea');
            const menuItems = document.querySelectorAll('#rightSidebarMenu a');

            // Hide content if clicking outside of it or the menu
            function hideContentIfOutsideClick(event) {
                if (!contentArea.contains(event.target) && !document.getElementById('rightSidebarMenu').contains(
                        event.target)) {
                    contentArea.classList.add('hidden');
                }
            }

            // Add click event listener to each menu item
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const contentId = this.getAttribute('data-content-id');

                    // Show content area
                    contentArea.classList.remove('hidden');

                    // Update content based on the clicked menu item
                    let content = '';
                    switch (contentId) {
                        case 'trades':
                            content = '<h2>Trades</h2><p>No opened trades</p>';
                            break;
                        case 'signals':
                            content = '<h2>Signals</h2><p>Signal information here...</p>';
                            break;
                        case 'socialTrading':
                            content = '<h2>Social Tradings</h2><p>Social trading data...</p>';
                            break;
                        case 'expressTrading':
                            content = '<h2>Express Tradings</h2><p>Express trading options...</p>';
                            break;
                        case 'tournaments':
                            content = '<h2>Tournaments</h2><p>Tournament details...</p>';
                            break;
                        case 'pendingTrades':
                            content = '<h2>Pending Trades</h2><p>Pending trades list...</p>';
                            break;
                        case 'hotkeys':
                            content = '<h2>Hot keys</h2><p>Hotkey shortcuts...</p>';
                            break;
                    }
                    contentArea.innerHTML = content;
                });
            });

            // Add click event listener to document for hiding content
            document.addEventListener('click', hideContentIfOutsideClick);
        });
    </script>
</body>

</html>
