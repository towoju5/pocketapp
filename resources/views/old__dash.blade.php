@extends('layouts.app')

@section('title', 'Trading Dashboard')

@section('content')
@php $__coin = $data->symbol ?? "USDCAD" @endphp


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
                    <div id="assetDropDown" class="absolute left-5 min-w-160 rounded-lg bg-gray-800 shadow-lg rounded-xl mt-2 hidden">
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
                    <div id="chartTpyeDropDown" class="absolute left-20 rounded hidden">
                        <div class="w-[70%] bg-gray-800 rounded-lg p-3 mt-2 left-30" style="margin-left: 10rem;">
                            <div class="title my-2">Chart types</div>
                            <div class="chart-types">
                                <ul class="flex gap-3">
                                    <li onclick="changeChartType('area')" class="area-selector chart-type-selector active block bg-[#293145] items-center">
                                        <svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon line-icon injected-svg" viewBox="0 0 40 35" width="40" height="35" data-src="" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
                                            <path d="M.22 16.28L4.4 11.6a.69.69 0 011.13-.06c1.2 1.12 2.21 2 3.4 3.16.23.23.33.17.52 0L18.2 4.54a.68.68 0 011.07 0c1.81 1.37 3.21 2.31 5 3.59.81.6 1.64 1.18 2.46 1.77.17.12.27.13.43 0L38.19.27c.24-.21.42-.16.55 0l1.08 1.23a.6.6 0 01.16.22.26.26 0 010 .24.24.24 0 01-.06.08c-1 .87-2.06 1.74-3.08 2.62l-9.38 8.12a.77.77 0 01-.88 0c-2.41-1.84-4.75-3.47-7.21-5.24-.28-.2-.41-.19-.63.07-3 3.54-5.92 6.88-9 10.39a.58.58 0 01-.91 0c-.91-.87-3-2.9-3.41-3.31-.21-.21-.33-.24-.55 0C3.9 15.8 3 16.81 2 17.9c-.19.21-.33.21-.52 0-.48-.37-.82-.7-1.27-1.12-.21-.19-.11-.36.01-.5zm.15 16.19h39.35a.19.19 0 01.2.2v2c0 .18-.08.25-.29.25H.45c-.29 0-.37-.07-.36-.37v-1.77a.27.27 0 01.28-.31z"></path>
                                            <path d="M.24 30.07A.14.14 0 01.09 30v-7c0-.29 0-.28.35-.64C2 21 3.43 19.16 5 17.73c.19-.17.29-.15.49 0A46.59 46.59 0 009 21.05a.62.62 0 00.8 0c2.73-2.66 5.59-6 9.18-10.27.2-.19.32-.2.57 0 2.4 1.76 4.67 3.39 7.18 5a.43.43 0 00.57 0c2.06-1.64 1.94-1.5 3.79-3.07 3.69-3.25 6-5.17 8.41-7.32.35-.21.44 0 .44.11v24.39c0 .09-.09.17-.22.17H.24z"></path>
                                        </svg>
                                        <!-- <br> <p class="py-2">Line</p> -->
                                    </li>

                                    <li onclick="changeChartType('bars')" class="bars-selector chart-type-selector block bg-[#293145] items-center flex flex-col">
                                        <svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon candles-icon injected-svg" viewBox="0 0 500.1 435.1" width="55" height="35" data-src="/themes/cabinet/svg/icons/chart-types/candles.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
                                            <path d="M2.8 405H499c.6 0 1.1.5 1.1 1.1v26.4c0 2.2-.2 2.4-2.5 2.5h-2c-163.7 0-327.3 0-491 .1-3.7 0-4.7-.8-4.6-4.6.3-7.6.1-15.2.1-22.8 0-1.5 1.2-2.7 2.7-2.7zM425.1 1.8c0 11.9.1 23.8-.1 35.7 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 79.7-.1 159.3 0 239 0 2.8-.7 3.7-3.6 3.6-7.5-.2-15 .1-22.5-.1-3.3-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 3.3-.8 4-4.2 4.1-7.2.1-14.5 0-21.9 0-3.3 0-4.1-.8-4.1-4.1.2-17.3 0-34.7.2-52 0-3.3-.8-4.2-4.1-4.1-7.5.2-15-.1-22.5.1-2.9.1-3.4-.7-3.4-3.6 0-5.1-.1-8.3-.1-12.5 0-75 0-150-.1-225 0-4.1.9-5.4 5.1-5.1 7.1.4 14.3 0 21.5.2 2 .1 3.6-1.5 3.6-3.5-.1-11.9-.1-23.8-.1-35.7 0-1 .8-1.9 1.9-1.9h26.3c1-.1 1.8.7 1.8 1.7zM199.1 164c0-18.3.1-36.7-.1-55 0-3.2.7-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.7-.7 3.6-3.6-.2-7.7.1-15.3-.1-23-.1-3.1.8-3.6 4.4-3.6 7.9 0 14.8.1 22.1 0 2.9 0 3.7.4 3.7 3-.1 7.8.1 15.7-.1 23.5-.1 2.8.7 3.7 3.6 3.6 7.7-.2 15.3.1 23-.1 2 0 3.6 1.5 3.6 3.5-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.5 3.6-7.5-.2-15 .1-22.5-.1-3.2-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 4-1.1 3.8-6.7 3.9-5.6.1-10.9 0-16.5 0s-7 .3-6.9-3.8c.2-17.3 0-34.7.2-52 0-3.2-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1-.2-18.3-.3-36.7-.3-55zM44.1 228c0-18.3.1-36.7-.1-55 0-3.2.8-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.6-.7 3.6-3.6-.2-9.7 0-19.3-.1-29-.1-3.2 1-3.5 4.9-3.5h20.7c3.6 0 4.6.4 4.5 3.5-.2 9.7 0 19.3-.1 29 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.6 3.6-7.7-.2-15.3.1-23-.1-2-.1-3.6 1.6-3.6 3.5.1 15.5 0 31 .1 46.5 0 3.5-.9 4-5 4-6.7.1-13.3 0-20.1-.1-4 0-5-.2-5-3.5.1-15.5 0-31 .1-46.5 0-3.3-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1.2-18.2.1-36.6.1-54.9z"></path>
                                        </svg>
                                        <!-- <br> <p class="py-2">Candles</p> -->
                                    </li>

                                    <li onclick="changeChartType('candles')" class="candles-selector chart-type-selector block bg-[#293145] items-center flex flex-col">
                                        <svg xmlns="//www.w3.org/2000/svg" fill="#8ea5c0" class="svg-icon bars-icon injected-svg" viewBox="0 0 500.1 435.1" width="40" height="35" data-src="/themes/cabinet/svg/icons/chart-types/bars.svg" xmlns:xlink="//www.w3.org/1999/xlink" role="img">
                                            <path d="M2.4 405h494.8c1.6 0 2.8 1.3 2.8 2.8 0 8.1-.1 16 .1 24.3 0 2.2-1.3 2.9-3.5 2.9-163.7 0-328.4 0-492.1.1-3.7 0-4.4-.6-4.4-4.6-.1-7.7-.1-15.4-.1-23.1 0-1.3 1.1-2.4 2.4-2.4zM423.9 0c1.7 0 3.1 1.4 3.1 3.1 0 103.8 0 207.6-.1 311.4 0 4.5 1 5.9 5.6 5.7 8.6-.4 17.3 0 26-.2 3.2-.1 3.5.9 3.5 5 .1 7.8-.1 14.2 0 20.8 0 3.4-.2 4.3-3 4.3-19.7-.1-39.3-.2-59 0-3.7 0-3-2-3-4.1v-80.5c0-26.7-.1-53.3.1-80 0-3.7-.8-4.8-4.6-4.6-9.3.3-18.7 0-28 .2-3.2.1-3.5-.9-3.5-4.8.1-6.9-.1-13.1 0-20.8 0-3.6.4-4.6 3.5-4.5 9.3.2 18.7-.1 28 .2 3.8.1 4.6-.9 4.6-4.6C397 98.7 397 50.8 397 3c0-1.7 1.3-3 3-3h23.9zM104 217.5c0 24 .1 48-.1 72 0 3.7.8 4.8 4.6 4.6 9.3-.3 18.7 0 28-.2 2.8-.1 3.6.7 3.6 3.6v23c0 2.8-.7 3.6-3.6 3.6-9.7-.2-19.3 0-29-.1-3 0-3.6.2-3.6 3.6v11.6c0 2.2-1 3-3.1 3H78.5c-3.5 0-4.5-1.2-4.5-3.7v-12.4c-.1-36.2-.1-72.3 0-108.5 0-3.7-.8-4.7-4.6-4.6-9.3.3-18.7 0-28 .2-2.8.1-3.6-.7-3.6-3.6v-23c0-2.8.7-3.6 3.6-3.6 9.7.2 19.3 0 29 .1 2.8.1 3.6-.7 3.6-3.6-.2-11.7 0-23.3-.1-35 0-2.8.7-3.6 3.6-3.6H100c3.3 0 4 .8 4 4.1-.2 24.1 0 48.3 0 72.5zM244 187.3c0 21.5-.1 43 .1 64.5 0 3.5-.8 4.5-4.4 4.3-7-.3-14-.3-21 0-4 .2-4.8-1-4.8-4.9.1-58.5.1-117 .1-175.5 0-6.8.1-13.7-.1-20.5-.1-2.6.7-3.4 3.3-3.3 7.8.2 15.7.2 23.5 0 2.6-.1 3.4.7 3.3 3.3-.2 9.8 0 19.7-.1 29.5 0 2.6.7 3.4 3.3 3.3 9.8-.2 19.7 0 29.5-.1 2.6 0 3.4.7 3.3 3.3v23.5c0 2.6-.7 3.3-3.3 3.3-9.7-.1-19.3.1-29-.1-3.1-.1-3.8.8-3.8 3.9.2 21.8.1 43.6.1 65.5z"></path>
                                        </svg>
                                        <!-- <br> <p class="py-2">Bars</p> -->
                                    </li>

                                    <li onclick="changeChartType('heikin')" class="heikin-selector chart-type-selector block bg-[#293145] items-center flex flex-col">
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
                                    <div class="toggle active"></div>
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
                <!-- <div id="chart" class="flex-grow w-full lg:max-h-[90vh]"></div> -->
                <canvas id="tradingChart" class="w-full h-full"></canvas>
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
                    <label for="hs-trailing-icon" class="block text-sm font-light mb-2">Amount</label>
                    <div class="relative">
                        <input type="text" id="hs-trailing-icon" name="amount"
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
                    <span id="profit_percentage">+92% </span>
                    <span id="payout">$19.20</span>
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
<div class="min-h-screen bg-[#222636] w-[20%] ml-auto" id="mainContent">
    <div class="p-4 text-white">Select a section from the sidebar.</div>
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
        <div id="openTrades" class="flex justify-center items-center mt-16 trade_list-page">
            <p class="text-gray-500">Open Trade Container</p>
        </div>

        <div id="closedTrades" class="flex justify-center items-center mt-16 hidden trade_list-page">
            <p class="text-gray-500">Closed Trade Container</p>
        </div>
    </div>

    <div id="rightSignals">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Trading Signals</h2>
            <div class="w-full mt-3">
                <div class="bg-gray-800 p-4 mb-3 rounded-lg flex items-center justify-between relative">
                    <div class="flex-grow">
                        <h3 class="text-lg">AUD/CHF OTC</h3>
                        <p class="text-gray-400 text-sm">$1</p>
                        <p class="text-gray-400 text-sm">Copied: 62 times</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="text-green-500 text-lg">⬆⬆</div>
                        <div class="w-24 h-1.5 bg-gray-700 rounded-full mt-1 relative">
                            <div class="bg-blue-500 h-full w-1/2 rounded-full"></div>
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded mt-2 text-sm">Copy signal</button>
                    </div>
                    <div class="absolute top-4 right-4 text-sm">07:33</div>
                </div>
                <div class="bg-gray-800 p-4 mb-3 rounded-lg flex items-center justify-between relative">
                    <div class="flex-grow">
                        <h3 class="text-lg">AUD/CHF OTC</h3>
                        <p class="text-gray-400 text-sm">$1</p>
                        <p class="text-gray-400 text-sm">Copied: 62 times</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="text-red-500 text-lg">⬇⬇</div>
                        <div class="w-24 h-1.5 bg-gray-700 rounded-full mt-1 relative">
                            <div class="bg-blue-500 h-full w-2/5 rounded-full"></div>
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded mt-2 text-sm">Copy signal</button>
                    </div>
                    <div class="absolute top-4 right-4 text-sm">02:03</div>
                </div>
                <div class="bg-gray-800 p-4 mb-3 rounded-lg flex items-center justify-between relative">
                    <div class="flex-grow">
                        <h3 class="text-lg">AUD/CHF OTC</h3>
                        <p class="text-gray-400 text-sm">$1</p>
                        <p class="text-gray-400 text-sm">Copied: 71 times</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="text-green-500 text-lg">⬆⬆</div>
                        <div class="w-24 h-1.5 bg-gray-700 rounded-full mt-1 relative">
                            <div class="bg-blue-500 h-full w-3/10 rounded-full"></div>
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded mt-2 text-sm">Copy signal</button>
                    </div>
                    <div class="absolute top-4 right-4 text-sm">12:03</div>
                </div>
            </div>
        </div>
    </div>
    <div id="rightSocialTrading">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Social Trading Platform</h2>
            <p>Connect with other traders and share strategies.</p>
        </div>
    </div>
    <div id="rightExpressTrades">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Express Trading</h2>
            <p>Quick access to streamlined trading operations.</p>
        </div>
    </div>
    <div id="rightTournaments">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Trading Tournaments</h2>
            <p>Participate in trading competitions and view rankings.</p>
        </div>
    </div>
    <div id="rightPendingTrades">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Pending Trades</h2>
            <p>Monitor and manage your pending trade orders.</p>
        </div>
    </div>
    <div id="rightHotkeys">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Hotkey Settings</h2>
            <p>Configure and view your trading hotkeys.</p>
        </div>
    </div>
