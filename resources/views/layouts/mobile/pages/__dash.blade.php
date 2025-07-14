@extends('layouts.mobile.app')

@section('content')
@php $__coin = $data->symbol ?? "USDCAD" @endphp
<div id="welcome-div">
	<div class="min-h-full">
		<!-- Full screen chart -->
		<div id="chart-container" class="relative" style="height: calc(100vh - 7.5rem);">
		</div>

		<!-- Updated Slider Structure -->
		<div class="slider-wrapper">
			<span class="progress-left">0%</span>
			<div class="slider-container">
				<div class="slider-bar"></div>
			</div>
			<span class="progress-right">100%</span>
		</div>

		<!-- Trading Actions Overlay -->
		<form method="POST" action="{{ route('trade.store') }}" class="trading-actions mb-10 z-10" id="tradeForm">
            @csrf
			<div class="flex justify-between items-center">
				<div id="timeContainer" class="input-container cursor-pointer">
					<p class="text-sm text-gray-400">Time</p>
					<div>
						<div class="relative">
							<input type="hidden" name="asset" id="assetTicker" value="{{ $__coin }}">
							<input type="text" pattern="^\d*\.?\d*$" step="any" class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"maxlength="8" placeholder="00:01:00" value="00:01:00" name="duration">

							<!-- Dollar sign positioned to the right inside the input -->
							<span class="absolute inset-y-0 end-0 flex items-center pe-3.5 pointer-events-none text-gray-500 dark:text-gray-400 text-sm">
								<i class="fa-regular fa-clock bg-[#23283b]"></i>
							</span>
						</div>
					</div>
				</div>

				<div id="amountContainer" class="input-container cursor-pointer">
					<p class="text-sm text-gray-400">Amount</p>
					<div>
						<input type="hidden" name="direction" id="direction" value="">
                        <input type="hidden" name="order_token" id="order_token" value="">
                        <input type="hidden" name="order_time" id="order_time" value="">
						
						<div class="relative">
							<input type="text" pattern="^\d*\.?\d*$" step="any" id="input_amount_field" name="amount" oninput="calculate_trade_profit()" class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]" autocomplete="off" placeholder="1000">

							<!-- Dollar sign positioned to the right inside the input -->
							<span class="absolute inset-y-0 end-0 flex items-center pe-3.5 pointer-events-none text-gray-500 dark:text-gray-400 text-sm">$</span>
						</div>
					</div>
				</div>
			</div>
			<div class="flex justify-between text-green-400 text-lg font-semibold">
				<p id="payout_total_text">Payout<br /><span class="text-white text-sm" id="payout_total">$16.20</span></p>
				<span id="profit_percentage">+{{ $data->asset_profit_margin }}% </span>
				<p>Profit<br /><span id="payout">$0.0</span></p>
			</div>
			<div class="flex space-x-4">
				<button type="button" name="action" data-value="up" class="_hover-up cta-button bg-green-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2">
					<svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<rect width="24" height="24" fill="none"></rect>
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM16 14C16 14.5523 15.5523 15 15 15C14.4477 15 14 14.5523 14 14V11.4142L9.70711 15.7071C9.31658 16.0976 8.68342 16.0976 8.29289 15.7071C7.90237 15.3166 7.90237 14.6834 8.29289 14.2929L12.5858 10H10C9.44772 10 9 9.55228 9 9C9 8.44772 9.44772 8 10 8H14.6717C15.4054 8 16 8.59489 16 9.32837V14Z"
								fill="#015b17"></path>
						</g>
					</svg>
					<span>BUY</span>
				</button>
				<buttontype="button" name="action" data-value="down"
                        class="_hover-down cta-button bg-red-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2">
					<svg width="24px" height="24px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<rect width="24" height="24" fill="none"></rect>
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM9.70711 8.29289C9.31658 7.90237 8.68342 7.90237 8.29289 8.29289C7.90237 8.68342 7.90237 9.31658 8.29289 9.70711L12.5858 14H10C9.44772 14 9 14.4477 9 15C9 15.5523 9.44772 16 10 16H14.6717C15.4054 16 16 15.4051 16 14.6716V10C16 9.44772 15.5523 9 15 9C14.4477 9 14 9.44772 14 10V12.5858L9.70711 8.29289Z"
								fill="#990000"></path>
						</g>
					</svg>
					<span>SELL</span>
				</button>
			</div>
		</form>
	</div>
</template>
<div class="hidden">
	<div id="assetBtn"></div>
	<div id="assetDropDown"></div>
	<div id="stockList"></div>
	<div id="bythetime"></div>
	<div id="searchBar"></div>
	<div id="chartTpyeBtn"></div>
	<div id="chartTpyeDropDown"></div>
</div>
@include("components.chart", ["data" => $data])
@endsection