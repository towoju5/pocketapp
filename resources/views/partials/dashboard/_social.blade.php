<div class="flex justify-between items-center pl-4 py-2 bg-[#2a3144]">
    <h1 class="text-gray-200 text-md text-center w-full">Social Trading</h1>
</div>

<div class="my-2 text-white">
    <div class="h-full flex flex-col">
        <div class="flex-1 overflow-y-auto pb-20">
            <div class="relative py-3 px-4">
                <select onchange="changeTraderSection(this.value)" class="w-full py-2.5 rounded-md text-xs px-4 bg-[#1d2130] border border-[#454a56] text-white">
                    <option value="24h">Top ranked traders for 24h</option>
                    <option value="7d">Top ranked traders</option>
                    <option value="30d">Top 100 traders</option>
                </select>
            </div>

            <div class="text-xs text-center text-gray-400 py-3 bg-[#1e2131]">REAL TRADING</div>

            {{-- 24 HOURS --}}
            <div class="trader-section" data-period="24h">
                @foreach($traders24hours as $trader)
                <div class="px-3 py-2 flex items-center gap-2">
                    <div style="width: 20%" class="flex items-center justify-center h-full">
                        <img src="{{ $trader->avatar }}" alt="Profile Image" class="w-10 h-10 rounded-full">
                    </div>
                    <div class="w-full gap-8">
                        <div class="flex items-center justify-between text-sm">
                            <p>{{ $trader->username }}</p>
                            <p style="color: #6fc274">+${{ number_format($trader->total_profit, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                                <p class="text-xs text-white">{{ $trader->trades->count() }}</p>
                            </div>
                            <div>
                                @php
                                $total = $trader->trades->count();
                                $wins = $trader->trades->where('trade_status', 'win')->count();
                                $percent = $total > 0 ? round(($wins / $total) * 100) : 0;
                                @endphp
                                <p class="text-xs text-right" style="color: #8ea5c0">Profitable trades:</p>
                                <p class="text-xs text-right text-white">{{ $percent }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


            {{-- ALL-TIME --}}
            <div class="trader-section hidden" data-period="7d">
                @foreach($tradersTopRanked as $trader)
                <div class="px-3 py-1 flex items-center gap-2 _social-trade-card">
                    <div style="width: 20%" class="flex items-center justify-center h-full">
                        <img src="{{ $trader->avatar }}" alt="Profile Image" class="w-10 h-10 rounded-full">
                    </div>
                    <div class="w-full gap-8">
                        <div class="flex items-center justify-between text-sm">
                            <p>{{ $trader->username }}</p>
                            <p style="color: #6fc274">+${{ number_format($trader->total_profit, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                                <p class="text-xs text-white">{{ $trader->trades->count() }}</p>
                            </div>
                            <div>
                                @php
                                $total = $trader->trades->count();
                                $wins = $trader->trades->where('trade_status', 'win')->count();
                                $percent = $total > 0 ? round(($wins / $total) * 100) : 0;
                                @endphp
                                <p class="text-xs text-right" style="color: #8ea5c0">Profitable trades:</p>
                                <p class="text-xs text-right text-white">{{ $percent }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


            {{-- TOP 100 --}}
            <div class="trader-section hidden" data-period="30d">
                @foreach($tradersTop100 as $trader)
                <div class="px-3 py-1 flex items-center gap-2">
                    <div style="width: 20%" class="flex items-center justify-center h-full">
                        <img src="{{ $trader->avatar }}" alt="Profile Image" class="w-10 h-10 rounded-full">
                    </div>
                    <div class="w-full gap-8">
                        <div class="flex items-center justify-between text-sm">
                            <p>{{ $trader->username }}</p>
                            <p style="color: #6fc274">+${{ number_format($trader->total_profit, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                                <p class="text-xs text-white">{{ $trader->trades->count() }}</p>
                            </div>
                            <div>
                                @php
                                $total = $trader->trades->count();
                                $wins = $trader->trades->where('trade_status', 'win')->count();
                                $percent = $total > 0 ? round(($wins / $total) * 100) : 0;
                                @endphp
                                <p class="text-xs text-right" style="color: #8ea5c0">Profitable trades:</p>
                                <p class="text-xs text-right text-white">{{ $percent }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


        </div>
    </div>
</div>


@push('css')
<style>
    .theme-dark-blue .right-sidebar-modal.sc-modal .sc-items__item:nth-child(2n) {
        background-color: #292d41;
    }
</style>
@endpush


{{-- JavaScript to handle dropdown toggle --}}
@push('js')
<script>
    function changeTraderSection(value) {
        const sections = document.querySelectorAll('.trader-section');
        sections.forEach(section => {
            section.classList.toggle('hidden', section.dataset.period !== value);
        });
    }

    // Optional: set default visible on load
    document.addEventListener("DOMContentLoaded", function() {
        changeTraderSection(document.getElementById('periodSelect').value);
    });
</script>
@endpush