</div>
@endsection


@push('js')
<script>
    const canvas = document.getElementById('tradingChart');
    const ctx = canvas.getContext('2d');
    const loader = document.getElementById('loader');

    let data = [];
    let chartType = "area";
    let isInitialDataLoaded = false;

    // Function to change chart type dynamically
    function changeChartType(chart_type) {
        $(".chart-type-selector").removeClass('active')
        $(`.${chart_type}-selector`).addClass('active')
        chartType = chart_type;
        drawChart();
    }

    // Resize Canvas
    function resizeCanvas() {
        const rect = canvas.parentElement.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        canvas.style.width = '100%';
        canvas.style.height = '100%';
    }

    resizeCanvas();

    window.addEventListener('resize', resizeCanvas);

    // Heikin Ashi Calculation
    function calculateHeikinAshi(data) {
        if (data.length < 2) return [];

        let haData = [];
        let prev = {
            open: data[0].open,
            close: data[0].close,
            high: data[0].high,
            low: data[0].low
        };

        data.forEach((candle, i) => {
            let haClose = (candle.open + candle.high + candle.low + candle.close) / 4;
            let haOpen = (prev.open + prev.close) / 2;
            let haHigh = Math.max(candle.high, haOpen, haClose);
            let haLow = Math.min(candle.low, haOpen, haClose);

            haData.push({
                open: haOpen,
                close: haClose,
                high: haHigh,
                low: haLow
            });
            prev = haData[i];
        });

        return haData;
    }

    // Drawing the Chart
    function drawChart() {
        if (!isInitialDataLoaded) return; // Don't draw until initial data is ready

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (data.length < 2) return;

        const xStep = canvas.width / data.length;
        const minPrice = Math.min(...data.map(d => d.low));
        const maxPrice = Math.max(...data.map(d => d.high));
        const priceRange = maxPrice - minPrice;
        const yScale = canvas.height / priceRange;

        if (chartType === "area") {
            ctx.fillStyle = "rgba(59, 130, 246, 0.3)";
            ctx.strokeStyle = "#3b82f6";
            ctx.lineWidth = 2;
            ctx.beginPath();

            data.forEach((point, i) => {
                const x = i * xStep;
                const y = canvas.height - ((point.close - minPrice) * yScale);
                if (i === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            });

            ctx.stroke();
            ctx.lineTo(canvas.width, canvas.height);
            ctx.lineTo(0, canvas.height);
            ctx.fill();
        } else if (chartType === "bars" || chartType === "candles" || chartType === "heikin") {
            const candleWidth = xStep * 0.8; // Increase candle width for better visibility
            const dataToPlot = chartType === "heikin" ? calculateHeikinAshi(data) : data;

            dataToPlot.forEach((point, i) => {
                const x = i * xStep;
                const yOpen = canvas.height - ((point.open - minPrice) * yScale);
                const yClose = canvas.height - ((point.close - minPrice) * yScale);
                const yHigh = canvas.height - ((point.high - minPrice) * yScale);
                const yLow = canvas.height - ((point.low - minPrice) * yScale);

                ctx.strokeStyle = point.close >= point.open ? "#16a34a" : "#dc2626";
                ctx.fillStyle = ctx.strokeStyle;
                ctx.lineWidth = 2;

                if (chartType === "bars") {
                    ctx.beginPath();
                    ctx.moveTo(x, yHigh);
                    ctx.lineTo(x, yLow);
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(x - candleWidth / 2, yOpen);
                    ctx.lineTo(x, yOpen);
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(x, yClose);
                    ctx.lineTo(x + candleWidth / 2, yClose);
                    ctx.stroke();
                } else if (chartType === "candles" || chartType === "heikin") {
                    ctx.beginPath();
                    ctx.moveTo(x, yHigh);
                    ctx.lineTo(x, yLow);
                    ctx.stroke();

                    ctx.fillRect(x - candleWidth / 2, Math.min(yOpen, yClose), candleWidth, Math.abs(yOpen - yClose));
                    ctx.strokeRect(x - candleWidth / 2, Math.min(yOpen, yClose), candleWidth, Math.abs(yOpen - yClose));
                }
            });
        }
    }

    // Fetch Initial Data
    const fetchInitialData = async () => {
        loader.style.display = "block"; // Show loader

        try {
            let candleUrl = "{{ url('api/stream/chart/' . $__coin) }}";
            const response = await fetch(candleUrl);
            const candles = await response.json();

            if (Array.isArray(candles)) {
                data = candles.map(candle => ({
                    time: new Date(candle.ts * 1000),
                    open: candle.o || candle.c,
                    high: candle.h || candle.c,
                    low: candle.l || candle.c,
                    close: candle.c
                }));

                if (data.length > 300) data = data.slice(-300); // Keep last 300 points
                isInitialDataLoaded = true;
                drawChart();
            } else {
                console.error('Unexpected response format:', candles);
            }
        } catch (error) {
            console.error('Error fetching initial data:', error);
        } finally {
            loader.style.display = "none"; // Hide loader after data is loaded
        }
    };

    // WebSocket Real-time Updates
    const websocketUrl = "wss://ws-plus.olymptrade.com/connect";
    const socket = new WebSocket(websocketUrl);

    socket.onopen = () => {
        console.log('WebSocket connected');
        const subscriptionMessage = JSON.stringify([{
            "e": 10,
            "t": 2,
            "d": {
                "pairs": ["{{ $__coin }}"],
                "chart_tfs": [3600, 86400],
                "with_forecast": true
            },
            "uuid": "1"
        }]);
        socket.send(subscriptionMessage);
    };

    socket.onmessage = (event) => {
        if (!isInitialDataLoaded) return; // Don't process WebSocket updates until initial data is loaded

        try {
            const message = JSON.parse(event.data);

            message.forEach(item => {
                if (item.d && Array.isArray(item.d) && item.d.length > 0) {
                    const pairData = item.d[0];

                    if (pairData.pair && pairData.rate) {
                        const newPoint = {
                            time: new Date(),
                            open: pairData.rate,
                            high: pairData.rate,
                            low: pairData.rate,
                            close: pairData.rate
                        };

                        data.push(newPoint);
                        if (data.length > 300) data.shift(); // Keep only 300 points
                        drawChart();
                    }
                }
            });

        } catch (error) {
            console.error('Error processing WebSocket message:', error);
        }
    };

    socket.onclose = () => console.log('WebSocket disconnected');
    socket.onerror = (error) => console.error('WebSocket error:', error);

    // Fetch initial data
    fetchInitialData();



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
