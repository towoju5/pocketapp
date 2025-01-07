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