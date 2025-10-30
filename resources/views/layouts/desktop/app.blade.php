    <!DOCTYPE html>
    <?php
        if (auth()->check()) {
            $user = auth()->user();
            $lang = $user->config['default_language'];
        }
    ?>
    <html lang="{{ $lang }}">

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
            .trade-open-close {
                margin: 0px!important
            }
            .rotate-180 {
                transform: rotate(180deg);
            }

            /* html {
                font-size: 13px !important;
                -webkit-tap-highlight-color: transparent;
            } */
            .skiptranslate {
                display: none;
            }
            #goog-gt-tt {
                display: none!important;
            }
            body {
                top: 0px!important;
            }
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

            /* .container {
                margin: 2rem;
                margin-left: 8rem;
            } */

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

            body {
                overflow: auto;
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* Internet Explorer 10+ */
            }

            body::-webkit-scrollbar {
                display: none;
                /* Chrome, Safari and Opera */
            }
        </style>
        @stack('css')
    </head>

    <body>
        @include('components.preloader')
        @include('layouts.desktop.header')

        <section class="flex flex-row w-full @if(!Route::is('dashboard')) @endif border-t border-gray-700">
            @include('layouts.desktop.sidebar')
            @if(!Route::is('dashboard') && !Route::is('dash'))
            <div class="left-margin">
            @endif
                @yield('content')
            @if(!Route::is('dashboard') && !Route::is('dash'))
            </div>
            @endif
        </section>

        <form method="POST" action="{{ route('logout') }}" class="d-inline" id="UserLogoutForm">
            @csrf
        </form>

        <!-- Hidden container for Google Translate -->
        <div id="google_translate_element" style="display:none;"></div>

        <!-- Include jQuery -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script>
            document.getElementById('chatForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('messageInput');
                if (input.value.trim()) {
                    axios.post('/chat/send', {
                            content: input.value
                        })
                        .then(response => {
                            input.value = '';
                        });
                }
            });

            window.Echo.private('chat.' + "{{ auth()->id() }}")
                .listen('ChatEvent', (e) => {
                    const messagesDiv = document.getElementById('messages');
                    const newMessage = document.createElement('div');
                    newMessage.className = 'p-3 rounded-lg bg-gray-200 text-black mr-auto';
                    newMessage.textContent = e.message.content;
                    messagesDiv.appendChild(newMessage);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });

                function toggleTradeMenu(button, tabKey) {
                    const container = button.closest('div').parentElement;

                    const tabs = container.querySelectorAll('.trade-open-close');
                    const indicators = container.querySelectorAll('.tab-indicator');
                    const contents = container.querySelectorAll('.trade-tab-content');

                    // Reset all tabs
                    tabs.forEach(tab => {
                        tab.classList.remove('text-gray-200', 'bg-[#1e2131]', 'active-tab');
                        tab.classList.add('text-gray-500', 'bg-[#272b3c]');
                    });

                    // Hide all indicators
                    indicators.forEach(indicator => indicator.classList.add('hidden'));

                    // Hide all content sections
                    contents.forEach(content => content.classList.add('hidden'));

                    // Activate the selected tab
                    button.classList.remove('text-gray-500', 'bg-[#272b3c]');
                    button.classList.add('text-gray-200', 'bg-[#1e2131]', 'active-tab');

                    const indicator = button.querySelector('.tab-indicator');
                    if (indicator) indicator.classList.remove('hidden');

                    // Show selected content
                    const activeContent = container.querySelector(`.trade-tab-content[data-tab="${tabKey}"]`);
                    if (activeContent) activeContent.classList.remove('hidden');
                }


                // On page load, activate all first tabs for each container
                window.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('[onclick^="toggleTradeMenu"]').forEach(button => {
                        if (button.innerText.includes('Opened') || button.innerText.includes('Updates')) {
                            toggleTradeMenu(button, 'active');
                        }
                    });
                });

            function initCountdowns() {
                const countdownElements = document.querySelectorAll('.signal-time');

                countdownElements.forEach(element => {
                    let timeString = element.textContent.trim();
                    let timerParts = timeString.split(':').map(Number);
                    let totalTime = timerParts[0] * 3600 + timerParts[1] * 60 + timerParts[2];

                    const updateCountdown = () => {
                        totalTime--;

                        if (totalTime < 0) {
                            clearInterval(timer);
                            element.textContent = '00:00:00';
                            return;
                        }

                        const newHours = Math.floor(totalTime / 3600);
                        const newMinutes = Math.floor((totalTime % 3600) / 60);
                        const newSeconds = totalTime % 60;

                        element.textContent = [
                            String(newHours).padStart(2, '0'),
                            String(newMinutes).padStart(2, '0'),
                            String(newSeconds).padStart(2, '0')
                        ].join(':');
                    };

                    const timer = setInterval(updateCountdown, 1000);
                });
            }
        </script>

        <!-- Custom JS files -->
        <script src="//cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <!-- <script src="{{ asset('assets/js/custom.js') }}"></script> -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
        <link rel="stylesheet" href="//cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
        <script src="//cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}');
                </script>
            @endforeach
        @endif

        @if (session('error'))
            <script>
                toastr.error('{{ session('error') }}');
            </script>
        @endif

        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}');
            </script>
        @endif

        @if (session('info'))
            <script>
                toastr.info('{{ session('info') }}');
            </script>
        @endif

        @if (session('message'))
            <script>
                toastr.info('{{ session('message') }}');
            </script>
        @endif
        <script>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };
        </script>

        @stack('js')
        @stack('scripts')
        @stack('script')

        <script>
            $(document).ready(function() {
                initCountdowns();
                // input mask for time.
                $("#hs-trailing-icon").inputmask(
                    "99:59:59", {
                        placeholder: "00:00:50",
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

        <script type="text/javascript">
            // function googleTranslateElementInit() {
            //     // Initialize Google Translate with page language
            //     new google.translate.TranslateElement({pageLanguage: '{{ $lang ?? 'en' }}'}, 'google_translate_element');
            // }
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'en,es,fr', // Customize the languages you want to offer
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    autoDisplay: false // Optional: set to true if you want it to always display
                }, 'google_translate_element');
            }

            // Load Google Translate script
            (function () {
                var gtScript = document.createElement('script');
                gtScript.type = 'text/javascript';
                gtScript.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                document.body.appendChild(gtScript);
            })();

            // Apply selected language without refresh
            function changeLanguage(langCode) {
                const select = document.querySelector('.goog-te-combo');
                if (select) {
                    select.value = langCode;
                    select.dispatchEvent(new Event('change'));
                } else {
                    // Fallback: try again in 0.5s
                    setTimeout(() => changeLanguage(langCode), 500);
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const userLang = "{{ $lang ?? 'en' }}";

                // Wait for the Google Translate dropdown to exist
                const interval = setInterval(() => {
                    const select = document.querySelector('.goog-te-combo');
                    if (select) {
                        clearInterval(interval);

                        // If already on correct language, stop here
                        if (select.value === userLang) return;

                        // Set the language
                        select.value = userLang;
                        select.dispatchEvent(new Event('change'));
                    }
                }, 500);
            });
        </script>

    </body>

    </html>