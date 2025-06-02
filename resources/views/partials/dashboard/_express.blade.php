@push('css')
<style>
    .content {
        display: none;
    }

    .content.active {
        display: block;
    }

    .trade-btn {
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        border: none;
        color: white;
        height: 45px;
    }

    .trade-btn.up {
        background-color: #16a34a;
    }

    .trade-btn.down {
        background-color: #dc2626;
    }

    .trade-btn-up-svg:hover {
        rotate: -45deg;
    }

    .trade-btn-down-svg:hover {
        rotate: 45deg;
    }

    .trade-btn.selected {
        outline: 2px solid #facc15;
    }

    .trade-btn:hover {
        background: -webkit-gradient(linear, left top, left bottom, from(#45b734), to(#369146)) 0;
        background: linear-gradient(#45b734, #369146) 0;
    }

    .trade-btn:hover {
        background: -webkit-gradient(linear, left bottom, left top, from(hsla(0, 0%, 100%, .1)), to(hsla(0, 0%, 100%, .1))), #32ac41;
        background: linear-gradient(0deg, hsla(0, 0%, 100%, .1), hsla(0, 0%, 100%, .1)), #32ac41;
        -webkit-box-shadow: 0 0 10px #40cb53;
        box-shadow: 0 0 10px #40cb53;
        color: #fff !important;
    }

    \#trade-confirm {
        margin-top: 16px;
    }

    \#trade-confirm input {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: none;
        margin-bottom: 8px;
    }

    \#trade-confirm button {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: none;
        background-color: #16a34a;
        color: white;
        cursor: pointer;
    }

    /* …keep everything you already had … */
    .filter-btn {
        @apply flex-1 py-2 rounded-lg text-xs text-white;
        background: #1d2130;
        border: 1px solid #454a56
    }

    .filter-btn.active {
        background: #2563eb
    }

    .countdown {
        background: #162032;
        border: 1px solid #44506a;
        font-size: 10px
    }

    .trade-btn[disabled] {
        opacity: .4;
        cursor: not-allowed
    }

    .trade-disabled {
        pointer-events: none;
        opacity: 0.3
    }
</style>
@endpush

<div class="flex justify-between items-center pl-4 py-2 bg-[#2a3144]">
    <h1 class="text-gray-200 text-md text-center w-full">Express Trading</h1>
</div>
<!-- Tabs -->
<div class="grid grid-cols-3 border-b border-[#2a3142]">
    <button onclick="toggleTradeMenu(this, 'new')" id="openExpressTradeBtn" class="trade-open-close relative py-2 text-gray-500 bg-[#1e2131] font-thin text-sm active-tab">
        New
        <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
    <button onclick="toggleTradeMenu(this, 'opened')" class="trade-open-close relative py-2 text-gray-500 bg-[#272b3c] font-thin text-sm">
        Opened
        <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
    <button onclick="toggleTradeMenu(this, 'closed')" class="trade-open-close relative py-2 text-gray-500 bg-[#272b3c] font-thin text-sm">
        Closed
        <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
    </button>
</div>

