@extends('layouts.app')

@section('title', 'Trading Dashboard')

@section('content')
@php $__coin = $data->symbol ?? "USDCAD" @endphp


<!-- THIS IS THE CHART DIV + THE BUY AND SELL -->
<section class="flex flex-row w-full box-contain overflow-hidden">
    <!-- EVERYTHING CHART IS DONE IN THE SECTION ELEMENT -->
    <!-- MY ATTEMPT AT DOING THE GRAPH MY SELF graph container -->
    <section class="bg-[#1a1f2e] text-gray-400 min-h-screen z-20 w-[80%]">
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Asset Dropdown Content -->
                    <div id="assetDropDown" class="absolute left-5 w-2xl rounded-lg bg-gray-800 shadow-lg rounded mt-2 hidden">
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

                <div>
                    <div class="relative inline-block" id="chartTpyeBtn" onclick="$('#chartTpyeDropDown').toggle('hidden')">
                        <!-- Clickable Dropdown Trigger -->
                        <button class="p-2 hover:bg-[#2a3142] rounded">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 40 35" width="40" height="35" data-src="/themes/cabinet/svgicons/chart-types/line.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
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
                                    <li class="active block items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon line-icon injected-svg" viewBox="0 0 40 35" width="40" height="35" data-src="" xmlns:xlink="http://www.w3.org/1999/xlink" role="img"><path d="M.22 16.28L4.4 11.6a.69.69 0 011.13-.06c1.2 1.12 2.21 2 3.4 3.16.23.23.33.17.52 0L18.2 4.54a.68.68 0 011.07 0c1.81 1.37 3.21 2.31 5 3.59.81.6 1.64 1.18 2.46 1.77.17.12.27.13.43 0L38.19.27c.24-.21.42-.16.55 0l1.08 1.23a.6.6 0 01.16.22.26.26 0 010 .24.24.24 0 01-.06.08c-1 .87-2.06 1.74-3.08 2.62l-9.38 8.12a.77.77 0 01-.88 0c-2.41-1.84-4.75-3.47-7.21-5.24-.28-.2-.41-.19-.63.07-3 3.54-5.92 6.88-9 10.39a.58.58 0 01-.91 0c-.91-.87-3-2.9-3.41-3.31-.21-.21-.33-.24-.55 0C3.9 15.8 3 16.81 2 17.9c-.19.21-.33.21-.52 0-.48-.37-.82-.7-1.27-1.12-.21-.19-.11-.36.01-.5zm.15 16.19h39.35a.19.19 0 01.2.2v2c0 .18-.08.25-.29.25H.45c-.29 0-.37-.07-.36-.37v-1.77a.27.27 0 01.28-.31z"></path><path d="M.24 30.07A.14.14 0 01.09 30v-7c0-.29 0-.28.35-.64C2 21 3.43 19.16 5 17.73c.19-.17.29-.15.49 0A46.59 46.59 0 009 21.05a.62.62 0 00.8 0c2.73-2.66 5.59-6 9.18-10.27.2-.19.32-.2.57 0 2.4 1.76 4.67 3.39 7.18 5a.43.43 0 00.57 0c2.06-1.64 1.94-1.5 3.79-3.07 3.69-3.25 6-5.17 8.41-7.32.35-.21.44 0 .44.11v24.39c0 .09-.09.17-.22.17H.24z"></path></svg>
                                        <!-- <br> <p class="py-2">Line</p> -->
                                    </li>
                                    
                                    <li class="block items-center flex flex-col">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon candles-icon injected-svg" viewBox="0 0 500.1 435.1" width="55" height="35" data-src="/themes/cabinet/svg/icons/chart-types/candles.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img"><path d="M2.8 405H499c.6 0 1.1.5 1.1 1.1v26.4c0 2.2-.2 2.4-2.5 2.5h-2c-163.7 0-327.3 0-491 .1-3.7 0-4.7-.8-4.6-4.6.3-7.6.1-15.2.1-22.8 0-1.5 1.2-2.7 2.7-2.7zM425.1 1.8c0 11.9.1 23.8-.1 35.7 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 79.7-.1 159.3 0 239 0 2.8-.7 3.7-3.6 3.6-7.5-.2-15 .1-22.5-.1-3.3-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 3.3-.8 4-4.2 4.1-7.2.1-14.5 0-21.9 0-3.3 0-4.1-.8-4.1-4.1.2-17.3 0-34.7.2-52 0-3.3-.8-4.2-4.1-4.1-7.5.2-15-.1-22.5.1-2.9.1-3.4-.7-3.4-3.6 0-5.1-.1-8.3-.1-12.5 0-75 0-150-.1-225 0-4.1.9-5.4 5.1-5.1 7.1.4 14.3 0 21.5.2 2 .1 3.6-1.5 3.6-3.5-.1-11.9-.1-23.8-.1-35.7 0-1 .8-1.9 1.9-1.9h26.3c1-.1 1.8.7 1.8 1.7zM199.1 164c0-18.3.1-36.7-.1-55 0-3.2.7-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.7-.7 3.6-3.6-.2-7.7.1-15.3-.1-23-.1-3.1.8-3.6 4.4-3.6 7.9 0 14.8.1 22.1 0 2.9 0 3.7.4 3.7 3-.1 7.8.1 15.7-.1 23.5-.1 2.8.7 3.7 3.6 3.6 7.7-.2 15.3.1 23-.1 2 0 3.6 1.5 3.6 3.5-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.5 3.6-7.5-.2-15 .1-22.5-.1-3.2-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 4-1.1 3.8-6.7 3.9-5.6.1-10.9 0-16.5 0s-7 .3-6.9-3.8c.2-17.3 0-34.7.2-52 0-3.2-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1-.2-18.3-.3-36.7-.3-55zM44.1 228c0-18.3.1-36.7-.1-55 0-3.2.8-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.6-.7 3.6-3.6-.2-9.7 0-19.3-.1-29-.1-3.2 1-3.5 4.9-3.5h20.7c3.6 0 4.6.4 4.5 3.5-.2 9.7 0 19.3-.1 29 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.6 3.6-7.7-.2-15.3.1-23-.1-2-.1-3.6 1.6-3.6 3.5.1 15.5 0 31 .1 46.5 0 3.5-.9 4-5 4-6.7.1-13.3 0-20.1-.1-4 0-5-.2-5-3.5.1-15.5 0-31 .1-46.5 0-3.3-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1.2-18.2.1-36.6.1-54.9z"></path></svg>
                                        <!-- <br> <p class="py-2">Candles</p> -->
                                    </li>
                                    
                                    <li class="block items-center flex flex-col">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon bars-icon injected-svg" viewBox="0 0 500.1 435.1" width="40" height="35" data-src="/themes/cabinet/svg/icons/chart-types/bars.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img"><path d="M2.4 405h494.8c1.6 0 2.8 1.3 2.8 2.8 0 8.1-.1 16 .1 24.3 0 2.2-1.3 2.9-3.5 2.9-163.7 0-328.4 0-492.1.1-3.7 0-4.4-.6-4.4-4.6-.1-7.7-.1-15.4-.1-23.1 0-1.3 1.1-2.4 2.4-2.4zM423.9 0c1.7 0 3.1 1.4 3.1 3.1 0 103.8 0 207.6-.1 311.4 0 4.5 1 5.9 5.6 5.7 8.6-.4 17.3 0 26-.2 3.2-.1 3.5.9 3.5 5 .1 7.8-.1 14.2 0 20.8 0 3.4-.2 4.3-3 4.3-19.7-.1-39.3-.2-59 0-3.7 0-3-2-3-4.1v-80.5c0-26.7-.1-53.3.1-80 0-3.7-.8-4.8-4.6-4.6-9.3.3-18.7 0-28 .2-3.2.1-3.5-.9-3.5-4.8.1-6.9-.1-13.1 0-20.8 0-3.6.4-4.6 3.5-4.5 9.3.2 18.7-.1 28 .2 3.8.1 4.6-.9 4.6-4.6C397 98.7 397 50.8 397 3c0-1.7 1.3-3 3-3h23.9zM104 217.5c0 24 .1 48-.1 72 0 3.7.8 4.8 4.6 4.6 9.3-.3 18.7 0 28-.2 2.8-.1 3.6.7 3.6 3.6v23c0 2.8-.7 3.6-3.6 3.6-9.7-.2-19.3 0-29-.1-3 0-3.6.2-3.6 3.6v11.6c0 2.2-1 3-3.1 3H78.5c-3.5 0-4.5-1.2-4.5-3.7v-12.4c-.1-36.2-.1-72.3 0-108.5 0-3.7-.8-4.7-4.6-4.6-9.3.3-18.7 0-28 .2-2.8.1-3.6-.7-3.6-3.6v-23c0-2.8.7-3.6 3.6-3.6 9.7.2 19.3 0 29 .1 2.8.1 3.6-.7 3.6-3.6-.2-11.7 0-23.3-.1-35 0-2.8.7-3.6 3.6-3.6H100c3.3 0 4 .8 4 4.1-.2 24.1 0 48.3 0 72.5zM244 187.3c0 21.5-.1 43 .1 64.5 0 3.5-.8 4.5-4.4 4.3-7-.3-14-.3-21 0-4 .2-4.8-1-4.8-4.9.1-58.5.1-117 .1-175.5 0-6.8.1-13.7-.1-20.5-.1-2.6.7-3.4 3.3-3.3 7.8.2 15.7.2 23.5 0 2.6-.1 3.4.7 3.3 3.3-.2 9.8 0 19.7-.1 29.5 0 2.6.7 3.4 3.3 3.3 9.8-.2 19.7 0 29.5-.1 2.6 0 3.4.7 3.3 3.3v23.5c0 2.6-.7 3.3-3.3 3.3-9.7-.1-19.3.1-29-.1-3.1-.1-3.8.8-3.8 3.9.2 21.8.1 43.6.1 65.5z"></path></svg>
                                        <!-- <br> <p class="py-2">Bars</p> -->
                                    </li>

                                    <li class="block items-center flex flex-col">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon heiken-ashi-icon injected-svg" viewBox="0 0 500.2 415.2" width="40" height="35" data-src="/themes/cabinet/svg/icons/chart-types/heiken-ashi.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img"><path d="M3.4 385.1h494.5c1.2 0 2.2 1 2.2 2.2 0 8.4-.1 16.9.1 25.3 0 2.2-1.1 2.5-2.8 2.5-163.7 0-329.1 0-492.8.1-3.7 0-4.7-.8-4.6-4.6.3-7.4.2-14.8.1-22.2 0-1.8 1.5-3.3 3.3-3.3zM46.1 79.6c0-25 .1-50-.1-75 0-3.7.9-4.6 4.6-4.6 27 .2 54 .2 81 0 3.7 0 4.6.9 4.6 4.6-.1 50-.1 100 0 150 0 3.7-.6 4.6-4.6 4.6h-21.4c-3.3 0-4.1.7-4.1 3.9.1 32.8 0 65.7.2 98.5 0 4.5-.2 5.5-4.8 5.5H80.2c-3.3.1-4.1-.9-4.1-4.1.1-32.8 0-65.7.2-98.5 0-4.5-1.1-5.5-5.6-5.4-7 .2-13.6 0-20.5 0-3.3 0-4.1-.9-4.1-4.1.1-25.1 0-50.2 0-75.4zM295.1 117.6c0 24-.1 48 .1 72 0 3.7-.8 4.6-4.6 4.6h-21.5c-3.3 0-4 .6-4 3.9.1 31 0 62 .2 93 0 4.1-.8 5.1-5.1 5.1h-21c-3.3.1-4.1-.8-4.1-4.1.1-31 0-62 .2-93 0-4.2-1-4.9-5.2-4.9h-20.4c-3.8 0-4.6-.9-4.6-4.6.2-35 .1-70 .1-105 0-13.2.1-26.3-.1-39.5 0-3.3.8-4.1 4.1-4.1 27.3.1 54.7.1 82 0 3.3 0 4.1.8 4.1 4.1-.3 24.2-.2 48.3-.2 72.5zM364.1 163.1c0-22.5.1-45-.1-67.5 0-3.7.8-4.6 4.6-4.6 27 .2 54 .2 81 0 3.7 0 4.6.8 4.6 4.6-.1 45-.1 90 0 135 0 3.7-.7 4.6-4.5 4.5-7.3 0-14.3.1-21.6 0-3.3 0-4.1.7-4 4 .1 28 0 56 .2 84 0 4.1-.8 5.1-5.1 5-7 0-13.9 0-21 .1-3.3.1-4.1-.8-4.1-4.1.1-28 0-56 .2-84 0-4.1-.9-5-5.1-5h-21c-3.3 0-4.1-.8-4.1-4 .1-22.7 0-45.3 0-68z"></path></svg>
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
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" height="35" fill="white" viewBox="0 0 33 33">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Main Chart Area -->
            <div id="chartContainer" class="w-[100%] h-[calc(100%- 4rem)]">
                <div id="chart" class="flex-grow w-full lg:max-h-[90vh]"></div>
                <!-- <canvas id="tradingChart" class="w-full h-full"></canvas> -->
            </div>
        </div>
    </section>
    <!-- buy and sell section -->
    <div class="w-max-lg bg-[#23283b] h-screen p-4 text-gray-200">
        <!-- Time Section -->
        <div class="mb-6">
            <div class="flex gap-2 items-center mb-1">
                <span class="text-gray-400 text-sm">Time UTC+1</span>
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex justify-between items-center border border-[#2c3245] rounded-lg bg-[#1f2334]">
                <span id="current-timeee" class="text-xl pl-2"></span>

                <script>
                    function updateCurrentTime() {
                        const currentTime = new Date();
                        const hours = currentTime.getUTCHours() + 1;
                        const minutes = currentTime.getUTCMinutes();
                        const seconds = currentTime.getUTCSeconds();

                        document.getElementById('current-timeee').innerText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    }

                    updateCurrentTime();
                    setInterval(updateCurrentTime, 1000);
                </script>
                <button class="p-1 hover:bg-[#2a3142] bg-[#23283b] p-2">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="mb-6">
            <div class="flex gap-2 items-center mb-1">
                <span class="text-gray-400 text-sm">Amount</span>
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex justify-between items-center border border-[#2c3245] rounded-lg bg-[#1f2334]">
                <input class="text-xl pl-2 w-full border-none outline-none focus:ring-0 text-sm appearance-none" type="number" value="20">
                <style type="text/css">
                    input::-webkit-outer-spin-button,
                    input::-webkit-inner-spin-button {
                        -webkit-appearance: none;
                        margin: 0;
                    }

                    /* Firefox */
                    input[type=number] {
                        -moz-appearance: textfield;
                    }
                </style>
                <button class="p-1 hover:bg-[#2a3142] bg-[#23283b] p-2">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Payout Section -->
        <div class="mb-6">
            <div class="flex gap-2 items-center mb-1">
                <span class="text-gray-400 text-sm">Payout</span>
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex justify-between items-center border-2 border-dashed border-[#2c3245] rounded-lg bg-[#1f2334] p-2">
                <span class="text-green-500">+84%</span>
                <span class="text-green-500">$1.84</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-2">
            <button class="w-full py-3 bg-green-500 hover:bg-green-600 text-white rounded flex items-center pl-4 gap-2">
                <div class="svg-icon-wrap">
                    <div>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="injected-svg" data-src="/themes/cabinet/svg/icons/btn-buy.svg?v=1" xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
                            <path d="M10 0C8.02219 0 6.08879 0.58649 4.4443 1.6853C2.79981 2.78412 1.51809 4.3459 0.761209 6.17317C0.00433284 8.00043 -0.193701 10.0111 0.192152 11.9509C0.578004 13.8907 1.53041 15.6725 2.92894 17.0711C4.32746 18.4696 6.10929 19.422 8.0491 19.8079C9.98891 20.1937 11.9996 19.9957 13.8268 19.2388C15.6541 18.4819 17.2159 17.2002 18.3147 15.5557C19.4135 13.9112 20 11.9778 20 10C19.9971 7.34874 18.9425 4.80691 17.0678 2.93219C15.1931 1.05746 12.6513 0.00294858 10 0Z" fill="#248F32"></path>
                            <path d="M13.8319 12.832L13.8288 7.17244C13.8278 6.90725 13.722 6.65311 13.5343 6.46549C13.3467 6.27786 13.0926 6.172 12.8274 6.17101L7.16786 6.16792C6.90411 6.17016 6.65203 6.27647 6.46647 6.46372C6.28091 6.65097 6.17688 6.90401 6.17703 7.16777C6.17717 7.43154 6.28148 7.68469 6.46725 7.87214C6.65301 8.0596 6.90521 8.16619 7.16897 8.16873L10.4135 8.17057L6.46366 12.1204C6.27612 12.308 6.17085 12.5624 6.17099 12.8278C6.17114 13.0931 6.2767 13.3477 6.46444 13.5354C6.65218 13.7232 6.90674 13.8287 7.1721 13.8289C7.43746 13.829 7.6919 13.7237 7.87944 13.5362L11.8293 9.58635L11.8311 12.8309C11.8329 13.0952 11.9392 13.3481 12.1267 13.5344C12.3143 13.7208 12.5678 13.8255 12.8321 13.8256C13.0963 13.8258 13.3498 13.7214 13.5371 13.5352C13.7244 13.349 13.8304 13.0962 13.8319 12.832Z" fill="white"></path>
                        </svg>
                    </div>
                </div>
                BUY
            </button>
            <button class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded flex items-center pl-4 gap-2">
                <div class="svg-icon-wrap">
                    <div>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="injected-svg" data-src="/themes/cabinet/svg/icons/btn-sell.svg?v=1" xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
                            <path d="M10 20C11.9778 20 13.9112 19.4135 15.5557 18.3147C17.2002 17.2159 18.4819 15.6541 19.2388 13.8268C19.9957 11.9996 20.1937 9.98891 19.8079 8.0491C19.422 6.10929 18.4696 4.32746 17.0711 2.92894C15.6725 1.53041 13.8907 0.578004 11.9509 0.192152C10.0111 -0.193701 8.00043 0.00433284 6.17317 0.761209C4.3459 1.51809 2.78412 2.79981 1.6853 4.4443C0.58649 6.08879 0 8.02219 0 10C0.00294858 12.6513 1.05746 15.1931 2.93219 17.0678C4.80691 18.9425 7.34874 19.9971 10 20Z" fill="#D1281F"></path>
                            <path d="M7.16786 13.8324L12.828 13.8287C13.0932 13.8277 13.3474 13.7218 13.535 13.5341C13.7227 13.3465 13.8286 13.0923 13.8296 12.8271L13.8333 7.16702C13.831 6.90324 13.7247 6.65115 13.5375 6.46559C13.3502 6.28003 13.0972 6.17602 12.8334 6.17619C12.5696 6.17636 12.3164 6.2807 12.1289 6.4665C11.9414 6.65231 11.8348 6.90454 11.8322 7.16832L11.8301 10.4132L7.88023 6.46333C7.6927 6.27579 7.43825 6.17053 7.17286 6.17071C6.90747 6.17088 6.65288 6.27647 6.4651 6.46425C6.27732 6.65203 6.17173 6.90662 6.17155 7.17201C6.17138 7.4374 6.27664 7.69185 6.46418 7.87939L10.414 11.8292L7.16917 11.8314C6.90539 11.834 6.65315 11.9406 6.46735 12.1281C6.28155 12.3156 6.17721 12.5688 6.17704 12.8325C6.17686 13.0963 6.28087 13.3494 6.46643 13.5366C6.65199 13.7239 6.90409 13.8302 7.16786 13.8324Z" fill="white"></path>
                        </svg>
                    </div>
                </div>
                SELL
            </button>
        </div>
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
            <svg class="w-6 h-6 text-slate-400 group-hover:text-slate-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <svg class="w-6 h-6 text-slate-400 group-hover:text-slate-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <button class="p-2 rounded-full bg-[#8ea5c0] text-[#2a3144] text-center">
                    <svg class="w-3 h-3 text-[#2a3144]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex border-b border-[#2a3142] w-full">
            <button class="relative py-2 text-gray-200 bg-[#1e2131] font-thin text-sm w-6/12">
                Opened
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
            </button>
            <button class="py-2 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
                Closed
            </button>
        </div>

        <div class="flex justify-center items-center mt-16">
            <p class="text-gray-500" id="contentText">HI</p>
        </div>

    </div>
    <div id="rightSignals">
        <div class="p-4 text-white">
            <h2 class="text-2xl font-bold mb-4">Trading Signals</h2>
            <p>Access real-time trading signals and market indicators.</p>
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
<script src="//unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>

<script>
    // Initialize the chart
    const canvas = document.getElementById('tradingChart');
    const ctx = canvas.getContext('2d');

    // Set canvas size with proper scaling
    function resizeCanvas() {
        const rect = canvas.parentElement.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        canvas.style.width = `${rect.width}px`;
        canvas.style.height = `${rect.height}px`;
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Sample data points (you would replace this with real-time data)
    let data = Array.from({
        length: 100
    }, (_, i) => ({
        time: new Date(Date.now() - (100 - i) * 1000),
        price: 174.7 + Math.random() * 0.1
    }));

    function drawChart() {
        ctx.clearRect(0, 0, canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio);

        // Draw grid
        ctx.strokeStyle = '#2a3142';
        ctx.lineWidth = 1;

        // Vertical grid lines
        for (let i = 0; i < canvas.width / window.devicePixelRatio; i += 50) {
            ctx.beginPath();
            ctx.moveTo(i, 0);
            ctx.lineTo(i, canvas.height / window.devicePixelRatio);
            ctx.stroke();
        }

        // Horizontal grid lines
        for (let i = 0; i < canvas.height / window.devicePixelRatio; i += 50) {
            ctx.beginPath();
            ctx.moveTo(0, i);
            ctx.lineTo(canvas.width / window.devicePixelRatio, i);
            ctx.stroke();
        }

        // Draw price line
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 2;
        ctx.beginPath();

        const xStep = (canvas.width / window.devicePixelRatio) / (data.length - 1);
        const minPrice = Math.min(...data.map(d => d.price));
        const maxPrice = Math.max(...data.map(d => d.price));
        const priceRange = maxPrice - minPrice;
        const yScale = (canvas.height / window.devicePixelRatio) / priceRange;

        data.forEach((point, index) => {
            const x = index * xStep;
            const y = (canvas.height / window.devicePixelRatio) - ((point.price - minPrice) * yScale);

            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });

        ctx.stroke();

        // Draw latest price
        const latestPrice = data[data.length - 1].price;
        ctx.fillStyle = '#94a3b8';
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(latestPrice.toFixed(3), canvas.width / window.devicePixelRatio - 10, 20);
    }

    drawChart();
    window.addEventListener('resize', drawChart);

    // Simulate real-time updates
    setInterval(() => {
        const now = new Date();
        const newPrice = data[data.length - 1].price + (Math.random() - 0.5) * 0.01;
        data.shift();
        data.push({
            time: now,
            price: newPrice
        });
        drawChart();
    }, 1000);
</script>

<script>
    // WebSocket URL
    const websocketUrl = "wss://ws-plus.olymptrade.com/connect";

    // Chart Initialization
    const chartContainer = document.getElementById('chart');
    const chart = LightweightCharts.createChart(chartContainer, {
        width: '100%',
        height: '100%',
        layout: {
            background: {
                type: 'solid',
                color: 'transparent'
            },
            textColor: '#fff',
            attributionLogo: true
        },
        grid: {
            vertLines: {
                color: '#293341',
            },
            horzLines: {
                color: '#293341',
            },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
        },
        rightPriceScale: {
            borderVisible: true,
        },
        timeScale: {
            borderVisible: false,
            timeVisible: true,
            secondsVisible: true,
            rightOffset: 50,
            barSpacing: 6,
            minBarSpacing: 0.5,
            fixLeftEdge: false,
            fixRightEdge: false,
            lockVisibleTimeRangeOnResize: true,
            rightBarStaysOnScroll: true,
        },
    });

    // Add Area Series
    const lineSeries = chart.addAreaSeries({
        topColor: 'rgba(33, 150, 243, 0.56)',
        bottomColor: 'rgba(33, 150, 243, 0.04)',
        lineColor: '#2196f3',
        lineWidth: 2,
        lastValueVisible: true,
        priceLineVisible: true,
        priceLineSource: LightweightCharts.PriceLineSource.LastBar,
        crosshairMarkerVisible: true,
        crosshairMarkerRadius: 6,
        crosshairMarkerBorderColor: '#ffffff',
        crosshairMarkerBackgroundColor: '#2196f3',
    });

    // Resize Chart on Window Resize
    window.addEventListener('resize', () => {
        chart.resize(chartContainer.offsetWidth, chartContainer.offsetHeight);
    });

    // Function to fetch initial data from Olymp API
    const fetchInitialData = async () => {
        try {
            let candleUrl = "{{ url('api/stream/chart/' . $__coin) }}";

            const response = await fetch(candleUrl);
            const candles = await response.json();

            console.log('Candles:', candles); // Log the candles to see its structure

            if (Array.isArray(candles)) { // Check if candles is an array
                const formattedInitialData = candles
                    .map(candle => ({
                        time: candle.ts,
                        value: candle.c,
                    }))
                    .filter(item => item.time !== null && item.value !== null);
                lineSeries.setData(formattedInitialData);
            } else {
                console.error('Unexpected response format:', candles);
            }
        } catch (error) {
            console.error('Error fetching initial data:', error);
        }
    };


    // Function to update chart with incremental data
    const updateChartWithNewData = (data) => {
        data.forEach(item => {
            // Check if item has 'd' and it contains data
            if (item.d && Array.isArray(item.d) && item.d.length > 0) {
                const pairData = item.d[0];

                // Check if 'pair' and 'rate' exist in the first item of 'd'
                if (pairData.pair && pairData.rate) {
                    lineSeries.update({
                        time: Math.floor((pairData.ts || Date.now()) /
                            1000), // Use 'ts' if it exists or default to current timestamp
                        value: pairData.rate,
                    });
                }
            }
        });
    };


    // WebSocket Initialization
    const socket = new WebSocket(websocketUrl);

    socket.onopen = () => {
        console.log('WebSocket connected');
        // Send subscription message
        const subscriptionMessage = JSON.stringify([{
            "e": 10,
            "t": 2,
            "d": {
                "pairs": ["{{ $__coin}}"],
                "chart_tfs": [3600, 86400, 604800, 2592000],
                "with_forecast": true
            },
            "uuid": "1"
        }]);
        socket.send(subscriptionMessage);
    };

    socket.onmessage = (event) => {
        try {
            const message = JSON.parse(event.data);
            updateChartWithNewData(message);
        } catch (error) {
            console.error('Error processing WebSocket message:', error);
        }
    };

    socket.onclose = () => {
        console.log('WebSocket disconnected');
    };

    socket.onerror = (error) => {
        console.error('WebSocket error:', error);
    };

    // Fetch initial data before setting up WebSocket
    fetchInitialData();

    window.onload = function() {
        // Connect to the trade.created channel
        var tradeChannel = Echo.channel('trade.created');
        var tradeUpdateChannel = Echo.channel('trade.updated');

        if (tradeChannel) {
            toastr.success("Trade update connected");
            console.log('Echo connected successfully');
        }

        // Listen for the 'trade.created' event
        tradeChannel.listen('.trade.created', function(data) {
            if (data && data.id) {
                console.log('Trade Created:', data);
                toastr.success(`Trade event received: ID: ${data.id}`);
            } else {
                console.error('Invalid trade.created event data:', data);
            }
        });

        // Listen for the 'trade-updated' event
        tradeUpdateChannel.listen('.trade-updated', function(data) {
            if (data && data.id) {
                console.log('Trade Updated:', data);
                toastr.success(`Update on trade ${data.id} received`);
            } else {
                console.error('Invalid trade-updated event data:', data);
            }
        });
    };


    // handle form submission.
    $('#tradeForm').on('submit', function(e) {
        e.preventDefault();
        $('.cta-button').prop('disabled', true);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    // Display trade data
                    const trade = response.trade;
                    const tradeHtml = response.html;
                    $('#tradesList').prepend(tradeHtml);

                    // Start countdown
                    let timeLeft = trade.trade_close_time;
                    const countdownInterval = setInterval(() => {
                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            $(`.countdown-${trade.id}`).text('Completed');
                            return;
                        }

                        $(`.countdown-${trade.id}`).text(`${timeLeft} seconds`);
                        timeLeft--;
                    }, 1000);

                    // Reset form
                    $('#tradeForm')[0].reset();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while placing the trade');
                console.error(xhr);
            }
        });
        $('.cta-button').prop('disabled', false);
    });
