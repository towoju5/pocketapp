<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <script src="//kit.fontawesome.com/7d607f3987.js" crossorigin="anonymous"></script>
  <title>Trades Interface</title>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    .chart-container {
      width: 100%;
      height: 90%;
      position: relative;
    }

    #chart {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    .trading-actions {
      position: absolute;
      left: 50%;
      bottom: 20px;
      /* Change from -85px to fixed value */
      transform: translate(-50%, 0);
      /* Remove Y translation */
      width: 85%;
      z-index: 1;
      backdrop-filter: blur(10px);
      background-color: #1f2937;
      /* Match nav bar color */
      padding: 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    #calculatorModal,
    #timeModal {
      z-index: 2;
    }

    .slider-wrapper {
      position: absolute;
      left: 50%;
      bottom: 180px;
      /* Adjust position relative to trading actions */
      transform: translate(-50%, 0);
      width: 80%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .slider-container {
      position: relative;
      width: 100%;
      height: 5px;
      background: #ff5252;
      border-radius: 2px;
      overflow: hidden;
    }

    .progress-left,
    .progress-right {
      position: relative;
      color: white;
      font-size: 14px;
      font-weight: bold;
      z-index: 3;
      min-width: 45px;
      text-align: center;
    }

    .progress-left {
      margin-right: 10px;
    }

    .progress-right {
      margin-left: 10px;
    }

    .slider-bar {
      width: 100%;
      height: 100%;
      background: #4caf50;
      animation: progressAnimation 10s linear infinite;
      transform-origin: left center;
    }

    @keyframes progressAnimation {
      0% {
        transform: scaleX(0);
      }

      100% {
        transform: scaleX(1);
      }
    }

    .input-container {
      width: 48%;
      /* Ensure both containers take up equal width */
    }

    .input-container p {
      margin: 0;
    }

    .input-container div {
      width: 100%;
      /* padding: 0.5rem; */
      background-color: #19212c;
      /* Tailwind's bg-gray-800 */
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Add these new styles */
    .left-navbar {
      position: fixed;
      top: 0;
      left: -80%;
      width: 80%;
      height: 100%;
      background-color: #151726;
      transition: left 0.3s ease-in-out;
      z-index: 1000;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
      overflow-y: auto;
    }

    .left-navbar.active {
      left: 0;
    }

    .navbar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      z-index: 999;
    }

    .navbar-overlay.active {
      display: block;
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Include Flowbite CSS -->
  <link href="https://cdn.jsdelivr.net/npm/flowbite @2.5.1/dist/flowbite.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
  <script src="//unpkg.com/lightweight-charts@3.8.0/dist/lightweight-charts.standalone.production.js"></script>
</head>

<body>
  <div class="h-dvh mobile-container bg-gray-900 text-white p-2">
    <!-- Add the left navbar markup -->
    <div class="navbar-overlay"></div>
    <div class="left-navbar">
      <div id="mainContent" class="">
        <div class="p-4 border-b border-gray-700">
          <div class="flex items-center gap-5">
            <div class="w-1/5 flex items-center justify-center">
              <div class="relative">
                  <img class="w-12 h-12 rounded-full" src="{{ $_user->avatar ?? '//pocket-uploads.com/images/cabinet/no_avatar.png'}}" alt="">
                  <span class="top-0 left-7 absolute  w-4 h-4 border-2 border-white dark:border-gray-800 rounded-full" style="background-color: red; right: 0; top: -8px;"></span>
              </div>
            </div>

            <div class="w-4/5 flex items-center justify-between">
              <div class="">
                <h2 class="flex my-2 text-md">{{ $_user->last_name .' '.$_user->first_name ?? null }}</h2>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-user"></i>
                  <span class="sensitive">{{ $_user->username ?? null }}</span>
                </div>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-envelope"></i>
                  <span class="sensitive">{{ $_user->email ?? null }}</span>
                </div>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-wallet"></i>
                  <span class="sensitive">{{ formatPrice($wallet_balance['balance'] ?? 0) }}</span>
                </div>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-earth-americas"></i>
                  <span class="sensitive">{{ request()->ip() }}</span>
                </div>
              </div>

              <div class="">
                <i class="fa-solid fa-eye"></i>
                <i class="fa-solid fa-eye-slash hidden"></i>
              </div>
            </div>
          </div>

          <button style="background: #172832; border: 1px solid #025b44"
            class="mt-3 px-4 py-2 bg-gray-700 rounded-lg hover:bg-green-500 w-full border border-green-500 flex items-center justify-center gap-2 text-sm">
            <svg width="17" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 465" fill="white">
              <path
                d="M469 0H45C30 1 15 19 15 35v79c0 23 17 43 40 45 6 1 9-1 9-7V60c0-8 5-12 15-12h346c10 0 15 4 15 12v106c0 7 2 9 8 11 22 4 40-12 40-34V35c0-19-12-31-31-31zM145 465c-20-2-40-22-38-48 0-3 1-5 5-4 5 2 8 5 12 9 6 6 14 8 23 8h209c14 0 26 10 29 24 1 3-3 2-5 5-1 2-108 7-110 7H145zM10 225c1-2 2-3 4-5l23-27c7-9 18-9 26 0l22 26c4 5 12 12 10 18-2 6-12 5-18 6-5 0-7 2-7 7v86c0 12-8 19-20 20-11 0-19-7-19-19v-86c0-4-3-7-7-7-5 0-12 1-16-5-2-5 1-8 3-11z" />
              <path
                d="M104 100c0-7 3-10 10-10h12c10 0 12 2 12 12v157c0 4 1 5 5 6 12 2 23 8 31 17 9 8 15 19 18 31 1 4 2 6 7 6h96c14 0 26 10 30 23 2 9-1 13-10 13H150c-25 0-45-21-45-46V100z" />
              <path
                d="M216 200c-25-18-29-50-8-72 25-27 73-27 97 0 18 19 18 47-1 66-13 13-29 19-47 19-16 0-29-4-41-13z" />
            </svg>

            Deposit
          </button>
        </div>

        <nav class="" style="background: #151726">
          <ul class="space-y-2">
            <li onclick="showContent('trading')" style="color: #fff; background: #262b3d"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path d="M4 5V19C4 19.5523 4.44772 20 5 20H19" stroke="#b5b5b5" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M18 9L13 13.9999L10.5 11.4998L7 14.9998" stroke="#b5b5b5" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
              </svg>
              <span>Trading</span>
            </li>
            <li onclick="showContent('finance')" style="color: #8fa5bf"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <defs>
                    <style>
                      .cls-1 {
                        fill: none;
                        stroke: #b5b5b5;
                        stroke-linecap: square;
                        stroke-miterlimit: 10;
                        stroke-width: 1.91px;
                      }
                    </style>
                  </defs>
                  <g id="dollar_1" data-name="dollar 1">
                    <line class="cls-1" x1="12" y1="1.5" x2="12" y2="4.36"></line>
                    <line class="cls-1" x1="12" y1="19.64" x2="12" y2="22.5"></line>
                    <path class="cls-1"
                      d="M6.27,16.77h0a2.87,2.87,0,0,0,2.87,2.87h4.77a3.82,3.82,0,0,0,3.82-3.82h0A3.82,3.82,0,0,0,13.91,12H10.09A3.82,3.82,0,0,1,6.27,8.18h0a3.82,3.82,0,0,1,3.82-3.82h4.77a2.87,2.87,0,0,1,2.87,2.87h0">
                    </path>
                  </g>
                </g>
              </svg>
              <span>Finance</span>
            </li>
            <li onclick="showContent('profile')" style="color: #8fa5bf"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg fill="#b5b5b5" class="w-4 h-4" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <g>
                    <path
                      d="M10.31,9.12H5.5A4.52,4.52,0,0,0,1,13.62,2.34,2.34,0,0,0,1,14H14.78a2.34,2.34,0,0,0,0-.38A4.51,4.51,0,0,0,10.31,9.12ZM8,7.88A3.94,3.94,0,1,0,4.06,3.94,3.94,3.94,0,0,0,8,7.88Z">
                    </path>
                  </g>
                </g>
              </svg>
              <span>Profile</span>
            </li>
            <li onclick="showContent('market')" style="color: #8fa5bf"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
                fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <title>cart 2</title>
                  <desc>Created with Sketch Beta.</desc>
                  <defs></defs>
                  <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                    <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-518.000000, -725.000000)"
                      fill="#b5b5b5">
                      <path
                        d="M528,751 C529.104,751 530,751.896 530,753 C530,754.104 529.104,755 528,755 C526.896,755 526,754.104 526,753 C526,751.896 526.896,751 528,751 L528,751 Z M524,753 C524,755.209 525.791,757 528,757 C530.209,757 532,755.209 532,753 C532,750.791 530.209,749 528,749 C525.791,749 524,750.791 524,753 L524,753 Z M526,747 C524.896,747 524,746.104 524,745 C524,745 547,743 546.972,743.097 C547.482,741.2 549.979,730.223 550,730 C550.054,729.45 549.553,729 549,729 L524,729 L524,727 L525,727 C525.553,727 526,726.553 526,726 C526,725.448 525.553,725 525,725 L519,725 C518.447,725 518,725.448 518,726 C518,726.553 518.447,727 519,727 L522,727 L522,745 C522,747.209 523.791,749 526,749 L549,749 C549.031,749 549,748.009 549,747 L526,747 L526,747 Z M540,751 C541.104,751 542,751.896 542,753 C542,754.104 541.104,755 540,755 C538.896,755 538,754.104 538,753 C538,751.896 538.896,751 540,751 L540,751 Z M536,753 C536,755.209 537.791,757 540,757 C542.209,757 544,755.209 544,753 C544,750.791 542.209,749 540,749 C537.791,749 536,750.791 536,753 L536,753 Z"
                        id="cart-2" sketch:type="MSShapeGroup"></path>
                    </g>
                  </g>
                </g>
              </svg>
              <span>Market</span>
            </li>
            <li onclick="showContent('achievements')" style="color: #fff; background: #262b3d"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg fill="#b5b5b5" class="w-4 h-4" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M31.835 9.233l-4.371-8.358c-0.255-0.487-0.915-0.886-1.464-0.886h-10.060c-0.011-0.001-0.022-0.003-0.033-0.004-0.009 0-0.018 0.003-0.027 0.004h-9.88c-0.55 0-1.211 0.398-1.47 0.883l-4.359 8.197c-0.259 0.486-0.207 1.248 0.113 1.696l15.001 20.911c0.161 0.224 0.375 0.338 0.588 0.338 0.212 0 0.424-0.11 0.587-0.331l15.247-20.758c0.325-0.444 0.383-1.204 0.128-1.691zM29.449 8.988h-5.358l2.146-6.144zM17.979 1.99h6.436l-1.997 5.716zM20.882 8.988h-9.301l4.396-6.316zM9.809 8.034l-2.006-6.044h6.213zM21.273 10.988l-5.376 15.392-5.108-15.392h10.484zM13.654 25.971l-10.748-14.983h5.776zM23.392 10.988h5.787l-11.030 15.018zM5.89 2.575l2.128 6.413h-5.539z">
                  </path>
                </g>
              </svg>
              <span>Achievements</span>
              <span class="ml-auto bg-blue-600 text-xs px-2 py-1 rounded-full">1</span>
            </li>
            <li onclick="showContent('chat')" style="color: #8fa5bf"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" class="w-4 h-4" viewBox="0 0 32 32" xml:space="preserve"
                fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <style type="text/css">
                    .duotone_een {
                      fill: #b5b5b5;
                    }

                    .duotone_twee {
                      fill: #b5b5b5;
                    }
                  </style>
                  <g>
                    <g>
                      <g>
                        <path class="duotone_een"
                          d="M20,10c4.418,0,8,3.582,8,8c0,1.368-0.345,2.654-0.95,3.78l0.945,3.359 c0.046,0.33-0.239,0.612-0.568,0.563l-3.196-0.921C23.002,25.549,21.555,26,20,26c-4.418,0-8-3.582-8-8S15.582,10,20,10z">
                        </path>
                      </g>
                      <g>
                        <path class="duotone_een"
                          d="M20,10c4.418,0,8,3.582,8,8c0,1.368-0.345,2.654-0.95,3.78l0.945,3.359 c0.046,0.33-0.239,0.612-0.568,0.563l-3.196-0.921C23.002,25.549,21.555,26,20,26c-4.418,0-8-3.582-8-8S15.582,10,20,10z">
                        </path>
                      </g>
                    </g>
                    <g>
                      <g>
                        <path class="duotone_twee"
                          d="M11,18c0-4.173,2.859-7.681,6.717-8.695C16.369,7.311,14.088,6,11.5,6C7.358,6,4,9.358,4,13.5 c0,1.275,0.32,2.474,0.881,3.525L4.005,20.14c-0.046,0.33,0.239,0.612,0.568,0.563l2.952-0.851C8.678,20.575,10.038,21,11.5,21 c0.008,0,0.016-0.001,0.025-0.001C11.191,20.059,11,19.053,11,18z">
                        </path>
                      </g>
                      <g>
                        <path class="duotone_twee"
                          d="M11,18c0-4.173,2.859-7.681,6.717-8.695C16.369,7.311,14.088,6,11.5,6C7.358,6,4,9.358,4,13.5 c0,1.275,0.32,2.474,0.881,3.525L4.005,20.14c-0.046,0.33,0.239,0.612,0.568,0.563l2.952-0.851C8.678,20.575,10.038,21,11.5,21 c0.008,0,0.016-0.001,0.025-0.001C11.191,20.059,11,19.053,11,18z">
                        </path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
              <span>Chat</span>
              <span class="ml-auto bg-blue-600 text-xs px-2 py-1 rounded-full">6</span>
            </li>
            <li onclick="showContent('help')" style="color: #8fa5bf"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#b5b5b5" class="w-4 h-4" viewBox="0 0 24 24">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z">
                  </path>
                </g>
              </svg>
              <span>Help</span>
              <span class="ml-auto bg-blue-600 text-xs px-2 py-1 rounded-full">!</span>
            </li>
            <li onclick="toggleDropdown()" style="color: #8fa5bf"
              class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#b5b5b5" class="w-4 h-4" viewBox="0 0 24 24">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z">
                  </path>
                </g>
              </svg>
              <span>English</span>
              <span class="ml-auto text-xs px-2 py-1">&darr;</span>
            </li>
            <div id="languageContent" class="bg-black pl-5 pr-3 text-xs grid grid-cols-3 gap-2 py-3 hidden"
              style="color: #8fa5bf">
              <li>Русский</li>
              <li>Português</li>
              <li>Español</li>
              <li>Italiano</li>
              <li>Polski</li>
              <li>Indonesian</li>
              <li>Français</li>
              <li>Thai</li>
              <li>Tiếng Việt</li>
              <li>العربية</li>
              <li>Malay</li>
              <li>中文</li>
              <li>Türkçe</li>
              <li>日本語</li>
              <li>한국어</li>
              <li>فارسی</li>
              <li>Српски</li>
              <li>Română</li>
              <li>Hrvatski</li>
              <li>हिन्दी</li>
              <li>ελληνικά</li>
              <li>বাংলা</li>
              <li>Українська</li>
              <li>Pilipinas</li>
              <li>Kiswahili</li>
            </div>
          </ul>

          <div class="mt-4 pt-4">
            <ul class="space-y-2">
              <li style="color: #8fa5bf; border-top: 1px solid #8fa5bf"
                class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
                <svg fill="#b5b5b5" class="w-4 h-4" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M1703.534 960c0-41.788-3.84-84.48-11.633-127.172l210.184-182.174-199.454-340.856-265.186 88.433c-66.974-55.567-143.323-99.389-223.85-128.415L1158.932 0h-397.78L706.49 269.704c-81.43 29.138-156.423 72.282-223.962 128.414l-265.073-88.32L18 650.654l210.184 182.174C220.39 875.52 216.55 918.212 216.55 960s3.84 84.48 11.633 127.172L18 1269.346l199.454 340.856 265.186-88.433c66.974 55.567 143.322 99.389 223.85 128.415L761.152 1920h397.779l54.663-269.704c81.318-29.138 156.424-72.282 223.963-128.414l265.073 88.433 199.454-340.856-210.184-182.174c7.793-42.805 11.633-85.497 11.633-127.285m-743.492 395.294c-217.976 0-395.294-177.318-395.294-395.294 0-217.976 177.318-395.294 395.294-395.294 217.977 0 395.294 177.318 395.294 395.294 0 217.976-177.317 395.294-395.294 395.294"
                      fill-rule="evenodd"></path>
                  </g>
                </svg>
                <span>Settings</span>
              </li>
              <li style="color: #8fa5bf"
                class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
                <svg fill="#b5b5b5" class="w-4 h-4 svg-icon apple-icon" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 12 15">
                  <g clip-path="url(#ab5162)">
                    <path
                      d="M9.337 7.872c-.006-1.075.48-1.887 1.465-2.484C10.25 4.6 9.419 4.166 8.32 4.08c-1.04-.082-2.176.606-2.592.606-.44 0-1.448-.577-2.239-.577C1.854 4.137.117 5.414.117 8.013c0 .767.14 1.56.422 2.379.375 1.075 1.729 3.712 3.14 3.668.739-.018 1.26-.525 2.221-.525.932 0 1.415.525 2.239.525 1.424-.02 2.648-2.417 3.006-3.495-1.91-.9-1.808-2.637-1.808-2.693Zm-1.658-4.81c.8-.95.726-1.814.703-2.124-.706.04-1.524.48-1.99 1.022-.512.58-.814 1.298-.75 2.106.765.059 1.462-.334 2.037-1.004Z">
                    </path>
                  </g>
                  <defs>
                    <clipPath id="ab5162">
                      <path d="M0 0h11.25v15H0z"></path>
                    </clipPath>
                  </defs>
                </svg>
                <span>App for iOS</span>
              </li>
              <li style="color: #8fa5bf"
                class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
                <svg class="svg-icon w-4 h-4" viewBox="0 0 19 16" fill="#b5b5b5" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M2.80005 0.800003C1.69692 0.800003 0.800049 1.69688 0.800049 2.8V11.8C0.800049 12.9031 1.69692 13.8 2.80005 13.8H8.30005L7.96567 14.8H5.80005C5.24692 14.8 4.80005 15.2469 4.80005 15.8C4.80005 16.3531 5.24692 16.8 5.80005 16.8H13.8C14.3532 16.8 14.8 16.3531 14.8 15.8C14.8 15.2469 14.3532 14.8 13.8 14.8H11.6344L11.3 13.8H16.8C17.9032 13.8 18.8 12.9031 18.8 11.8V2.8C18.8 1.69688 17.9032 0.800003 16.8 0.800003H2.80005ZM16.8 2.8V9.8H2.80005V2.8H16.8Z">
                  </path>
                </svg>
                <span>Desktop Version</span>
              </li>
              <li style="color: #8fa5bf" onclick="toggleLeftNavbar()"
                class="flex items-center gap-3 text-sm px-3 py-2 px-5 hover:bg-gray-700 cursor-pointer">
                <svg fill="#b5b5b5" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 52 52"
                  enable-background="new 0 0 52 52" xml:space="preserve">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <g>
                      <path
                        d="M21,48.5v-3c0-0.8-0.7-1.5-1.5-1.5h-10C8.7,44,8,43.3,8,42.5v-33C8,8.7,8.7,8,9.5,8h10 C20.3,8,21,7.3,21,6.5v-3C21,2.7,20.3,2,19.5,2H6C3.8,2,2,3.8,2,6v40c0,2.2,1.8,4,4,4h13.5C20.3,50,21,49.3,21,48.5z">
                      </path>
                      <path
                        d="M49.6,27c0.6-0.6,0.6-1.5,0-2.1L36.1,11.4c-0.6-0.6-1.5-0.6-2.1,0l-2.1,2.1c-0.6,0.6-0.6,1.5,0,2.1l5.6,5.6 c0.6,0.6,0.2,1.7-0.7,1.7H15.5c-0.8,0-1.5,0.6-1.5,1.4v3c0,0.8,0.7,1.6,1.5,1.6h21.2c0.9,0,1.3,1.1,0.7,1.7l-5.6,5.6 c-0.6,0.6-0.6,1.5,0,2.1l2.1,2.1c0.6,0.6,1.5,0.6,2.1,0L49.6,27z">
                      </path>
                    </g>
                  </g>
                </svg>
                <span>Logout</span>
              </li>
            </ul>
          </div>
        </nav>
      </div>

      <!-- Main Content -->
      @include('layouts.mobile.components.sidebar')
    </div>

    <div class="top-nav z-20" style="position: relative;">
        @include('layouts.mobile.components.top-nav')
    </div>
    <!-- Restore original single container -->
    <div class="" id="main-content" style="padding-bottom: 7rem">
      @yield('content')
    </div>

    <div class="bottom-nav z-20 bottom-0 flex-1" id="bottom-nav">
        @include('layouts.mobile.components.bottom-nav')
    </div>
  </div>

  <!-- Move scripts to end of body -->
  <script src="//cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
  <script src="{{ asset('mobile/js/custom-chart.js') }}"></script>
  <script src="{{ asset('mobile/js/navigation.js') }}"></script>
  <script src="{{ asset('mobile/js/dropdown.js') }}"></script>
  <script src="{{ asset('mobile/js/account-dropdown.js') }}"></script>
  <script src="{{ asset('mobile/js/wallet-modal.js') }}"></script>
  <script src="{{ asset('mobile/js/tabs.js') }}"></script>
  <script src="{{ asset('mobile/js/custom.js') }}"></script>
  <link rel="stylesheet" href="//cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
  <script src="//cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
  <script>
      toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "positionClass": "toast-top-right",
          "timeOut": "5000"
      };
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const mainContent = document.getElementById("mainContent");
        const hiddenSections = document.getElementById("hidden-sections");

        let activeTarget = null;

        window.handleNavigation = function (button) {
            const targetId = button.dataset.target;
            const content = hiddenSections.querySelector(`#${targetId}`);

            if (!content) {
                mainContent.innerHTML = "<div class='text-white'>Content not found</div>";
                mainContent.classList.remove("hidden");
                activeTarget = null;
                return;
            }

            if (activeTarget === targetId) {
                // Same item tapped again — close
                mainContent.innerHTML = "";
                mainContent.classList.add("hidden");
                button.classList.remove("bg-[#23283b]");
                activeTarget = null;
                return;
            }

            // Show new section
            mainContent.innerHTML = content.innerHTML;
            mainContent.classList.remove("hidden");

            // Update button states
            document.querySelectorAll('.nav-item').forEach(btn => {
                btn.classList.remove("bg-[#23283b]");
            });
            button.classList.add("bg-[#23283b]");

            activeTarget = targetId;
        };
    });
  </script>

  <script>
      @if ($errors->any())
          @foreach ($errors->all() as $error)
              toastr.error("{!! $error !!}");
          @endforeach
      @endif

      @if (session('error'))
          toastr.error("{!! session('error') !!}");
      @endif

      @if (session('success'))
          toastr.success("{!! session('success') !!}");
      @endif

      @if (session('info'))
          toastr.info("{!! session('info') !!}");
      @endif

      @if (session('warning'))
          toastr.warning("{!! session('warning') !!}");
      @endif
  </script>
  @stack('js')
  @stack('scripts')
</body>

</html>
