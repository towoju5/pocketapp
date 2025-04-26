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
                            <span class="text-white font-medium" id="selectedAsset">American Express OTC</span>
                            <svg xmlns="//www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Asset Dropdown Content -->
                    <div id="assetDropDown" class="absolute left-5 min-w-160 rounded-lg bg-gray-800 shadow-lg rounded-xl mt-2 z-10 hidden">
                        <div class="flex">
                            <!-- Categories -->
                            <div class="w-1/3 bg-gray-700 p-2">
                                <ul>
                                    <li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('all')">All</li>
                                    <li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('tech')">Tech</li>
                                    <li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('finance')">Finance</li>
                                    <li class="p-2 hover:bg-gray-600 cursor-pointer" onclick="filterStocks('retail')">Retail</li>
                                </ul>
                            </div>

                            <!-- Stock List -->
                            <div class="w-2/3 p-2">
                                <input type="text" id="searchBar" placeholder="Search..." class="w-full px-2 py-1 bg-gray-700 rounded text-white">
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
            <div id="chartContainer" class="w-[100%] -mt-[1rem] h-[calc(100% + 1rem)]">
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
                    @include('components.preloader')
                </div>
                <div id="chart-container"></div>
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
                        <input type="number" step="any" id="input_amount_field" name="amount" oninput="calculate_trade_profit()"
                            class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"
                            placeholder="1" value="1" name="amount">
                        <input type="hidden" name="direction" id="direction" value="">
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
            </div>
        </form>
    </div>
</section>