</script>

<script>
    // Toggle Dropdown
    document.getElementById("assetBtn").addEventListener("click", function() {
        document.getElementById("assetDropDown").classList.toggle("hidden");
    });

    // Stock Data
    const stocks = "{!! get_assets() !!}";

    // Load stocks
    function loadStocks(filter = "all") {
        const stockList = document.getElementById("stockList");
        stockList.innerHTML = "";
        stocks.forEach(stock => {
            if (filter === "all" || stock.category === filter) {
                const li = document.createElement("li");
                li.className = "p-2 flex justify-between hover:bg-gray-700 cursor-pointer";
                li.innerHTML = `<span>${stock.name}</span> <span class="text-green-400">${stock.payout}</span>`;
                li.onclick = function() {
                    document.getElementById("selectedAsset").innerText = stock.name;
                    document.getElementById("assetDropDown").classList.add("hidden");
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
    document.getElementById("searchBar").addEventListener("keyup", function() {
        const searchValue = this.value.toLowerCase();
        const stockItems = document.querySelectorAll("#stockList li");
        stockItems.forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(searchValue) ? "flex" : "none";
        });
    });

    // Close Dropdown on Outside Click
    document.addEventListener("click", function(event) {
        if (!document.getElementById("assetBtn").contains(event.target) && !document.getElementById("assetDropDown").contains(event.target)) {
            document.getElementById("assetDropDown").classList.add("hidden");
        }
    });
</script>
@endpush


@push('css')
<style>
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
</style>
@endpush