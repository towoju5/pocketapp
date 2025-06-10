@extends('layouts.app')

@section('title', 'Trading Dashboard')

@section('content')
@php $__coin = $data->symbol ?? "USDCAD" @endphp
<style>
    .signal-card {
        padding: 10px;
        width: 330px;
    }

    .signal-card:nth-child(even) {
        background-color: #292d41;
    }

    .signal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* margin-bottom: 10px; */
    }

    .signal-title {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #A0AEC0;
        gap: .75rem;
    }

    .signal-title i {
        color: #FFA500;
        margin-right: 5px;
    }

    .signal-percentage {
        color: #4CAF50;
        font-weight: bold;
    }

    .signal-time {
        color: white;
        /* font-size: 10px; */
    }

    .signal-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .signal-price {
        display: flex;
        align-items: center;
        font-size: 14px;
        font-weight: bold;
    }

    .signal-price i {
        color: #4CAF50;
        margin-right: 5px;
    }

    .signal-profit {
        color: #4CAF50;
        font-size: 14px;
        font-weight: bold;
    }

    .signal-button {
        background-color: #19222D;
        border: 1px solid #0F5D42;
        border-radius: 5px;
        padding: 4px;
        text-align: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .signal-button i {
        margin-right: 5px;
    }
    .fa-arrow-up {
        color: rgb(73, 167, 68);
        margin-right: 2px;
        transform: rotate(45deg);
    }
</style>



<!-- THIS IS THE CHART DIV + THE BUY AND SELL -->
<section class="flex flex-grow w-full box-contain overflow-hidden">
    <!-- EVERYTHING CHART IS DONE IN THE SECTION ELEMENT -->
    <!-- MY ATTEMPT AT DOING THE GRAPH MY SELF graph container -->
    <section class="text-gray-400 min-h-screen z-20 w-full" style="background-image: url('/assets/images/bg.jpg');">
        <!-- Background Image -->

        <!-- Main Content -->
        <div class="relative z-10 min-h-screen w-full">
            <!-- CHART CONTROLS Top Navigation Bar -->
            <div class="flex items-center space-x-2 p-2 bg-[#1a1f2e]/50 z-10">
                <!-- Stock Selector -->
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
                    <div id="assetDropDown" class="absolute left-1 min-w-160 rounded-lg bg-gray-800 shadow-lg rounded-xl z-10 hidden">
                        <div class="flex">
                            <!-- Categories -->
                            <div class="w-1/3 bg-gray-700 p-2">
                                <ul>
                                    <li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('all')">All</li>
                                    @foreach($assetCategories as $aGroup)
                                        <li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('{{$aGroup->asset_group}}')">{{ ucfirst($aGroup->asset_group) }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Stock List -->
                            <div class="w-2/3 p-2">
                                <input type="text" id="searchBar" placeholder="Search..." class="w-full px-2 py-1 bg-gray-700 rounded text-white" style="overflow: scroll">
                                <ul id="stockList" class="mt-2">
                                    <!-- Stocks will be dynamically added here -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat type selection -->
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
                    <div id="chartTpyeDropDown" class="absolute left-20 rounded hidden z-20">
                        <div class="w-[70%] bg-gray-800 rounded-lg p-3 mt-2 left-30" style="margin-left: 10rem;">
                            <div class="title my-2">Chart types</div>
                            <div class="chart-types">
                                <ul class="flex gap-3">
                                    <li onclick="setChartType('line')" class="area-selector chart-type-selector active block bg-[#293145] items-center">
                                        <svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon line-icon injected-svg" viewBox="0 0 40 35" width="40" height="35" data-src="" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
                                            <path d="M.22 16.28L4.4 11.6a.69.69 0 011.13-.06c1.2 1.12 2.21 2 3.4 3.16.23.23.33.17.52 0L18.2 4.54a.68.68 0 011.07 0c1.81 1.37 3.21 2.31 5 3.59.81.6 1.64 1.18 2.46 1.77.17.12.27.13.43 0L38.19.27c.24-.21.42-.16.55 0l1.08 1.23a.6.6 0 01.16.22.26.26 0 010 .24.24.24 0 01-.06.08c-1 .87-2.06 1.74-3.08 2.62l-9.38 8.12a.77.77 0 01-.88 0c-2.41-1.84-4.75-3.47-7.21-5.24-.28-.2-.41-.19-.63.07-3 3.54-5.92 6.88-9 10.39a.58.58 0 01-.91 0c-.91-.87-3-2.9-3.41-3.31-.21-.21-.33-.24-.55 0C3.9 15.8 3 16.81 2 17.9c-.19.21-.33.21-.52 0-.48-.37-.82-.7-1.27-1.12-.21-.19-.11-.36.01-.5zm.15 16.19h39.35a.19.19 0 01.2.2v2c0 .18-.08.25-.29.25H.45c-.29 0-.37-.07-.36-.37v-1.77a.27.27 0 01.28-.31z"></path>
                                            <path d="M.24 30.07A.14.14 0 01.09 30v-7c0-.29 0-.28.35-.64C2 21 3.43 19.16 5 17.73c.19-.17.29-.15.49 0A46.59 46.59 0 009 21.05a.62.62 0 00.8 0c2.73-2.66 5.59-6 9.18-10.27.2-.19.32-.2.57 0 2.4 1.76 4.67 3.39 7.18 5a.43.43 0 00.57 0c2.06-1.64 1.94-1.5 3.79-3.07 3.69-3.25 6-5.17 8.41-7.32.35-.21.44 0 .44.11v24.39c0 .09-.09.17-.22.17H.24z"></path>
                                        </svg>
                                        <!-- <br> <p class="py-2">Line</p> -->
                                    </li>

                                    <li onclick="setChartType('candlestick')" class="bars-selector chart-type-selector block bg-[#293145] items-center flex flex-col">
                                        <svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon candles-icon injected-svg" viewBox="0 0 500.1 435.1" width="55" height="35" data-src="/themes/cabinet/svg/icons/chart-types/candles.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
                                            <path d="M2.8 405H499c.6 0 1.1.5 1.1 1.1v26.4c0 2.2-.2 2.4-2.5 2.5h-2c-163.7 0-327.3 0-491 .1-3.7 0-4.7-.8-4.6-4.6.3-7.6.1-15.2.1-22.8 0-1.5 1.2-2.7 2.7-2.7zM425.1 1.8c0 11.9.1 23.8-.1 35.7 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 79.7-.1 159.3 0 239 0 2.8-.7 3.7-3.6 3.6-7.5-.2-15 .1-22.5-.1-3.3-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 3.3-.8 4-4.2 4.1-7.2.1-14.5 0-21.9 0-3.3 0-4.1-.8-4.1-4.1.2-17.3 0-34.7.2-52 0-3.3-.8-4.2-4.1-4.1-7.5.2-15-.1-22.5.1-2.9.1-3.4-.7-3.4-3.6 0-5.1-.1-8.3-.1-12.5 0-75 0-150-.1-225 0-4.1.9-5.4 5.1-5.1 7.1.4 14.3 0 21.5.2 2 .1 3.6-1.5 3.6-3.5-.1-11.9-.1-23.8-.1-35.7 0-1 .8-1.9 1.9-1.9h26.3c1-.1 1.8.7 1.8 1.7zM199.1 164c0-18.3.1-36.7-.1-55 0-3.2.7-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.7-.7 3.6-3.6-.2-7.7.1-15.3-.1-23-.1-3.1.8-3.6 4.4-3.6 7.9 0 14.8.1 22.1 0 2.9 0 3.7.4 3.7 3-.1 7.8.1 15.7-.1 23.5-.1 2.8.7 3.7 3.6 3.6 7.7-.2 15.3.1 23-.1 2 0 3.6 1.5 3.6 3.5-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.5 3.6-7.5-.2-15 .1-22.5-.1-3.2-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 4-1.1 3.8-6.7 3.9-5.6.1-10.9 0-16.5 0s-7 .3-6.9-3.8c.2-17.3 0-34.7.2-52 0-3.2-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1-.2-18.3-.3-36.7-.3-55zM44.1 228c0-18.3.1-36.7-.1-55 0-3.2.8-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.6-.7 3.6-3.6-.2-9.7 0-19.3-.1-29-.1-3.2 1-3.5 4.9-3.5h20.7c3.6 0 4.6.4 4.5 3.5-.2 9.7 0 19.3-.1 29 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.6 3.6-7.7-.2-15.3.1-23-.1-2-.1-3.6 1.6-3.6 3.5.1 15.5 0 31 .1 46.5 0 3.5-.9 4-5 4-6.7.1-13.3 0-20.1-.1-4 0-5-.2-5-3.5.1-15.5 0-31 .1-46.5 0-3.3-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1.2-18.2.1-36.6.1-54.9z"></path>
                                        </svg>
                                        <!-- <br> <p class="py-2">Candles</p> -->
                                    </li>

                                    <li onclick="setChartType('bar')" class="candles-selector chart-type-selector block bg-[#293145] items-center flex flex-col">
                                        <svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon bars-icon injected-svg" viewBox="0 0 500.1 435.1" width="40" height="35" data-src="/themes/cabinet/svg/icons/chart-types/bars.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
                                            <path d="M2.4 405h494.8c1.6 0 2.8 1.3 2.8 2.8 0 8.1-.1 16 .1 24.3 0 2.2-1.3 2.9-3.5 2.9-163.7 0-328.4 0-492.1.1-3.7 0-4.4-.6-4.4-4.6-.1-7.7-.1-15.4-.1-23.1 0-1.3 1.1-2.4 2.4-2.4zM423.9 0c1.7 0 3.1 1.4 3.1 3.1 0 103.8 0 207.6-.1 311.4 0 4.5 1 5.9 5.6 5.7 8.6-.4 17.3 0 26-.2 3.2-.1 3.5.9 3.5 5 .1 7.8-.1 14.2 0 20.8 0 3.4-.2 4.3-3 4.3-19.7-.1-39.3-.2-59 0-3.7 0-3-2-3-4.1v-80.5c0-26.7-.1-53.3.1-80 0-3.7-.8-4.8-4.6-4.6-9.3.3-18.7 0-28 .2-3.2.1-3.5-.9-3.5-4.8.1-6.9-.1-13.1 0-20.8 0-3.6.4-4.6 3.5-4.5 9.3.2 18.7-.1 28 .2 3.8.1 4.6-.9 4.6-4.6C397 98.7 397 50.8 397 3c0-1.7 1.3-3 3-3h23.9zM104 217.5c0 24 .1 48-.1 72 0 3.7.8 4.8 4.6 4.6 9.3-.3 18.7 0 28-.2 2.8-.1 3.6.7 3.6 3.6v23c0 2.8-.7 3.6-3.6 3.6-9.7-.2-19.3 0-29-.1-3 0-3.6.2-3.6 3.6v11.6c0 2.2-1 3-3.1 3H78.5c-3.5 0-4.5-1.2-4.5-3.7v-12.4c-.1-36.2-.1-72.3 0-108.5 0-3.7-.8-4.7-4.6-4.6-9.3.3-18.7 0-28 .2-2.8.1-3.6-.7-3.6-3.6v-23c0-2.8.7-3.6 3.6-3.6 9.7.2 19.3 0 29 .1 2.8.1 3.6-.7 3.6-3.6-.2-11.7 0-23.3-.1-35 0-2.8.7-3.6 3.6-3.6H100c3.3 0 4 .8 4 4.1-.2 24.1 0 48.3 0 72.5zM244 187.3c0 21.5-.1 43 .1 64.5 0 3.5-.8 4.5-4.4 4.3-7-.3-14-.3-21 0-4 .2-4.8-1-4.8-4.9.1-58.5.1-117 .1-175.5 0-6.8.1-13.7-.1-20.5-.1-2.6.7-3.4 3.3-3.3 7.8.2 15.7.2 23.5 0 2.6-.1 3.4.7 3.3 3.3-.2 9.8 0 19.7-.1 29.5 0 2.6.7 3.4 3.3 3.3 9.8-.2 19.7 0 29.5-.1 2.6 0 3.4.7 3.3 3.3v23.5c0 2.6-.7 3.3-3.3 3.3-9.7-.1-19.3.1-29-.1-3.1-.1-3.8.8-3.8 3.9.2 21.8.1 43.6.1 65.5z"></path>
                                        </svg>
                                        <!-- <br> <p class="py-2">Bars</p> -->
                                    </li>

                                    <li onclick="setChartType('heikinAshi')" class="heikin-selector chart-type-selector block bg-[#293145] items-center flex flex-col">
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

                <!-- Tool Icons -->

                <button class="p-2 hover:bg-[#2a3142] rounded" onclick="alert('Clicked 2')">
                    <i class="items__fa fa fa-sliders text-white"></i>
                </button>
                <button class="p-2 hover:bg-[#2a3142] rounded" onclick="alert('Clicked 3')">
                    <i class="items__fa fa fa-paint-brush text-white"></i>
                </button>
                <button class="p-2 hover:bg-[#2a3142] rounded" onclick="alert('Clicked 4')">
                    <i class="items__fa fa fa-ellipsis-h text-white"></i>
                </button>
                <button class="p-2 hover:bg-[#2a3142] rounded" onclick="alert('Clicked 5')">
                    <svg class="h-5 w-5 text-white" xmlns="//www.w3.org/2000/svg" height="35" fill="white" viewBox="0 0 33 33">
                        <path d="M15.199 3.287v8.626a3.287 3.287 0 01-3.286 3.286H3.286A3.287 3.287 0 010 11.913V3.287A3.288 3.288 0 013.286 0h8.627a3.286 3.286 0 013.286 3.287zM29.714 0h-8.627a3.288 3.288 0 00-3.286 3.287v8.626a3.287 3.287 0 003.286 3.286h8.627A3.287 3.287 0 0033 11.913V3.287A3.288 3.288 0 0029.714 0zM11.913 17.801H3.286A3.286 3.286 0 000 21.087v8.627A3.288 3.288 0 003.286 33h8.627a3.288 3.288 0 003.286-3.286v-8.627a3.284 3.284 0 00-3.286-3.286zm17.801 0h-8.627a3.287 3.287 0 00-3.286 3.286v8.627A3.288 3.288 0 0021.087 33h8.627A3.288 3.288 0 0033 29.714v-8.627a3.286 3.286 0 00-3.286-3.286z"></path>
                    </svg>
                </button>
            </div>

            <!-- Time and Settings Bar -->
            <div class="flex items-center justify-between px-4 py-2">
                <div class="flex items-center space-x-2">
                    <span id="current-time">16:59:04 UTC+1</span>

                    <script>
                        function updateCurrentTime() {
                            const currentTime = new Date();
                            const hours = (currentTime.getUTCHours() + 1).toString().padStart(2, '0');
                            const minutes = currentTime.getUTCMinutes().toString().padStart(2, '0');
                            const seconds = currentTime.getUTCSeconds().toString().padStart(2, '0');

                            document.getElementById('current-time').innerText = `${hours}:${minutes}:${seconds} UTC+1`;
                        }

                        updateCurrentTime();
                        setInterval(updateCurrentTime, 1000);
                    </script>
                    <button class="hover:bg-[#2a3142] rounded p-1">
                        <i class="current-time__action-icon fa fa-cog"></i>
                    </button>
                </div>
            </div>

            <!-- Bottom Time Scale -->
            <div class="absolute bottom-4 left-4">
                <div class="bg-[#2a3142] px-3 py-1 rounded flex items-center space-x-2">
                    <span>M5</span>
                    <svg xmlns="//www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <!-- Main Chart Area -->
            <div id="chartContainer" class="w-[100%] -mt-[2rem] h-[calc(100% + 1rem)]" style="margin-top: -3rem;">
                <style>
                    #loader {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        font-size: 18px;
                        color: #3b82f6;
                        display: none;
                    }
                </style>

                <div id="loader">
                    <!-- @include('components.preloader') -->
                </div>
                @if($isOutOfTradingHours == true)
                <div class="max-w-md mx-auto mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md shadow-md">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m0-4h.01M12 6v6m0 6h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                        <div>
                        <p class="font-semibold mb-1">Market Unavailable</p>
                        <p class="text-sm leading-relaxed">
                            Trading for the selected asset is currently unavailable as the market is closed. 
                            Please select a different asset to continue trading.
                        </p>
                        </div>
                    </div>
                </div>
                @else
                <div id="chart-container"></div>
                @endif
            </div>
        </div>
    </section>
    <!-- buy and sell section -->
    <div class="w-max-lg bg-[#23283b] h-screen text-gray-200">
        <form method="POST" action="{{ route('trade.store') }}" class="p-3 rounded-lg text-white space-y-4"
            id="tradeForm">
            @csrf

            <!-- Time Input -->
            <div class="max-w-sm space-y-3">
                <div>
                    <label for="hs-trailing-icon" class="block text-sm font-light mb-2">Time</label>
                    <div class="relative">
                        <input type="text" id="hs-trailing-icon" name="duration"
                            class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"
                            id="timeInput" maxlength="8" placeholder="00:01:00" value="00:01:00" name=duration">
                        <input type="hidden" name="asset" id="assetTicker" value="{{ $__coin }}">
                        <div
                            class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-10 border-l p-3 border-[#293341]">
                            <i class="fa-regular fa-clock bg-[#23283b]"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amount Input -->
            <div class="max-w-sm space-y-3">
                <div>
                    <label for="input_amount_field" class="block text-sm font-light mb-2">Amount</label>
                    <div class="relative">
                        <input type="text" pattern="^\d*\.?\d*$" step="any" id="input_amount_field" name="amount" oninput="calculate_trade_profit()" class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]" autocomplete="off" placeholder="1000">
                        <input type="hidden" name="direction" id="direction" value="">
                        <input type="hidden" name="order_token" id="order_token" value="">
                        <input type="hidden" name="order_time" id="order_time" value="">
                        <div
                            class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-10 border-l p-3 border-[#293341]">
                            <svg class="currency-icon currency-icon--usd" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" xmlns="//www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2">
                                </circle>
                                <path
                                    d="M15 9h-4a1 1 0 1 0 0 2h2a3 3 0 0 1 0 6v1a1 1 0 0 1-2 0v-1H9a1 1 0 0 1 0-2h4a1 1 0 0 0 0-2h-2a3 3 0 0 1 0-6V6a1 1 0 0 1 2 0v1h2a1 1 0 1 1 0 2Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout Display -->
            <div class="text-sm">
                <label>Payout</label>
                <div
                    class="text-green-400 border border-dashed rounded-lg mb-3 border-[#293341] p-3 flex justify-between">
                    <span id="profit_percentage">+{{ $data->asset_profit_margin }}% </span>
                    <span id="payout">$0.0</span>
                </div>

                @if($isOutOfTradingHours == true)
                <div class="max-w-md mx-auto mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md shadow-md">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m0-4h.01M12 6v6m0 6h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                        <div>
                        <p class="font-semibold mb-1">Market Unavailable</p>
                        <p class="text-sm leading-relaxed">
                            Trading for the selected asset is currently unavailable as the market is closed. 
                            Please select a different asset to continue trading.
                        </p>
                        </div>
                    </div>
                </div>
                @else
                <!-- Buy and Sell Buttons -->
                <div class="gap-2 space-y-2">
                    <button type="button" name="action" data-value="up"
                        class="_hover-up cta-button transition duration-300 ease-in-out gap-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 w-full">
                        <i class="fas fa-arrow-up"></i>
                        BUY
                    </button>
                    <button type="button" name="action" data-value="down"
                        class="_hover-down cta-button transition duration-300 ease-in-out gap-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400 w-full">
                        <i class="fas fa-arrow-up"></i>
                        SELL
                    </button>
                </div>
                @endif
            </div>
        </form>
    </div>
</section>


<!-- trades -->
<!-- Main Content Area -->
<div class="min-h-screen bg-[#222636] w-[20%] ml-auto hidden" id="mainContent" style="min-width: 330px;">
    <div class="text-white"></div>
</div>

<aside class="flex flex-col justify-between h-screen w-28 bg-[#1e2131] py-4 overflow-hidden ml-auto">
    <nav class="flex flex-col items-center space-y-8 text-center overflow-hidden">
        <!-- Trades -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightTrades">
            <i class="fa fa-history w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Trades</span>
        </a>

        <!-- Signals -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightSignals">
            <svg class="w-6 h-6 text-slate-400 group-hover:text-slate-200" xmlns="//www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
            </svg>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Signals</span>
        </a>

        <!-- Social Trading -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightSocialTrading">
            <i class="fa fa-users w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Social Trading</span>
        </a>

        <!-- Express Trades -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightExpressTrades">
            <i class="fa fa-bullseye w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Express Trades</span>
        </a>

        <!-- Tournaments -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightTournaments">
            <i class="fa fa-trophy w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Tournaments</span>
        </a>

        <!-- Pending Trades -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightPendingTrades">
            <i class="fa fa-hourglass-end w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Pending Trades</span>
        </a>

        <!-- Hotkeys -->
        <a href="#" class="right-nav-link flex flex-col items-center group" data-section="rightHotkeys">
            <i class="fa fa-keyboard-o w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1">Hotkeys</span>
        </a>
    </nav>

    <!-- Full screen button -->
    <div class="flex flex-col items-center">
        <button id="fullscreen-btn" class="flex flex-col items-center group">
            <svg class="w-6 h-6 text-slate-400 group-hover:text-slate-200" xmlns="//www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
            </svg>
            <span class="text-xs text-slate-400 group-hover:text-slate-200 mt-1 mb-4">Full screen</span>
            <i class="fa fa-long-arrow-right w-6 h-6 text-slate-400 group-hover:text-slate-200"></i>
        </button>
    </div>

</aside>

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
@endsection


@push('js')
<script>
    // calculate percentage expected return profit
    function calculate_trade_profit() {
        const asset_profit = parseFloat("{{ $data->asset_profit_margin }}"); // e.g. 0.9
        const inputEl = document.getElementById("input_amount_field");
        const input_amount = parseFloat(inputEl.value);

        if (!isNaN(asset_profit) && !isNaN(input_amount)) {
            const profit = (asset_profit / 100) * input_amount;
            document.getElementById('payout').textContent = '$' + profit.toFixed(2);
        } else {
            // console.log('Invalid number entered');
        }
    }
    
    window.Echo.channel(`trades.user.${userId}`)
        .listen('TradeUpdated', (event) => {
            console.log('Trade updated:', event);

            updateOrInsertTradeCard(event);
            startCountdowns([event]);
        });

    window.Echo.channel('signals')
        .listen('SignalCreated', (e) => {
            console.log("New Signal:", e);
        });

</script>

@if($isOutOfTradingHours == false)
<script>
    /***********************************************************
     *               GLOBALS & INITIAL SETUP
     ***********************************************************/
    const restApiUrl = "https://iqcent.com/trade-api/history";
    const websocketUrl = "wss://iqcent.com/trade-api-ws/api/ws/price";
    let ws;
    let chart;
    let series;
    let chartType = "line";
    let showArea = true;
    let autoScroll = true;
    let latestRate = null;
    let candleData = [];

    function initChart() {
        const chartContainer = document.getElementById("chart-container");

        chart = LightweightCharts.createChart(chartContainer, {
            layout: {
                backgroundColor: 'transparent',
                textColor: '#ffffff',
            },
            grid: {
                vertLines: { visible: false },
                horzLines: { visible: false },
            },
            timeScale: {
                rightOffset: 30,
                barSpacing: 8,
                fixLeftEdge: false,
            },
        });

        chart.applyOptions({
            localization: {
                priceFormatter: (price) => {
                    return price.toFixed(5); // Example: Format to 5 decimal places
                }
            }
        });
        
        createSeries(chartType);
        handleResize();
    }

    /***********************************************************
     *               SERIES CREATION / SWITCHING
     ***********************************************************/
    function createSeries(type) {
        if (series) {
            chart.removeSeries(series);
            series = null;
        }

        switch (type) {
            case 'line':
                if (showArea) {
                    series = chart.addAreaSeries({
                        topColor: 'rgba(67, 83, 254, 0.4)',
                        bottomColor: 'rgba(67, 83, 254, 0.0)',
                        lineColor: '#4353fe',
                    });
                } else {
                    series = chart.addLineSeries({ color: '#fff' });
                }
                break;
            case 'bar':
                series = chart.addBarSeries({
                    upColor: '#4bffb5',
                    downColor: '#ff4976',
                    borderVisible: false,
                });
                break;
            case 'candlestick':
            case 'heikinAshi':
                series = chart.addCandlestickSeries({
                    upColor: '#4bffb5',
                    downColor: '#ff4976',
                    borderVisible: false,
                    wickVisible: true,
                });
                break;
        }

        updateSeriesData();
    }

    function updateSeriesData() {
        if (!series) return;

        if (chartType === 'heikinAshi') {
            const haData = transformToHeikinAshi(candleData);
            series.setData(haData);
        } else if (chartType === 'line') {
            const lineData = candleData.map(c => ({
                time: c.time,
                value: c.close,
            }));
            series.setData(lineData);
        } else {
            series.setData(candleData);
        }
    }

    function transformToHeikinAshi(data) {
        if (!data || data.length === 0) return [];
        let haData = [];
        let prevHaOpen = data[0].open;
        let prevHaClose = data[0].close;

        for (let i = 0; i < data.length; i++) {
            const d = data[i];
            const haClose = (d.open + d.high + d.low + d.close) / 4;
            const haOpen = (prevHaOpen + prevHaClose) / 2;
            const haHigh = Math.max(d.high, haOpen, haClose);
            const haLow = Math.min(d.low, haOpen, haClose);

            haData.push({
                time: d.time,
                open: haOpen,
                high: haHigh,
                low: haLow,
                close: haClose,
            });

            prevHaOpen = haOpen;
            prevHaClose = haClose;
        }

        return haData;
    }

    /***********************************************************
     *            FETCH HISTORICAL DATA (REST API)
     ***********************************************************/
    async function fetchHistoricalData() {
        try {
            const from = Math.floor((Date.now() - 3600 * 1000 * 24) / 1000); // 24 hours ago
            const to = Math.floor(Date.now() / 1000);
            const symbol = encodeURIComponent("{{ $chart_coin }}_Strike");
            const resolution = 1;

            const url = `${restApiUrl}?from=${from}&to=${to}&symbol=${symbol}&firstDataRequest=true&resolution=${resolution}`;

            const response = await fetch(url);
            const data = await response.json();

            if (data && data.result && Array.isArray(data.result)) {
                candleData = data.result.map(c => ({
                    time: Math.floor(c.time / 1000), // Convert from milliseconds to seconds
                    open: c.open,
                    high: c.high,
                    low: c.low,
                    close: c.close,
                }));
                // console.log(candleData);
                updateSeriesData();
                scrollIfEnabled();
            } else {
                console.error("Unexpected historical data format", data);
            }
        } catch (error) {
            console.error("Failed to fetch historical data", error);
        }
    }

    /***********************************************************
     *            LIVE DATA VIA WEBSOCKET
     ***********************************************************/
    function connectWebSocket() {
        ws = new WebSocket(websocketUrl);

        ws.onopen = () => {
            // console.log("WebSocket connected");
            const subscribeMessage = {
                id: "{{ $chart_coin }}", // Match the REST API symbol
                param: "Option",
                operation: "SUBSCRIBE.TICK"
            };
            ws.send(JSON.stringify(subscribeMessage));
        };

        ws.onmessage = (event) => {
            try {
                const message = JSON.parse(event.data);
                // console.log("WebSocket message received:", message);

                if (message && typeof message.p === 'number' && typeof message.t === 'number') {
                    const now = Math.floor(message.t / 1000); // Convert ms to seconds
                    const price = parseFloat(message.p.toFixed(6)); // Precision 6 decimals
                    latestRate = price;
                    const orderToken = btoa(latestRate.toString());
                    document.getElementById('order_token').value = orderToken;
                    document.getElementById('order_time').value = message.t;
                    // For candleData (for bars/candles/heikin ashi):
                    const lastCandle = candleData[candleData.length - 1];
                    if (!lastCandle || now >= lastCandle.time + 60) {
                        candleData.push({
                            time: now,
                            open: price,
                            high: price,
                            low: price,
                            close: price,
                        });
                    } else {
                        lastCandle.close = price;
                        lastCandle.high = Math.max(lastCandle.high, price);
                        lastCandle.low = Math.min(lastCandle.low, price);
                    }

                    // For real-time update:
                    if (chartType === 'line') {
                        series.update({ time: now, value: price });
                    } else if (chartType === 'candlestick' || chartType === 'bar' || chartType === 'heikinAshi') {
                        updateSeriesData(); // Full update for candle types
                    }

                    scrollIfEnabled();
                } else {
                    console.error("Unexpected websocket data:", message);
                }
            } catch (error) {
                console.error("Failed to process WebSocket message:", error);
            }
        };

        ws.onerror = (err) => {
            console.error("WebSocket error", err);
        };

        ws.onclose = () => {
            console.warn("WebSocket closed. Reconnecting...");
            setTimeout(connectWebSocket, 3000);
        };
    }


    /***********************************************************
     *                AUTO-SCROLL CONTROL
     ***********************************************************/
    function scrollIfEnabled() {
        if (autoScroll) {
            chart.timeScale().scrollToRealTime();
        }
    }

    /***********************************************************
     *              UI EVENT HANDLERS
     ***********************************************************/
    function setChartType(type) {
        chartType = type;
        createSeries(chartType);
    }

    function toggleArea() {
        showArea = !showArea;
        if (chartType === 'line') {
            createSeries('line');
        }
    }

    function toggleAutoScroll() {
        autoScroll = !autoScroll;
        if (autoScroll) {
            scrollIfEnabled();
        }
    }

    /***********************************************************
     *                CHART RESIZING
     ***********************************************************/
    function handleResize() {
        const chartContainer = document.getElementById("chart-container");
        function resizeChart() {
            chart.applyOptions({
                width: chartContainer.clientWidth,
                height: chartContainer.clientHeight,
            });
        }
        window.addEventListener("resize", resizeChart);
        new ResizeObserver(resizeChart).observe(chartContainer);
        resizeChart();
    }

    /***********************************************************
     *                INIT EVERYTHING
     ***********************************************************/
    initChart();
    fetchHistoricalData();
    connectWebSocket();
</script>
@endif

<script>
  /***********************************************************
   *                STOCK Loading and s
   ***********************************************************/
    var assets = {!! get_assets() !!};

	// Convert assets to the required format
	const stocks = assets.map(asset => {
		return {
			name: asset.name,
			symbol: asset.symbol,
			payout: asset.asset_profit_margin + "%", // Default payout
			category: asset.asset_group // Categorizing based on asset group
		};
	});


	// Load stocks
	function loadStocks(filter = "all") {
		const stockList = document.getElementById("stockList");
		stockList.innerHTML = "";
		stocks.forEach((stock) => {
			if (filter === "all" || stock.category === filter) {
				const li = document.createElement("li");
				li.className =
					"p-2 flex justify-between hover:bg-gray-700 cursor-pointer";
				li.innerHTML = `<span>${stock.name}</span> <span class="text-green-400">${stock.payout}</span>`;
				li.onclick = function () {
                    document.getElementById("selectedAsset").innerText = stock.name;
                    const sanitizedSymbol = stock.symbol.replace(/\//g, '--'); // Replace all '/' with '--'
                    window.location.href = "/dashboard/" + sanitizedSymbol;
                    document
                        .getElementById("assetDropDown")
                        .classList.add("hidden");
                };
				stockList.appendChild(li);
			}
		});
	}

	loadStocks();

	// Filter Stocks
	function filterStocks(category) {
		loadStocks(category);
	}

	// Search Functionality
	document.getElementById("searchBar").addEventListener("keyup", function () {
		const searchValue = this.value.toLowerCase();
		const stockItems = document.querySelectorAll("#stockList li");
		stockItems.forEach((item) => {
			item.style.display = item.textContent
				.toLowerCase()
				.includes(searchValue)
				? "flex"
				: "none";
		});
	});

	// Close Dropdown on Outside Click
	document.addEventListener("click", function (event) {
		if (
			!document.getElementById("assetBtn").contains(event.target) &&
			!document.getElementById("assetDropDown").contains(event.target)
		) {
			document.getElementById("assetDropDown").classList.add("hidden");
		}
	});

	// stock dropdown
	document.getElementById("chartTpyeBtn").addEventListener("click", function () {
		document.getElementById("chartTpyeDropDown").classList.toggle("hidden");
		});
		document.addEventListener("click", function (event) {
		if (
			!document.getElementById("chartTpyeBtn").contains(event.target) &&
			!document.getElementById("chartTpyeDropDown").contains(event.target)
		) {
			document.getElementById("chartTpyeDropDown").classList.add("hidden");
		}
	});
</script>
@endpush


@push('css')
<!-- Styles -->
<style>
        #chart-container {
            margin-top: -1rem;
            width: 100%;
            height: 90vh; /* Adjust height as needed */
            background-color: transparent;
        }
        #controls {
            padding: 10px;
            background: #f8f8f8;
        }
        button {
            margin: 5px;
            padding: 8px;
            cursor: pointer;
        }
    .active-tab {
        border-bottom: 2px solid #3b82f6;
        /* blue-500 */
    }

    .tv-lightweight-charts {
        width: 100% !important;
        height: auto;
    }

    table {
        width: 100% !important;
    }

    body {
        overflow: hidden !important;
    }

    .container {
        background-color: #222436;
        padding: 15px;
        border-radius: 8px;
        width: 300px;
    }

    .title {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .chart-types {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .chart-types li {
        background: #2c2e48;
        border: none;
        padding: 10px;
        border-radius: 5px;
        color: white;
        flex: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .chart-types li.active {
        background: #4a4d71;
    }

    .settings {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .setting {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .toggle {
        width: 34px;
        height: 18px;
        background: #555;
        border-radius: 9px;
        position: relative;
        cursor: pointer;
    }

    .toggle::before {
        content: '';
        width: 14px;
        height: 14px;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: 0.3s;
    }

    .toggle.active {
        background: #4caf50;
    }

    .toggle.active::before {
        left: 18px;
    }



    /* // scss to css */
    .right-widget-container .signals-list,
    .right-sidebar-modal .signals-list {
        --second-column-width: 130px;
        --gap: 2px;
        display: block;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
    }

    .right-widget-container .signals-list .copy-signal-item,
    .right-sidebar-modal .signals-list .copy-signal-item {
        display: flex;
        flex: 1;
        flex-direction: column;
        margin-left: calc(-1 * var(--gap));
        padding: 8px 10px;
        font-size: 13px;
    }

    .right-widget-container .signals-list .copy-signal-item>*,
    .right-sidebar-modal .signals-list .copy-signal-item>* {
        margin-left: var(--gap);
    }

    .right-widget-container .signals-list .copy-signal-item__row,
    .right-sidebar-modal .signals-list .copy-signal-item__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-left: -10px;
    }

    .right-widget-container .signals-list .copy-signal-item__row>*,
    .right-sidebar-modal .signals-list .copy-signal-item__row>* {
        margin-left: 10px;
    }

    .right-widget-container .signals-list .copy-signal-item .progress-info,
    .right-sidebar-modal .signals-list .copy-signal-item .progress-info {
        position: relative;
        display: flex;
        align-items: center;
        width: 128px;
    }

    .right-widget-container .signals-list .copy-signal-item .progress-info .progress,
    .right-sidebar-modal .signals-list .copy-signal-item .progress-info .progress {
        position: absolute;
        top: 0;
        bottom: 0;
        overflow: hidden;
        margin: auto;
        width: 100%;
    }

    .right-widget-container .signals-list .copy-signal-item .icons-column,
    .right-sidebar-modal .signals-list .copy-signal-item .icons-column {
        width: 20px;
        white-space: nowrap;
        text-align: center;
    }

    .right-widget-container .signals-list .copy-signal-item .icons-column--three,
    .right-sidebar-modal .signals-list .copy-signal-item .icons-column--three {
        width: 30px;
    }

    .right-widget-container .signals-list .copy-signal-item .icons-column--four,
    .right-sidebar-modal .signals-list .copy-signal-item .icons-column--four {
        width: 36px;
    }

    .right-widget-container .signals-list .copy-signal-item__symbol,
    .right-sidebar-modal .signals-list .copy-signal-item__symbol {
        flex: 1;
        white-space: nowrap;
    }

    .right-widget-container .signals-list .copy-signal-item__symbol .price,
    .right-sidebar-modal .signals-list .copy-signal-item__symbol .price {
        white-space: nowrap;
    }

    .right-widget-container .signals-list .copy-signal-item__symbol.pointer,
    .right-sidebar-modal .signals-list .copy-signal-item__symbol.pointer {
        cursor: pointer;
    }

    .right-widget-container .signals-list .copy-signal-item__price,
    .right-sidebar-modal .signals-list .copy-signal-item__price {
        flex: 1;
    }

    .right-widget-container .signals-list .copy-signal-item__progress,
    .right-sidebar-modal .signals-list .copy-signal-item__progress {
        display: flex;
        width: var(--second-column-width);
    }

    .right-widget-container .signals-list .copy-signal-item__progress>*,
    .right-sidebar-modal .signals-list .copy-signal-item__progress>* {
        margin-left: 10px;
    }

    .right-widget-container .signals-list .copy-signal-item__progress>*:first-child,
    .right-sidebar-modal .signals-list .copy-signal-item__progress>*:first-child {
        margin-left: 0;
    }

    .right-widget-container .signals-list .copy-signal-item__progress .trade-opened,
    .right-sidebar-modal .signals-list .copy-signal-item__progress .trade-opened {
        margin-top: -2px;
        width: 100%;
        font-size: 12px;
        text-align: center;
    }

    .right-widget-container .signals-list .copy-signal-item__action,
    .right-sidebar-modal .signals-list .copy-signal-item__action {
        width: var(--second-column-width);
    }

    .right-widget-container .signals-list .copy-signal-item__action a,
    .right-sidebar-modal .signals-list .copy-signal-item__action a {
        padding: 1px 6px 2px;
        width: 100%;
        font-size: 12px;
        color: #fff;
    }

    .theme-dark-blue .right-widget-container .signals-list .copy-signal-item__action a,
    .theme-dark-blue .right-sidebar-modal .signals-list .copy-signal-item__action a {
        border-color: #025b44;
        background-color: #025b44;
    }

    .theme-light .right-widget-container .signals-list .copy-signal-item__action a,
    .theme-light .right-sidebar-modal .signals-list .copy-signal-item__action a {
        background-color: #5cb85c;
    }

    .theme-light .right-widget-container .signals-list .copy-signal-item__action a:hover,
    .theme-light .right-sidebar-modal .signals-list .copy-signal-item__action a:hover {
        background-color: #449d44;
    }

    .right-widget-container .signals-list .copy-signal-item__stats,
    .right-sidebar-modal .signals-list .copy-signal-item__stats {
        justify-content: space-between;
        font-size: 11px;
    }

    .right-widget-container .signals-list .copy-signal-item .tooltip-content,
    .right-sidebar-modal .signals-list .copy-signal-item .tooltip-content {
        left: -70px;
        padding: 3px 6px;
    }

    .right-widget-container .signals-list .copy-signal-item .tooltip-content .tooltip-text,
    .right-sidebar-modal .signals-list .copy-signal-item .tooltip-content .tooltip-text {
        font-size: 12px;
    }

    .right-widget-container .signals-list .signal-label,
    .right-sidebar-modal .signals-list .signal-label {
        min-height: 32px;
        font-size: 11px;
    }

    .right-widget-container .signals-list .signal-item,
    .right-sidebar-modal .signals-list .signal-item {
        display: flex;
        justify-content: space-between;
    }

    .right-widget-container .signals-list .signal-item>span,
    .right-sidebar-modal .signals-list .signal-item>span {
        padding: 7px;
    }

    .right-widget-container .signals-list .signal-item>span.price,
    .right-sidebar-modal .signals-list .signal-item>span.price {
        margin-right: 10px;
    }

    .right-widget-container .signals-list .updates-wrapper,
    .right-widget-container .signals-list .all-wrapper,
    .right-sidebar-modal .signals-list .updates-wrapper,
    .right-sidebar-modal .signals-list .all-wrapper {
        display: block;
    }
</style>
@endpush
