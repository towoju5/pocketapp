<div class="flex justify-between items-center pl-4 py-2 bg-[#2a3144]">
    <h1 class="text-gray-200 text-md text-center w-[80%]">Trades</h1>
    <div class="w-[20%]">
        <button onclick="window.location.href='{{ route('trade.index') }}'" class="p-2 rounded-full bg-[#8ea5c0] text-[#2a3144] text-center">
            <svg class="w-3 h-3 text-[#2a3144]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>

<!-- Tabs -->
<div class="flex border-b border-[#2a3142] w-full">
    <button onclick="toggleTradeMenu(this, 'active')" class="trade-open-close relative py-2 text-gray-500 bg-[#1e2131] font-thin text-sm w-6/12 active-tab">
        Opened
        <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
    <button onclick="toggleTradeMenu(this, 'closed')" class="trade-open-close relative py-2 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
        Closed
        <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
</div>

<!-- Trade Containers -->
<div class="trade-open-content trade_list-page flex justify-center items-center mt-0 trade-tab-content" data-tab="active">
    <div id="openTradeList">
        @foreach($active_trades as $trade)
            @include('mini-pages.trade-list')
        @endforeach
    </div>
</div>


<div class="trade-closed-content trade_list-page items-center trade-tab-content hidden" data-tab="closed">
    @forelse($recent_closed_trades as $trade)
        @include('mini-pages.trade-list')
    @empty
        <p id="regular-trade-no-trade-text-default" class="text-gray-500 flex-1 text-center mt-16">Closed Trade Container</p>
    @endforelse
</div>