<!-- trades -->
<!-- Main Content Area -->
<div class="min-h-screen bg-[#222636] w-[20%] ml-auto" id="mainContent" style="min-width: 330px;">
    <div class="text-white">Select a section from the sidebar.</div>
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
        <div class="flex justify-between items-center pl-4 py-2 bg-[#2a3144]">
            <h1 class="text-gray-200 text-md text-center w-[80%]" id="contentTitle">Trades</h1>
            <div class="w-[20%]">
                <button onclick="window.location.href='{{ route('trade.index') }}'" class="p-2 rounded-full bg-[#8ea5c0] text-[#2a3144] text-center">
                    <svg class="w-3 h-3 text-[#2a3144]" xmlns="//www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-[#2a3142] w-full">
            <button id="openTab" class="trade-open-close relative py-2 text-gray-200 bg-[#1e2131] font-thin text-sm w-6/12">
                Opened
                <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
            </button>
            <button id="closedTab" class="trade-open-close py-2 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
                Closed
                <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
            </button>
        </div>

        <!-- Trade Containers -->
        <div id="openTrades" class="flex justify-center items-center mt-0 trade_list-page">
            <div id="openTradeList">
                @foreach($active_trades as $trade)
                    <div class="signal-card">
                        <div class="signal-header">
                            <div class="signal-title gap-3">
                                <i class="fas fa-star-o text[#ff9706]"></i> <span class="text-[#8ea5c0]"> {{ $trade->trade_currency }}</span> <span class="signal-percentage">+85%</span>
                            </div>
                            <div class="signal-time">03:59:40</div>
                        </div>
                        <div class="signal-body">
                            <div class="signal-price text-[#8ea5c0]">
                                @if($trade->trade_direction == 'up')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                                ${{ formatPrice($trade->trade_amount) }}
                            </div>
                            <div class="signal-profit total_amount base_plus_profit">${{ formatPrice($trade->trade_amount) }}</div>
                            <div class="signal-profit">+$1,705.10</div>
                        </div>
                        <div class="signal-button">
                            <i class="fas fa-angle-double-up"></i> Double Up
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="closedTrades" class="flex justify-center items-center mt-16 hidden trade_list-page">
            <p class="text-gray-500">Closed Trade Container</p>
        </div>
    </div>

    <div id="rightSignals">
        <div class="p-1 text-white">
        <div class="h-full overflow-y-auto pb-20">
            <h1 class="text-lg font-medium mb-4 px-4">Signals</h1>

            <!-- Filter Buttons -->
            <div class="flex items-center mb-6 px-4">
                <div class="flex-1 flex space-x-3">
                <button onclick="switchTab('updates')" class="tab-btn px-6 py-2 bg-blue-600 rounded-lg text-sm font-medium transition-colors duration-200">
                    Updates
                </button>
                <button onclick="switchTab('all-updates')" class="tab-btn px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors duration-200">
                    All
                </button>
                </div>
                <div class="flex space-x-2">
                <button onclick="switchTab('settings-icon')" class="tab-btn p-2.5 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
                <button onclick="switchTab('telegram-bot')" class="tab-btn p-2.5 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
                </div>
            </div>

            <!-- Signal Items -->
            <div id="updates" class="content mt-4">
                <div class="flex items-center justify-between px-3 py-1">
                <div>
                    <div class="text-sm font-bold">EUR/JPY</div>
                    <div class="text-xs">₦1,500</div>
                    <div class="text-xs text-gray-400">Copied: 36 times</div>
                </div>
                <div class="">
                    <div class="text-xs text-gray-400 text-right">04:37</div>
                    <button class="bg-green-600 text-white text-xs px-4 py-1 rounded">
                    Copy signal
                    </button>
                    <div class="text-green-500 text-right text-xs">1 min ago</div>
                </div>
                </div>
                <div class="flex items-center justify-between px-3 py-1" style="background: #292d41">
                <div>
                    <div class="text-sm font-bold">EUR/JPY</div>
                    <div class="text-xs">₦1,500</div>
                    <div class="text-xs text-gray-400">Copied: 36 times</div>
                </div>
                <div class="">
                    <div class="text-xs text-gray-400 text-right">04:37</div>
                    <button class="bg-green-600 text-white text-xs px-4 py-1 rounded">
                    Copy signal
                    </button>
                    <div class="text-green-500 text-right text-xs">1 min ago</div>
                </div>
                </div>
                <div class="flex items-center justify-between px-3 py-1">
                <div>
                    <div class="text-sm font-bold">EUR/JPY</div>
                    <div class="text-xs">₦1,500</div>
                    <div class="text-xs text-gray-400">Copied: 36 times</div>
                </div>
                <div class="">
                    <div class="text-xs text-gray-400 text-right">04:37</div>
                    <button class="bg-green-600 text-white text-xs px-4 py-1 rounded">
                    Copy signal
                    </button>
                    <div class="text-green-500 text-right text-xs">1 min ago</div>
                </div>
                </div>
                <div class="flex items-center justify-between px-3 py-1" style="background: #292d41">
                <div>
                    <div class="text-sm font-bold">EUR/JPY</div>
                    <div class="text-xs">₦1,500</div>
                    <div class="text-xs text-gray-400">Copied: 36 times</div>
                </div>
                <div class="">
                    <div class="text-xs text-gray-400 text-right">04:37</div>
                    <button class="bg-green-600 text-white text-xs px-4 py-1 rounded">
                    Copy signal
                    </button>
                    <div class="text-green-500 text-right text-xs">1 min ago</div>
                </div>
                </div>
                <div class="flex items-center justify-between px-3 py-1">
                <div>
                    <div class="text-sm font-bold">EUR/JPY</div>
                    <div class="text-xs">₦1,500</div>
                    <div class="text-xs text-gray-400">Copied: 36 times</div>
                </div>
                <div class="">
                    <div class="text-xs text-gray-400 text-right">04:37</div>
                    <button class="bg-green-600 text-white text-xs px-4 py-1 rounded">
                    Copy signal
                    </button>
                    <div class="text-green-500 text-right text-xs">1 min ago</div>
                </div>
                </div>
                <div class="flex items-center justify-between px-3 py-1" style="background: #292d41">
                <div>
                    <div class="text-sm font-bold">EUR/JPY</div>
                    <div class="text-xs">₦1,500</div>
                    <div class="text-xs text-gray-400">Copied: 36 times</div>
                </div>
                <div class="">
                    <div class="text-xs text-gray-400 text-right">04:37</div>
                    <button class="bg-green-600 text-white text-xs px-4 py-1 rounded">
                    Copy signal
                    </button>
                    <div class="text-green-500 text-right text-xs">1 min ago</div>
                </div>
                </div>
            </div>

            <!-- All Updates -->
            <div id="all-updates" class="content hidden rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-2 gap-2 rounded-lg py-2 px-4" style="background: #1d2130; border: 1px solid #454a56">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                    <path d="M16.2429 5.75708C18.586 8.10023 18.586 11.8992 16.2429 14.2424M7.75758 14.2424C5.41443 11.8992 5.41443 8.10023 7.75758 5.75708M4.92893 17.0708C1.02369 13.1656 1.02369 6.83395 4.92893 2.92871M19.0715 2.92871C22.9768 6.83395 22.9768 13.1656 19.0715 17.0708M12.0002 11.9998C13.1048 11.9998 14.0002 11.1043 14.0002 9.99976C14.0002 8.89519 13.1048 7.99976 12.0002 7.99976C10.8957 7.99976 10.0002 8.89519 10.0002 9.99976C10.0002 11.1043 10.8957 11.9998 12.0002 11.9998ZM12.0002 11.9998V20.9998" stroke="#b5b5b5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
                <select class="w-full text-white text-xs bg-transparent">
                    <option>M1</option>
                    <option>M2</option>
                    <option>M3</option>
                </select>
                </div>
                <h2 class="text-lg font-semibold text-center border-b border-gray-600 pb-2 px-4">
                Currencies
                </h2>
                <div class="mt-4">
                <div class="flex justify-between p-2 text-xs">
                    <span>AED/CNY OTC</span>
                    <span class="text-red-500">↓↓</span>
                </div>
                <div class="flex justify-between p-2 text-xs" style="background: #292d41">
                    <span>AUD/CAD OTC</span>
                    <span class="text-green-400">↑</span>
                </div>
                <div class="flex justify-between p-2 text-xs">
                    <span>AUD/CHF OTC</span>
                    <span class="text-green-400">↑↑</span>
                </div>
                <div class="flex justify-between p-2 text-xs" style="background: #292d41">
                    <span>AUD/JPY OTC</span>
                    <span class="text-red-400">↓</span>
                </div>
                <div class="flex justify-between p-2 text-xs">
                    <span>AED/CNY OTC</span>
                    <span class="text-red-500">↓↓</span>
                </div>
                <div class="flex justify-between p-2 text-xs" style="background: #292d41">
                    <span>AUD/CAD OTC</span>
                    <span class="text-green-400">↑</span>
                </div>
                <div class="flex justify-between p-2 text-xs">
                    <span>AUD/CHF OTC</span>
                    <span class="text-green-400">↑↑</span>
                </div>
                <div class="flex justify-between p-2 text-xs" style="background: #292d41">
                    <span>AUD/JPY OTC</span>
                    <span class="text-red-400">↓</span>
                </div>
                </div>
            </div>
            <div id="settings-icon" class="content hidden w-full p-2">
                <p class="text-xs text-gray-400">
                <strong>Disclaimer:</strong> The signals are based on the indicators
                available on the platform (e.g. SMA, Bollinger Bands) and cannot be
                absolutely accurate. We strongly recommend using signals as an
                additional but not as a self-sufficient instrument of technical
                analysis.
                </p>
                <!-- Toggle Switch -->
                <div class="flex items-center mt-4">
                <label class="relative inline-flex items-center cursor-pointer">
                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
                <span class="ml-3 text-white text-sm">System signals</span>
                </div>
            </div>
            <div id="telegram-bot" class="content hidden w-full p-2">
                <p class="text-xs text-gray-400">
                A signal bot for Telegram messenger provides free signals with the
                ability to perform automated trades. Follow the link below to enable
                the bot.
                </p>
                <a href="https://t.me/YOUR_TELEGRAM_BOT" target="_blank" class="mt-4 inline-block bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg text-center text-xs">
                Telegram Bot
                </a>
            </div>
            </div>
        </div>
    </div>
    <div id="rightSocialTrading">
        <div class="p-3 text-white">
        <div class="h-full flex flex-col">
            <div class="flex-1 overflow-y-auto pb-20">
                <h1 class="text-lg text-sm mb-4 px-4">Social Trading</h1>
                <div class="relative mb-6 px-4">
                    <button id="periodDropdown" data-dropdown="period" class="w-full py-2.5 rounded-lg text-xs flex justify-between items-center px-4" style="background: #1d2130; border: 1px solid #454a56">
                        <span id="selectedPeriod">Top ranked traders for 24h</span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="periodMenu" class="hidden absolute w-full bg-gray-800 rounded-lg mt-2 shadow-lg z-10 py-1">
                        <button class="period-option w-full text-left px-4 py-2 text-sm hover:bg-gray-700" data-period="24h">
                        Top ranked traders for 24h
                        </button>
                        <button class="period-option w-full text-left px-4 py-2 text-sm hover:bg-gray-700" data-period="7d">
                        Top ranked traders for 7 days
                        </button>
                        <button class="period-option w-full text-left px-4 py-2 text-sm hover:bg-gray-700" data-period="30d">
                        Top ranked traders for 30 days
                        </button>
                        <button class="period-option w-full text-left px-4 py-2 text-sm hover:bg-gray-700" data-period="90d">
                        Top ranked traders for 90 days
                        </button>
                        <button class="period-option w-full text-left px-4 py-2 text-sm hover:bg-gray-700" data-period="1y">
                        Top ranked traders for 1 year
                        </button>
                    </div>
                </div>

                <div class="text-xs text-center text-gray-400 mb-3">REAL TRADING</div>

                <div class="flex flex-col gap-3">
                    <div class="px-3 py-1 flex items-center gap-2">
                        <div style="width: 20%" class="flex items-center justify-center h-full">
                        <i class="fa-regular fa-circle-user text-2xl" aria-hidden="true"></i>
                        </div>
                        <div class="w-full gap-8">
                        <div class="flex items-center justify-between text-sm">
                            <p>user98987654</p>
                            <p style="color: #6fc274">+$8,500.00</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                            <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                            <p class="text-xs text-white">130</p>
                            </div>
                            <div>
                            <p class="text-xs text-right" style="color: #8ea5c0">
                                Profitable trades:
                            </p>
                            <p class="text-xs text-right text-white">44%</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between"></div>
                        </div>
                    </div>
                    <div class="px-3 py-1 flex items-center gap-2" style="background: #292d41">
                        <div style="width: 20%" class="flex items-center justify-center h-full">
                        <i class="fa-regular fa-circle-user text-2xl" aria-hidden="true"></i>
                        </div>
                        <div class="w-full">
                        <div class="flex items-center justify-between text-sm">
                            <p>user98987654</p>
                            <p style="color: #6fc274">+$8,500.00</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                            <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                            <p class="text-xs text-white">130</p>
                            </div>
                            <div>
                            <p class="text-xs text-right" style="color: #8ea5c0">
                                Profitable trades:
                            </p>
                            <p class="text-xs text-right text-white">44%</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between"></div>
                        </div>
                    </div>
                    <div class="px-3 py-1 flex items-center gap-2">
                        <div style="width: 20%" class="flex items-center justify-center h-full">
                        <i class="fa-regular fa-circle-user text-2xl" aria-hidden="true"></i>
                        </div>
                        <div class="w-full">
                        <div class="flex items-center justify-between text-sm">
                            <p>user98987654</p>
                            <p style="color: #6fc274">+$8,500.00</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                            <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                            <p class="text-xs text-white">130</p>
                            </div>
                            <div>
                            <p class="text-xs text-right" style="color: #8ea5c0">
                                Profitable trades:
                            </p>
                            <p class="text-xs text-right text-white">44%</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between"></div>
                        </div>
                    </div>
                    <div class="px-3 py-1 flex items-center gap-2" style="background: #292d41">
                        <div style="width: 20%" class="flex items-center justify-center h-full">
                        <i class="fa-regular fa-circle-user text-2xl" aria-hidden="true"></i>
                        </div>
                        <div class="w-full">
                        <div class="flex items-center justify-between text-sm">
                            <p>user98987654</p>
                            <p style="color: #6fc274">+$8,500.00</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                            <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                            <p class="text-xs text-white">130</p>
                            </div>
                            <div>
                            <p class="text-xs text-right" style="color: #8ea5c0">
                                Profitable trades:
                            </p>
                            <p class="text-xs text-right text-white">44%</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between"></div>
                        </div>
                    </div>
                    <div class="px-3 py-1 flex items-center gap-2">
                        <div style="width: 20%" class="flex items-center justify-center h-full">
                        <i class="fa-regular fa-circle-user text-2xl" aria-hidden="true"></i>
                        </div>
                        <div class="w-full">
                        <div class="flex items-center justify-between text-sm">
                            <p>user98987654</p>
                            <p style="color: #6fc274">+$8,500.00</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                            <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                            <p class="text-xs text-white">130</p>
                            </div>
                            <div>
                            <p class="text-xs text-right" style="color: #8ea5c0">
                                Profitable trades:
                            </p>
                            <p class="text-xs text-right text-white">44%</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between"></div>
                        </div>
                    </div>
                    <div class="px-3 py-1 flex items-center gap-2" style="background: #292d41">
                        <div style="width: 20%" class="flex items-center justify-center h-full">
                        <i class="fa-regular fa-circle-user text-2xl" aria-hidden="true"></i>
                        </div>
                        <div class="w-full">
                        <div class="flex items-center justify-between text-sm">
                            <p>user98987654</p>
                            <p style="color: #6fc274">+$8,500.00</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                            <p class="text-xs" style="color: #8ea5c0">Number of trades:</p>
                            <p class="text-xs text-white">130</p>
                            </div>
                            <div>
                            <p class="text-xs text-right" style="color: #8ea5c0">
                                Profitable trades:
                            </p>
                            <p class="text-xs text-right text-white">44%</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between"></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="rightExpressTrades">
        <div class="p-3 text-white">
        <div class="h-full overflow-hidden">
            <!-- Express Trades Section -->
            <div class="mb-4 text-lg font-semibold">Express Trades</div>
            <div class="flex justify-between gap-2 mt-2">
                <button onclick="switchTab('express-new', this)" class="tab-btn w-full rounded-lg text-white text-xs p-2" style="background: #1d2130; border: 1px solid #454a56">
                New
                </button>
                <button onclick="switchTab('express-opened', this)" class="tab-btn w-full rounded-lg text-white text-xs p-2" style="background: #1d2130; border: 1px solid #454a56">
                Opened
                </button>
                <button onclick="switchTab('express-closed', this)" class="tab-btn w-full rounded-lg text-white text-xs p-2" style="background: #1d2130; border: 1px solid #454a56">
                Closed
                </button>
            </div>

            <div id="express-new" class="content">
                <div class="text-white text-xs p-2 rounded-lg mt-3 flex items-center gap-2" style="background: #31262b; border: 1px dashed #a34b19">
                ⚠ Express-orders are available starting from the "Master" profile level.
                </div>
                <button class="w-full py-2 rounded-md text-white mt-3 text-xs" style="background: #172832; border: 1px solid #025b44">
                Acquire the profile level
                </button>

                <div class="mt-4 text-sm text-gray-400">Select assets</div>
                <div class="flex space-x-2 mt-2">
                <button class="flex-1 py-2 rounded-lg text-white text-xs" style="background: #1d2130; border: 1px solid #454a56">
                    💲
                </button>
                <button class="flex-1 py-2 rounded-lg text-white text-xs" style="background: #1d2130; border: 1px solid #454a56">
                    💧
                </button>
                <button class="flex-1 py-2 bg-blue-600 rounded-lg text-white text-xs" style="background: #1d2130; border: 1px solid #454a56">
                    📈
                </button>
                <button class="flex-1 py-2 rounded-lg text-white text-xs" style="background: #1d2130; border: 1px solid #454a56">
                    🔍
                </button>
                </div>

                <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <button class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg">
                    ⬆
                    </button>
                    <div class="w-full text-center">
                    <p class="text-xs">
                        APPLE OTC <span class="text-green-400">+92%</span>
                    </p>
                    <p class="w-full p-0.5 text-center" style="
                        background: #162032;
                        border: 1px solid #44506a;
                        font-size: 10px;
                        ">
                        M1 00:56
                    </p>
                    </div>

                    <button class="flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg">
                    ⬇
                    </button>
                </div>
                </div>
            </div>

            <div id="express-opened" class="content hidden mt-6">
                <div class="p-2 rounded-lg text-white text-xs" style="background: #314463; border: 1px dashed #009af9">
                <p>ℹ️ No opened express trades.</p>
                </div>
                <button class="w-full mt-4 p-2 hover:bg-green-800 text-white text-xs rounded-lg" style="background: #172832; border: 1px solid #025b44">
                Create new express
                </button>
            </div>
            <div id="express-closed" class="content hidden mt-6">
                <div class="p-2 rounded-lg text-white text-xs" style="background: #314463; border: 1px dashed #009af9">
                <p>ℹ️ No closed express trades.</p>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div id="rightTournaments">
        <div class="p-3 text-white">
            <div class="h-full overflow-hidden">
            <!-- Header -->
            <div class="flex w-full h-12 justify-between space-x-4 items-center mb-6">
                <button onclick="switchTab('all-tournaments', this)" class="tab-btn text-white text-xs w-full p-2 rounded-lg">
                All Tournaments
                </button>
                <button onclick="switchTab('tournament-statistics', this)" class="tab-btn text-white text-xs w-full p-2 rounded-lg">
                Statistics
                </button>
            </div>

            <div id="all-tournaments" class="content flex flex-col gap-4">
                <!-- Tournament Card: Hour Play -->
                <div class="">
                <div style="
                    background: radial-gradient(
                        131.28% 421.17% at 100% 0,
                        rgba(8, 124, 199, 0.2) 0,
                        transparent 100%
                        ),
                        #1e2131;
                    " class="rounded-t-lg px-5 pt-5 flex items-center justify-between">
                    <div>
                    <div class="text-xl">Hour play</div>
                    <div class="">
                        <div class="text-xs" style="color: #8ea5c0">Prize fund</div>
                        <div class="">₦150,000</div>
                    </div>
                    <div class="">
                        <div class="text-xs" style="color: #8ea5c0">Participation fee</div>
                        <div class="">₦1,500</div>
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
                    <div>
                    <div class="text-xs" style="color: #8ea5c0">Ends in:</div>
                    <div>00:05:29</div>
                    </div>
                    <button class="px-10 py-2 text-xs" style="background: #172832; border: 1px solid #025b44">
                    Join
                    </button>
                </div>
                </div>
                <!-- Tournament Card: Day Off -->
                <div class="">
                <div style="
                    background: radial-gradient(
                        126.93% 414.63% at 98.83% 2.29%,
                        rgba(136, 51, 203, 0.2) 0,
                        transparent 100%
                        ),
                        #1e2131;
                    " class="rounded-t-lg px-5 pt-5 flex items-center justify-between">
                    <div>
                    <div class="text-xl">Day Off</div>
                    <div class="">
                        <div class="text-xs" style="color: #8ea5c0">Prize fund</div>
                        <div class="">₦370,000</div>
                    </div>
                    <div class="">
                        <div class="text-xs" style="color: #8ea5c0">Participation fee</div>
                        <div class="">Free</div>
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
                    <div>
                    <div class="text-xs" style="color: #8ea5c0">Ends in:</div>
                    <div>00:05:29</div>
                    </div>
                    <button class="px-10 py-2 text-xs" style="background: #172832; border: 1px solid #025b44">
                    Join
                    </button>
                </div>
                </div>

                <!-- Tournament Card: Hour Play -->
            </div>

            <!-- //// -->
            <div id="tournament-statistics" class="content hidden p-2 w-60" style="color: #8ea5c0">
                <h2 class="text-sm text-white mb-2" style="color: #8ea5c0">
                Tournament Stats
                </h2>
                <div class="space-y-2 text-gray-300 text-sm" style="color: #8ea5c0">
                <p class="flex justify-between">
                    <span>Tournaments won:</span> <span>0</span>
                </p>
                <p class="flex justify-between">
                    <span>Total prize money:</span> <span>₦0</span>
                </p>
                <p class="flex justify-between">
                    <span>Largest prize:</span> <span>₦0</span>
                </p>
                </div>
            </div>
            </div>
        </div>
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
            console.log([
                'Profit is: ' + profit.toFixed(2),
                'Input amount is: ' + input_amount,
                'Asset profit margin is: ' + asset_profit
            ]);
        } else {
            console.log('Invalid number entered');
        }
    }
  /***********************************************************
   *               GLOBALS & INITIAL SETUP
   ***********************************************************/
  const websocketUrl = "wss://green.derivws.com/websockets/v3?app_id=16929&l=EN&brand=deriv";
  let ws;
  let chart;
  let series;            // current active series
  let chartType = "candlestick";
  let showArea = false; 
  let autoScroll = true; // toggled by "Enable autoscroll"

  // We'll store the raw candle data in memory
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
        rightOffset: 30,  // Extra offset so the latest candle isn't on the extreme edge
        barSpacing: 8,
        fixLeftEdge: true,
      },
    });

    // Create initial series
    createSeries(chartType);

    // Handle resizing
    handleResize();
  }

  /***********************************************************
   *               SERIES CREATION / SWITCHING
   ***********************************************************/
  function createSeries(type) {
    // If a series already exists, remove it
    if (series) {
      chart.removeSeries(series);
      series = null;
    }

    // Decide which type of series to create
    // For Heikin Ashi, we also use candlestick series,
    // but we transform the data to Heikin Ashi before setting.
    switch (type) {
      case 'line':
        // If "show area" is enabled, use area series instead
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
        series = chart.addCandlestickSeries({
          upColor: '#4bffb5',
          downColor: '#ff4976',
          borderVisible: false,
          wickVisible: true,
        });
        break;

      case 'heikinAshi':
        // We'll use a candlestick series for drawing Heikin Ashi
        series = chart.addCandlestickSeries({
          upColor: '#4bffb5',
          downColor: '#ff4976',
          borderVisible: false,
          wickVisible: true,
        });
        break;
    }

    // Now that we have the new series, set data from candleData (if we already fetched some)
    updateSeriesData();
  }

  // Transform standard OHLC data to Heikin Ashi
  function transformToHeikinAshi(data) {
    if (!data || data.length === 0) return [];

    let haData = [];
    // Initialize previous HaOpen & HaClose with the first real candle
    let prevHaOpen = data[0].open;
    let prevHaClose = data[0].close;

    for (let i = 0; i < data.length; i++) {
      let d = data[i];
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

  // Depending on chartType, set or update the series data
  function updateSeriesData() {
    if (!series) return;

    switch (chartType) {
      case 'line':
        // Convert OHLC to single-value data
        const singleValueData = candleData.map(c => ({
          time: c.time,
          value: c.close,
        }));
        series.setData(singleValueData);
        break;

      case 'bar':
      case 'candlestick':
        // Both bar & candlestick need full OHLC
        series.setData(candleData);
        break;

      case 'heikinAshi':
        // Convert standard OHLC to Heikin Ashi
        const haData = transformToHeikinAshi(candleData);
        series.setData(haData);
        break;
    }
  }

  /***********************************************************
   *            FETCH & SUBSCRIBE TO WEBSOCKET DATA
   ***********************************************************/
  function fetchHistoricalData() {
    ws = new WebSocket(websocketUrl);

    ws.onopen = () => {
      console.log("Connected to WebSocket");
      // Request candle data instead of ticks
      const request = {
        ticks_history: "JD10",
        adjust_start_time: 1,
        count: 1000,
        end: "latest",
        style: "candles",    // <--- request OHLC data
        subscribe: 1,
        granularity: 60      // 1-minute candles (adjust as you like)
      };
      ws.send(JSON.stringify(request));
    };

    ws.onmessage = (event) => {
      const data = JSON.parse(event.data);

      // Historical candles
      if (data.msg_type === "candles" && data.candles) {
        // Convert each candle to the format needed by Lightweight Charts
        candleData = data.candles.map(c => ({
          time: c.epoch,
          open: c.open,
          high: c.high,
          low: c.low,
          close: c.close,
        }));
        updateSeriesData();
        scrollIfEnabled();
      }

      // A new candle or updated candle (for real-time subscription)
      if (data.msg_type === "ohlc") {
        const c = data.ohlc;
        // We either update the last candle or push a new one if a new epoch arrived
        const lastCandle = candleData[candleData.length - 1];
        if (lastCandle && lastCandle.time === c.open_time) {
          // Update existing candle
          lastCandle.open = c.open;
          lastCandle.high = c.high;
          lastCandle.low = c.low;
          lastCandle.close = c.close;
        } else {
          // New candle
          candleData.push({
            time: c.open_time,
            open: c.open,
            high: c.high,
            low: c.low,
            close: c.close,
          });
        }
        updateSeriesData();
        scrollIfEnabled();
      }
    };

    ws.onclose = () => {
      console.log("WebSocket closed. Reconnecting...");
      setTimeout(fetchHistoricalData, 3000);
    };
  }

  // Auto-scroll to the most recent candle if enabled
  function scrollIfEnabled() {
    if (!autoScroll) return;
    chart.timeScale().scrollToRealTime();
  }

  /***********************************************************
   *              UI EVENT HANDLERS (Chart Types / Toggles)
   ***********************************************************/
  function setChartType(type) {
    chartType = type;
    createSeries(chartType);
  }

  // “Show area” toggle for line-based charts
  function toggleArea() {
    showArea = !showArea;
    // Only relevant if we’re on a line-based chart
    if (chartType === 'line') {
      createSeries('line');
    }
  }

  // “Enable autoscroll” toggle
  function toggleAutoScroll() {
    autoScroll = !autoScroll;
    if (autoScroll) {
      scrollIfEnabled();
    }
  }

  /***********************************************************
   *             CHART RESIZING
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


  /***********************************************************
   *                STOCK Loading and s
   ***********************************************************/
    var assets = {!! get_assets() !!};

	// Convert assets to the required format
	const stocks = assets.map(asset => {
		return {
			name: asset.display_symbol + " OTC",
			symbol: asset.symbol,
			payout: "+92%", // Default payout
			category: asset.asset_group === "stocks" ? "tech" : "crypto" // Categorizing based on asset group
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
					// alert(stock.symbol + " Selected");
					window.location.href="/dashboard/"+stock.symbol
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
