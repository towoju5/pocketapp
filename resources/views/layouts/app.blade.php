<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trading Panel - Polaris Option')</title>

    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/lightweight-charts/4.1.1/lightweight-charts.standalone.production.js"></script> -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/i18next/23.7.11/i18next.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-i18next/1.2.1/jquery-i18next.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
        /* html {
            font-size: 13px !important;
            -webkit-tap-highlight-color: transparent;
        } */
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- TradingView Widget Script -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="//unpkg.com/lightweight-charts@3.8.0/dist/lightweight-charts.standalone.production.js"></script>
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
            background-image: url(<?php echo asset('assets/images/bg.jpg') ?>)
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

        .panel-heading {
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            border-top-left-radius: var(10px);
            border-top-right-radius: var(10px);
            justify-content: space-between;
            background-color: #20273f;
            color: #7e91a7;
        }

        .panel-title {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 16px;
            color: inherit;
        }
    </style>
        <script>
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            if (input.value.trim()) {
                axios.post('/chat/send', { content: input.value })
                    .then(response => {
                        input.value = '';
                    });
            }
        });

        window.Echo.private('chat.' + {{ auth()->id() }})
            .listen('ChatEvent', (e) => {
                const messagesDiv = document.getElementById('messages');
                const newMessage = document.createElement('div');
                newMessage.className = 'p-3 rounded-lg bg-gray-200 text-black mr-auto';
                newMessage.textContent = e.message.content;
                messagesDiv.appendChild(newMessage);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
    </script>
    @stack('css')
</head>

<body style="overflow: auto;">
    <!-- @include('components.preloader') -->

    @include('layouts.header')

    <section class="flex flex-row w-full @if(!Route::is('dashboard'))overflow-hidden @endif border-t border-gray-700">
        @include('layouts.sidebar')

        @yield('content')
    </section>

    <form method="POST" action="{{ route('logout') }}" class="d-inline" id="UserLogoutForm">
        @csrf
    </form>





    <!-- Include jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#openTab").click(function () {
                // Show Open Trades, Hide Closed Trades
                $("#openTrades").removeClass("hidden");
                $("#closedTrades").addClass("hidden");

                // Update active/inactive tab styles
                $("#openTab").addClass("text-gray-200 bg-[#1e2131]").removeClass("text-gray-500 bg-[#272b3c]");
                $("#closedTab").addClass("text-gray-500 bg-[#272b3c]").removeClass("text-gray-200 bg-[#1e2131]");

                // Ensure blue bottom border is only on the active tab
                $("#openTab .tab-indicator").removeClass("hidden");
                $("#closedTab .tab-indicator").addClass("hidden");
            });

            $("#closedTab").click(function () {
                // Show Closed Trades, Hide Open Trades
                $("#closedTrades").removeClass("hidden");
                $("#openTrades").addClass("hidden");

                // Update active/inactive tab styles
                $("#closedTab").addClass("text-gray-200 bg-[#1e2131]").removeClass("text-gray-500 bg-[#272b3c]");
                $("#openTab").addClass("text-gray-500 bg-[#272b3c]").removeClass("text-gray-200 bg-[#1e2131]");

                // Ensure blue bottom border is only on the active tab
                $("#closedTab .tab-indicator").removeClass("hidden");
                $("#openTab .tab-indicator").addClass("hidden");
            });

            // Set the default active tab on page load
            $("#openTab").trigger("click");
        });
    </script>
    <!-- Custom JS files -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!-- <script src="//s3.tradingview.com/tv.js"></script> -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    @stack('js')
    
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
                }
            );
        });
    </script>

    <script>
        // Initialize progress circle
        const circle = new ProgressBar.Circle('#progress-circle', {
            color: '#00A3FF',
            strokeWidth: 8,
            trailWidth: 8,
            trailColor: '#2A2D3E',
            easing: 'easeInOut',
            duration: 1400,
            text: {
                autoStyleContainer: false
            },
            from: {
                color: '#00A3FF'
            },
            to: {
                color: '#00A3FF'
            },
            step: (state, circle) => {
                circle.path.setAttribute('stroke', state.color);
            }
        });

        // Animate to 80%
        circle.animate(0.6);


        // Get all navigation items
        const navItems = document.querySelectorAll('.nav-item');
        const contentTitle = document.getElementById('contentTitle');
        const contentText = document.getElementById('contentText');

        // Add click event listener to each navigation item
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const text = item.getAttribute('data-text');
                contentTitle.textContent = text;
                contentText.textContent = `Testing: ${text}`;
            });
        });

        function toggleDropdown(id) {
            const content = document.getElementById(id);
            const arrow = document.getElementById(`arrow-${id}`);

            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }


        document.addEventListener("DOMContentLoaded", function() {
            const contentArea = document.getElementById("content-area");
            const navLinks = document.querySelectorAll(".nav-link");
            const hiddenSections = document.getElementById("hidden-sectionss");
            let activeSection = null; // Track the currently active section

            // Initially hide the content area
            contentArea.style.display = "none";

            navLinks.forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    const sectionId = this.dataset.section;
                    const sectionContent = hiddenSections.querySelector(`#${sectionId}`);

                    // Toggle logic
                    if (activeSection === sectionId) {
                        // If the same section is clicked again, hide content
                        contentArea.style.display = "none";
                        activeSection = null;
                        this.classList.remove("bg-[#23283b]", "w-[200%]", "py-2");
                    } else {
                        // Show content
                        contentArea.style.display = "block";
                        contentArea.innerHTML = sectionContent ? sectionContent.innerHTML : "<div class='p-1 text-white'>Content Not Found</div>";
                        activeSection = sectionId;

                        // Remove active styles from all links
                        navLinks.forEach(l => l.classList.remove("bg-[#23283b]", "w-[200%]", "py-2"));
                        // Add active styles to the clicked link
                        this.classList.add("bg-[#23283b]", "w-[200%]", "py-2");
                    }
                });
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const mainContent = document.getElementById("mainContent");
            const rightNavLinks = document.querySelectorAll(".right-nav-link");
            const hiddenSections = document.getElementById("hidden-sections");
            const fullscreenBtn = document.getElementById("fullscreen-btn");
            let activeSection = null; // Track the currently active section

            // Initially hide the main content
            mainContent.style.display = "none";

            // Handle right sidebar navigation
            rightNavLinks.forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    const sectionId = this.dataset.section;
                    const sectionContent = hiddenSections.querySelector(`#${sectionId}`);

                    if (activeSection === sectionId) {
                        // If the same section is clicked again, hide content
                        mainContent.style.display = "none";
                        activeSection = null;
                        this.classList.remove("bg-[#23283b]", "w-[200%]", "py-2");
                    } else {
                        // Show content
                        mainContent.style.display = "block";
                        mainContent.innerHTML = sectionContent ? sectionContent.innerHTML : "<div class='p-1 text-white'>Content Not Found</div>";
                        activeSection = sectionId;

                        // Remove active styles from all links
                        rightNavLinks.forEach(l => l.classList.remove("bg-[#23283b]", "w-[200%]", "py-2"));
                        // Add active styles to the clicked link
                        this.classList.add("bg-[#23283b]", "w-[200%]", "py-2");
                    }
                });
            });

            // Handle fullscreen functionality
            fullscreenBtn.addEventListener("click", function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            });
        });


        // Function to handle errors
        function handleError() {
            const ipElement = document.getElementById('userIp');
            ipElement.textContent = 'Failed to load IP';
            document.getElementById('countryFlag').style.display = 'none';
        }

        // Function to fetch IP and country data
        async function fetchIPData() {
            try {
                // First try to get IP from ipify
                const ipResponse = await fetch('https://api.ipify.org?format=json');
                const ipData = await ipResponse.json();
                const ip = ipData.ip;

                // Then get country data from ip-api
                const countryResponse = await fetch(`http://ip-api.com/json/${ip}`);
                const countryData = await countryResponse.json();

                if (countryData.status === 'success') {
                    // Update IP address
                    document.getElementById('userIp').textContent = ip;

                    // Update country flag
                    const flagImg = document.getElementById('countryFlag');
                    const countryCode = countryData.countryCode.toLowerCase();
                    flagImg.src = `https://flagcdn.com/w20/${countryCode}.png`;
                    flagImg.alt = countryData.country;
                    flagImg.style.display = 'inline';
                } else {
                    handleError();
                }
            } catch (error) {
                handleError();
                console.error('Error fetching IP data:', error);
            }
        }

        // Call the function when the page loads
        document.addEventListener('DOMContentLoaded', fetchIPData);
    </script>

</body>

</html>