<div class="default-styled-tab-content">
    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 trade-tab-content" data-tab="new">
        <div class="text-white">
            <div class="h-full overflow-hidden">
                {{-- Express Trades header + tabs stay exactly as before --}}
                <div id="express-new" class="content active">

                    {{-- ########################################
                          AMOUNT INPUT (appears after a pick)
                    ######################################## --}}
                    <div id="trade-confirm" class="mb-4 space-y-2" style="display: none;">
                        <div id="selected-trade-list" class="space-y-1">
                        </div>

                        <input type="number" id="trade-amount"
                            class="w-full p-2 rounded bg-transparent text-sm"
                            placeholder="Enter amount" />

                        <button onclick="submitExpressTrade()" class="w-full p-2 bg-green-700 rounded text-white text-sm">
                            Place Trades
                        </button>
                    </div>


                    {{-- ⚠ level notice + “Acquire level” button - unchanged  --}}

                    {{-- ############  TYPE FILTER ############ --}}
                    <div class="mt-4 grid grid-cols-4" id="asset-filters">
                        <button class="border border-gray-600 hover:border-blue-600 rounded-md py-2 active" data-filter="CRYPTO_OTC">
                            <i class="fa fa-usd"></i>
                        </button>
                        <button class="border border-gray-600 hover:border-blue-600 rounded-md py-2" data-filter="FOREX_OTC">
                            <i class="fa fa-tint"></i>
                        </button>
                        <button class="border border-gray-600 hover:border-blue-600 rounded-md py-2" data-filter="STOCKS_USD">
                            <i class="fa fa-file-text"></i>
                        </button>
                        <button class="border border-gray-600 hover:border-blue-600 rounded-md py-2" data-filter="search">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>

                    {{-- ###########  ASSET LIST ############ --}}
                    <div id="asset-list" class="mt-4 space-y-3">
                        @foreach($assets as $asset)
                        <div class="asset-item-{{ $asset->id }} flex items-center justify-between gap-3" data-type="{{ $asset->asset_group }}">
                            {{-- up --}}
                            <button class="trade-btn up asset_{{ $asset->id }}" data-assetId="{{ $asset->id }}" data-percentage="{{ $asset->asset_profit_margin * 100 }}" data-asset="{{ $asset->symbol }}" data-direction="up" data-close-time="30" onclick="selectTrade(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" class="trade-btn-up-svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
                                    <path d="M10 0C8.02219 0 6.08879 0.58649 4.4443 1.6853C2.79981 2.78412 1.51809 4.3459 0.761209 6.17317C0.00433284 8.00043 -0.193701 10.0111 0.192152 11.9509C0.578004 13.8907 1.53041 15.6725 2.92894 17.0711C4.32746 18.4696 6.10929 19.422 8.0491 19.8079C9.98891 20.1937 11.9996 19.9957 13.8268 19.2388C15.6541 18.4819 17.2159 17.2002 18.3147 15.5557C19.4135 13.9112 20 11.9778 20 10C19.9971 7.34874 18.9425 4.80691 17.0678 2.93219C15.1931 1.05746 12.6513 0.00294858 10 0Z" fill="#248F32"></path>
                                    <path d="M13.8319 12.832L13.8288 7.17244C13.8278 6.90725 13.722 6.65311 13.5343 6.46549C13.3467 6.27786 13.0926 6.172 12.8274 6.17101L7.16786 6.16792C6.90411 6.17016 6.65203 6.27647 6.46647 6.46372C6.28091 6.65097 6.17688 6.90401 6.17703 7.16777C6.17717 7.43154 6.28148 7.68469 6.46725 7.87214C6.65301 8.0596 6.90521 8.16619 7.16897 8.16873L10.4135 8.17057L6.46366 12.1204C6.27612 12.308 6.17085 12.5624 6.17099 12.8278C6.17114 13.0931 6.2767 13.3477 6.46444 13.5354C6.65218 13.7232 6.90674 13.8287 7.1721 13.8289C7.43746 13.829 7.6919 13.7237 7.87944 13.5362L11.8293 9.58635L11.8311 12.8309C11.8329 13.0952 11.9392 13.3481 12.1267 13.5344C12.3143 13.7208 12.5678 13.8255 12.8321 13.8256C13.0963 13.8258 13.3498 13.7214 13.5371 13.5352C13.7244 13.349 13.8304 13.0962 13.8319 12.832Z" fill="white"></path>
                                </svg>
                            </button>

                            {{-- middle --}}
                            <div class="w-full text-center relative">
                                <div class="custom-dropdown relative inline-block w-full">
                                    <p class="text-xs">
                                        {{ $asset->symbol }}
                                        <span class="text-green-400">+{{ $asset->payout }}%</span>
                                    </p>

                                    <!-- Countdown Trigger -->
                                    <p class="countdown cursor-pointer selected-time" data-duration="{{ $asset->duration }}">
                                        M {{ $asset->duration }} 01:00
                                    </p>

                                    <!-- Dropdown -->
                                    <div class="dropdown-menu absolute mb-2 bg-[#162032] p-2 grid grid-cols-2 gap-2 text-sm text-gray-200 z-50 hidden"
                                        style="width: 120%; margin-left: -15px;">
                                        <p class="flex-1 col-span-2 text-left text-gray-400 text-xs px-1">Time until purchase</p>
                                        <!-- Options injected via jQuery -->
                                    </div>
                                </div>
                            </div>

                            {{-- down --}}
                            <button class="trade-btn down asset_{{ $asset->id }}" data-assetId="{{ $asset->id }}" data-percentage="{{ $asset->asset_profit_margin * 100 }}" data-asset="{{ $asset->symbol }}" data-direction="down" data-close-time="30" onclick="selectTrade(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" class="trade-btn-down-svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
                                    <path d="M10 20C11.9778 20 13.9112 19.4135 15.5557 18.3147C17.2002 17.2159 18.4819 15.6541 19.2388 13.8268C19.9957 11.9996 20.1937 9.98891 19.8079 8.0491C19.422 6.10929 18.4696 4.32746 17.0711 2.92894C15.6725 1.53041 13.8907 0.578004 11.9509 0.192152C10.0111 -0.193701 8.00043 0.00433284 6.17317 0.761209C4.3459 1.51809 2.78412 2.79981 1.6853 4.4443C0.58649 6.08879 0 8.02219 0 10C0.00294858 12.6513 1.05746 15.1931 2.93219 17.0678C4.80691 18.9425 7.34874 19.9971 10 20Z" fill="#D1281F"></path>
                                    <path d="M7.16786 13.8324L12.828 13.8287C13.0932 13.8277 13.3474 13.7218 13.535 13.5341C13.7227 13.3465 13.8286 13.0923 13.8296 12.8271L13.8333 7.16702C13.831 6.90324 13.7247 6.65115 13.5375 6.46559C13.3502 6.28003 13.0972 6.17602 12.8334 6.17619C12.5696 6.17636 12.3164 6.2807 12.1289 6.4665C11.9414 6.65231 11.8348 6.90454 11.8322 7.16832L11.8301 10.4132L7.88023 6.46333C7.6927 6.27579 7.43825 6.17053 7.17286 6.17071C6.90747 6.17088 6.65288 6.27647 6.4651 6.46425C6.27732 6.65203 6.17173 6.90662 6.17155 7.17201C6.17138 7.4374 6.27664 7.69185 6.46418 7.87939L10.414 11.8292L7.16917 11.8314C6.90539 11.834 6.65315 11.9406 6.46735 12.1281C6.28155 12.3156 6.17721 12.5688 6.17704 12.8325C6.17686 13.0963 6.28087 13.3494 6.46643 13.5366C6.65199 13.7239 6.90409 13.8302 7.16786 13.8324Z" fill="white"></path>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-4 text-center">
                        <button id="submit-trades" class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50" disabled>
                            Submit Trades
                        </button>
                    </div>

                </div>
                {{-- express-opened / express-closed blocks unchanged --}}
            </div>
        </div>

    </div>
    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 trade-tab-content hidden" data-tab="opened">
        @forelse($openedTrades as $trade)
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-300"></div>
            </div>
        </div>
        @empty
        <a class="bg-[#172832] text-white border border-[#025b44] py-2 rounded-md flex justify-center hover:cursor-pointer" onclick="$('#openExpressTradeBtn').click()">Create new express</a>
        @endforelse
    </div>
    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 trade-tab-content hidden" data-tab="closed">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">closed tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
</div>



@push('js')
<script>
    let selectedTrades = [];

    function renderSelectedTrades() {
        const container = document.getElementById('selected-trade-list');
        const tradeConfirm = document.getElementById('trade-confirm');

        if (selectedTrades.length === 0) {
            tradeConfirm.style.display = 'none';
            return;
        }

        tradeConfirm.style.display = 'block';
        container.innerHTML = '';

        selectedTrades.forEach((trade, index) => {
            const div = document.createElement('div');
            div.className = 'group bg-gray-600 p-2 rounded text-sm';

            const icon = trade.direction === 'up' ?
                '<i class="fa fa-arrow-up text-green-600"></i>' :
                '<i class="fa fa-arrow-down text-red-600"></i>';

            div.innerHTML = `
            <div class="flex justify-between items-start">
                <div class="group">
                    <p class="flex gap-2 items-center font-medium mb-1">
                        ${icon}
                        <span>${trade.asset}</span>
                        <span class="text-gray-600">${trade.percentage}%</span>
                    </p>
                    <p class="text-xs text-gray-500">Time until purchase: ${trade.close_time}s</p>
                </div>
                <button onclick="removeTrade(${index})" class="text-red-600 text-sm">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;

            container.appendChild(div);
        });
    }
        
    function updateTradeButtonStyles(assetId) {
        const assetItems = document.getElementsByClassName(`asset-item-${assetId}`);

        const isStillSelected = selectedTrades.some(trade => trade.asset_id == assetId);

        Array.from(assetItems).forEach(item => {
            if (isStillSelected) {
                item.classList.add('trade-disabled');
            } else {
                item.classList.remove('trade-disabled');
            }
        });
    }



    function removeTrade(index) {
        const removedTrade = selectedTrades[index]; // get the removed trade before removal
        selectedTrades.splice(index, 1);
        renderSelectedTrades();
        if (removedTrade) {
            updateTradeButtonStyles(removedTrade.asset_id); // update styles for removed asset
        }
    }


    function selectTrade(t) {
        const asset = $(t).data('asset');
        const assetId = $(t).data('assetid');
        const direction = $(t).data('direction');
        const closeTime = $(t).data('close-time');
        const percentage = $(t).data('percentage');

        const tradeKey = `${assetId}-${direction}-${closeTime}`;

        const existingIndex = selectedTrades.findIndex(trade =>
            `${trade.asset_id}-${trade.direction}-${trade.close_time}` === tradeKey
        );

        if (existingIndex > -1) {
            selectedTrades.splice(existingIndex, 1);
        } else {
            selectedTrades.push({
                asset_id: assetId,
                asset: asset,
                direction: direction,
                close_time: closeTime,
                percentage: percentage
            });
        }

        renderSelectedTrades();
        updateTradeButtonStyles(assetId);
    }

    function submitExpressTrade() {
        if (selectedTrades.length === 0) {
            alert('No trades selected!');
            return;
        }

        const amount = parseFloat($('#trade-amount').val() || 10);

        const payload = {
            trades: selectedTrades,
            amount: amount
        };

        $.ajax({
            url: "{{ route('submit.express.trade') }}",
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                // alert('Trades submitted successfully!');
                toastr.success(res.message);
                selectedTrades = [];
                renderSelectedTrades();
                $('#trade-amount').val('');
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    }


    $(document).ready(function() {
        const options = [
            '00:30', '00:60', '01:00', '01:30', '02:00', '02:30',
            '03:00', '03:30', '04:00', '04:30', '05:00', '01:05:30'
        ];

        const $dropdown = $('.custom-dropdown');
        const $menu = $dropdown.find('.dropdown-menu');
        const $trigger = $dropdown.find('.selected-time');

        // Populate options
        options.forEach(option => {
            const $opt = $(`<div class="px-2 py-1 hover:bg-gray-200 bg-[#293145] cursor-pointer rounded">${option}</div>`);
            $opt.on('click', function() {
                $trigger.text(option);
                $menu.hide();
            });
            $menu.append($opt);
        });

        // Toggle dropdown on trigger click
        $trigger.on('click', function(e) {
            e.stopPropagation();
            $menu.toggle();
        });

        // Close dropdown on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-dropdown').length) {
                $menu.hide();
            }
        });
    });
</script>
@endpush