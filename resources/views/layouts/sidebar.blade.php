<div id="sideMenu" class="bg-[#1c1f26] min-h-screen px-0 py-1 lg:w-24 fixed z-10">
    <!-- Menu Items -->
    <button @if(Route::is('dashboard*')) bg-slate-400 @endif data-href="{{ route('dashboard') }}" class="menu-item py-3 hover:bg-slate-400 w-full text-gray-400 hover:text-white" data-tooltip="Analytics">
        <i class="fa-solid fa-chart-line"></i>
        <span class="hidden_text hidden lg:block">Trading</span>
    </button>

    <button @if(Route::is('deposit.create')) bg-[#434858] @endif data-href="{{ route('finance.history') }}" class="menu-item py-3 w-full hover:bg-slate-400 text-gray-400 hover:text-white"
        data-tooltip="Finance">
        <i class="fa-solid fa-dollar-sign"></i>
        <span class="hidden_text hidden lg:block">Finance</span>
    </button>

    <button @if(Route::is('trade.index')) bg-slate-400 @endif data-href="{{ route('profile.edit') }}" class="menu-item py-3 w-full hover:bg-slate-400 text-gray-400 hover:text-white"
        data-tooltip="Profile">
        <i class="fa-solid fa-user"></i>
        <span class="hidden_text hidden lg:block">Profile</span>
    </button>

    <button @if(Route::is('trade.index')) bg-slate-400 @endif data-href="#" class="menu-item py-3 w-full hover:bg-slate-400 text-gray-400 hover:text-white relative" data-tooltip="Cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="hidden_text hidden lg:block">Market</span>
    </button>

    <button data-href="#" class="menu-item py-3 w-full hover:bg-slate-400 text-gray-400 hover:text-white relative"
        data-tooltip="Notifications">
        <i class="fa-regular fa-gem"></i>
        <span class="absolute top-2 right-2 bg-[#0c69a9] text-xs rounded-md text-white px-2 py-1">6</span>
        <span class="hidden_text hidden lg:block">Achievements</span>
    </button>

    <button data-href="#" class="menu-item py-3 w-full hover:bg-slate-400 text-gray-400 hover:text-white relative" data-tooltip="Chat">
        <i class="fa-regular fa-comment"></i>
        <span class="absolute top-2 right-2 bg-[#0c69a9] text-xs rounded-md text-white px-2 py-1">6</span>
        <span class="hidden_text hidden lg:block">Chat</span>
    </button>

    <button data-href="#" class="menu-item py-3 w-full hover:bg-slate-400 text-gray-400 hover:text-white" data-tooltip="Help">
        <i class="fa-solid fa-circle-question"></i>
        <span class="hidden_text hidden lg:block">Help</span>
    </button>

    <div class="bottom-3 fixed items-center mx-0 px-0 space-y-2">
        <button onclick="$('#UserLogoutForm').submit()" class="menu-item py-4 w-full text-gray-400 hover:text-white"
            data-tooltip="Help">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="hidden_text hidden lg:block">Trades</span>
        </button>

        <button class="menu-item py-4 w-full text-gray-400 hover:text-white" data-tooltip="Help" id="leftSideBarArrow">
            <i class="fa-solid fa-arrow-right-long"></i>
        </button>
    </div>
</div>



@push('js')
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $('#leftSideBarArrow').click(function() {
                $('#sideMenu').toggleClass('w-20 lg:w-20');
                $('#sideMenu').toggleClass('w-64 lg:w-64');
            });

            // Redirect to the page
            $('.menu-item').click(function() {
                window.location.href = $(this).data('href');
            });
        });
    </script>
@endpush
