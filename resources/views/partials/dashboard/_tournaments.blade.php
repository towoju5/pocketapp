<div class="flex justify-between items-center pl-4 py-2 bg-[#2a3144]">
    <h1 class="text-gray-200 text-md text-center w-full">Tournaments</h1>
</div>
<!-- Tabs -->
<div class="grid grid-cols-2 border-b border-[#2a3142]">
    <button onclick="toggleTradeMenu(this, 'new')" class="trade-open-close relative py-2 text-gray-500 bg-[#1e2131] font-thin text-sm active-tab">
        All Tournaments
        <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
    <button onclick="toggleTradeMenu(this, 'statistics')" class="trade-open-close relative py-2 text-gray-500 bg-[#272b3c] font-thin text-sm">
        Statistics
        <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
</div>

<div class="p-4 rounded-lg trade-tab-content" data-tab="new">
    <!-- Tournament Card: Hour Play -->
    <div class="my-3 text-white">
        <div style="
            background: radial-gradient(
                131.28% 421.17% at 100% 0,
                rgba(8, 124, 199, 0.2) 0,
                transparent 100%
                ),
                #1e2131;
            " class="rounded-t-lg px-5 pt-2 flex items-center justify-between">
            <div>
                <div class="text-xl">Hour play</div>
                <div class="">
                    <div class="text-xs text-[#8ea5c0]">Prize fund</div>
                    <div class="text-[14px]">₦150,000</div>
                </div>
                <div class="">
                    <div class="text-xs text-[#8ea5c0]">Participation fee</div>
                    <div class="text-[14px]">₦1,500</div>
                </div>
            </div>
            <div class="tournament__img-wrap">
                <img src="https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png" srcset="
                https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png     1x,
                https://m.pocketoption.com/images/cabinet/tournaments/hour_play@2x.webp 2x
                " alt="Hour play" class="tournament__img">
            </div>
        </div>
        <div style="background: #262b3d" class="px-5 py-2 rounded-b-lg flex items-center justify-between">
            <div class="w-full">
                <div class="text-xs text-[#8ea5c0]">Ends in:</div>
                <div>00:05:29</div>
            </div>
            <button class="w-full py-2 text-xs rounded-md" style="background: #172832; border: 1px solid #025b44">
                Join
            </button>
        </div>
    </div>
    <!-- Tournament Card: Day Off -->
    <div class="my-3 text-white">
        <div style="background: radial-gradient(126.93% 414.63% at 98.83% 2.29%,rgba(136, 51, 203, 0.2) 0,transparent 100%),#1e2131;" class="rounded-t-lg px-5 pt-2 flex items-center justify-between">
            <div>
                <div class="text-xl">Day Off</div>
                <div class="">
                    <div class="text-xs text-[#8ea5c0]">Prize fund</div>
                    <div class="text-[14px]">₦150,000</div>
                </div>
                <div class="">
                    <div class="text-xs text-[#8ea5c0]">Participation fee</div>
                    <div class="text-[14px]">₦1,500</div>
                </div>
            </div>
            <div class="tournament__img-wrap">
                <img src="https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png" srcset="
                https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png     1x,
                https://m.pocketoption.com/images/cabinet/tournaments/hour_play@2x.webp 2x
                " alt="Hour play" class="tournament__img">
            </div>
        </div>
        <div class="px-5 py-2 rounded-b-lg grid grid-cols-2 items-center justify-between bg-[#262b3d]">
            <div class="w-full">
                <div class="text-xs" style="color: #8ea5c0">Ends in:</div>
                <div>00:05:29</div>
            </div>
            <button class="w-full py-2 text-xs rounded-md" style="background: #172832; border: 1px solid #025b44">
                Join
            </button>
        </div>
    </div>

    <!-- Tournament Card: Hour Play -->
</div>

<!-- //// -->
<div class="p-4 rounded-lg trade-tab-content hidden" data-tab="statistics">
    <div class="space-y-2 text-gray-300 text-sm mt-3" style="color: #8ea5c0">
        <p class="grid grid-cols-2">
            <span>Tournaments won:</span> <span>0</span>
        </p>
        <p class="grid grid-cols-2">
            <span>Total prize money:</span> <span>$0</span>
        </p>
        <p class="grid grid-cols-2">
            <span>Largest prize:</span> <span>$0</span>
        </p>
    </div>
</div>
