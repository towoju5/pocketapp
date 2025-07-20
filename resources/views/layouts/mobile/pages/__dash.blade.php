@extends('layouts.mobile.app')

@section('content')
@php $__coin = $data->symbol ?? "USDCAD" @endphp
<style>
	.tv-lightweight-charts {
		height: 90vh!important;
	}
</style>
<div id="welcome-div">
	<div class="min-h-full" id="main-content">
		<div id="mainContent" class="hidden w-full min-h-screen bg-[#1a1c2c] text-white p-4"></div>
		<!-- Full screen chart -->
		<div id="chart-container" class="relative"></div>

		<!-- Updated Slider Structure -->
		<div class="flex justify-between items-center gap-10 absolute top-20 z-10">
			<div>
				<div class="relative inline-block" id="assetBtn">
					<!-- Clickable Dropdown Trigger -->
					<div class="flex items-center bg-[#2a3142] px-4 py-2 rounded cursor-pointer">
						<span class="text-white font-medium" id="selectedAsset">{{ $data->name }}</span>
						<svg xmlns="//www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
							<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
						</svg>
					</div>
				</div>

				<!-- Asset Dropdown Content -->
				<div id="assetDropDown" class="absolute left-1 rounded-lg bg-gray-800 shadow-lg rounded-xl z-10 hidden h-xl" style="width: 94vw">
					<div class="flex" style="height: 55vh;">
						<!-- Categories -->
						<div class="grid bg-gray-700 p-2 overflow-x-hidden">
							<ul>
								<li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('all')">All</li>
								@foreach($assetCategories as $aGroup)
									<li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('{{$aGroup->asset_group}}')">{{ ucfirst($aGroup->asset_group) }}</li>
								@endforeach
							</ul>
						</div>

						<!-- Stock List -->
						<div class="grid p-2 overflow-y-scroll">
							<input type="text" id="searchBar" placeholder="Search..." class="w-full h-10 px-2 py-1 bg-gray-700 rounded text-white" style="overflow: scroll">
							<ul id="stockList" class="mt-2 overflow-y-scroll">
								<!-- Stocks will be dynamically added here -->
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div>
				<div class="relative inline-block" id="chartTpyeBtn">
					<!-- Clickable Dropdown Trigger -->
					<button class="p-2 hover:bg-[#2a3142] rounded">
						<svg class="h-5 w-5 text-white" xmlns="//www.w3.org/2000/svg" fill="white" viewBox="0 0 40 35" width="40" height="35" data-src="/themes/cabinet/svgicons/chart-types/line.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
							<path d="M.22 16.28L4.4 11.6a.69.69 0 011.13-.06c1.2 1.12 2.21 2 3.4 3.16.23.23.33.17.52 0L18.2 4.54a.68.68 0 011.07 0c1.81 1.37 3.21 2.31 5 3.59.81.6 1.64 1.18 2.46 1.77.17.12.27.13.43 0L38.19.27c.24-.21.42-.16.55 0l1.08 1.23a.6.6 0 01.16.22.26.26 0 010 .24.24.24 0 01-.06.08c-1 .87-2.06 1.74-3.08 2.62l-9.38 8.12a.77.77 0 01-.88 0c-2.41-1.84-4.75-3.47-7.21-5.24-.28-.2-.41-.19-.63.07-3 3.54-5.92 6.88-9 10.39a.58.58 0 01-.91 0c-.91-.87-3-2.9-3.41-3.31-.21-.21-.33-.24-.55 0C3.9 15.8 3 16.81 2 17.9c-.19.21-.33.21-.52 0-.48-.37-.82-.7-1.27-1.12-.21-.19-.11-.36.01-.5zm.15 16.19h39.35a.19.19 0 01.2.2v2c0 .18-.08.25-.29.25H.45c-.29 0-.37-.07-.36-.37v-1.77a.27.27 0 01.28-.31z"></path>
							<path d="M.24 30.07A.14.14 0 01.09 30v-7c0-.29 0-.28.35-.64C2 21 3.43 19.16 5 17.73c.19-.17.29-.15.49 0A46.59 46.59 0 009 21.05a.62.62 0 00.8 0c2.73-2.66 5.59-6 9.18-10.27.2-.19.32-.2.57 0 2.4 1.76 4.67 3.39 7.18 5a.43.43 0 00.57 0c2.06-1.64 1.94-1.5 3.79-3.07 3.69-3.25 6-5.17 8.41-7.32.35-.21.44 0 .44.11v24.39c0 .09-.09.17-.22.17H.24z"></path>
						</svg>
					</button>
				</div>

				<!-- Asset Dropdown Content -->
				<div id="chartTpyeDropDown" class="absolute left-20 rounded hidden" style="margin-left: -3rem; margin-top: 5px;">
					<div class="w-full bg-gray-800 rounded-lg p-3">
						<div class="title my-2">Chart types</div>
						<div class="chart-types">
							<ul class="flex gap-4 my-3">
								<li onclick="setChartType('line')" class="area-selector chart-type-selector rounded-lg p-3 active block bg-[#293145] items-center">
									<svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon line-icon injected-svg" viewBox="0 0 40 35" width="40" height="35" data-src="" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
										<path d="M.22 16.28L4.4 11.6a.69.69 0 011.13-.06c1.2 1.12 2.21 2 3.4 3.16.23.23.33.17.52 0L18.2 4.54a.68.68 0 011.07 0c1.81 1.37 3.21 2.31 5 3.59.81.6 1.64 1.18 2.46 1.77.17.12.27.13.43 0L38.19.27c.24-.21.42-.16.55 0l1.08 1.23a.6.6 0 01.16.22.26.26 0 010 .24.24.24 0 01-.06.08c-1 .87-2.06 1.74-3.08 2.62l-9.38 8.12a.77.77 0 01-.88 0c-2.41-1.84-4.75-3.47-7.21-5.24-.28-.2-.41-.19-.63.07-3 3.54-5.92 6.88-9 10.39a.58.58 0 01-.91 0c-.91-.87-3-2.9-3.41-3.31-.21-.21-.33-.24-.55 0C3.9 15.8 3 16.81 2 17.9c-.19.21-.33.21-.52 0-.48-.37-.82-.7-1.27-1.12-.21-.19-.11-.36.01-.5zm.15 16.19h39.35a.19.19 0 01.2.2v2c0 .18-.08.25-.29.25H.45c-.29 0-.37-.07-.36-.37v-1.77a.27.27 0 01.28-.31z"></path>
										<path d="M.24 30.07A.14.14 0 01.09 30v-7c0-.29 0-.28.35-.64C2 21 3.43 19.16 5 17.73c.19-.17.29-.15.49 0A46.59 46.59 0 009 21.05a.62.62 0 00.8 0c2.73-2.66 5.59-6 9.18-10.27.2-.19.32-.2.57 0 2.4 1.76 4.67 3.39 7.18 5a.43.43 0 00.57 0c2.06-1.64 1.94-1.5 3.79-3.07 3.69-3.25 6-5.17 8.41-7.32.35-.21.44 0 .44.11v24.39c0 .09-.09.17-.22.17H.24z"></path>
									</svg>
									<!-- <br> <p class="py-2">Line</p> -->
								</li>

								<li onclick="setChartType('candlestick')" class="bars-selector chart-type-selector rounded-lg p-3 block bg-[#293145] items-center flex flex-col">
									<svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon candles-icon injected-svg" viewBox="0 0 500.1 435.1" width="55" height="35" data-src="/themes/cabinet/svg/icons/chart-types/candles.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
										<path d="M2.8 405H499c.6 0 1.1.5 1.1 1.1v26.4c0 2.2-.2 2.4-2.5 2.5h-2c-163.7 0-327.3 0-491 .1-3.7 0-4.7-.8-4.6-4.6.3-7.6.1-15.2.1-22.8 0-1.5 1.2-2.7 2.7-2.7zM425.1 1.8c0 11.9.1 23.8-.1 35.7 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 79.7-.1 159.3 0 239 0 2.8-.7 3.7-3.6 3.6-7.5-.2-15 .1-22.5-.1-3.3-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 3.3-.8 4-4.2 4.1-7.2.1-14.5 0-21.9 0-3.3 0-4.1-.8-4.1-4.1.2-17.3 0-34.7.2-52 0-3.3-.8-4.2-4.1-4.1-7.5.2-15-.1-22.5.1-2.9.1-3.4-.7-3.4-3.6 0-5.1-.1-8.3-.1-12.5 0-75 0-150-.1-225 0-4.1.9-5.4 5.1-5.1 7.1.4 14.3 0 21.5.2 2 .1 3.6-1.5 3.6-3.5-.1-11.9-.1-23.8-.1-35.7 0-1 .8-1.9 1.9-1.9h26.3c1-.1 1.8.7 1.8 1.7zM199.1 164c0-18.3.1-36.7-.1-55 0-3.2.7-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.7-.7 3.6-3.6-.2-7.7.1-15.3-.1-23-.1-3.1.8-3.6 4.4-3.6 7.9 0 14.8.1 22.1 0 2.9 0 3.7.4 3.7 3-.1 7.8.1 15.7-.1 23.5-.1 2.8.7 3.7 3.6 3.6 7.7-.2 15.3.1 23-.1 2 0 3.6 1.5 3.6 3.5-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.5 3.6-7.5-.2-15 .1-22.5-.1-3.2-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 4-1.1 3.8-6.7 3.9-5.6.1-10.9 0-16.5 0s-7 .3-6.9-3.8c.2-17.3 0-34.7.2-52 0-3.2-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1-.2-18.3-.3-36.7-.3-55zM44.1 228c0-18.3.1-36.7-.1-55 0-3.2.8-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.6-.7 3.6-3.6-.2-9.7 0-19.3-.1-29-.1-3.2 1-3.5 4.9-3.5h20.7c3.6 0 4.6.4 4.5 3.5-.2 9.7 0 19.3-.1 29 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.6 3.6-7.7-.2-15.3.1-23-.1-2-.1-3.6 1.6-3.6 3.5.1 15.5 0 31 .1 46.5 0 3.5-.9 4-5 4-6.7.1-13.3 0-20.1-.1-4 0-5-.2-5-3.5.1-15.5 0-31 .1-46.5 0-3.3-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1.2-18.2.1-36.6.1-54.9z"></path>
									</svg>
									<!-- <br> <p class="py-2">Candles</p> -->
								</li>

								<li onclick="setChartType('bar')" class="candles-selector chart-type-selector rounded-lg p-3 block bg-[#293145] items-center flex flex-col">
									<svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon bars-icon injected-svg" viewBox="0 0 500.1 435.1" width="40" height="35" data-src="/themes/cabinet/svg/icons/chart-types/bars.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
										<path d="M2.4 405h494.8c1.6 0 2.8 1.3 2.8 2.8 0 8.1-.1 16 .1 24.3 0 2.2-1.3 2.9-3.5 2.9-163.7 0-328.4 0-492.1.1-3.7 0-4.4-.6-4.4-4.6-.1-7.7-.1-15.4-.1-23.1 0-1.3 1.1-2.4 2.4-2.4zM423.9 0c1.7 0 3.1 1.4 3.1 3.1 0 103.8 0 207.6-.1 311.4 0 4.5 1 5.9 5.6 5.7 8.6-.4 17.3 0 26-.2 3.2-.1 3.5.9 3.5 5 .1 7.8-.1 14.2 0 20.8 0 3.4-.2 4.3-3 4.3-19.7-.1-39.3-.2-59 0-3.7 0-3-2-3-4.1v-80.5c0-26.7-.1-53.3.1-80 0-3.7-.8-4.8-4.6-4.6-9.3.3-18.7 0-28 .2-3.2.1-3.5-.9-3.5-4.8.1-6.9-.1-13.1 0-20.8 0-3.6.4-4.6 3.5-4.5 9.3.2 18.7-.1 28 .2 3.8.1 4.6-.9 4.6-4.6C397 98.7 397 50.8 397 3c0-1.7 1.3-3 3-3h23.9zM104 217.5c0 24 .1 48-.1 72 0 3.7.8 4.8 4.6 4.6 9.3-.3 18.7 0 28-.2 2.8-.1 3.6.7 3.6 3.6v23c0 2.8-.7 3.6-3.6 3.6-9.7-.2-19.3 0-29-.1-3 0-3.6.2-3.6 3.6v11.6c0 2.2-1 3-3.1 3H78.5c-3.5 0-4.5-1.2-4.5-3.7v-12.4c-.1-36.2-.1-72.3 0-108.5 0-3.7-.8-4.7-4.6-4.6-9.3.3-18.7 0-28 .2-2.8.1-3.6-.7-3.6-3.6v-23c0-2.8.7-3.6 3.6-3.6 9.7.2 19.3 0 29 .1 2.8.1 3.6-.7 3.6-3.6-.2-11.7 0-23.3-.1-35 0-2.8.7-3.6 3.6-3.6H100c3.3 0 4 .8 4 4.1-.2 24.1 0 48.3 0 72.5zM244 187.3c0 21.5-.1 43 .1 64.5 0 3.5-.8 4.5-4.4 4.3-7-.3-14-.3-21 0-4 .2-4.8-1-4.8-4.9.1-58.5.1-117 .1-175.5 0-6.8.1-13.7-.1-20.5-.1-2.6.7-3.4 3.3-3.3 7.8.2 15.7.2 23.5 0 2.6-.1 3.4.7 3.3 3.3-.2 9.8 0 19.7-.1 29.5 0 2.6.7 3.4 3.3 3.3 9.8-.2 19.7 0 29.5-.1 2.6 0 3.4.7 3.3 3.3v23.5c0 2.6-.7 3.3-3.3 3.3-9.7-.1-19.3.1-29-.1-3.1-.1-3.8.8-3.8 3.9.2 21.8.1 43.6.1 65.5z"></path>
									</svg>
									<!-- <br> <p class="py-2">Bars</p> -->
								</li>

								<li onclick="setChartType('heikinAshi')" class="heikin-selector chart-type-selector rounded-lg p-3 block bg-[#293145] items-center flex flex-col">
									<svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon heiken-ashi-icon injected-svg" viewBox="0 0 500.2 415.2" width="40" height="35" data-src="/themes/cabinet/svg/icons/chart-types/heiken-ashi.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
										<path d="M3.4 385.1h494.5c1.2 0 2.2 1 2.2 2.2 0 8.4-.1 16.9.1 25.3 0 2.2-1.1 2.5-2.8 2.5-163.7 0-329.1 0-492.8.1-3.7 0-4.7-.8-4.6-4.6.3-7.4.2-14.8.1-22.2 0-1.8 1.5-3.3 3.3-3.3zM46.1 79.6c0-25 .1-50-.1-75 0-3.7.9-4.6 4.6-4.6 27 .2 54 .2 81 0 3.7 0 4.6.9 4.6 4.6-.1 50-.1 100 0 150 0 3.7-.6 4.6-4.6 4.6h-21.4c-3.3 0-4.1.7-4.1 3.9.1 32.8 0 65.7.2 98.5 0 4.5-.2 5.5-4.8 5.5H80.2c-3.3.1-4.1-.9-4.1-4.1.1-32.8 0-65.7.2-98.5 0-4.5-1.1-5.5-5.6-5.4-7 .2-13.6 0-20.5 0-3.3 0-4.1-.9-4.1-4.1.1-25.1 0-50.2 0-75.4zM295.1 117.6c0 24-.1 48 .1 72 0 3.7-.8 4.6-4.6 4.6h-21.5c-3.3 0-4 .6-4 3.9.1 31 0 62 .2 93 0 4.1-.8 5.1-5.1 5.1h-21c-3.3.1-4.1-.8-4.1-4.1.1-31 0-62 .2-93 0-4.2-1-4.9-5.2-4.9h-20.4c-3.8 0-4.6-.9-4.6-4.6.2-35 .1-70 .1-105 0-13.2.1-26.3-.1-39.5 0-3.3.8-4.1 4.1-4.1 27.3.1 54.7.1 82 0 3.3 0 4.1.8 4.1 4.1-.3 24.2-.2 48.3-.2 72.5zM364.1 163.1c0-22.5.1-45-.1-67.5 0-3.7.8-4.6 4.6-4.6 27 .2 54 .2 81 0 3.7 0 4.6.8 4.6 4.6-.1 45-.1 90 0 135 0 3.7-.7 4.6-4.5 4.5-7.3 0-14.3.1-21.6 0-3.3 0-4.1.7-4 4 .1 28 0 56 .2 84 0 4.1-.8 5.1-5.1 5-7 0-13.9 0-21 .1-3.3.1-4.1-.8-4.1-4.1.1-28 0-56 .2-84 0-4.1-.9-5-5.1-5h-21c-3.3 0-4.1-.8-4.1-4 .1-22.7 0-45.3 0-68z"></path>
									</svg>
									<!-- <br> <p class="py-2">Heikin Ashi</p> -->
								</li>
							</ul>
						</div>
						<div class="title">Settings</div>
						<div class="settings">
							<div class="setting">
								<span>Show area</span>
								<div onclick="toggleArea()" class="toggle active"></div>
							</div>
							<div class="setting">
								<span>Enable autoscroll</span>
								<div class="toggle"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

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
	
	<!-- RIGHT NAV BAR Hidden Content Sections -->
	<div id="hidden-sections" class="hidden">
		<div id="rightTrades">
			@include('partials.dashboard._tradings')
		</div>

		<div id="rightSignals">
			@include('partials.dashboard._signal')
		</div>
		<div id="rightSocialTrading" class="bg-[#151726]">
			@include('partials.dashboard._social')
		</div>
		<div id="rightExpressTrades">
			@include('partials.dashboard._express', ['openedTrades' => $openedExpressTrades])
		</div>
		<div id="rightTournaments">
			@include('partials.dashboard._tournaments')
		</div>
		<div id="rightPendingTrades">
			<div class="p-3 text-white">
				<h2 class="text-2xl font-bold mb-4">Pending Trades</h2>
				<p>Monitor and manage your pending trade orders.</p>
			</div>
		</div>
		<div id="rightHotkeys">
			<div class="p-3 text-white">
				<h2 class="text-2xl font-bold mb-4">Hotkey Settings</h2>
				<p>Configure and view your trading hotkeys.</p>
			</div>
		</div>
	</div>
</template>
@include("components.chart", ["data" => $data])
@endsection