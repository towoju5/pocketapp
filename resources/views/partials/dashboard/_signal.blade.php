<div class="flex justify-between items-center pl-4 py-2 bg-[#2a3144]">
    <h1 class="text-gray-200 text-md text-center w-[80%]">Signals</h1>
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
    <button onclick="toggleTradeMenu(this, 'active')" class="trade-open-close relative py-2 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
        Updates
        <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
    <button onclick="toggleTradeMenu(this, 'closed')" class="trade-open-close relative py-2 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
        All
        <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
</div>

<!-- Trade Containers -->
<div class="trade-open-content trade_list-page flex justify-center items-center mt-0">
    <div id="openSignalList" style="overflow: scroll!important; width: -moz-available;">
        @foreach($signals as $signal)
        <div class="flex items-center justify-between px-3 py-1 border-b border-gray-200 signal-item" 
             data-id="{{ $signal['id'] }}" 
             data-duration="{{ $signal['duration'] }}"
             data-created-at="{{ \Carbon\Carbon::parse($signal['created_at'])->toIso8601String() }}"
             id="signal-{{ $signal['id'] }}">
            <div>
                <div class="text-sm font-bold text-white">{{ $signal['asset'] }}</div>
                <div class="text-xs text-white">₦{{ number_format($signal['amount'], 0) }}</div>
                <div class="text-xs text-gray-400">Copied: N/A</div>
            </div>
            <div class="text-right">
                <div class="text-xs text-gray-400">
                    <span class="countdown-timer" id="timer-{{ $signal['id'] }}">--:--</span>
                </div>
                <button class="bg-green-600 text-white text-xs px-4 py-1 rounded copy-signal-btn" 
                        data-id="{{ $signal['id'] }}" 
                        id="btn-{{ $signal['id'] }}">
                    Copy signal
                </button>
                <div class="text-green-500 text-xs">
                    {{ \Carbon\Carbon::parse($signal['created_at'])->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<div class="trade-closed-content trade_list-page flex justify-center items-center mt-4 hidden">
    <div class="flex flex-col w-full">
        @foreach(get_assets() as $key => $val)
        <div class="flex justify-between text-xs mb-2 text-white bg-[#272b3c] p-4">
            <span>{{ $val->symbol }}</span>
            <span class="flex gap-0 text-red-500">
                <i class="fa fa-long-arrow-up"></i>
                <i class="fa fa-long-arrow-up"></i>
            </span>
        </div>
        @endforeach
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const signals = document.querySelectorAll(".signal-item");

        signals.forEach(signal => {
            const id = signal.dataset.id;
            const createdAt = new Date(signal.dataset.createdAt);
            const duration = parseInt(signal.dataset.duration, 10) * 1000; // convert to ms
            const expireAt = new Date(createdAt.getTime() + duration);
            const timerEl = document.getElementById(`timer-${id}`);

            const interval = setInterval(() => {
                const now = new Date();
                const remaining = expireAt - now;

                if (remaining <= 0) {
                    clearInterval(interval);
                    signal.remove(); // Remove signal from DOM
                    return;
                }

                const minutes = Math.floor(remaining / 1000 / 60).toString().padStart(2, '0');
                const seconds = Math.floor((remaining / 1000) % 60).toString().padStart(2, '0');
                timerEl.textContent = `${minutes}:${seconds}`;
            }, 1000);
        });
    });
</script>
