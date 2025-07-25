<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <script src="//kit.fontawesome.com/7d607f3987.js" crossorigin="anonymous"></script>
  <title>Trades Interface</title>
  <link href="//cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
  <script src="//unpkg.com/lightweight-charts@3.8.0/dist/lightweight-charts.standalone.production.js"></script>
  <style>
    body {
      max-width: 640px;
      height: 100vh;
      /* Add dynamic viewport height */
      margin: 0 auto;
      position: relative;
      overflow: hidden;
    }

    .mobile-container {
      width: 100%;
      height: 100%;
      /* Change from 90% to 100% */
      overflow: hidden;
      position: relative;
    }

    #main-content {
      height: calc(100% - 130px);
      /* Adjust calculation for nav heights */
      overflow: hidden;
      position: relative;
    }

    .chart-container {
      width: 100%;
      height: 100%;
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
      padding: 0.5rem;
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

    /* 2+2 = 22 */
    .set_time_plus {
      background-color: #1f2334;
      border: 1px solid #2c3245;
      color: white;
    }

    .set_time_time {
      width: 100px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="mobile-container bg-gray-900 text-white">
    <!-- Add the left navbar markup -->
    <div class="navbar-overlay"></div>
    <div class="left-navbar">
      <div id="mainContent" class="">
        <div class="p-4 border-b border-gray-700">
          <div class="flex items-center gap-5">
            <div class="w-1/5 flex items-center justify-center">
              <svg class="w-18 h-18 rounded-full border-2 border-green-500" version="1.1" id="Capa_1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 25.916 25.916" xml:space="preserve" fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <g>
                    <g>
                      <path style="fill: #c4dede"
                        d="M7.938,8.13c0.09,0.414,0.228,0.682,0.389,0.849c0.383,2.666,2.776,4.938,4.698,4.843 c2.445-0.12,4.178-2.755,4.567-4.843c0.161-0.166,0.316-0.521,0.409-0.938c0.104-0.479,0.216-1.201-0.072-1.583 c-0.017-0.02-0.127-0.121-0.146-0.138c0.275-0.992,0.879-2.762-0.625-4.353c-0.815-0.862-1.947-1.295-2.97-1.637 c-3.02-1.009-5.152,0.406-6.136,2.759C7.981,3.256,7.522,4.313,8.078,6.32C8.024,6.356,7.975,6.402,7.934,6.458 C7.645,6.839,7.833,7.651,7.938,8.13z">
                      </path>
                      <path style="fill: #32ac41"
                        d="M23.557,22.792c-0.084-1.835-0.188-4.743-1.791-7.122c0,0-0.457-0.623-1.541-1.037 c0,0-2.354-0.717-3.438-1.492l-0.495,0.339l0.055,3.218l-2.972,7.934c-0.065,0.174-0.231,0.289-0.416,0.289 s-0.351-0.115-0.416-0.289l-2.971-7.934c0,0,0.055-3.208,0.054-3.218c0.007,0.027-0.496-0.339-0.496-0.339 c-1.082,0.775-3.437,1.492-3.437,1.492c-1.084,0.414-1.541,1.037-1.541,1.037c-1.602,2.379-1.708,5.287-1.792,7.122 c-0.058,1.268,0.208,1.741,0.542,1.876c4.146,1.664,15.965,1.664,20.112,0C23.35,24.534,23.614,24.06,23.557,22.792z">
                      </path>
                      <path style="fill: #32ac41"
                        d="M13.065,14.847l-0.134,0.003c-0.432,0-0.868-0.084-1.296-0.232l1.178,1.803l-1.057,1.02 l1.088,6.607c0.009,0.057,0.058,0.098,0.116,0.098c0.057,0,0.106-0.041,0.116-0.098l1.088-6.607l-1.058-1.02l1.161-1.776 C13.888,14.756,13.487,14.83,13.065,14.847z">
                      </path>
                    </g>
                  </g>
                </g>
              </svg>
            </div>

            <div class="w-4/5 flex items-center justify-between">
              <div class="">
                <h2 class="flex my-2 text-md">Unknown Client</h2>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-user"></i>
                  <span class="sensitive">id: 44265667</span>
                </div>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-envelope"></i>
                  <span class="sensitive">johndoe@gmail.com</span>
                </div>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-wallet"></i>
                  <span class="sensitive">₦49,957.90</span>
                </div>
                <div class="flex items-center text-xs gap-2 text-white">
                  <i class="fa-solid fa-earth-americas"></i>
                  <span class="sensitive">102.89.76.40 🇳🇬</span>
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
      <div class="content p-5">
        <!-- Tradding sub left nav -->
        <div id="tradingContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="mt-5 flex flex-col gap-2">
            <a href="./index.html" class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon qt-real" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none">
                <path fill="#8ea5c0" fill-rule="evenodd"
                  d="M7.832 1.858C8.822 1.308 10.12 1 11.5 1c1.38 0 2.678.309 3.668.858C16.123 2.388 17 3.282 17 4.5v16.11c0 1.207-.901 2.069-1.842 2.57-.984.525-2.278.82-3.658.82s-2.673-.295-3.658-.82C6.901 22.68 6 21.818 6 20.61V4.5c0-1.218.877-2.111 1.832-2.642ZM8 7.231V8.5c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98V7.231c-.966.494-2.197.769-3.5.769S8.966 7.725 8 7.231ZM15 4.5c0 .162-.13.52-.804.894-.64.355-1.59.606-2.696.606-1.105 0-2.057-.251-2.696-.606C8.13 5.019 8 4.662 8 4.5c0-.162.13-.52.804-.894C9.444 3.251 10.394 3 11.5 3c1.105 0 2.057.251 2.696.606.674.375.804.732.804.894Zm0 6.83c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.17c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98v-1.17Zm0 4c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.17c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98v-1.17Zm0 4c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.28c0 .108.099.441.783.806.64.341 1.597.584 2.717.584s2.076-.243 2.717-.584c.684-.365.783-.698.783-.805V19.33Z"
                  clip-rule="evenodd"></path>
                <path fill="#8ea5c0"
                  d="M16.584 21.951c.416.049.4.049.916.049 1.38 0 2.674-.295 3.658-.82.941-.501 1.842-1.363 1.842-2.57V14.5c0-1.218-.877-2.111-1.832-2.642-.99-.55-2.288-.858-3.668-.858-.515 0-.02-.082-.5 0v2c.45-.104-.044 0 .5 0 1.105 0 2.057.251 2.696.606.674.374.804.732.804.894 0 .162-.13.52-.804.894-.64.355-1.59.606-2.696.606-.544 0-.05.104-.5 0v2h.5c1.303 0 2.534-.275 3.5-.769v1.38c0 .107-.099.44-.783.805-.64.341-1.597.584-2.717.584H17l-.416 1.951ZM6 6.014A9.163 9.163 0 0 0 5.5 6c-1.38 0-2.679.309-3.668.858C.877 7.388 0 8.282 0 9.5c0 .104.006.206.019.306A1.005 1.005 0 0 0 0 10v7.61c0 1.207.901 2.069 1.842 2.57.985.525 2.278.82 3.658.82.168 0 .335-.004.5-.013v-2.003c-.163.01-.33.016-.5.016-1.12 0-2.077-.243-2.717-.584C2.099 18.05 2 17.718 2 17.61v-1.508c.966.573 2.193.897 3.5.897.168 0 .335-.005.5-.016V14.98a5.83 5.83 0 0 1-.5.021c-1.08 0-2.005-.293-2.631-.712C2.236 13.864 2 13.388 2 13v-.769c.966.494 2.197.769 3.5.769.168 0 .335-.005.5-.014v-2.003a7.43 7.43 0 0 1-.5.017c-1.105 0-2.057-.251-2.696-.606C2.13 10.02 2 9.662 2 9.5c0-.162.13-.52.804-.894C3.444 8.251 4.394 8 5.5 8c.17 0 .337.006.5.017V6.014Z">
                </path>
              </svg>

              <div class="text-sm">
                <p>Quick Account</p>
                <p>Real Account</p>
              </div>
            </a>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 49" width="24" height="24" fill="#8ea5c0"
                class="svg-icon mt5-real-account">
                <path
                  d="M14.216 1H31.34l4.091 4.09H14.216a4.1 4.1 0 0 0-4.108 4.092v28.636a4.1 4.1 0 0 0 4.108 4.091h3.842A6.98 6.98 0 0 0 20.101 46h-5.885C9.68 46 6 42.337 6 37.818V9.182C6 4.663 9.679 1 14.216 1ZM29.899 46h5.885C40.322 46 44 42.337 44 37.818V13.66l-4.108-4.108v28.266a4.1 4.1 0 0 1-4.108 4.091h-3.842A6.98 6.98 0 0 1 29.899 46Z">
                </path>
                <path fill-rule="evenodd"
                  d="M32.593 1.586A2 2 0 0 0 31.18 1H28v12a4 4 0 0 0 4 4h12v-3.179a2 2 0 0 0-.586-1.414L32.594 1.586Z"
                  clip-rule="evenodd"></path>
                <path
                  d="M24.949 47.583c-.185 0-.375-.006-.566 0-.076 0-.102-.019-.102-.093.006-.361 0-.729.006-1.09 0-.08-.025-.093-.101-.1-.732-.05-1.438-.192-2.105-.497-.102-.05-.14-.094-.102-.212.127-.448.241-.897.356-1.339.019-.074.038-.093.114-.05.732.368 1.508.567 2.334.542.242-.006.477-.05.7-.15.54-.255.636-.87.19-1.27-.24-.217-.533-.342-.826-.454-.407-.15-.82-.274-1.215-.46-.413-.2-.807-.43-1.106-.779-.687-.784-.655-2.035.063-2.832.452-.492 1.03-.754 1.673-.89.076-.013.095-.044.095-.113-.006-.36 0-.728-.006-1.09 0-.074.025-.099.102-.099.388.006.776.006 1.164 0 .076 0 .095.025.089.093-.007.33 0 .66 0 .99 0 .075.012.106.101.106.617.038 1.222.137 1.788.392.076.032.095.069.07.144-.121.442-.242.877-.35 1.32-.026.1-.058.093-.134.056-.439-.193-.897-.343-1.38-.386-.375-.038-.75-.063-1.113.056-.286.093-.502.255-.553.579a.547.547 0 0 0 .165.491c.172.181.388.287.604.393.401.193.82.33 1.234.498.47.187.91.417 1.278.765.535.511.713 1.146.63 1.843-.12 1.127-.84 1.78-1.889 2.136-.146.05-.292.087-.445.112-.064.006-.083.03-.083.1.007.404 0 .802.007 1.2 0 .076-.02.094-.096.094-.19-.012-.388-.006-.591-.006ZM17 23h1v9h-1zM22 20h1v11h-1zM27 24h1v9h-1zM32 21h1v10h-1z">
                </path>
                <rect width="3" height="6" x="16" y="25" rx="1"></rect>
                <rect width="3" height="8" x="21" y="22" rx="1"></rect>
                <rect width="3" height="6" x="26" y="26" rx="1"></rect>
                <rect width="3" height="6" x="31" y="22" rx="1"></rect>
              </svg>
              <div class="text-sm">
                <p>Shares Trading</p>
                <p>Real Account</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 49" width="24" height="24" fill="#8ea5c0"
                class="svg-icon mt5-real-account">
                <path
                  d="M14.216 1H31.34l4.091 4.09H14.216a4.1 4.1 0 0 0-4.108 4.092v28.636a4.1 4.1 0 0 0 4.108 4.091h3.842A6.98 6.98 0 0 0 20.101 46h-5.885C9.68 46 6 42.337 6 37.818V9.182C6 4.663 9.679 1 14.216 1ZM29.899 46h5.885C40.322 46 44 42.337 44 37.818V13.66l-4.108-4.108v28.266a4.1 4.1 0 0 1-4.108 4.091h-3.842A6.98 6.98 0 0 1 29.899 46Z">
                </path>
                <path fill-rule="evenodd"
                  d="M32.593 1.586A2 2 0 0 0 31.18 1H28v12a4 4 0 0 0 4 4h12v-3.179a2 2 0 0 0-.586-1.414L32.594 1.586Z"
                  clip-rule="evenodd"></path>
                <path
                  d="M24.949 47.583c-.185 0-.375-.006-.566 0-.076 0-.102-.019-.102-.093.006-.361 0-.729.006-1.09 0-.08-.025-.093-.101-.1-.732-.05-1.438-.192-2.105-.497-.102-.05-.14-.094-.102-.212.127-.448.241-.897.356-1.339.019-.074.038-.093.114-.05.732.368 1.508.567 2.334.542.242-.006.477-.05.7-.15.54-.255.636-.87.19-1.27-.24-.217-.533-.342-.826-.454-.407-.15-.82-.274-1.215-.46-.413-.2-.807-.43-1.106-.779-.687-.784-.655-2.035.063-2.832.452-.492 1.03-.754 1.673-.89.076-.013.095-.044.095-.113-.006-.36 0-.728-.006-1.09 0-.074.025-.099.102-.099.388.006.776.006 1.164 0 .076 0 .095.025.089.093-.007.33 0 .66 0 .99 0 .075.012.106.101.106.617.038 1.222.137 1.788.392.076.032.095.069.07.144-.121.442-.242.877-.35 1.32-.026.1-.058.093-.134.056-.439-.193-.897-.343-1.38-.386-.375-.038-.75-.063-1.113.056-.286.093-.502.255-.553.579a.547.547 0 0 0 .165.491c.172.181.388.287.604.393.401.193.82.33 1.234.498.47.187.91.417 1.278.765.535.511.713 1.146.63 1.843-.12 1.127-.84 1.78-1.889 2.136-.146.05-.292.087-.445.112-.064.006-.083.03-.083.1.007.404 0 .802.007 1.2 0 .076-.02.094-.096.094-.19-.012-.388-.006-.591-.006ZM17 23h1v9h-1zM22 20h1v11h-1zM27 24h1v9h-1zM32 21h1v10h-1z">
                </path>
                <rect width="3" height="6" x="16" y="25" rx="1"></rect>
                <rect width="3" height="8" x="21" y="22" rx="1"></rect>
                <rect width="3" height="6" x="26" y="26" rx="1"></rect>
                <rect width="3" height="6" x="31" y="22" rx="1"></rect>
              </svg>
              <div class="text-sm">
                <p>Share Trading</p>
                <p>Demo Account</p>
              </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg version="1.1" class="svg-icon mt4-real-account" xmlns="http://www.w3.org/2000/svg" x="0" y="0"
                width="24" height="24" fill="#8ea5c0" viewBox="0 0 40 41" xml:space="preserve">
                <g clip-path="url(#mt4r9310)">
                  <path fill-rule="evenodd"
                    d="M12.002 39.973c.216.02.444.026.444.026s.434-.005.725-.034a5.459 5.459 0 0 0 .684-.112 7.657 7.657 0 0 0 2.573-1.012l.403-.243a5.49 5.49 0 0 1-1.754-3.173c-.309.178-.62.354-.932.526-1.101.603-2.229.481-3.304-.162-.214-.128-.132-.213-.036-.313l.047-.05c1.096-1.24 1.633-2.692 1.593-4.347-.094-3.881-3.779-6.752-7.567-5.878-.412.095-.455-.046-.454-.375.003-1.223.46-2.212 1.515-2.864.702-.434 1.412-.855 2.121-1.276.546-.325 1.093-.65 1.635-.98.242-.147.407-.043.594.077 1.316.85 2.633 1.698 3.953 2.544.023.015.047.035.07.056.074.064.152.13.242.06.048-.038.045-.149.042-.245v-.06l-.001-1.11c-.001-.832-.002-1.664.007-2.496.003-.238-.084-.367-.277-.486a27.92 27.92 0 0 1-.686-.439c-.31-.203-.62-.406-.94-.591-.294-.171-.379-.357-.373-.704.02-1.33.018-2.658.015-3.987l-.002-1.38c0-1.369.605-2.373 1.802-3.03.214-.117.316-.155.415.154.872 2.712 3.267 4.43 6.083 4.39 2.704-.04 4.994-1.788 5.796-4.475.006-.037.07-.18.19-.116 1.28.583 1.932 1.603 1.944 3.06.004.634.002 1.269 0 1.903-.004 1.114-.008 2.228.02 3.34.014.486-.14.73-.534.948-.303.169-.593.36-.883.552-.2.132-.4.264-.605.39-.183.11-.261.23-.259.454.013 1.229.013 2.458.001 3.687-.002.313.083.318.315.167.504-.328 1.01-.654 1.517-.98.823-.53 1.647-1.06 2.463-1.599.29-.19.503-.206.805-.02.835.516 1.68 1.017 2.526 1.518l.986.586c1.135.678 1.66 1.688 1.645 2.999-.003.294-.043.424-.416.336a6.195 6.195 0 0 0-6.717 2.845c-1.394 2.343-1.136 5.291.664 7.308.242.27.184.367-.09.514-1.175.63-2.339.611-3.494-.125a50.474 50.474 0 0 0-.584-.367 5.491 5.491 0 0 1-1.764 3.217l.448.27a7.656 7.656 0 0 0 2.488.977c.129.028.521.112 1.09.151h-.897l.91.001h-.013.282-.063 1.092-1.029c.6-.015 1.562-.221 1.963-.355a7.043 7.043 0 0 0 3.07-2.008c.153-.171.292-.219.523-.192 3.138.362 5.954-1.576 6.774-4.643.023-.082.172-.658.172-1.644 0 0-.03-.671-.1-1.128-.156-1.029-.579-1.675-.647-1.753-.515-.585-.454-1.155-.264-1.877.8-3.044-.481-6.042-3.18-7.656l-.291-.175a325.91 325.91 0 0 0-3.934-2.328c-.276-.16-.36-.333-.357-.643.015-1.368.012-2.736.008-4.104v-.566c-.007-3.409-2.21-6.13-5.546-6.846-.246-.053-.382-.153-.496-.392C25.19 1.634 23.59.397 21.314.072c-.513-.09-.921-.07-.921-.07a5.768 5.768 0 0 0-1.202.14c-2.037.471-3.487 1.662-4.371 3.546-.104.22-.22.317-.462.367-3.462.719-5.658 3.427-5.66 6.959L8.696 12.1c-.002 1.194-.004 2.389.01 3.583.003.303-.1.456-.353.604-1.414.823-2.82 1.662-4.225 2.502a6.884 6.884 0 0 0-2.882 8.387c.05.124.097.239-.018.388-.584.758-.945 1.62-1.125 2.56v.002c-.156.737-.088 1.51-.088 1.51.028.637.15 1.086.17 1.16v.006c.827 3.085 3.635 5.003 6.823 4.643.18-.02.3-.006.426.136 1.093 1.234 2.438 2.031 4.069 2.312l.015.003c.064.013.3.06.484.077ZM6.147 28.36C7.76 28.314 9.08 29.57 9.133 31.2c.053 1.585-1.235 2.928-2.855 2.98-1.579.05-2.92-1.245-2.966-2.867-.045-1.573 1.24-2.913 2.835-2.956Zm31.534 2.887c-.001 1.611-1.317 2.942-2.9 2.93-1.645-.012-2.954-1.332-2.924-2.948.029-1.602 1.33-2.883 2.919-2.872 1.6.01 2.905 1.31 2.905 2.89ZM17.59 6.139a2.898 2.898 0 0 1 2.966-2.813c1.606.043 2.883 1.36 2.846 2.935-.04 1.635-1.343 2.907-2.948 2.877-1.638-.031-2.924-1.378-2.865-2.999Z"
                    clip-rule="evenodd"></path>
                  <path
                    d="m20.328 28 .038-2.284H16.84L16 24.342 19.737 16h3.738l-.058 7.374H25l-.515 2.342h-1.03l.02 2.284h-3.147Zm-1.067-4.626h1.258v-3.851l-1.258 3.851ZM20.446 38.995c-.151 0-.308-.005-.464 0-.063 0-.083-.015-.083-.077.005-.295 0-.597.005-.892 0-.067-.021-.077-.084-.082-.599-.04-1.177-.158-1.725-.408-.083-.041-.114-.077-.083-.174.104-.367.198-.734.292-1.097.015-.06.031-.076.094-.04.599.3 1.235.464 1.912.443.199-.005.391-.04.574-.122.443-.21.52-.714.156-1.04-.198-.18-.438-.282-.677-.373-.334-.123-.673-.225-.996-.378-.339-.163-.662-.352-.907-.638-.563-.642-.537-1.668.052-2.321.37-.403.845-.617 1.371-.73.063-.01.078-.035.078-.091-.005-.296 0-.597-.005-.893 0-.062.02-.082.083-.082.318.005.636.005.954 0 .063 0 .078.02.073.076-.005.27 0 .541 0 .812 0 .061.01.086.084.086.505.031 1 .113 1.464.322.063.025.078.056.058.117-.1.363-.199.72-.287 1.082-.021.082-.047.076-.11.046a3.624 3.624 0 0 0-1.13-.316c-.308-.031-.616-.052-.913.045-.234.077-.412.21-.453.475a.448.448 0 0 0 .135.403c.141.148.318.235.495.321.329.159.673.27 1.012.409.385.153.745.341 1.047.627.438.418.584.939.516 1.51-.099.924-.688 1.46-1.548 1.75-.12.041-.24.072-.365.092-.052.005-.067.026-.067.082.005.331 0 .658.005.984 0 .062-.016.077-.078.077-.157-.01-.318-.005-.485-.005Z">
                  </path>
                </g>
                <defs>
                  <clipPath id="mt4r9310">
                    <path d="M0 0h40.976v40H0z"></path>
                  </clipPath>
                </defs>
              </svg>
              <div class="text-sm">
                <p>Forex MT4</p>
                <p>Real Account</p>
              </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg version="1.1" class="svg-icon mt4-real-account" xmlns="http://www.w3.org/2000/svg" x="0" y="0"
                width="24" height="24" fill="#8ea5c0" viewBox="0 0 40 41" xml:space="preserve">
                <g clip-path="url(#mt4r9310)">
                  <path fill-rule="evenodd"
                    d="M12.002 39.973c.216.02.444.026.444.026s.434-.005.725-.034a5.459 5.459 0 0 0 .684-.112 7.657 7.657 0 0 0 2.573-1.012l.403-.243a5.49 5.49 0 0 1-1.754-3.173c-.309.178-.62.354-.932.526-1.101.603-2.229.481-3.304-.162-.214-.128-.132-.213-.036-.313l.047-.05c1.096-1.24 1.633-2.692 1.593-4.347-.094-3.881-3.779-6.752-7.567-5.878-.412.095-.455-.046-.454-.375.003-1.223.46-2.212 1.515-2.864.702-.434 1.412-.855 2.121-1.276.546-.325 1.093-.65 1.635-.98.242-.147.407-.043.594.077 1.316.85 2.633 1.698 3.953 2.544.023.015.047.035.07.056.074.064.152.13.242.06.048-.038.045-.149.042-.245v-.06l-.001-1.11c-.001-.832-.002-1.664.007-2.496.003-.238-.084-.367-.277-.486a27.92 27.92 0 0 1-.686-.439c-.31-.203-.62-.406-.94-.591-.294-.171-.379-.357-.373-.704.02-1.33.018-2.658.015-3.987l-.002-1.38c0-1.369.605-2.373 1.802-3.03.214-.117.316-.155.415.154.872 2.712 3.267 4.43 6.083 4.39 2.704-.04 4.994-1.788 5.796-4.475.006-.037.07-.18.19-.116 1.28.583 1.932 1.603 1.944 3.06.004.634.002 1.269 0 1.903-.004 1.114-.008 2.228.02 3.34.014.486-.14.73-.534.948-.303.169-.593.36-.883.552-.2.132-.4.264-.605.39-.183.11-.261.23-.259.454.013 1.229.013 2.458.001 3.687-.002.313.083.318.315.167.504-.328 1.01-.654 1.517-.98.823-.53 1.647-1.06 2.463-1.599.29-.19.503-.206.805-.02.835.516 1.68 1.017 2.526 1.518l.986.586c1.135.678 1.66 1.688 1.645 2.999-.003.294-.043.424-.416.336a6.195 6.195 0 0 0-6.717 2.845c-1.394 2.343-1.136 5.291.664 7.308.242.27.184.367-.09.514-1.175.63-2.339.611-3.494-.125a50.474 50.474 0 0 0-.584-.367 5.491 5.491 0 0 1-1.764 3.217l.448.27a7.656 7.656 0 0 0 2.488.977c.129.028.521.112 1.09.151h-.897l.91.001h-.013.282-.063 1.092-1.029c.6-.015 1.562-.221 1.963-.355a7.043 7.043 0 0 0 3.07-2.008c.153-.171.292-.219.523-.192 3.138.362 5.954-1.576 6.774-4.643.023-.082.172-.658.172-1.644 0 0-.03-.671-.1-1.128-.156-1.029-.579-1.675-.647-1.753-.515-.585-.454-1.155-.264-1.877.8-3.044-.481-6.042-3.18-7.656l-.291-.175a325.91 325.91 0 0 0-3.934-2.328c-.276-.16-.36-.333-.357-.643.015-1.368.012-2.736.008-4.104v-.566c-.007-3.409-2.21-6.13-5.546-6.846-.246-.053-.382-.153-.496-.392C25.19 1.634 23.59.397 21.314.072c-.513-.09-.921-.07-.921-.07a5.768 5.768 0 0 0-1.202.14c-2.037.471-3.487 1.662-4.371 3.546-.104.22-.22.317-.462.367-3.462.719-5.658 3.427-5.66 6.959L8.696 12.1c-.002 1.194-.004 2.389.01 3.583.003.303-.1.456-.353.604-1.414.823-2.82 1.662-4.225 2.502a6.884 6.884 0 0 0-2.882 8.387c.05.124.097.239-.018.388-.584.758-.945 1.62-1.125 2.56v.002c-.156.737-.088 1.51-.088 1.51.028.637.15 1.086.17 1.16v.006c.827 3.085 3.635 5.003 6.823 4.643.18-.02.3-.006.426.136 1.093 1.234 2.438 2.031 4.069 2.312l.015.003c.064.013.3.06.484.077ZM6.147 28.36C7.76 28.314 9.08 29.57 9.133 31.2c.053 1.585-1.235 2.928-2.855 2.98-1.579.05-2.92-1.245-2.966-2.867-.045-1.573 1.24-2.913 2.835-2.956Zm31.534 2.887c-.001 1.611-1.317 2.942-2.9 2.93-1.645-.012-2.954-1.332-2.924-2.948.029-1.602 1.33-2.883 2.919-2.872 1.6.01 2.905 1.31 2.905 2.89ZM17.59 6.139a2.898 2.898 0 0 1 2.966-2.813c1.606.043 2.883 1.36 2.846 2.935-.04 1.635-1.343 2.907-2.948 2.877-1.638-.031-2.924-1.378-2.865-2.999Z"
                    clip-rule="evenodd"></path>
                  <path
                    d="m20.328 28 .038-2.284H16.84L16 24.342 19.737 16h3.738l-.058 7.374H25l-.515 2.342h-1.03l.02 2.284h-3.147Zm-1.067-4.626h1.258v-3.851l-1.258 3.851ZM20.446 38.995c-.151 0-.308-.005-.464 0-.063 0-.083-.015-.083-.077.005-.295 0-.597.005-.892 0-.067-.021-.077-.084-.082-.599-.04-1.177-.158-1.725-.408-.083-.041-.114-.077-.083-.174.104-.367.198-.734.292-1.097.015-.06.031-.076.094-.04.599.3 1.235.464 1.912.443.199-.005.391-.04.574-.122.443-.21.52-.714.156-1.04-.198-.18-.438-.282-.677-.373-.334-.123-.673-.225-.996-.378-.339-.163-.662-.352-.907-.638-.563-.642-.537-1.668.052-2.321.37-.403.845-.617 1.371-.73.063-.01.078-.035.078-.091-.005-.296 0-.597-.005-.893 0-.062.02-.082.083-.082.318.005.636.005.954 0 .063 0 .078.02.073.076-.005.27 0 .541 0 .812 0 .061.01.086.084.086.505.031 1 .113 1.464.322.063.025.078.056.058.117-.1.363-.199.72-.287 1.082-.021.082-.047.076-.11.046a3.624 3.624 0 0 0-1.13-.316c-.308-.031-.616-.052-.913.045-.234.077-.412.21-.453.475a.448.448 0 0 0 .135.403c.141.148.318.235.495.321.329.159.673.27 1.012.409.385.153.745.341 1.047.627.438.418.584.939.516 1.51-.099.924-.688 1.46-1.548 1.75-.12.041-.24.072-.365.092-.052.005-.067.026-.067.082.005.331 0 .658.005.984 0 .062-.016.077-.078.077-.157-.01-.318-.005-.485-.005Z">
                  </path>
                </g>
                <defs>
                  <clipPath id="mt4r9310">
                    <path d="M0 0h40.976v40H0z"></path>
                  </clipPath>
                </defs>
              </svg>
              <div class="text-sm">
                <p>Forex MT4</p>
                <p>Demo Account</p>
              </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg version="1.1" class="svg-icon mt4-real-account" xmlns="http://www.w3.org/2000/svg" x="0" y="0"
                width="24" height="24" fill="#8ea5c0" viewBox="0 0 40 41" xml:space="preserve">
                <g clip-path="url(#mt4r9310)">
                  <path fill-rule="evenodd"
                    d="M12.002 39.973c.216.02.444.026.444.026s.434-.005.725-.034a5.459 5.459 0 0 0 .684-.112 7.657 7.657 0 0 0 2.573-1.012l.403-.243a5.49 5.49 0 0 1-1.754-3.173c-.309.178-.62.354-.932.526-1.101.603-2.229.481-3.304-.162-.214-.128-.132-.213-.036-.313l.047-.05c1.096-1.24 1.633-2.692 1.593-4.347-.094-3.881-3.779-6.752-7.567-5.878-.412.095-.455-.046-.454-.375.003-1.223.46-2.212 1.515-2.864.702-.434 1.412-.855 2.121-1.276.546-.325 1.093-.65 1.635-.98.242-.147.407-.043.594.077 1.316.85 2.633 1.698 3.953 2.544.023.015.047.035.07.056.074.064.152.13.242.06.048-.038.045-.149.042-.245v-.06l-.001-1.11c-.001-.832-.002-1.664.007-2.496.003-.238-.084-.367-.277-.486a27.92 27.92 0 0 1-.686-.439c-.31-.203-.62-.406-.94-.591-.294-.171-.379-.357-.373-.704.02-1.33.018-2.658.015-3.987l-.002-1.38c0-1.369.605-2.373 1.802-3.03.214-.117.316-.155.415.154.872 2.712 3.267 4.43 6.083 4.39 2.704-.04 4.994-1.788 5.796-4.475.006-.037.07-.18.19-.116 1.28.583 1.932 1.603 1.944 3.06.004.634.002 1.269 0 1.903-.004 1.114-.008 2.228.02 3.34.014.486-.14.73-.534.948-.303.169-.593.36-.883.552-.2.132-.4.264-.605.39-.183.11-.261.23-.259.454.013 1.229.013 2.458.001 3.687-.002.313.083.318.315.167.504-.328 1.01-.654 1.517-.98.823-.53 1.647-1.06 2.463-1.599.29-.19.503-.206.805-.02.835.516 1.68 1.017 2.526 1.518l.986.586c1.135.678 1.66 1.688 1.645 2.999-.003.294-.043.424-.416.336a6.195 6.195 0 0 0-6.717 2.845c-1.394 2.343-1.136 5.291.664 7.308.242.27.184.367-.09.514-1.175.63-2.339.611-3.494-.125a50.474 50.474 0 0 0-.584-.367 5.491 5.491 0 0 1-1.764 3.217l.448.27a7.656 7.656 0 0 0 2.488.977c.129.028.521.112 1.09.151h-.897l.91.001h-.013.282-.063 1.092-1.029c.6-.015 1.562-.221 1.963-.355a7.043 7.043 0 0 0 3.07-2.008c.153-.171.292-.219.523-.192 3.138.362 5.954-1.576 6.774-4.643.023-.082.172-.658.172-1.644 0 0-.03-.671-.1-1.128-.156-1.029-.579-1.675-.647-1.753-.515-.585-.454-1.155-.264-1.877.8-3.044-.481-6.042-3.18-7.656l-.291-.175a325.91 325.91 0 0 0-3.934-2.328c-.276-.16-.36-.333-.357-.643.015-1.368.012-2.736.008-4.104v-.566c-.007-3.409-2.21-6.13-5.546-6.846-.246-.053-.382-.153-.496-.392C25.19 1.634 23.59.397 21.314.072c-.513-.09-.921-.07-.921-.07a5.768 5.768 0 0 0-1.202.14c-2.037.471-3.487 1.662-4.371 3.546-.104.22-.22.317-.462.367-3.462.719-5.658 3.427-5.66 6.959L8.696 12.1c-.002 1.194-.004 2.389.01 3.583.003.303-.1.456-.353.604-1.414.823-2.82 1.662-4.225 2.502a6.884 6.884 0 0 0-2.882 8.387c.05.124.097.239-.018.388-.584.758-.945 1.62-1.125 2.56v.002c-.156.737-.088 1.51-.088 1.51.028.637.15 1.086.17 1.16v.006c.827 3.085 3.635 5.003 6.823 4.643.18-.02.3-.006.426.136 1.093 1.234 2.438 2.031 4.069 2.312l.015.003c.064.013.3.06.484.077ZM6.147 28.36C7.76 28.314 9.08 29.57 9.133 31.2c.053 1.585-1.235 2.928-2.855 2.98-1.579.05-2.92-1.245-2.966-2.867-.045-1.573 1.24-2.913 2.835-2.956Zm31.534 2.887c-.001 1.611-1.317 2.942-2.9 2.93-1.645-.012-2.954-1.332-2.924-2.948.029-1.602 1.33-2.883 2.919-2.872 1.6.01 2.905 1.31 2.905 2.89ZM17.59 6.139a2.898 2.898 0 0 1 2.966-2.813c1.606.043 2.883 1.36 2.846 2.935-.04 1.635-1.343 2.907-2.948 2.877-1.638-.031-2.924-1.378-2.865-2.999Z"
                    clip-rule="evenodd"></path>
                  <path
                    d="m20.328 28 .038-2.284H16.84L16 24.342 19.737 16h3.738l-.058 7.374H25l-.515 2.342h-1.03l.02 2.284h-3.147Zm-1.067-4.626h1.258v-3.851l-1.258 3.851ZM20.446 38.995c-.151 0-.308-.005-.464 0-.063 0-.083-.015-.083-.077.005-.295 0-.597.005-.892 0-.067-.021-.077-.084-.082-.599-.04-1.177-.158-1.725-.408-.083-.041-.114-.077-.083-.174.104-.367.198-.734.292-1.097.015-.06.031-.076.094-.04.599.3 1.235.464 1.912.443.199-.005.391-.04.574-.122.443-.21.52-.714.156-1.04-.198-.18-.438-.282-.677-.373-.334-.123-.673-.225-.996-.378-.339-.163-.662-.352-.907-.638-.563-.642-.537-1.668.052-2.321.37-.403.845-.617 1.371-.73.063-.01.078-.035.078-.091-.005-.296 0-.597-.005-.893 0-.062.02-.082.083-.082.318.005.636.005.954 0 .063 0 .078.02.073.076-.005.27 0 .541 0 .812 0 .061.01.086.084.086.505.031 1 .113 1.464.322.063.025.078.056.058.117-.1.363-.199.72-.287 1.082-.021.082-.047.076-.11.046a3.624 3.624 0 0 0-1.13-.316c-.308-.031-.616-.052-.913.045-.234.077-.412.21-.453.475a.448.448 0 0 0 .135.403c.141.148.318.235.495.321.329.159.673.27 1.012.409.385.153.745.341 1.047.627.438.418.584.939.516 1.51-.099.924-.688 1.46-1.548 1.75-.12.041-.24.072-.365.092-.052.005-.067.026-.067.082.005.331 0 .658.005.984 0 .062-.016.077-.078.077-.157-.01-.318-.005-.485-.005Z">
                  </path>
                </g>
                <defs>
                  <clipPath id="mt4r9310">
                    <path d="M0 0h40.976v40H0z"></path>
                  </clipPath>
                </defs>
              </svg>
              <div class="text-sm">
                <p>Forex MT5</p>
                <p>Real Account</p>
              </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg version="1.1" class="svg-icon mt4-real-account" xmlns="http://www.w3.org/2000/svg" x="0" y="0"
                width="24" height="24" fill="#8ea5c0" viewBox="0 0 40 41" xml:space="preserve">
                <g clip-path="url(#mt4r9310)">
                  <path fill-rule="evenodd"
                    d="M12.002 39.973c.216.02.444.026.444.026s.434-.005.725-.034a5.459 5.459 0 0 0 .684-.112 7.657 7.657 0 0 0 2.573-1.012l.403-.243a5.49 5.49 0 0 1-1.754-3.173c-.309.178-.62.354-.932.526-1.101.603-2.229.481-3.304-.162-.214-.128-.132-.213-.036-.313l.047-.05c1.096-1.24 1.633-2.692 1.593-4.347-.094-3.881-3.779-6.752-7.567-5.878-.412.095-.455-.046-.454-.375.003-1.223.46-2.212 1.515-2.864.702-.434 1.412-.855 2.121-1.276.546-.325 1.093-.65 1.635-.98.242-.147.407-.043.594.077 1.316.85 2.633 1.698 3.953 2.544.023.015.047.035.07.056.074.064.152.13.242.06.048-.038.045-.149.042-.245v-.06l-.001-1.11c-.001-.832-.002-1.664.007-2.496.003-.238-.084-.367-.277-.486a27.92 27.92 0 0 1-.686-.439c-.31-.203-.62-.406-.94-.591-.294-.171-.379-.357-.373-.704.02-1.33.018-2.658.015-3.987l-.002-1.38c0-1.369.605-2.373 1.802-3.03.214-.117.316-.155.415.154.872 2.712 3.267 4.43 6.083 4.39 2.704-.04 4.994-1.788 5.796-4.475.006-.037.07-.18.19-.116 1.28.583 1.932 1.603 1.944 3.06.004.634.002 1.269 0 1.903-.004 1.114-.008 2.228.02 3.34.014.486-.14.73-.534.948-.303.169-.593.36-.883.552-.2.132-.4.264-.605.39-.183.11-.261.23-.259.454.013 1.229.013 2.458.001 3.687-.002.313.083.318.315.167.504-.328 1.01-.654 1.517-.98.823-.53 1.647-1.06 2.463-1.599.29-.19.503-.206.805-.02.835.516 1.68 1.017 2.526 1.518l.986.586c1.135.678 1.66 1.688 1.645 2.999-.003.294-.043.424-.416.336a6.195 6.195 0 0 0-6.717 2.845c-1.394 2.343-1.136 5.291.664 7.308.242.27.184.367-.09.514-1.175.63-2.339.611-3.494-.125a50.474 50.474 0 0 0-.584-.367 5.491 5.491 0 0 1-1.764 3.217l.448.27a7.656 7.656 0 0 0 2.488.977c.129.028.521.112 1.09.151h-.897l.91.001h-.013.282-.063 1.092-1.029c.6-.015 1.562-.221 1.963-.355a7.043 7.043 0 0 0 3.07-2.008c.153-.171.292-.219.523-.192 3.138.362 5.954-1.576 6.774-4.643.023-.082.172-.658.172-1.644 0 0-.03-.671-.1-1.128-.156-1.029-.579-1.675-.647-1.753-.515-.585-.454-1.155-.264-1.877.8-3.044-.481-6.042-3.18-7.656l-.291-.175a325.91 325.91 0 0 0-3.934-2.328c-.276-.16-.36-.333-.357-.643.015-1.368.012-2.736.008-4.104v-.566c-.007-3.409-2.21-6.13-5.546-6.846-.246-.053-.382-.153-.496-.392C25.19 1.634 23.59.397 21.314.072c-.513-.09-.921-.07-.921-.07a5.768 5.768 0 0 0-1.202.14c-2.037.471-3.487 1.662-4.371 3.546-.104.22-.22.317-.462.367-3.462.719-5.658 3.427-5.66 6.959L8.696 12.1c-.002 1.194-.004 2.389.01 3.583.003.303-.1.456-.353.604-1.414.823-2.82 1.662-4.225 2.502a6.884 6.884 0 0 0-2.882 8.387c.05.124.097.239-.018.388-.584.758-.945 1.62-1.125 2.56v.002c-.156.737-.088 1.51-.088 1.51.028.637.15 1.086.17 1.16v.006c.827 3.085 3.635 5.003 6.823 4.643.18-.02.3-.006.426.136 1.093 1.234 2.438 2.031 4.069 2.312l.015.003c.064.013.3.06.484.077ZM6.147 28.36C7.76 28.314 9.08 29.57 9.133 31.2c.053 1.585-1.235 2.928-2.855 2.98-1.579.05-2.92-1.245-2.966-2.867-.045-1.573 1.24-2.913 2.835-2.956Zm31.534 2.887c-.001 1.611-1.317 2.942-2.9 2.93-1.645-.012-2.954-1.332-2.924-2.948.029-1.602 1.33-2.883 2.919-2.872 1.6.01 2.905 1.31 2.905 2.89ZM17.59 6.139a2.898 2.898 0 0 1 2.966-2.813c1.606.043 2.883 1.36 2.846 2.935-.04 1.635-1.343 2.907-2.948 2.877-1.638-.031-2.924-1.378-2.865-2.999Z"
                    clip-rule="evenodd"></path>
                  <path
                    d="m20.328 28 .038-2.284H16.84L16 24.342 19.737 16h3.738l-.058 7.374H25l-.515 2.342h-1.03l.02 2.284h-3.147Zm-1.067-4.626h1.258v-3.851l-1.258 3.851ZM20.446 38.995c-.151 0-.308-.005-.464 0-.063 0-.083-.015-.083-.077.005-.295 0-.597.005-.892 0-.067-.021-.077-.084-.082-.599-.04-1.177-.158-1.725-.408-.083-.041-.114-.077-.083-.174.104-.367.198-.734.292-1.097.015-.06.031-.076.094-.04.599.3 1.235.464 1.912.443.199-.005.391-.04.574-.122.443-.21.52-.714.156-1.04-.198-.18-.438-.282-.677-.373-.334-.123-.673-.225-.996-.378-.339-.163-.662-.352-.907-.638-.563-.642-.537-1.668.052-2.321.37-.403.845-.617 1.371-.73.063-.01.078-.035.078-.091-.005-.296 0-.597-.005-.893 0-.062.02-.082.083-.082.318.005.636.005.954 0 .063 0 .078.02.073.076-.005.27 0 .541 0 .812 0 .061.01.086.084.086.505.031 1 .113 1.464.322.063.025.078.056.058.117-.1.363-.199.72-.287 1.082-.021.082-.047.076-.11.046a3.624 3.624 0 0 0-1.13-.316c-.308-.031-.616-.052-.913.045-.234.077-.412.21-.453.475a.448.448 0 0 0 .135.403c.141.148.318.235.495.321.329.159.673.27 1.012.409.385.153.745.341 1.047.627.438.418.584.939.516 1.51-.099.924-.688 1.46-1.548 1.75-.12.041-.24.072-.365.092-.052.005-.067.026-.067.082.005.331 0 .658.005.984 0 .062-.016.077-.078.077-.157-.01-.318-.005-.485-.005Z">
                  </path>
                </g>
                <defs>
                  <clipPath id="mt4r9310">
                    <path d="M0 0h40.976v40H0z"></path>
                  </clipPath>
                </defs>
              </svg>
              <div class="text-sm">
                <p>Forex MT4</p>
                <p>Demo Account</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Finance sub left nav -->
        <div id="financeContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="mt-5 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon deposit-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.11 465.01">
                <path
                  d="M469.1 0c19.29 0 30.96 12.5 31 31v86.27c0 6.97-1.72 13.84-5.06 19.95-.16.29-.29.52-.38.66-8.89 14.2-25.56 21.04-41.37 19.39-7-1.36-8.19-2.78-8.19-9.88V60.91c0-8.94-4.35-11.87-14.31-11.8-172.34 1.32-189.84 1.55-348.85.05-10.35-.1-14.83 2.65-14.83 11.78 0 29 0 57.99-.01 86.98 0 5.95-1.66 8.01-7.23 9.18-24.75 5.2-47.64-13.28-47.74-38.68-.1-26.33-.03-52.65-.02-78.98 0-17.21 10.61-32.83 24.55-37.5C38.44 1.37 39.88.72 45.1 0h424zM143.1 465c-.4.01-.5-.02-.78-.03-19.93-2.55-40.07-23.15-38.27-49.49.25-3.61.89-5.08 5.04-3.75 4.92 1.58 8.05 5.29 11.41 8.55 6.68 6.48 14.4 8.82 23.53 8.8 69.65-.13 139.31-.12 208.96-.03 14.15.02 26 10.56 28.95 24.14.67 3.06-3.24 1.95-4.94 4.83-.89 1.5-107.42 6.87-109.17 6.87L143.1 465zM6.78 225.19c1.05-1.67 2.1-3.18 3.57-4.82 8.08-9.07 16.31-18.07 23.61-27.75 7.48-8.71 18.59-9.37 26.31.08 6.73 9.06 14.43 17.43 21.95 25.88 4.17 4.69 12.36 12.03 9.91 18.44-2.29 5.78-12.46 5.13-18.42 5.48-4.77.28-6.78 2.66-6.71 7.65.23 15.15.02 70.5 0 85.65-.01 11.81-8.6 19.85-20.15 19.89-11.57.04-19.6-7.46-19.63-19.22-.03-15.27-.22-71.26-.08-86.53.04-3.82-2.88-7.12-6.7-7.3-5.47-.26-12.22.72-16.55-5.62-2-5 .71-8.32 2.89-11.83z">
                </path>
                <path
                  d="M104.11 100.08c.01-7.22 2.78-10.02 9.88-10.07 4.33-.04 8.66-.01 12.99-.01 10.14 0 12.12 2.02 12.12 12.34 0 52.48.07 104.95-.14 157.43-.01 3.92 1.13 5.47 4.61 6.08 12.54 2.19 23.05 8.69 31.84 17.29 8.69 8.5 15.1 18.93 17.61 31.16.86 4.18 2.12 5.91 7.03 5.86 31.99-.32 63.98.07 95.96-.27 14.65-.15 27.12 9.88 30.84 23.31 2.48 8.96-.41 12.8-9.58 12.8-55.81 0-111.62.01-167.43 0-24.92 0-45.73-20.71-45.74-45.51-.01-34.49 0-68.97 0-103.46 0-35.65-.01-71.3.01-106.95z">
                </path>
                <path
                  d="M216.02 200.55c-25.48-18.18-29.25-50.37-8.58-72.61 24.6-26.47 72.52-26.53 97.22-.11 18.06 19.32 17.98 46.76-.45 65.75-13.02 13.42-29.41 18.97-46.76 19.48-15.75-.15-29.37-3.9-41.43-12.51zM151.12 410.19c-23.53.53-49.36-19.98-47.05-50.44.26-3.43 1.27-4.04 4.76-3.06 5.21 1.47 8.35 5.41 11.84 8.75 6.59 6.29 14.15 8.65 23.1 8.63 59.98-.13 119.97.02 179.95-.13 13.96-.04 26.49 9.7 30.18 23.47 2.39 8.95-.51 12.59-9.84 12.59h-97.47c-31.83 0-63.67-.54-95.47.19z">
                </path>
                <path
                  d="M407.7 207.03c0 34.49.01 68.97 0 103.46-.01 24.8-20.82 45.51-45.74 45.51-55.81.01-111.62 0-167.43 0-9.17 0-12.06-3.84-9.58-12.8 3.72-13.43 16.19-23.46 30.84-23.31 31.98.34 63.97-.05 95.96.27 4.91.05 6.17-1.68 7.03-5.86 2.51-12.23 8.92-22.66 17.61-31.16 8.79-8.6 19.3-15.1 31.84-17.29 3.48-.61 4.62-2.16 4.61-6.08-.21-52.48-.14-104.95-.14-157.43 0-10.32 1.98-12.34 12.12-12.34 4.33 0 8.66-.03 12.99.01 7.1.05 9.87 2.85 9.88 10.07.02 35.65.01 71.3.01 106.95zM265.04 409.96h-97.47c-9.33 0-12.23-3.64-9.84-12.59 3.69-13.77 16.22-23.51 30.18-23.47 59.98.15 119.97 0 179.95.13 8.95.02 16.51-2.34 23.1-8.63 3.49-3.34 6.63-7.28 11.84-8.75 3.49-.98 4.5-.37 4.76 3.06 2.31 30.46-23.52 50.97-47.05 50.44-31.8-.73-63.64-.19-95.47-.19zM265.04 464.89h-97.47c-9.33 0-12.23-3.64-9.84-12.59 3.69-13.77 15.94-23.29 29.9-23.25 59.98.15 120.25-.22 180.23-.09 8.95.02 16.51-2.34 23.1-8.63 3.49-3.34 6.63-7.28 11.84-8.75 3.49-.98 4.5-.37 4.76 3.06 2.31 30.46-23.52 50.97-47.05 50.44-33.43-.07-63.64-.19-95.47-.19z">
                </path>
              </svg>

              <div class="text-sm">
                <p>Deposit</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon deposit-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.11 465.01">
                <path
                  d="M469.1 0c19.29 0 30.96 12.5 31 31v86.27c0 6.97-1.72 13.84-5.06 19.95-.16.29-.29.52-.38.66-8.89 14.2-25.56 21.04-41.37 19.39-7-1.36-8.19-2.78-8.19-9.88V60.91c0-8.94-4.35-11.87-14.31-11.8-172.34 1.32-189.84 1.55-348.85.05-10.35-.1-14.83 2.65-14.83 11.78 0 29 0 57.99-.01 86.98 0 5.95-1.66 8.01-7.23 9.18-24.75 5.2-47.64-13.28-47.74-38.68-.1-26.33-.03-52.65-.02-78.98 0-17.21 10.61-32.83 24.55-37.5C38.44 1.37 39.88.72 45.1 0h424zM143.1 465c-.4.01-.5-.02-.78-.03-19.93-2.55-40.07-23.15-38.27-49.49.25-3.61.89-5.08 5.04-3.75 4.92 1.58 8.05 5.29 11.41 8.55 6.68 6.48 14.4 8.82 23.53 8.8 69.65-.13 139.31-.12 208.96-.03 14.15.02 26 10.56 28.95 24.14.67 3.06-3.24 1.95-4.94 4.83-.89 1.5-107.42 6.87-109.17 6.87L143.1 465zM6.78 225.19c1.05-1.67 2.1-3.18 3.57-4.82 8.08-9.07 16.31-18.07 23.61-27.75 7.48-8.71 18.59-9.37 26.31.08 6.73 9.06 14.43 17.43 21.95 25.88 4.17 4.69 12.36 12.03 9.91 18.44-2.29 5.78-12.46 5.13-18.42 5.48-4.77.28-6.78 2.66-6.71 7.65.23 15.15.02 70.5 0 85.65-.01 11.81-8.6 19.85-20.15 19.89-11.57.04-19.6-7.46-19.63-19.22-.03-15.27-.22-71.26-.08-86.53.04-3.82-2.88-7.12-6.7-7.3-5.47-.26-12.22.72-16.55-5.62-2-5 .71-8.32 2.89-11.83z">
                </path>
                <path
                  d="M104.11 100.08c.01-7.22 2.78-10.02 9.88-10.07 4.33-.04 8.66-.01 12.99-.01 10.14 0 12.12 2.02 12.12 12.34 0 52.48.07 104.95-.14 157.43-.01 3.92 1.13 5.47 4.61 6.08 12.54 2.19 23.05 8.69 31.84 17.29 8.69 8.5 15.1 18.93 17.61 31.16.86 4.18 2.12 5.91 7.03 5.86 31.99-.32 63.98.07 95.96-.27 14.65-.15 27.12 9.88 30.84 23.31 2.48 8.96-.41 12.8-9.58 12.8-55.81 0-111.62.01-167.43 0-24.92 0-45.73-20.71-45.74-45.51-.01-34.49 0-68.97 0-103.46 0-35.65-.01-71.3.01-106.95z">
                </path>
                <path
                  d="M216.02 200.55c-25.48-18.18-29.25-50.37-8.58-72.61 24.6-26.47 72.52-26.53 97.22-.11 18.06 19.32 17.98 46.76-.45 65.75-13.02 13.42-29.41 18.97-46.76 19.48-15.75-.15-29.37-3.9-41.43-12.51zM151.12 410.19c-23.53.53-49.36-19.98-47.05-50.44.26-3.43 1.27-4.04 4.76-3.06 5.21 1.47 8.35 5.41 11.84 8.75 6.59 6.29 14.15 8.65 23.1 8.63 59.98-.13 119.97.02 179.95-.13 13.96-.04 26.49 9.7 30.18 23.47 2.39 8.95-.51 12.59-9.84 12.59h-97.47c-31.83 0-63.67-.54-95.47.19z">
                </path>
                <path
                  d="M407.7 207.03c0 34.49.01 68.97 0 103.46-.01 24.8-20.82 45.51-45.74 45.51-55.81.01-111.62 0-167.43 0-9.17 0-12.06-3.84-9.58-12.8 3.72-13.43 16.19-23.46 30.84-23.31 31.98.34 63.97-.05 95.96.27 4.91.05 6.17-1.68 7.03-5.86 2.51-12.23 8.92-22.66 17.61-31.16 8.79-8.6 19.3-15.1 31.84-17.29 3.48-.61 4.62-2.16 4.61-6.08-.21-52.48-.14-104.95-.14-157.43 0-10.32 1.98-12.34 12.12-12.34 4.33 0 8.66-.03 12.99.01 7.1.05 9.87 2.85 9.88 10.07.02 35.65.01 71.3.01 106.95zM265.04 409.96h-97.47c-9.33 0-12.23-3.64-9.84-12.59 3.69-13.77 16.22-23.51 30.18-23.47 59.98.15 119.97 0 179.95.13 8.95.02 16.51-2.34 23.1-8.63 3.49-3.34 6.63-7.28 11.84-8.75 3.49-.98 4.5-.37 4.76 3.06 2.31 30.46-23.52 50.97-47.05 50.44-31.8-.73-63.64-.19-95.47-.19zM265.04 464.89h-97.47c-9.33 0-12.23-3.64-9.84-12.59 3.69-13.77 15.94-23.29 29.9-23.25 59.98.15 120.25-.22 180.23-.09 8.95.02 16.51-2.34 23.1-8.63 3.49-3.34 6.63-7.28 11.84-8.75 3.49-.98 4.5-.37 4.76 3.06 2.31 30.46-23.52 50.97-47.05 50.44-33.43-.07-63.64-.19-95.47-.19z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Withdraw</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon history-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 492.1 489">
                <path
                  d="M205.41 485.29C128.99 470.64 71.64 429.31 33.29 361.8c-18.48-32.54-27.6-67.94-29.96-105.2-.28-4.46.67-5.9 5.4-5.73 11.15.39 22.33.27 33.49.04 3.54-.07 4.75.63 4.95 4.51 2.68 51.5 22.83 95.46 59.06 131.75 64.59 64.69 166.37 77.43 243.89 30.01 53.25-32.58 86.61-79.75 95.97-142.09 9.89-65.9-9.07-123.38-55.05-171.29-63.61-66.27-166.93-79.44-245.68-32.25-21.79 13.06-40.63 29.47-56.31 49.52-2.37 3.03-2.26 4.62.49 7.18 16.45 15.34 32.72 30.87 49.09 46.3 2.19 2.06 2.76 2.53 4.94 4.41 1.45 1.25 1.64 4.74-1.86 4.98 0 0-88.55-.06-135.04.04C1.94 184 0 182.5 0 177.83c0-41.86.08-117.97.09-126.3 0-.82-.02-1.86.18-2.37.54-1.38 2.45-2.38 4.63-.43 7.74 6.92 11.74 10.9 18.69 17.42 8.75 8.21 17.5 16.42 26.17 24.71 3.05 2.92 4.86 3.73 8.3-.43C97.95 42.1 148.85 12.78 210.83 2.78 216.28 1.91 231.27 0 250.19 0c17.5 0 31.42 2.26 37.8 3.38 58.61 10.27 107.73 37.66 146.08 83.18 45.21 53.66 63.69 116 56.52 185.63-4.81 46.69-22.67 88.51-51.96 125.03-39.59 49.35-91.22 78.54-153.59 88.92-6.28 1.05-17.36 2.86-29.86 2.86-13.05 0-32.22-.67-49.77-3.71z">
                </path>
                <path
                  d="M291.18 216.49c0 31-.07 61.99.09 92.99.02 3.71-.83 4.63-4.59 4.61-45.99-.15-91.99-.15-137.98 0-3.7.01-4.67-.81-4.62-4.58.22-18.49.18-36.99.02-55.49-.03-3.25.8-4.11 4.08-4.09 24.83.14 49.66-.01 74.49.18 3.73.03 4.62-.85 4.61-4.6-.15-40.49-.03-80.99-.2-121.48-.02-4.14.93-5.19 5.1-5.13 18.33.26 36.66.2 54.99.03 3.26-.03 4.1.82 4.09 4.08-.13 31.16-.08 62.32-.08 93.48z">
                </path>
              </svg>
              <div class="text-sm">
                <p>History</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon cashback-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 499.93">
                <path
                  d="M247.49 499.93c-8.2 0-16.4-.46-24.5-1.71-3.41-.53-6.91-.55-10.32-1.1-61.1-9.77-111.91-37.97-152.49-84.65-1.09-1.26-2.24-2.47-3.29-3.76-5.16-6.33-5.15-6.34-11.28-.63-12.25 11.42-22.46 20.57-34.57 32.22-2.7 2.6-5.07 1.74-5.07-2.15 0 0 .03-73.05.02-111.05 0-2.35-.46-4.29 3.35-4.28 41.82.16 78.98.1 120.8.12 2.25-.06 4.17 2.57 1.72 4.79-15.26 14.17-29.1 27.17-44.42 41.28-2.27 2.09-2.65 3.34-.53 5.97 44.08 54.67 100.94 82.3 171.37 80.08 54.63-1.72 101.16-23.17 139.06-62.15 37.54-38.6 56.96-85.33 57.99-139.38.08-4.3.94-5.88 5.67-5.74 11.76.35 23.54.17 35.31.14 2.03-.01 3.67 1.64 3.67 3.67l-.28 9.77c-.26 5.51-.94 13.21-1 13.67-.72 7.39-1.48 11.55-2.59 18.11-17.83 105.34-102.86 188.15-209.28 204.03-4.25.63-21.8 2.75-26.35 2.75h-12.99zM253.5.01c12.02.31 15.11.23 21.75 1.01 4.2.49 6.91.95 11.09 1.59 59.1 9.09 108.74 35.84 149.19 79.8 3.43 3.73 6.59 7.7 9.72 11.69 2.11 2.7 3.6 3.09 6.66.14 13.56-13.05 23.22-21.81 36.99-34.64 2.83-3.01 5.14-1.89 5.13 1.22.04 37.99-.03 73.96-.01 111.95 0 2.37.42 4.28-3.36 4.26-41.82-.16-78.72-.1-120.54-.12-4.51.23-3.44-3.48-1.89-4.86 15.26-14.17 29.02-27.1 44.34-41.21 2.27-2.09 2.63-3.35.51-5.97C369 70.19 312.11 42.24 241.71 44.85c-91.48 3.39-168.17 66.1-190.98 154.56-4.05 15.72-6.06 31.7-6.11 47.91-.01 3.5-.67 4.83-4.64 4.73-12.31-.29-24.63-.15-36.94-.12-1.68 0-3.04-1.36-3.04-3.04v-2.68c0-10.05.74-20.09 2.22-30.04l.02-.13c7.15-48.4 25.91-91.61 58.03-128.7 40.47-46.74 91.36-74.83 152.42-84.61 4.04-.65 5.29-.93 10.48-1.67 2.97-.42 12.5-1.03 15.5-1.07l14.83.02z">
                </path>
                <path
                  d="M211.49 279.93c7.33 0 14.67.15 21.99-.07 3.01-.09 3.92.62 3.92 3.79 0 9.99.29 19.99.73 29.97.2 4.59 1.07 9.16 6.83 10.18 5.24.93 9.47-1.76 11.1-6.98 5.53-17.79-1.28-36.98-16.66-47.08-9.45-6.21-19.03-12.25-28.25-18.79-22.23-15.76-31.92-44.89-23.74-70.94 5.81-18.51 19.88-27.47 37.65-31.99 1.29-.33 2.59-.62 3.89-.89 8.27-1.67 8.27-.96 8.27-9.13 0-1.17.01-2.5-.1-4.83-.19-3.81.71-4.31 4.84-4.35 5.59-.06 10.48-.06 15.99.03 2.71.05 3.34.81 3.27 3.38-.09 3.18-.06 4.3-.13 8.48.29 4.54 1.51 5.47 5.3 6.14 17.91 3.16 32.3 11.21 38.98 29.34 3.39 9.19 3.97 18.72 3.34 28.41-.13 1.93-1.74 3.42-3.67 3.4-15-.13-30-.15-44.99.01-3.09.03-3.4-1.17-3.42-3.77-.03-5.99-.24-12-.7-17.97-.49-6.42-3.09-9.16-8.21-9.33-5.5-.18-8.67 3.18-9.7 10.25-1.54 10.57 3.32 21.08 12.21 27.01 11.71 7.81 23.56 15.45 35.63 22.69 19.24 11.53 27.76 28.78 28.35 50.64.28 10.55-.29 20.95-3.74 31.05-7.04 20.58-22.49 30.69-42.91 34.73-4.89.97-6.25.38-6.36 7.58.06 10.56.78 12.61-2.85 12.86-2.85.12-7.04.12-11.01.13-11.44.03-10.11.01-10.11-11.16 0-8.24 0-8.24-8.06-10.04-26.15-5.85-41.19-23.59-43.05-51.06-.42-6.14-.34-9.61-.47-18.48-.04-2.68.76-3.36 3.35-3.31 7.49.21 14.99.1 22.49.1z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Cashback</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon promo-codes-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 354">
                <path
                  d="M466.98 354H31.67c-3.39 0-6.76-.58-9.95-1.73-9.78-3.58-16.44-10.17-19.98-20C.59 329.01 0 325.56 0 322.1V219.78c0-2.15 1.74-3.87 3.89-3.89 6.14-.04 12.27.41 18.43-.76 18.93-3.61 32.22-18.9 32.22-37.63 0-18.72-13.31-34.03-32.22-37.63-6.4-1.22-12.77-.69-19.15-.77-1.76-.02-3.17-1.46-3.17-3.22V31.89c0-3.63.64-7.23 1.9-10.64C5.15 12.6 10.98 6.48 19.37 2.6 23.13.89 27.22 0 31.36 0h437.08c14.65 0 21.29 7.25 26.37 14.58.2.29.39.59.58.89C498.56 20.73 500 26.86 500 33v289.43c0 3.68-.69 7.33-2.04 10.75l-.63 1.59c-3.09 7.85-9.39 14.01-17.31 16.91-4.17 1.54-8.59 2.32-13.04 2.32zM250.01 40.43c-68.33 0-136.66.03-204.98-.08-3.61-.01-4.75.67-4.69 4.54.24 17.83.22 35.66.01 53.49-.04 3.52 1.2 4.81 4.32 5.93 10.47 3.78 19.6 9.86 27.46 17.72 40.08 40.07 25.37 108.65-27.66 128.69-3.32 1.25-4.12 2.81-4.09 6.09.16 17.33.21 34.67-.03 51.99-.05 3.96 1.04 4.84 4.9 4.84 136.49-.1 272.98-.1 409.47-.01 3.7 0 4.98-.65 4.97-4.76-.14-87.83-.14-175.65 0-263.48.01-4.25-1.12-5.08-5.18-5.07-68.18.15-136.34.11-204.5.11z">
                </path>
                <path
                  d="M408.14 83.47c.19 3.19-1.24 5.8-3.01 7.9-51.29 60.85-102.56 121.71-153.84 182.57-2.68 3.18-5.29 6.43-8.12 9.48-3.34 3.59-6.98 4.83-11.59 3.73-5.86-1.4-10.09-4.88-12.88-10.16-2.74-5.2-1.76-9.66 2.05-14.15 29.3-34.52 58.48-69.14 87.68-103.75 24.05-28.5 48.07-57.03 72.09-85.56 4.94-5.87 9.09-7.33 15.12-5.21 7.05 2.47 12.03 8.37 12.5 15.15zM313 114.86c.05 26.43-21.38 48.04-47.73 48.14-26.66.1-48.3-21.47-48.27-48.1.04-26.38 21.58-47.89 47.97-47.9 26.41 0 47.97 21.48 48.03 47.86zm-47.54-17.81c-9.73-.25-18.01 7.52-18.4 17.24-.4 9.9 7.57 18.4 17.49 18.66 9.72.25 18.01-7.52 18.4-17.25.39-9.9-7.58-18.4-17.49-18.65zM361.37 192c26.33.14 47.72 21.81 47.62 48.25-.09 26.55-21.91 47.98-48.62 47.74-26.29-.23-47.55-22-47.37-48.49.19-26.31 21.91-47.64 48.37-47.5zm17.58 48.21c.11-9.73-7.77-17.91-17.5-18.15-9.93-.25-18.29 7.81-18.41 17.74-.11 9.72 7.78 17.91 17.5 18.16 9.93.25 18.3-7.83 18.41-17.75zM141.98 102.99c-5.66 0-11.31-.08-16.97.04-2.31.05-3.58-.21-3.52-3.12.19-9.98.16-19.96.01-29.94-.04-2.5.8-3.03 3.13-3.01 11.48.11 22.96.13 34.44-.01 2.52-.03 2.99.86 2.97 3.14-.11 9.98-.13 19.96.01 29.94.03 2.46-.75 3.08-3.1 3.01-5.65-.15-11.31-.05-16.97-.05zM142 164.99c-5.66 0-11.31-.07-16.97.03-2.28.04-3.6-.16-3.54-3.1.19-9.98.16-19.96.02-29.94-.03-2.48.75-3.06 3.11-3.03 11.48.11 22.96.12 34.44-.01 2.49-.03 3.01.82 2.99 3.12-.11 9.98-.13 19.96.01 29.94.03 2.43-.71 3.1-3.08 3.03-5.66-.14-11.32-.04-16.98-.04zM141.5 226.99c-5.49 0-10.98-.07-16.47.03-2.29.04-3.59-.17-3.54-3.1.19-9.98.16-19.96.02-29.94-.04-2.48.76-3.05 3.11-3.03 11.48.11 22.96.12 34.44-.01 2.5-.03 3.01.82 2.99 3.13-.11 9.98-.13 19.96.01 29.94.03 2.44-.72 3.09-3.08 3.03-5.83-.15-11.66-.05-17.48-.05zM141.98 287.99c-5.66 0-11.31-.08-16.97.04-2.33.05-3.57-.23-3.51-3.13.19-9.65.16-19.3.01-28.94-.04-2.52.82-3.02 3.14-3 11.48.11 22.96.12 34.44-.01 2.53-.03 2.99.88 2.96 3.15-.11 9.65-.13 19.3.01 28.95.04 2.47-.78 3.07-3.11 3-5.66-.16-11.32-.06-16.97-.06z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Promo codes</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon my-safe-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="#8ea5c0" viewBox="0 0 24 24" fill="none">
                <g clip-path="url(#clip0_1_355321)">
                  <path
                    d="M23.0831 0C23.0917 0.0561634 23.1394 0.0408024 23.1746 0.0518431C23.655 0.201132 23.9773 0.617317 23.9966 1.11895C24.0029 1.2788 23.9981 1.43913 23.9981 1.59898V19.9102C23.9981 20.7598 23.5418 21.2173 22.6952 21.2173C22.2697 21.2173 21.8438 21.223 21.4183 21.2139C21.2791 21.211 21.2502 21.2547 21.2482 21.3872C21.2603 22.3761 21.0632 22.4653 20.1362 22.4653H17.7955C17.0264 22.4653 16.7354 22.3482 16.7292 21.4266C16.7316 21.2331 16.6651 21.213 16.4993 21.2134C14.7888 21.2192 13.0788 21.2144 11.3683 21.2206C11.1664 21.2216 11.1341 21.1866 11.1356 20.8937C11.1375 20.4291 11.1399 20.0028 11.1375 19.4949C11.1361 19.1589 11.1197 19.0787 11.3621 19.0792C14.7749 19.0855 18.1872 19.0821 21.6 19.0883C21.8043 19.0888 21.8631 19.0504 21.8631 18.833C21.8558 13.3534 21.8558 7.87343 21.8631 2.3939C21.8635 2.16733 21.7966 2.12797 21.587 2.12797C15.7573 2.13373 9.92812 2.13373 4.09846 2.12749C3.88163 2.12749 3.82478 2.17789 3.82622 2.39726C3.83586 4.2689 3.83056 6.14101 3.83249 8.01312C3.83345 8.63668 3.8426 9.26024 3.84983 9.88379C3.8532 10.1622 3.84019 10.1953 3.56121 10.2525C2.98784 10.3619 2.47228 10.6206 1.99189 10.9495C1.96684 10.9663 1.94178 10.9831 1.91625 11.0003C1.8194 11.0647 1.69027 10.9951 1.69027 10.8794V10.8583C1.69027 7.73854 1.70231 4.61836 1.68111 1.49865C1.67581 0.773806 1.88685 0.249135 2.60526 0H23.0831Z">
                  </path>
                  <path
                    d="M0 18.8354C0 18.5623 0.0949207 18.2934 0.282835 18.0947C0.398474 17.9728 0.54206 17.8801 0.733828 17.8384C0.86585 17.8096 0.816703 17.7001 0.817185 17.6224C0.820076 16.8471 0.816703 16.0719 0.819594 15.2961C0.825858 13.5699 1.95961 12.127 3.66047 11.801C4.66364 11.609 5.69669 11.5826 6.65409 12.0478C7.97382 12.6896 8.66766 13.7634 8.71584 15.226C8.74186 16.0243 8.72403 16.8245 8.71777 17.6238C8.7168 17.7568 8.74812 17.8139 8.884 17.8523C9.33162 17.98 9.58651 18.3261 9.58747 18.7931C9.59036 20.2001 9.59036 21.6066 9.58747 23.0136C9.58651 23.46 9.36631 23.7677 8.95627 23.9424C8.86473 23.9803 8.76691 24 8.66766 24H1.02582C0.728047 24 0.439912 23.88 0.240434 23.6597C0.238024 23.6573 0.236097 23.6549 0.233688 23.6525C0.0785384 23.4802 0 23.2531 0 23.0217V18.8354ZM4.76772 17.8081C5.55406 17.8081 6.34041 17.8057 7.12724 17.8105C7.25204 17.811 7.31227 17.7942 7.31082 17.6459C7.30311 16.8466 7.32238 16.0464 7.30118 15.2476C7.27372 14.2065 6.59193 13.3794 5.59936 13.1739C5.14162 13.0794 4.67135 13.0966 4.20783 13.1384C3.14106 13.2349 2.3258 14.0159 2.25931 15.0758C2.20486 15.9442 2.24051 16.8178 2.23136 17.6891C2.22943 17.86 2.34989 17.8067 2.43228 17.8067C3.21092 17.8091 3.98908 17.8081 4.76772 17.8081Z">
                  </path>
                  <path
                    d="M15.7101 9.35289C15.7029 9.64859 15.7077 9.94429 15.7077 10.24C15.7077 10.5035 15.7067 10.7675 15.7087 11.0316C15.7111 11.4751 15.9472 11.7098 16.3909 11.7118C16.8588 11.7137 17.1036 11.4857 17.105 11.0392C17.1069 10.4954 17.1103 9.95197 17.1021 9.40858C17.1002 9.26601 17.1368 9.1844 17.2659 9.10568C17.8239 8.76486 18.1188 8.09281 17.9978 7.48078C17.8504 6.73481 17.2592 6.1967 16.5311 6.14629C15.7862 6.09493 15.1155 6.53992 14.8789 7.2422C14.6356 7.96369 14.9064 8.69861 15.5819 9.12632C15.6749 9.18536 15.7125 9.24392 15.7101 9.35289Z">
                  </path>
                </g>
                <defs>
                  <clipPath id="clip0_1_355321">
                    <rect width="24" height="24"></rect>
                  </clipPath>
                </defs>
              </svg>
              <div class="text-sm">
                <p>My Safe</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile sub left nav -->
        <div id="profileContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="mt-5 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon trading-profile-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 498.4 511.1">
                <path
                  d="M256.8 0s13-.2 21 1c26.5 3.9 50.5 13.5 70.4 31.6 37.2 33.8 52 75.6 43.3 125.5-2.5 14.1-8 26.9-15 39.2-11 19.2-20 39.6-32.6 58-6.6 9.6-15 15.6-27.5 14.9-10.1-.6-20.3 0-30.5-.2-6.4-.1-11.7 2.3-16.2 6.6-7.3 6.9-10.4 15.7-10.4 25.6v195.6c0 7.4-6 13.3-13.4 13.3-80.5 0-154.3-.1-234.8.1-5.1 0-13.8-2.1-10.5-14.8.2-.6 15.2-55.3 25.2-80 6.4-15.8 17.8-27.2 31.1-36.9 9.1-6.7 19.6-11.1 29.5-16.5 23.1-12.7 48.5-20.1 72.3-31.3 27.2-12.9 37.5-46.4 21.9-72.2-5.1-8.4-10.2-16.8-15-25.4-8.5-15-17.5-29.8-24.9-45.4-11.5-24.2-14.8-49.8-10.6-76.4 8.4-52.8 48.1-95.9 100-108.3 6-1.7 15-3.7 26.7-4z">
                </path>
                <path
                  d="M304.8 509.3c-7.7-2.9-10.4-9.8-10.4-17.8.1-52.3 0-104.6 0-157 0-9.7.1-19.3-.1-29 0-2.8.7-3.6 3.6-3.6 10.9-.1 20.1-.1 30.5 0 5.1 0 5.5.2 5.5 5.3-.2 52.8 0 105.6-.3 158.5 0 5.3 1.6 6.1 6.4 6.1 51-.2 102-.1 153-.1h1.2c2.4.1 4.2 2.1 4.2 4.5v29.5c0 2.4-1.9 4.3-4.3 4.3H312c-1.6 0-4.6.3-7.2-.7z">
                </path>
                <path
                  d="M493.8 416c-.9-.7-4.1-4.4-5.9-6.5-2.3-2.8-4.5-4.9-6-6.6-4-4.6-4.8-2.9-7-.7-15.7 15.6-28.2 29.6-43.3 46.2-2.8 3.1-9.7 3.5-13.5-.3-6.5-7-10-10.3-16.3-17.1-2.3-2.5-4.2-3.6-6.7-1-6.8 7.1-10.9 11-17.8 18-2.1 2.1-6.8 4.7-11.4.8-7.3-7.8-9-9.3-16.6-16.8-2.6-3-3.6-7.3.3-11.2 16.3-15.8 25.7-25 41.8-41 3.2-3.1 8.2-3.4 11.5-.2 7 7.3 11.4 11.4 18.4 18.7 2.1 2.3 3.3 2.1 5.4 0 7.4-7.5 14.8-14.8 22.5-22.1 2.2-2.1 1.8-3.1-.2-5-5.6-5.2-9.8-9.4-15.3-14.7-.8-.8-1.5-1.7-1-3.3.4-1.4 2.7-1.3 3.9-1.3 20.4 0 35.5.2 55.9 0 3.9 0 5.9 2.1 5.9 5.6v56.1c0 2.8-1.8 4.7-4.6 2.4z">
                </path>
              </svg>

              <div class="text-sm">
                <p>Trading profile</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon profile-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 497.06 511.14">
                <path
                  d="M237.81.01c.97.01 3.56.13 4.53.16 11.11.42 31.42 3.69 47.5 11.41 48.8 23.26 75.17 62.15 78.96 115.88 1.59 22.6-2.93 44.63-13.91 64.56-12.88 23.39-25.64 46.85-39.44 69.74-14.23 23.61-3.92 55.93 20.7 68.44 7.24 3.68 14.58 7.07 22.18 9.94 28.27 10.69 56.13 22.41 81.48 39.03 13.9 9.11 24.91 21.17 31.68 36.98 5.26 12.29 8.72 25.05 12.25 37.81 4.24 15.3 9.46 30.35 13.06 45.83 1.34 5.75-3.03 11.24-8.93 11.24H247.4c-3.83 0-7.35-2.4-8.48-6.06-.71-2.28-.86-4.77-.86-7.37.05-65.14.03-130.28.05-195.42 0-6.65-1.45-12.94-4.84-18.63-5.21-8.76-12.58-13.91-23.28-13.57-9.79-.02-24.91.04-29 .01-20.91-.8-20.82-5.1-27.47-14.71-11.82-17.08-20.3-36.05-30.5-54.04-4.66-8.23-9.66-16.4-12.61-25.35-6.84-20.77-9.62-42.15-5.42-63.84C115.66 57 147.79 21.13 201.61 4.79c3.53-1.07 17.44-4.98 36.2-4.78z">
                </path>
                <path
                  d="M.1 310.2c0-1.75 1.42-3.17 3.17-3.17 64.94 0 129.88.04 194.82-.1 4.96-.01 5.01 1.3 4.93 7.96-.14 11.86-.14 17.98.02 29.02.08 6.03.02 7.22-4.44 7.22-65-.13-129.99-.09-194.99-.09-1.93 0-3.5-1.57-3.5-3.5V310.2zM.1 390.53c0-1.93 1.57-3.5 3.5-3.5 64.83 0 129.66.04 194.49-.1 4.96-.01 4.89 1.3 4.93 7.96.06 12.64-.12 18.73.02 29.02.08 6.03.02 7.22-4.44 7.22-64.85-.13-129.68-.1-194.5-.1-2.21 0-4-1.79-4-4v-36.5zM.1 470.87c0-2.12 1.72-3.84 3.83-3.84 64.72 0 129.43.04 194.15-.1 4.15-.01 5.22.93 5.12 5.11-.27 11.6-.15 23.21-.12 34.82.01 2.3-1.86 4.17-4.17 4.17-64.81 0-129.62-.04-194.43.11-3.94.01-4.56-1.1-4.49-4.69.22-11.86.13-23.72.11-35.58z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Profile</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M13.066 1.078 3.394 3.67c-.522.14-.922.437-1.199.89a1.76 1.76 0 0 0-.215 1.427L4.98 17.189c-.38.065-.74.013-1.08-.158-.395-.198-.645-.496-.752-.893L.05 4.566c-.107-.397-.039-.78.204-1.15a1.73 1.73 0 0 1 1.035-.732L11.052.068a1.73 1.73 0 0 1 1.263.117c.394.198.645.496.752.893Z">
                </path>
                <path
                  d="M16.735 3.196 7.152 5.764a2.086 2.086 0 0 0-1.262.907c-.295.458-.376.936-.242 1.433l3.001 11.202c-.38.066-.74.014-1.08-.157-.395-.198-.645-.496-.752-.894l-3.1-11.57c-.107-.399-.039-.782.204-1.15a1.73 1.73 0 0 1 1.035-.733l9.764-2.616c.447-.12.868-.081 1.263.117.394.197.645.495.752.893Z">
                </path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="m12.984 23.192 9.764-2.617c.447-.12.788-.381 1.02-.785.234-.405.29-.83.17-1.278L20.452 5.494a1.622 1.622 0 0 0-.786-1.02 1.622 1.622 0 0 0-1.277-.17L8.624 6.918c-.447.12-.788.382-1.02.786-.234.404-.29.83-.17 1.278L10.92 22c.12.448.382.788.786 1.021.404.234.83.29 1.277.17Zm6.669-4.403-4.882 1.308a.815.815 0 0 1-.642-.079.815.815 0 0 1-.39-.517.815.815 0 0 1 .08-.642.815.815 0 0 1 .516-.39l4.882-1.308a.815.815 0 0 1 .642.079.815.815 0 0 1 .39.517.815.815 0 0 1-.08.642.815.815 0 0 1-.516.39Zm-.654-2.441-4.882 1.308a.815.815 0 0 1-.642-.079.815.815 0 0 1-.39-.517.815.815 0 0 1 .08-.642.815.815 0 0 1 .516-.39l4.882-1.307a.815.815 0 0 1 .642.078.815.815 0 0 1 .39.517.815.815 0 0 1-.08.643.815.815 0 0 1-.516.389Zm-5.262-8.835a.5.5 0 0 1 .751.014l1.137 1.293.007.007.002.002h.002l.01-.001 1.689-.195a.5.5 0 0 1 .51.754l-.883 1.467-.002.003a.014.014 0 0 0-.002.007c0 .002 0 .005.002.007v.002l.728 1.583a.507.507 0 0 1-.572.705l-1.672-.382-.017.001a.042.042 0 0 0-.016.008l-1.257 1.166a.507.507 0 0 1-.848-.324l-.161-1.737a.015.015 0 0 0-.002-.007.015.015 0 0 0-.006-.004l-.003-.002-1.497-.83a.5.5 0 0 1 .064-.907l1.56-.677.009-.003.002-.001v-.003l.003-.01.338-1.687a.5.5 0 0 1 .123-.249Z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Loyalty Program</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon security-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 375 374.2">
                <path
                  d="M162.3.9c5 1.1 10.1 1.8 15.1 2.8 40.5 7.8 81 15.6 121.5 23.3 5.4 1 9.7 4.7 9.5 11.7-.4 18.1-.2 36.2-.1 54.4 0 3-.5 3.6-3.6 2.5-6.9-2.3-14-3.8-21.3-4.5-2.7-.2-3-1.3-3-3.5.1-10.9 0-21.7.1-32.6 0-2.4-.5-3.5-3-3.9-40.3-7.6-80.6-15.4-120.9-23-4.2-1-11.1 1.2-16.6 2.2C103.7 37 67.5 44 31.2 50.9c-2.8.5-3.6 1.4-3.5 4.3.1 38.7-.2 77.5.2 116.2.2 20.1 5.4 39.5 12.3 58.3 11.5 30.9 29.4 57.6 54 79.6 16.7 14.9 35.6 26.3 56.7 34 2.2.8 4.2.9 6.4.1 10.1-3.7 19.7-8.3 29-13.7 1.2-.7 2.1-1.6 3.7-.5 7.5 5.5 15.5 10 24.4 14 1 .5 1.2 1.8.3 2.5-6.6 4.8-13.3 8.9-20.2 12.6-10.7 5.7-21.7 10.5-33.2 14.2-.7.2-5.3 1.8-6.6 1.8h-.3c-1.8 0-6.7-1.6-7.3-1.8-37.3-12.2-68.4-33.7-93.7-63.5-25-29.5-41.3-63.4-49.8-101.1v-.2C1 196.2 0 184.5 0 172.8V39c0-5.9 4.1-11 9.8-12.2h.1c40.6-7.6 81.2-15.4 121.7-23.3 4.8-.9 9.7-1.6 14.4-2.6 5.4-1.2 11-1.2 16.3 0z">
                </path>
                <path
                  d="M375 220.7c0 8.5-.7 17-2.7 25.3v.1c-12.3 51.4-58 86-110.8 83.8-51.4-2.2-95.3-42.8-101.6-93.9-7.4-59.4 33.3-112.5 92.7-121.1 57.3-8.3 112.5 32.8 121.1 89.9.2 1.1 1.3 10.1 1.3 13.4v2.5zm-108 81.1c43.9.1 79.8-35.8 79.9-79.8.1-44.2-35.9-80.1-80.4-80.1-43.6.1-79.5 36.2-79.4 80 .1 44.1 35.8 79.9 79.9 79.9z">
                </path>
                <path
                  d="M258.7 89.4c0 .7-.3 1.2-2.1 1.2-1.8.1-4.4 0-5.9.2-57.5 8.1-101.3 48.1-112.9 104.8-8.7 42.4 2 80.4 30.2 113.3 2.5 3 1.5 3.3-1.3 5-3.2 1.9-6.5 3.7-9.9 5.1-1.7.6-3.6.7-5.4.1-51.8-18.9-81.8-58.9-96.8-110.4-3.4-11.5-5.1-23.3-5-35.5.3-31.9.1-63.7.1-95.6 0-5.6 1.6-7.5 7.1-8.7 31.8-6.4 63.6-12.8 95.4-19.3 2.5-.5 4.7.2 7 .6l90.6 18.3c8.2 1.7 9 2.7 9 10.9-.1.8-.1 7.2-.1 10zM226.2 205.2c4.7 0 8.3 2 11.4 5.3 5.1 5.5 10.5 10.6 15.6 16.1 1.6 1.7 2.6 1.7 4.2 0 14-14.5 28.2-28.8 42.2-43.3 3-3.1 6.4-4.6 10.6-4.5 4.9.1 10.1 2.4 12.5 7.1 2.7 5.1 1.9 11.4-2.6 16-10.6 10.9-21.3 21.8-31.9 32.6-7.8 8-16 15.8-23.5 24.1-6.4 7-12.6 5.1-17.8.8-11.3-9.6-21-20.9-31.2-31.6-4-4.2-4.2-9.9-1.7-15 3.1-4.6 7.3-7.7 12.2-7.6z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Security</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon live-trading-history-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.11 480.71">
                <path
                  d="M500.11 348.11c0 10.43-1.01 20.88-3.58 30.99-.01.05-.03.1-.04.15-10.88 41.68-35.55 72.09-74.92 89.49-47.39 20.95-92.27 13.79-132.95-16.84-30.33-22.84-46.33-54.6-50.19-92.42-.26-2.59.24-3.48 3.01-3.46 10.83.1 21.66.01 32.49-.12 2.21-.03 3.01.44 3.3 2.92 5.59 47.66 44.91 83.14 92.68 82.7 47.88-.43 83.82-36.35 90.32-77.26 7.95-50-22.62-95.85-72.01-106.43-32.52-6.97-61.18 1.7-85.25 24.97-2.41 2.33-2.1 3.55.11 5.75 9.45 9.39 18.71 18.98 28.03 28.52.01.01.02.02.03.04 1.7 1.74.38 4.63-2.05 4.61h-.07c-27.66.03-55.31.03-82.97.02-6.94 0-6.94-.04-6.94-7.07 0-28.49.01-84.01 0-84.51-.04-3.01 2.95-3.56 4.4-2.13 7.49 7.42 13.91 13.75 20.98 20.87 2.26 2.27 4.43 4.63 6.68 6.91 1.76 1.78 2.81 1.49 4.71-.36 27.26-26.47 59.97-39.74 97.92-38.14 37.66 1.59 69.24 16.81 94.17 45.44 17.55 20.15 27.67 43.52 31.14 69.9v.02c.62 4.7 1 9.43 1 14.18v1.26zM362.71.1c1.87 0 3.39 1.52 3.39 3.39-.02 14.2-.15 28.41.11 42.61.08 4.18-.98 5.11-5.12 5.11-119.24-.13-238.49-.1-357.73-.1-1.8 0-3.25-1.46-3.25-3.25C.12 33.26.22 18.68 0 4.1-.05.66.68 0 4.11 0c119.53.11 239.07.1 358.6.1zM.11 105.18c0-2.26 1.83-4.08 4.08-4.08 118.8 0 237.6.03 356.4-.11 5.46-.01 5.68 1.6 5.68 9.06v33.5c0 7.29-.05 8.65-5.18 8.64-119.27-.12-238.55-.1-357.82-.1-1.75 0-3.17-1.42-3.17-3.17v-43.74zM.11 206.6c0-2.49 2.01-4.5 4.5-4.5 59.5 0 118.99.04 178.49-.1 5.12-.01 5.12 1.38 5.12 8.6v35.03c0 6.38-.05 7.57-4.62 7.56-59.94-.13-119.88-.09-179.82-.09-2.03 0-3.67-1.64-3.67-3.67V206.6zM.11 306.76c0-2.03 1.64-3.67 3.67-3.67 59.77 0 119.55.04 179.32-.1 5.11-.01 5.12 1.37 5.12 8.58v35.04c0 6.38-.05 7.58-4.62 7.57-59.88-.13-119.77-.09-179.65-.09-2.12 0-3.83-1.72-3.83-3.83v-43.5zM.11 409.1c0-2.21 1.79-4 4-4 59.66 0 119.32.04 178.99-.1 5.11-.01 5.12 1.44 5.12 8.66v34.92c0 6.38-.05 7.63-4.62 7.62-60-.13-119.99-.09-179.99-.09-1.93 0-3.5-1.57-3.5-3.5V409.1z">
                </path>
                <path
                  d="M353.99 301.6c0-3.3.35-3.63 4.97-3.55 8.92.16 15.42-.03 23.48-.03 5.01 0 5.01.39 5 3.66-.12 11.99.05 23.98-.13 35.97-.04 3.02.68 4.05 3.83 3.97 8.65-.21 17.32.08 25.97-.15 3.43-.09 3.85.41 3.86 3.94.01 9.58.02 17.14.01 26.47 0 3.24-.38 3.81-4 3.79-19.65-.13-39.3-.15-58.95.02-3.34.03-4.17-.92-4.12-4.17 0-13.42.09-59.45.08-69.92z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Trading history</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon pocket-friends-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <circle cx="16.6647" cy="13.457" r="3.32188" fill="currentColor"></circle>
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M8.80558 18.502V21.8065L4.00838 22.9678V11.2997C3.99997 11.1705 4 11.0402 4 10.9089C4 6.59168 7.5095 3.08744 11.8354 3.08871C15.2944 3.09107 18.2317 5.34239 19.2669 8.4563C19.2922 8.53845 19.2668 8.6277 19.2079 8.68153C19.1406 8.73745 19.0481 8.74856 18.9723 8.70903C18.2906 8.37789 17.5331 8.19048 16.7251 8.19151C15.7909 8.19107 14.9158 8.44033 14.1668 8.8734C13.6029 8.2304 12.7781 7.82267 11.8607 7.8217C10.1775 7.82042 8.80558 9.18848 8.80558 10.8722C8.80558 10.8822 8.80558 10.8913 8.80558 10.9004V13.9742L11.5745 13.2866C11.5829 13.2845 11.5913 13.2851 11.5913 13.2888C11.5997 13.2912 11.5998 13.2967 11.5998 13.3021C11.5998 13.304 11.5998 13.3067 11.5998 13.3085C11.5998 14.7825 12.2225 16.1119 13.2241 17.0464C13.283 17.1029 13.3083 17.1878 13.2915 17.2667C13.2662 17.3477 13.2073 17.4088 13.1231 17.4297C12.2899 17.6367 10.371 18.1133 8.80558 18.502Z"
                  fill="currentColor"></path>
              </svg>
              <div class="text-sm">
                <p>Pocket Friends</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Market sub left nav -->
        <div id="marketContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="mt-5 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon market-icon" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 505.54 446.78">
                <path
                  d="M505.54 80.51v1.17c0 3.07-1.76 10.62-1.95 11.38-15.18 60.96-30.32 121.93-45.5 182.89-2.51 10.08-7.41 13.84-17.71 13.84-84.12 0-168.25 0-252.37.01-1.83 0-3.68.1-5.49.07-2.97-.05-3.22 1.11-2.56 3.64 2.55 9.79 5.2 19.57 7.25 29.47.82 3.95 2.35 4.9 6.18 4.89 68.3-.12 136.6-.08 204.9-.09 3.34 0 6.62.23 9.93.78 27.15 4.55 46.87 25.28 50.1 52.82 2.96 25.23-11.08 50.47-35.27 60.91-4.88 2.31-9.61 3.5-18.76 4.33-1.22.11-2.46.17-3.68.17h-1.63c-4.66 0-9.3-.57-13.83-1.68-31.29-7.94-50.44-37.6-44.51-69.16.27-1.44.61-2.87.97-4.3.46-1.86-.95-3.66-2.86-3.66h-78.57c-1.88 0-3.25 1.76-2.81 3.58.08.32.16.64.24.97 7.2 29.25-7.39 58.43-35.12 69.79-3.44 1.41-7.05 2.46-10.71 3.3-7.25 1.66-21.36 1.16-29.66-1.25-35.92-9.25-55.23-57.37-29.58-92.72 1.69-2.33 1.69-4.28 1.04-6.86-25.12-100.13-50.19-200.26-75.19-300.4-.82-3.26-2.12-4.43-5.68-4.36-24.09.41-31.93.42-53.4.18C9.77 40.11 0 35 0 20.7 0 6 9.43-.08 19.54 0c26.31.21 47.57.18 73.88.08 13.83-.05 15.22 3.02 17.63 14.81 2.91 14.28 6.97 28.32 9.91 42.59.85 4.13 2.44 4.82 6.06 4.8 22.14-.15 44.29-.13 66.43-.13 95.9.01 191.8.02 287.71.08 3.81 0 7.68.2 11.42.86 3.31.59 5.81 2.17 7.85 4.27 3.39 3.49 5.11 8.28 5.11 13.15zm-162.91 22.24v.02c-7.99 0-13.94.04-21.92-.11-2.56-.05-4.92.87-4.9 5.28.17 15.47-.33 29.02-.35 44.5 0 2.34.29 3.51 3.18 3.47 13.81-.17 27.62-.11 41.43-.04 2.21.01 3.21-.49 3.49-2.93 1.82-15.51 3.76-31.01 5.81-46.5.38-2.86-.3-3.82-3.27-3.75-7.82.18-15.65.06-23.47.06zm117.82 3.94c.5-1.98-1-3.91-3.04-3.91-15.44.02-30.57.11-45.7-.1-4.41-.06-4.44 1.79-5.14 7.43-2.11 16.94-3.93 31.06-5.34 41.09-.55 3.93-.65 4.72 1.85 4.71 14.14-.05 28.29-.05 42.43 0 1.95.01 2.72-.53 3.23-2.63 3.76-15.45 7.71-30.84 11.71-46.59zm-300.34-3.92v-.02c-7.82 0-15.65.14-23.46-.07-3.08-.08-3.66.86-2.94 3.65 3.98 15.41 7.93 30.83 11.69 46.29.66 2.72 1.48 3.24 5.02 3.23 13.17-.04 25.71-.04 39.33.03 3.45.02 3.93-.59 3.59-3.16-2.02-15.48-4.01-30.97-5.79-46.49-.32-2.8-1.27-3.59-3.99-3.53-7.81.18-15.63.07-23.45.07zm91.91.01v-.03c-7.65 0-15.31.15-22.96-.07-3.09-.09-3.98.66-3.56 3.82 2.04 15.3 3.94 30.62 5.72 45.95.31 2.67 1.15 3.5 3.9 3.46 13.31-.16 26.62-.13 39.93-.02 2.61.02 3.76-.39 3.73-3.43-.14-15.47-.56-29.06-.64-44.77-.01-4.5-1.98-5.08-4.59-5.03-7.82.17-13.71.12-21.53.12zm150.69 146.96c6.24.16 12.5-.2 18.73.12 2.59.13 3.46-.94 4.01-3.18 3.89-15.75 7.81-31.49 11.83-47.2.54-2.1-.79-3.79-2.95-3.76-11.49.15-22.99.3-34.48-.07-3.86-.12-4.75 1.38-5.16 4.6-2.43 15.88-4.94 30.63-7.07 45.95-.41 2.96 1.48 3.75 4.25 3.64 3.62-.13 7.23-.2 10.84-.1zm-89.17-2.96c-.03 2.68 2.59 3 6.88 3h22.71c6.8 0 8.21.66 8.63-2.96 1.84-15.83 3.82-31.65 5.9-47.45.49-3.75-.34-3.57-7.73-3.57h-27.89c-6.12 0-7.45.4-7.55 3.82-.77 26.5-.71 31.58-.95 47.16zm-154.5-51c-3.25-.06-2.28 2.26-2.2 2.57 4.06 16.73 7.88 32.24 11.9 48.98.58 2.41 2.13 2.47 4.09 2.46 9.66-.06 19.32-.15 28.97.04 3.1.06 4.91-.92 4.45-3.72-2.12-12.85-5.34-32.59-7.46-47.4-.25-1.78-.36-3.01-2.79-2.99-13.13.13-23.85.06-36.96.06zm120.34 3.45c-.05-3.39-1.63-3.43-7.36-3.43h-30.54c-5.14 0-5.08.77-4.74 3.45 1.97 15.65 3.96 31.3 5.72 46.97.34 2.99 1.31 3.58 4.63 3.57 9.28-.01 18.45-.01 27.79.02 3.03.01 5.88-.66 5.82-3.37-.14-7.81-.94-23.55-1.32-47.21zm-79.47 168.49c-10.94.04-19.51 8.72-19.47 19.7.05 10.92 8.73 19.48 19.73 19.43 10.94-.04 19.51-8.72 19.47-19.69-.05-10.93-8.73-19.49-19.73-19.44zm199.21 0c-10.98-.03-19.65 8.56-19.68 19.49-.03 10.96 8.57 19.62 19.52 19.64 10.98.03 19.65-8.56 19.68-19.49.03-10.96-8.57-19.62-19.52-19.64z">
                </path>
              </svg>

              <div class="text-sm">
                <p>Market</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon purchases-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 499.01 500.02">
                <path
                  d="M498.42 192.7c0 .01-.01.02-.01.03-2.33 9.71-8.41 15.63-17.74 19.19-43.75 16.66-87.39 33.61-131.07 50.45-.93.36-1.84.77-2.79 1.07-1.39.44-2.96 1.35-4.13-.14-1.08-1.39-1.2-3.1-.23-4.7 2.41-3.99 4.82-7.97 7.27-11.94 2.54-4.1 5.23-8.11 7.67-12.27 2.44-4.15 5.64-6.88 10.32-8.65 33.07-12.51 66.01-25.37 99.07-37.92 3.63-1.38 4.31-2.66 2.87-6.35-10.89-27.82-21.62-55.72-32.21-83.66-1.37-3.61-2.63-4.3-6.35-2.86-73.06 28.33-146.16 56.52-219.3 84.64-3.4 1.31-4.51 2.28-2.96 6.16 7.94 19.86 15.44 39.89 23.3 59.78 1.21 3.06.92 6.52-.84 9.3-3.2 5.05-6.1 10.29-9.29 15.35-1.39 2.2-2.85 4.97-6.04 4.44-2.85-.47-3.47-3.36-4.35-5.63-7.69-19.81-15.27-39.66-22.98-59.46-7.46-19.16-14.79-38.38-22.41-57.48-3.54-8.88-6.97-17.87-10.02-26.97-5.15-15.37 1.11-28.78 16.22-34.59C249.7 60.79 327 31.09 404.29 1.36c5.12-2.07 10.05-1.41 13.39-.46 12.6 3.56 17.08 12.66 21.49 24.88 8.65 24 18.26 47.66 27.39 71.49 10.24 26.71 20.4 53.45 30.59 80.18 1.85 4.87 2.42 10.18 1.27 15.25zm-306.38-54.67c1.09-.41 2.18-.81 3.26-1.22 24.16-9.32 48.31-18.63 72.47-27.95 49.09-18.93 98.16-37.9 147.3-56.7 3.68-1.41 3.63-2.42 1.98-6.79-1.45-3.84-2.91-7.37-4.18-10.86-1.87-5.15-2.69-6.14-7.03-4.45-50.9 19.77-101.86 39.37-152.82 58.99-22.28 8.58-44.6 17.08-66.87 25.69-1.52.59-4.19 1.06-3.18 3.6 2.4 6.01 4.64 12.09 7.02 18.1.31.8.56 2.08 2.05 1.59zM2.18 367.9c9.24-5.32 18.5-10.61 27.71-15.99 3.79-2.21 6.26-.92 8.13 2.59 5.61 10.56 11.25 21.1 16.84 31.68 11.86 22.46 23.69 44.95 35.53 67.42 3.79 7.2 7.61 14.39 11.39 21.6 3.01 5.74 2.55 7.31-2.89 10.32-7.43 4.1-14.85 8.24-22.32 12.26-1.35.72-2.76 1.32-4.18 1.89-2.18.89-4.74.05-5.87-2.02-.01-.01-.01-.03-.02-.04-7.44-13.91-14.94-27.79-22.33-41.73-8.78-16.56-17.44-33.19-26.22-49.76-5.74-10.83-11.6-21.6-17.41-32.4-1.13-2.07-.4-4.65 1.64-5.82z">
                </path>
                <path
                  d="M332.58 233.46c.25 4.99-1.51 10.13-4.37 14.92-28.5 47.67-56.78 95.47-85.68 142.89-8.56 14.05-20.73 25.16-35.05 33.5-8.72 5.07-14.5 8.55-25 11.46-5.01 1.39-12.67 2.87-17.78 3.23-13.23.95-25.4 4.58-36.32 12.29-3.39 2.39-7.03 4.46-10.71 6.37-4.09 2.12-6.27.79-9.59-5.79-14.55-28.83-28.44-54.88-46.35-87.9-4.42-8.15-6.88-10.95-1.34-14 7.13-3.93 13.88-8.58 21.14-12.25 7.31-3.7 10.67-9.66 11.83-17.26 6.47-42.28 12.96-84.55 19.25-126.86.81-5.47 4.21-8.11 8.59-9.88 8.31-3.37 16.79-6.3 25.14-9.57 4.37-1.71 7.4-.52 9.51 3.55 3.76 7.25 6.22 15.02 9.17 22.6 10.6 27.23 20.99 54.54 31.69 81.73 2.51 6.37 4.9 12.96 11.2 15.99 6.75 3.25 17.45 2.12 21.56-4.85 15.44-26.22 31.37-52.15 47.31-78.07 6.69-10.88 21.45-15.57 35.4-11.75 11.43 3.14 20.14 15.5 20.4 29.65z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Purchases</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon gem-lotto-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 482.35 491">
                <path
                  d="M284.68 7.9c6.34-6.24 14.02-10.12 24.11-5.51 2.06.94 4.37 3.19 5.12 3.91 13.64 13.21 24.9 23.72 38.8 36.66 2.06 1.92 2.01 2.96.28 5.14-10.66 13.49-14.16 28.92-10.52 45.57 8.61 39.48 56.86 54.51 87.48 27.41 1.68-1.48 2.61-2.76 4.97-.49 13.71 13.22 27.7 26.14 41.43 39.34 7.47 7.17 7.77 19.03.69 26.58-17.22 18.36-34.63 36.53-51.83 54.91-2.09 2.23-3.17 1.95-5.11.07-6.93-6.74-14.17-13.16-20.93-20.05-2.73-2.78-3.9-1.91-6.23.57-3.63 3.86-5.78 6.2-8.37 8.83-3.14 3.18-3.94 4.87-.26 8 6.72 5.71 12.78 12.19 19.41 18.01 3.05 2.68 3.16 4.05.22 7.16-67.57 71.29-135 142.71-202.42 214.14-13.53 14.51-20.2 15.95-29.78 7.85-14.43-13.88-28.7-27.37-43.17-40.93-1.76-1.64-2.18-2.57-.38-4.67 5.15-6.01 8.5-13.14 10.44-20.75 5.91-23.26-3.93-47.04-24.14-59.09-19.31-11.51-45.8-8.93-62.88 6.56-2.76 2.5-3.75 1.68-5.78-.26-13.14-12.54-26.37-24.98-39.58-37.45-8.09-7.64-8.29-18.21-.53-26.42 27.29-28.85 54.58-57.71 81.86-86.57 42.02-44.46 84.06-88.9 126.01-133.43 2.2-2.34 3.4-2.69 5.81-.27 6.24 6.25 12.88 12.09 19.16 18.3 2.53 2.51 3.61 2.62 6.78-.87 2.91-3.2 5.39-5.89 8.47-9.02 2.91-2.95 3.38-3.85.96-6.05-6.78-6.16-13.23-12.7-20.09-18.77-2.59-2.29-2.04-3.55-.02-5.66 13.31-13.94 26.55-27.96 39.77-41.98 3.37-3.62 6.71-7.3 10.25-10.77zm-89.39 234.73c-.64-.6-1.68-.39-2.04.41-1.08 2.46-2.05 4.69-3.04 6.91-12.11 26.99-24.24 53.96-36.31 80.96-.84 1.88-2.26 3.97-.29 6 2.07 2.14 3.98.48 5.84-.48 5.91-3.05 11.79-6.15 17.68-9.23 21.95-11.46 43.87-22.99 65.9-34.29 3.67-1.88 2.74-2.97.41-5.05-4.47-3.98-8.76-8.18-13.12-12.29-11.56-10.87-23.13-21.75-35.03-32.94zm-17.04 96.28c-1.12.56-1.39 2.74-.22 3.19 1.07.41 2.77.47 4.55.28 33.37-3.6 66.74-7.14 100.12-10.65 1.51-.16 3.21-.07 3.83-1.59.62-1.53-1.01-2.28-1.93-3.15-8.55-8.15-17.22-16.18-25.65-24.45-2.14-2.1-3.7-1.78-6.06-.6-15.9 7.95-31.89 15.72-47.83 23.6-9.12 4.49-17.67 8.79-26.81 13.37zm-31.79-28.94c0 1.48-.1 3.44 1.62 3.82 1.13.38 1.95-1.86 2.46-3.07 10.69-25.26 21.26-50.57 32.03-75.79 1.17-2.74.49-4.13-1.44-5.91-8.44-7.79-16.75-15.74-25.17-23.56-.99-.92-2.64-3-3.72-1.74-.58.68-.75 5.49-1.1 10.86-1.6 24.42-4.71 85.57-4.68 95.39zM298.38 128.9c.69-.73.66-1.91-.07-2.6-9.15-8.64-18.17-17.11-27.13-25.65-1.65-1.58-2.36-2.28-4.86.58-3.57 4.1-6.65 7.35-9.57 10.26-2.64 2.63-3.55 3.39-1.49 5.31 9.14 8.51 18.2 17.1 27.25 25.69 1.13 1.07 1.85 1.39 3.11 0 4.17-4.56 8.45-9.03 12.76-13.59zm56.12 50.63c-.52-.42-1.27-.37-1.73.12-.38.4-.72.76-1.05 1.12-3.75 3.98-7.35 8.11-11.3 11.88-2.04 1.94-1.76 2.99.11 4.73 8.89 8.26 17.69 16.61 26.46 24.99 1.45 1.38 2.15 1.64 3.8-.03 4.14-4.2 7.6-7.79 11.63-12.28 2.39-2.44 1.23-2.8-5.65-9.13-7.33-6.75-10.79-10.6-19.08-18.46-.88-.83-2.33-2.26-3.19-2.94zm-13.31-11.01c.4-.53.35-1.2-.08-1.71-.15-.18-.31-.36-.49-.53-9.3-8.79-18.62-17.55-27.88-26.38-1.41-1.34-2.06-.72-3.12.44-3.92 4.28-7.9 8.5-11.99 12.6-1.64 1.64-1.23 2.72.24 4.09 8.97 8.42 17.53 17.27 26.84 25.31 1.97 1.7 1.8 2.12 5.58-1.98 3.6-3.9 5.44-5.66 10.05-10.89.12-.15.74-.81.85-.95zM208.38 240.8c13.57 12.83 26.35 24.9 39.11 36.96.8.76 2.12.19 2.12-.92 0-12.88-.01-25.76.01-38.64 0-1.76-.35-2.7-2.56-2.55-12.19.86-24.38 1.58-37.7 2.4-1.38.09-1.99 1.79-.98 2.75zm33.48-20.24c-10.68-10.07-20.8-19.61-31.26-29.48-1.23-1.16-3.27-.45-3.5 1.23-1.54 10.98-2.95 21.25-4.47 31.5-.35 2.37.2 3.44 2.7 3.25 11.46-.88 22.92-1.74 35.17-2.66 1.93-.14 2.77-2.51 1.36-3.84zm20.91 19.78c-.81-.76-2.14-.19-2.14.92v36.94c0 1.95-.53 4.33 2.88 3.62 10.73-2.22 21.46-4.43 33-6.81 1-.21 1.36-1.47.62-2.17-11.48-10.86-22.55-21.33-34.36-32.5zm3.51 52.36c-1.62.35-1.1 1.82-.79 2.12 9.31 8.81 18.52 17.43 27.82 26.25 1.98 1.87 2.98 1.74 3.41-1.08 1-6.58 4.32-27.44 5.03-33.12.1-.79-.75-1.39-1.53-1.23-11.99 2.49-22.15 4.6-33.94 7.06zm-70.4-104.23c.24-1.55-1.14-2.86-2.67-2.55-10.72 2.18-20.75 4.22-30.78 6.27-1.09.22-2.58.19-2.94 1.38-.4 1.3 1.03 1.94 1.84 2.71 5.18 4.94 10.39 9.86 15.6 14.78 3.59 3.38 7.19 6.74 11.07 10.38 1 .93 2.64.37 2.85-.98 1.71-10.89 3.34-21.24 5.03-31.99z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Gem Lottery</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon mining-icon" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 500.29 500">
                <path
                  d="M141.6 500c-3.4 0-6.84-.66-9.79-2.36-.01-.01-.02-.01-.04-.02-6.5-3.86-10.29-9.5-10.41-17.08-.2-12.32-.21-24.65-.01-36.97.19-11.38 9.13-19.56 20.9-19.57 11.8-.01 20.78 8.14 20.98 19.48.22 12.32.23 24.65-.01 36.97-.16 8.19-4.3 14.08-11.67 17.78-.01.01-.02.01-.03.02-2.8 1.34-5.96 1.75-9.06 1.75h-.86zM213.6 500c-3.4 0-6.84-.66-9.79-2.36-.01-.01-.02-.01-.04-.02-6.5-3.86-10.29-9.5-10.41-17.08-.2-12.32-.21-24.65-.01-36.97.19-11.38 9.13-19.56 20.9-19.57 11.8-.01 20.78 8.14 20.98 19.48.22 12.32.23 24.65-.01 36.97-.16 8.19-4.3 14.08-11.67 17.78-.01.01-.02.01-.03.02-2.8 1.34-5.96 1.75-9.06 1.75h-.86zM285.6 500c-3.4 0-6.84-.66-9.79-2.36-.01-.01-.02-.01-.04-.02-6.5-3.86-10.29-9.5-10.41-17.08-.2-12.32-.21-24.65-.01-36.97.19-11.38 9.13-19.56 20.9-19.57 11.8-.01 20.78 8.14 20.98 19.48.22 12.32.23 24.65-.01 36.97-.16 8.19-4.3 14.08-11.67 17.78-.01.01-.02.01-.03.02-2.8 1.34-5.96 1.75-9.06 1.75h-.86zM357.6 500c-3.4 0-6.84-.66-9.79-2.36-.01-.01-.02-.01-.04-.02-6.5-3.86-10.29-9.5-10.41-17.08-.2-12.32-.21-24.65-.01-36.97.19-11.37 9.13-19.56 20.9-19.57 11.8-.01 20.78 8.14 20.98 19.48.22 12.32.23 24.65-.01 36.97-.16 8.19-4.3 14.08-11.67 17.78-.01.01-.02.01-.03.02-2.8 1.34-5.96 1.75-9.06 1.75h-.86zM141.69 0c4.65 0 9.25 1.23 13.12 3.8 2.08 1.39 3.96 3.03 5.32 5.38 2.05 3.54 3.2 7.25 3.18 11.37-.04 11.66.06 23.32-.04 34.97-.1 12.11-8.79 20.49-21.02 20.48-12.2-.01-20.84-8.42-20.94-20.56-.09-11.32-.05-22.65-.01-33.97.03-10.45 4.34-16.62 14.17-20.49h.01c2.15-.98 6.21-.98 6.21-.98zM211.55.05c.15-.02 2.21-.05 2.36-.05 4.57.04 9.09 1.27 12.9 3.8 2.08 1.39 3.96 3.03 5.32 5.38 2.05 3.54 3.2 7.25 3.18 11.37-.04 11.66.06 23.32-.04 34.97-.1 12.11-8.79 20.49-21.02 20.48-12.2-.01-20.84-8.42-20.94-20.56-.09-11.32-.05-22.65-.01-33.97.03-10.45 4.34-16.62 14.17-20.49h.01c3.31-.98 3.08-.83 4.07-.93zM285.69 0c4.65 0 9.26 1.23 13.12 3.8 2.08 1.39 3.96 3.03 5.32 5.38 2.05 3.54 3.2 7.25 3.18 11.37-.04 11.66.06 23.32-.04 34.97-.1 12.11-8.79 20.49-21.02 20.48-12.2-.01-20.84-8.42-20.94-20.56-.09-11.32-.05-22.65-.01-33.97.03-10.45 2.65-16.36 12.48-20.23 0 0 2.45-.8 4.25-1.04 1.82-.24 3.66-.2 3.66-.2zM356.46.1c.74-.07 1.49-.1 2.23-.08 4.3.16 8.53 1.39 12.12 3.78 2.08 1.39 3.96 3.03 5.32 5.38 2.05 3.54 3.2 7.25 3.18 11.37-.04 11.66.06 23.32-.04 34.97-.1 12.11-8.79 20.49-21.02 20.48-12.2-.01-20.84-8.42-20.94-20.56-.09-11.32-.05-22.65-.01-33.97.03-10.45 3.4-16.29 11.7-19.59 0 0 3.06-1.38 7.46-1.78zM4.83 128.49c2.3-2.92 5.18-5.26 9.22-6.47 2.24-.67 4.51-1.04 6.86-1.04 11.64.04 23.28-.06 34.91.04 12.09.11 20.47 8.78 20.47 20.98 0 12.19-8.4 20.87-20.47 20.98-9.64.09-19.53.24-28.92.14-8.77-.09-15.91-1.19-21.1-6.46-7.52-7.62-7.6-19.75-.97-28.17zM4.83 200.49c2.3-2.92 5.18-5.26 9.22-6.47 2.24-.67 4.51-1.04 6.86-1.04 11.64.04 23.28-.06 34.91.04 12.09.11 20.47 8.78 20.47 20.98 0 12.19-8.4 20.87-20.47 20.98-9.64.09-19.53.24-28.92.14-8.77-.09-15.91-1.19-21.1-6.46-7.52-7.62-7.6-19.75-.97-28.17zM4.83 272.49c2.3-2.92 5.18-5.26 9.22-6.47 2.24-.67 4.51-1.04 6.86-1.04 11.64.04 23.28-.06 34.91.04 12.09.11 20.47 8.78 20.47 20.98 0 12.19-8.4 20.87-20.47 20.98-9.64.09-19.41.24-28.92.14-8.61.1-15.91-1.19-21.1-6.46-7.52-7.62-7.6-19.75-.97-28.17zM4.83 344.49c2.3-2.92 5.18-5.26 9.22-6.47 2.24-.67 4.51-1.04 6.86-1.04 11.64.04 23.28-.06 34.91.04 12.09.11 20.47 8.78 20.47 20.98 0 12.19-8.4 20.87-20.47 20.98-9.64.09-19.36.06-28.92.14-8.86.1-15.91-1.19-21.1-6.46-7.52-7.62-7.6-19.75-.97-28.17zM494.54 156.98c-2.53 2.7-5.69 4.69-9.93 5.48-2.59.48-5.28.51-7.92.53-10.64.06-21.28.1-31.92 0-12.07-.11-20.48-8.79-20.48-20.97 0-12.19 8.39-20.87 20.46-20.99 10.31-.1 20.61-.02 30.92-.02 10.95 0 16.42 1.91 20.82 8.58 5.62 8.52 5.03 19.93-1.95 27.39zM494.54 228.98c-2.53 2.7-5.69 4.69-9.93 5.48-2.59.48-5.28.51-7.92.53-10.64.06-21.28.1-31.92 0-12.07-.11-20.48-8.79-20.48-20.97 0-12.19 8.39-20.87 20.46-20.99 10.31-.1 20.61-.02 30.92-.02 10.95 0 16.42 1.91 20.82 8.58 5.62 8.52 5.03 19.93-1.95 27.39zM494.54 300.98c-2.53 2.7-5.69 4.69-9.93 5.48-2.59.48-5.28.51-7.92.53-10.64.06-21.28.1-31.92 0-12.07-.11-20.48-8.79-20.48-20.97 0-12.19 8.39-20.87 20.46-20.99 10.31-.1 20.61-.02 30.92-.02 10.95 0 16.42 1.91 20.82 8.58 5.62 8.52 5.03 19.93-1.95 27.39zM494.54 372.98c-2.53 2.7-5.69 4.69-9.93 5.48-2.59.48-5.28.51-7.92.53-10.64.06-21.28.1-31.92 0-12.07-.11-20.48-8.79-20.48-20.97 0-12.19 8.39-20.87 20.46-20.99 10.31-.1 20.61-.02 30.92-.02 10.95 0 16.42 1.91 20.82 8.58 5.62 8.52 5.03 19.93-1.95 27.39zM250.83 403H118.85c-16.37 0-22.05-9.9-22.19-28.17-.59-80.16-.59-160.73-.11-250.49.1-17.7 6.17-27.33 22.22-27.33 87.98-.01 175.97-.01 263.95 0 15.58 0 22.33 8.99 22.45 25.49.67 88.05.41 169.4-.11 253.59-.11 17.43-6.34 26.91-22.27 26.91-43.98.01-87.97 0-131.96 0zm34.49-163.59c.37-1.08-.43-2.19-1.57-2.19h-66.17c-1.19-.02-1.43.02-1.75.39-.35.39-.14 1.14-.13 1.18 10.58 30.18 20.99 59.87 31.59 90.04.7 1.99 1.8 4.29 3.97 4.23 2.06 0 3.06-2.46 3.63-4.09 5.23-15.14 10.28-30.35 15.42-45.52 4.98-14.69 10-29.35 15.01-44.04zm56.22.53c.98-1.16.15-2.94-1.37-2.93-12.96.02-24.92.13-36.88-.13-2.97-.06-3.17 1.75-3.79 3.38-6.1 16.18-12.12 32.39-18.15 48.6-3.52 9.46-6.99 18.94-10.53 28.39-.49 1.3-.97 2.72.46 3.43 1.36.68 3.73-2.4 4.16-2.91 16.23-19.04 32.47-38.08 48.68-57.14 5.66-6.64 11.26-13.35 17.42-20.69zM160.53 237c-1.32 0-2.03 1.55-1.18 2.55 3.19 3.75 5.91 6.95 8.63 10.13 19.57 22.85 39.14 45.71 58.76 68.51.93 1.08 2.52 3.07 3.74 2.23 1.12-.76.21-2.73-.24-3.92-9.62-25.64-19.31-51.26-29.05-76.87-.48-1.25-.84-2.7-2.96-2.69-12.21.11-24.45.07-37.7.06zm64.01-11.28h51.65c1.17 0 1.78-1.41.98-2.27-8.18-8.86-16.17-17.42-24-26.13-1.91-2.12-3.01-2.13-4.91-.09-8.09 8.65-16.29 17.19-24.74 26.06-.88.92-.24 2.43 1.02 2.43zm-32.25-36.6c-1.53 0-2.43 1.72-1.55 2.98 5.42 7.79 10.5 15.1 15.59 22.4 3.97 5.7 3.96 5.68 8.59.63 1.89-2.06 3.92-3.98 5.8-6.05 5.15-5.66 10.27-11.35 15.7-17.37.9-1 .19-2.59-1.15-2.59h-42.98zm73.81-.05c-1.51 0-2.28 1.81-1.24 2.9 8.25 8.72 16.12 17.05 24.02 25.36.97 1.03 1.88 2.45 3.39.29 6.02-8.61 12.12-17.16 18.42-26.02.75-1.06 0-2.53-1.3-2.53H266.1zm55.56 7.06c-6.49 9.47-12.67 18.48-18.9 27.57-.6.88.03 2.07 1.09 2.07h38.31c1.08 0 1.71-1.2 1.11-2.1-6.19-9.21-12.19-18.12-18.52-27.53-.73-1.09-2.35-1.1-3.09-.01zM159.1 225.65h36.97c1.22 0 1.94-1.37 1.25-2.38-6.09-8.92-12.08-17.69-18.27-26.75-.7-1.02-2.2-1.03-2.91-.01-6.22 8.91-12.21 17.49-18.45 26.44-.78 1.13.03 2.7 1.41 2.7z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Gem mining</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" xmlns="http://www.w3.org/2000/svg"
                class="svg-icon social-trading-reward" viewBox="0 0 37.5 50">
                <path
                  d="M29.8 32.76c-.45.89-.89 1.78-1.67 2.23-.77.44-1.75.39-2.74.33-.75-.04-1.5-.08-2.17.09-.63.17-1.24.57-1.86.98-.83.55-1.68 1.11-2.61 1.11s-1.77-.56-2.6-1.1c-.62-.41-1.23-.81-1.87-.98-.67-.18-1.42-.14-2.18-.09-.99.05-1.97.11-2.73-.33-.78-.45-1.23-1.34-1.67-2.23-.34-.67-.67-1.34-1.15-1.81-.48-.48-1.15-.82-1.82-1.15-.88-.44-1.77-.89-2.22-1.67-.44-.76-.39-1.75-.33-2.74.04-.75.08-1.51-.09-2.18-.17-.63-.57-1.24-.98-1.86C.56 20.53 0 19.68 0 18.75s.56-1.77 1.1-2.6c.41-.62.81-1.23.98-1.87.18-.66.14-1.42.09-2.17-.05-.99-.11-1.97.33-2.73.45-.78 1.34-1.22 2.23-1.67.67-.34 1.34-.67 1.81-1.15.48-.48.81-1.14 1.15-1.81.45-.89.89-1.78 1.67-2.23.77-.44 1.75-.39 2.74-.33.75.04 1.51.08 2.18-.09.63-.17 1.24-.57 1.86-.98.83-.55 1.68-1.11 2.61-1.11s1.8.58 2.63 1.14c.59.4 1.16.78 1.75.95.67.18 1.42.14 2.18.09.99-.05 1.97-.11 2.74.33.78.45 1.22 1.34 1.67 2.23.34.67.67 1.34 1.15 1.81.48.48 1.14.81 1.81 1.15.89.45 1.78.89 2.23 1.67.44.76.39 1.75.33 2.74-.04.75-.08 1.51.09 2.18.17.63.57 1.24.98 1.86.55.83 1.11 1.68 1.11 2.61s-.53 1.78-1.06 2.62c-.39.61-.77 1.22-.93 1.85-.18.67-.14 1.42-.09 2.18.05.99.11 1.97-.33 2.74-.45.78-1.34 1.23-2.23 1.67-.67.34-1.34.67-1.81 1.15s-.81 1.14-1.15 1.81ZM.55 44.67C0 44.01-.15 43.1.17 42.3l3.18-8.02c3.54 3.5 8.25 5.79 13.5 6.25l-3.28 8.02c-.35.86-1.15 1.42-2.07 1.45h-.1c-.89 0-1.68-.49-2.09-1.29l-2.09-4.13-4.41.88c-.84.17-1.71-.14-2.26-.8Zm20.1-4.14c5.25-.45 9.97-2.75 13.5-6.25l3.19 8.02c.32.8.17 1.71-.38 2.37-.55.66-1.42.97-2.26.8l-4.41-.88-2.09 4.13c-.4.8-1.2 1.28-2.08 1.28-.08 0-.08-.04-.09-.04l-.02.04c-.93-.04-1.72-.59-2.07-1.45l-3.28-8.02ZM12.32 18.8c1.32 0 2.4-1.08 2.4-2.4s-1.08-2.4-2.4-2.4a2.4 2.4 0 0 0 0 4.8ZM10.4 20A2.4 2.4 0 0 0 8 22.4c0 .66.54 1.2 1.2 1.2h3.21a4.823 4.823 0 0 1 2.84-3.25c-.36-.23-.79-.35-1.25-.35h-3.6Zm13.2 0c-.44 0-.85.12-1.2.32 1.4.56 2.52 1.77 2.91 3.28h3.09c.66 0 1.2-.54 1.2-1.2 0-1.32-1.08-2.4-2.4-2.4h-3.6Zm.45 3.6a3.622 3.622 0 0 0-2.58-2.31c-.26-.06-.53-.09-.81-.09h-3.6c-.32 0-.66.04-.92.12-1.15.3-2.08 1.17-2.48 2.28-.14.38-.21.78-.21 1.2 0 .66.54 1.2 1.2 1.2h8.4c.66 0 1.2-.54 1.2-1.2 0-.42-.07-.82-.21-1.2Zm1.47-4.8c1.32 0 2.4-1.08 2.4-2.4s-1.08-2.4-2.4-2.4a2.4 2.4 0 0 0 0 4.8ZM18.86 14c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3Z"
                  style="fill-rule: evenodd"></path>
              </svg>
              <div class="text-sm">
                <p>Social Rewards</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Acheivemeent sub left nav -->
        <div id="achievementsContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="mt-5 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon achievements-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 497.74 456">
                <path
                  d="M86.39 0h324.85c2.54 0 4.6 2.06 4.61 4.6.02 6.8.07 13.6-.07 20.39-.06 2.83.49 3.83 3.66 4.23 19.32 2.39 38.79 3.45 58 6.81 8.32 1.46 14.07 6.07 17.14 13.67 2.37 5.87 3.46 12.19 3.09 18.57-.83 14.12-.69 28.27-1.68 42.41-1.19 16.88-4.29 33.28-8.94 49.5-3.66 12.78-9.03 24.71-17.37 35.03-19.07 23.58-44.61 36.43-73.69 42.69-19.18 4.13-38.51 7.55-57.82 11.05-7.49 1.36-14.07 3.8-20.13 8.85-9.85 8.21-20.93 14.71-32.93 19.5-5.16 2.06-5.23 2.91-.83 6.22 10 7.52 15.44 17.21 14.59 30.04-.49 7.49-3.54 13.74-8.36 19.34-3.46 4.02-2.97 5.85 1.77 8.26 2.12 1.08 4.37 1.5 6.65 1.98 13.87 2.91 26.51 8.51 33.12 21.7 8.48 16.91 4.38 26.58-13.46 30.07-15.57 3.05-31.37 3.84-47.22 3.85-25.98.01-51.99 1.08-77.9-1.79-7.08-.78-14.27-1.23-21.03-3.91-8.59-3.41-11.35-7.67-10.52-16.85.77-8.39 4.79-15.16 10.91-20.74 7.49-6.82 16.47-10.58 26.32-12.4 2.31-.43 4.53-1 6.62-2.09 4.19-2.19 4.95-4.36 1.76-7.62-12.99-13.28-12.41-37.12 6.19-50.11 4.03-2.81 3.89-3.87-.63-5.68-11.2-4.49-21.89-10.16-30.95-17.95-8.29-7.12-17.65-9.92-27.92-11.75-26.33-4.69-52.8-8.65-78.24-17.54-16.78-5.87-30.71-15.46-42.7-28.45-17.14-18.57-23.76-41.61-28.47-65.46C.22 113.11.8 89.37.01 65.77c-.15-4.44.82-8.93 2.15-13.26 2.86-9.28 8.8-14.88 18.58-16.56 19.05-3.29 38.35-4.36 57.51-6.73 3.17-.39 3.72-1.4 3.66-4.23-.14-6.81-.09-13.63-.07-20.45 0-2.51 2.04-4.54 4.55-4.54zm164.02 53.35c-.67-1.22-2.42-1.23-3.11-.02-6.18 10.96-12.43 21.01-17.24 31.7-4.37 9.72-10.7 14.05-21.18 15.19-11.71 1.28-23.25 4.16-34.89 6.48-1.38.28-1.92 1.98-.92 2.98.23.23.47.45.68.68 7.62 8.14 14.64 17 23.05 24.22 9.26 7.95 10.95 16.71 8.57 28.09-2.22 10.64-3.02 21.58-4.34 33.04-.16 1.38 1.26 2.41 2.52 1.82 8.41-3.94 16.33-7.66 24.26-11.36 6.92-3.23 13.86-8.97 20.77-8.93 7.11.04 14.21 5.67 21.28 8.94 8.03 3.71 16.02 7.49 24.27 11.36 1.24.58 2.69-.41 2.53-1.77v-.02c-1.8-14.33-3.48-28.69-5.54-42.99-.68-4.68.09-8.2 3.74-11.47 4.08-3.65 7.64-7.89 11.39-11.91 6-6.43 11.97-12.89 18.28-19.71.92-.99.44-2.62-.88-2.94-.93-.23-1.75-.42-2.57-.58-13.84-2.69-27.65-5.57-41.56-7.88-3.8-.63-5.75-2.29-7.45-5.44-6.99-12.96-14.15-25.85-21.66-39.48zM71.6 73.16h-.09c-3.82.21-9.62.38-15.39.91-4.67.43-8.57 2.1-9.79 7.41-2.23 9.69-.55 31.13.51 39.24 2.16 16.65 6.23 32.58 15.79 46.66 8.97 13.21 20.89 22.3 36.87 25.15 5.69 1.01 11.87 1.44 16.05-3.75 4.1-5.09 1.45-10.38-.69-15.46-.13-.3-.23-.62-.36-.92-12.66-28.28-21.76-57.64-26.82-88.24-1.82-10.88-2.1-11.01-16.08-11zm358.4.49c-.08-.01-.16-.02-.24-.02-2.76 0-5.39.1-8-.02-8.19-.39-9.85.54-11.18 8.35-5.62 32.98-15.14 64.72-29.2 95.1-2.58 5.58-1.34 10.68 2.88 13.88 4.79 3.63 10.31 2.67 15.34 1.4 24.24-6.12 38.17-22.91 45.83-45.7 3.6-10.72 5.35-21.95 6.13-33.16.72-10.22 1.96-20.56.13-30.83-.72-4.05-2.55-6.83-7.07-7.86-4.92-1.13-9.87-.43-14.62-1.14z">
                </path>
                <path
                  d="M215.84 456c-30-1.78-59.4-4.86-78.62-9.94-13.19-3.49-19.16-9.24-19.37-25.1-.07-5.24-.99-10.62 1.01-15.76 2.85-7.32 8.26-11.78 15.73-13.82 1.79-.49 3.81-.88 4.31 1.79 2.64 14.04 14.13 16.69 25.28 18.68 19.66 3.51 39.52 5.5 59.52 6.19 24.51.84 49.01.57 73.41-1.31 15.17-1.17 30.45-2.84 45.16-7.34 7.94-2.43 14.93-6.02 16.16-15.54.34-2.66 2.1-3.05 4.38-2.62 9.3 1.77 16.99 10.98 17.01 20.43.01 4.33.03 8.66 0 13-.08 11.01-5.95 17.69-19.05 21.52-13.75 4.02-27.89 5.71-42.1 6.82-10.45.82-30.18 2.22-46.85 3h-55.98z">
                </path>
              </svg>

              <div class="text-sm">
                <p>Achievements</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon achievements-history-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                <g>
                  <g>
                    <path
                      d="M170.3 0H90.5L0 160h101.1L170.3 0zM576 160L485.5 0h-79.8l69.2 160H576zM426.7 160L357.5 0h-139l-69.2 160h277.4zM453 237.63h83.85L576 192H475.3L453 237.63zM100.7 192H0l218.7 255a3 3 0 005-3.3zM330 257.63a20 20 0 0120-20h58.15L427.8 192H148.2l137.1 318.2a3 3 0 005.5 0l39.2-91.05z">
                    </path>
                    <path
                      d="M570.31 342.85h-201a5.69 5.69 0 01-5.69-5.69v-17.07a22.76 22.76 0 0122.76-22.75h22.75v-24.65a5.69 5.69 0 015.69-5.69h19a5.69 5.69 0 015.69 5.69v24.65h60.68v-24.65a5.69 5.69 0 015.69-5.69h19a5.69 5.69 0 015.69 5.69v24.65h22.76A22.76 22.76 0 01576 320.09v17.07a5.69 5.69 0 01-5.69 5.69zm-201 15.15h201a5.69 5.69 0 015.69 5.69V487a22.75 22.75 0 01-22.75 22.75H386.38A22.75 22.75 0 01363.62 487V363.71a5.69 5.69 0 015.69-5.71zm158 45.48L514 390a5.69 5.69 0 00-8.05 0l-50.26 49.86-21.8-22a5.69 5.69 0 00-8 0l-13.47 13.36a5.69 5.69 0 000 8l39.16 39.48a5.69 5.69 0 008 0l67.77-67.22a5.69 5.69 0 000-8.05z">
                    </path>
                  </g>
                </g>
              </svg>
              <div class="text-sm">
                <p>History</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon achievements-rating-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 336.22 253.97">
                <g>
                  <g>
                    <path
                      d="M30.35 2.26l-8.3 16.83L3.48 21.8a4.07 4.07 0 00-2.25 6.94l13.44 13.09-3.18 18.49a4.07 4.07 0 005.9 4.29L34 55.88l16.61 8.73a4.07 4.07 0 005.9-4.29l-3.18-18.49 13.44-13.09a4.07 4.07 0 00-2.25-6.94L46 19.09 37.65 2.26a4.08 4.08 0 00-7.3 0z">
                    </path>
                    <rect x="96.22" y="11.54" width="240" height="42" rx="9.89"></rect>
                    <path
                      d="M30.35 96.71l-8.3 16.83-18.57 2.7a4.07 4.07 0 00-2.25 6.94l13.44 13.1-3.18 18.49a4.06 4.06 0 005.9 4.28L34 150.32l16.61 8.73a4.07 4.07 0 005.9-4.28l-3.18-18.49 13.44-13.1a4.07 4.07 0 00-2.25-6.94L46 113.54l-8.3-16.83a4.07 4.07 0 00-7.3 0z">
                    </path>
                    <rect x="96.22" y="105.99" width="190" height="42" rx="10.22"></rect>
                    <path
                      d="M30.35 191.15L22.05 208l-18.57 2.69a4.07 4.07 0 00-2.25 6.94l13.44 13.09-3.18 18.49a4.07 4.07 0 005.9 4.29L34 244.76l16.61 8.74a4.07 4.07 0 005.9-4.29l-3.18-18.49 13.44-13.09a4.07 4.07 0 00-2.25-6.94L46 208l-8.3-16.83a4.08 4.08 0 00-7.3 0z">
                    </path>
                    <rect x="96.22" y="200.43" width="140" height="42" rx="10.34"></rect>
                  </g>
                </g>
              </svg>
              <div class="text-sm">
                <p>Rating</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon achievements-help-icon"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 572.02 372.99">
                <g>
                  <g>
                    <path
                      d="M75.38 172a50.25 50.25 0 10-50.26-50.25A50.29 50.29 0 0075.38 172zm175.87 25.13a87.94 87.94 0 10-87.94-87.94 87.9 87.9 0 0087.94 87.93zm60.3 25.12H305a121.42 121.42 0 01-107.56 0H191a90.47 90.47 0 00-90.45 90.45v22.6a37.7 37.7 0 0037.64 37.7h226.12A37.7 37.7 0 00402 335.3v-22.61a90.47 90.47 0 00-90.45-90.45zm-175.64-10.53a50.1 50.1 0 00-35.41-14.6H50.25A50.29 50.29 0 000 247.37v25.12a25.1 25.1 0 0025.12 25.13h51.75a115.12 115.12 0 0159-85.9zM471.82 0c-55.34 0-100.2 36.25-100.2 81 0 19.31 8.38 37 22.31 50.9-4.93 19.62-21.25 37.1-21.45 37.3a3.09 3.09 0 00-.58 3.39 3 3 0 002.85 1.87c25.95 0 45.41-12.38 55-20a119.93 119.93 0 0042 7.56C527.17 162 572 125.74 572 81S527.17 0 471.82 0zm13.28 119.2q-5.22 2.05-8.32 3.14a22 22 0 01-7.22 1.08c-4.21 0-7.49-1-9.83-3.08a10 10 0 01-3.5-7.83 28.79 28.79 0 01.26-3.77c.18-1.28.46-2.73.85-4.34l4.35-15.4c.38-1.47.71-2.87 1-4.19a18 18 0 00.4-3.61c0-2-.41-3.33-1.22-4.11s-2.35-1.16-4.65-1.16a12.06 12.06 0 00-3.45.53c-1.18.35-2.2.69-3 1l1.16-4.74c2.85-1.16 5.57-2.16 8.17-3a24.39 24.39 0 017.39-1.24q6.27 0 9.68 3a10.1 10.1 0 013.4 7.88c0 .67-.08 1.85-.24 3.53a23.47 23.47 0 01-.87 4.64l-4.33 15.32a42.45 42.45 0 00-.95 4.23 21.17 21.17 0 00-.42 3.58c0 2 .45 3.42 1.36 4.16s2.5 1.1 4.74 1.1a13.53 13.53 0 003.58-.55 20.91 20.91 0 002.89-1zm-.77-62.2a10.32 10.32 0 01-7.33 2.77 10.43 10.43 0 01-7.29-2.77 8.94 8.94 0 01-3-6.82 9.05 9.05 0 013-6.85 10.83 10.83 0 0114.62 0 9.07 9.07 0 013 6.85 9 9 0 01-3 6.82z">
                    </path>
                  </g>
                </g>
              </svg>
              <div class="text-sm">
                <p>Community help</p>
              </div>
            </div>
          </div>
        </div>

        <!-- chat sub left nav -->
        <div id="chatContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="flex items-center gap-2 my-3">
            <div class="w-10 p-2 flex items-center justify-center" style="background: #293145">
              <i class="fa-regular fa-square-check" style="color: #8ea5c0"></i>
            </div>
            <div class="flex items-center justify-between w-full px-2 py-1"
              style="border: 1px solid #44506a; background: #1d2130">
              <input type="text" class="bg-transparent w-full" placeholder="Search..." />
              <i class="fa-solid fa-magnifying-glass" style="color: #8ea5c0"></i>
            </div>
            <div class="w-10 flex items-center justify-center p-2" style="background: #293145">
              <i class="fa-regular fa-pen-to-square" style="color: #8ea5c0"></i>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <button style="background: #314463; border: 1px solid #009af9" class="w-full py-2 rounded-md text-xs">
              Chats
            </button>
            <button style="background: #1d2130; border: 1px solid #454a56" class="w-full py-2 rounded-md text-xs">
              Notifications
            </button>
          </div>

          <div class="mt-3 flex flex-col gap-3">
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon qt-real" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none">
                <path fill="#8ea5c0" fill-rule="evenodd"
                  d="M7.832 1.858C8.822 1.308 10.12 1 11.5 1c1.38 0 2.678.309 3.668.858C16.123 2.388 17 3.282 17 4.5v16.11c0 1.207-.901 2.069-1.842 2.57-.984.525-2.278.82-3.658.82s-2.673-.295-3.658-.82C6.901 22.68 6 21.818 6 20.61V4.5c0-1.218.877-2.111 1.832-2.642ZM8 7.231V8.5c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98V7.231c-.966.494-2.197.769-3.5.769S8.966 7.725 8 7.231ZM15 4.5c0 .162-.13.52-.804.894-.64.355-1.59.606-2.696.606-1.105 0-2.057-.251-2.696-.606C8.13 5.019 8 4.662 8 4.5c0-.162.13-.52.804-.894C9.444 3.251 10.394 3 11.5 3c1.105 0 2.057.251 2.696.606.674.375.804.732.804.894Zm0 6.83c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.17c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98v-1.17Zm0 4c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.17c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98v-1.17Zm0 4c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.28c0 .108.099.441.783.806.64.341 1.597.584 2.717.584s2.076-.243 2.717-.584c.684-.365.783-.698.783-.805V19.33Z"
                  clip-rule="evenodd"></path>
                <path fill="#8ea5c0"
                  d="M16.584 21.951c.416.049.4.049.916.049 1.38 0 2.674-.295 3.658-.82.941-.501 1.842-1.363 1.842-2.57V14.5c0-1.218-.877-2.111-1.832-2.642-.99-.55-2.288-.858-3.668-.858-.515 0-.02-.082-.5 0v2c.45-.104-.044 0 .5 0 1.105 0 2.057.251 2.696.606.674.374.804.732.804.894 0 .162-.13.52-.804.894-.64.355-1.59.606-2.696.606-.544 0-.05.104-.5 0v2h.5c1.303 0 2.534-.275 3.5-.769v1.38c0 .107-.099.44-.783.805-.64.341-1.597.584-2.717.584H17l-.416 1.951ZM6 6.014A9.163 9.163 0 0 0 5.5 6c-1.38 0-2.679.309-3.668.858C.877 7.388 0 8.282 0 9.5c0 .104.006.206.019.306A1.005 1.005 0 0 0 0 10v7.61c0 1.207.901 2.069 1.842 2.57.985.525 2.278.82 3.658.82.168 0 .335-.004.5-.013v-2.003c-.163.01-.33.016-.5.016-1.12 0-2.077-.243-2.717-.584C2.099 18.05 2 17.718 2 17.61v-1.508c.966.573 2.193.897 3.5.897.168 0 .335-.005.5-.016V14.98a5.83 5.83 0 0 1-.5.021c-1.08 0-2.005-.293-2.631-.712C2.236 13.864 2 13.388 2 13v-.769c.966.494 2.197.769 3.5.769.168 0 .335-.005.5-.014v-2.003a7.43 7.43 0 0 1-.5.017c-1.105 0-2.057-.251-2.696-.606C2.13 10.02 2 9.662 2 9.5c0-.162.13-.52.804-.894C3.444 8.251 4.394 8 5.5 8c.17 0 .337.006.5.017V6.014Z">
                </path>
              </svg>

              <div class="text-sm">
                <p>Support Chat (Online)</p>
                <p class="text-xs" style="color: #8ea5c0">
                  Welcome to the Support Chat.....
                </p>
              </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon qt-real" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none">
                <path fill="#8ea5c0" fill-rule="evenodd"
                  d="M7.832 1.858C8.822 1.308 10.12 1 11.5 1c1.38 0 2.678.309 3.668.858C16.123 2.388 17 3.282 17 4.5v16.11c0 1.207-.901 2.069-1.842 2.57-.984.525-2.278.82-3.658.82s-2.673-.295-3.658-.82C6.901 22.68 6 21.818 6 20.61V4.5c0-1.218.877-2.111 1.832-2.642ZM8 7.231V8.5c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98V7.231c-.966.494-2.197.769-3.5.769S8.966 7.725 8 7.231ZM15 4.5c0 .162-.13.52-.804.894-.64.355-1.59.606-2.696.606-1.105 0-2.057-.251-2.696-.606C8.13 5.019 8 4.662 8 4.5c0-.162.13-.52.804-.894C9.444 3.251 10.394 3 11.5 3c1.105 0 2.057.251 2.696.606.674.375.804.732.804.894Zm0 6.83c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.17c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98v-1.17Zm0 4c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.17c0 .333.171.67.77.98.622.32 1.572.52 2.73.52s2.108-.2 2.73-.52c.599-.31.77-.647.77-.98v-1.17Zm0 4c-.982.466-2.222.67-3.5.67s-2.518-.204-3.5-.67v1.28c0 .108.099.441.783.806.64.341 1.597.584 2.717.584s2.076-.243 2.717-.584c.684-.365.783-.698.783-.805V19.33Z"
                  clip-rule="evenodd"></path>
                <path fill="#8ea5c0"
                  d="M16.584 21.951c.416.049.4.049.916.049 1.38 0 2.674-.295 3.658-.82.941-.501 1.842-1.363 1.842-2.57V14.5c0-1.218-.877-2.111-1.832-2.642-.99-.55-2.288-.858-3.668-.858-.515 0-.02-.082-.5 0v2c.45-.104-.044 0 .5 0 1.105 0 2.057.251 2.696.606.674.374.804.732.804.894 0 .162-.13.52-.804.894-.64.355-1.59.606-2.696.606-.544 0-.05.104-.5 0v2h.5c1.303 0 2.534-.275 3.5-.769v1.38c0 .107-.099.44-.783.805-.64.341-1.597.584-2.717.584H17l-.416 1.951ZM6 6.014A9.163 9.163 0 0 0 5.5 6c-1.38 0-2.679.309-3.668.858C.877 7.388 0 8.282 0 9.5c0 .104.006.206.019.306A1.005 1.005 0 0 0 0 10v7.61c0 1.207.901 2.069 1.842 2.57.985.525 2.278.82 3.658.82.168 0 .335-.004.5-.013v-2.003c-.163.01-.33.016-.5.016-1.12 0-2.077-.243-2.717-.584C2.099 18.05 2 17.718 2 17.61v-1.508c.966.573 2.193.897 3.5.897.168 0 .335-.005.5-.016V14.98a5.83 5.83 0 0 1-.5.021c-1.08 0-2.005-.293-2.631-.712C2.236 13.864 2 13.388 2 13v-.769c.966.494 2.197.769 3.5.769.168 0 .335-.005.5-.014v-2.003a7.43 7.43 0 0 1-.5.017c-1.105 0-2.057-.251-2.696-.606C2.13 10.02 2 9.662 2 9.5c0-.162.13-.52.804-.894C3.444 8.251 4.394 8 5.5 8c.17 0 .337.006.5.017V6.014Z">
                </path>
              </svg>

              <div class="text-sm">
                <p>General Chat (English)</p>
                <p class="text-xs" style="color: #8ea5c0">
                  Welcome to the Support Chat.....
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- help sub left nav -->
        <div id="helpContent" class="hidden">
          <p class="flex items-center gap-2" onclick="goBack()">
            <span style="background: #262b3d; color: #8ea5c0"
              class="back-btn w-10 h-10 flex items-center justify-center rounded-md">← </span>Back
          </p>

          <div class="mt-5 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg class="svg-icon svg-icon-supp" width="24" height="24" fill="#8ea5c0"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 438.31 411">
                <path
                  d="M432.1 218.21l-26.47-26.47a21.18 21.18 0 00-29.89 0L355.44 212a5.29 5.29 0 000 7.49l48.87 48.87a5.29 5.29 0 007.49 0l20.3-20.3a21.11 21.11 0 000-29.85zm-86.57 11.27a5.34 5.34 0 00-7.53 0L222.38 345.11 213 398.6a10.57 10.57 0 0012.24 12.24l53.5-9.37 115.67-115.63a5.29 5.29 0 000-7.49zm-72.78 158.73l-28.4 5-13.7-13.7 5-28.4h16v21.14h21.14zM344 267.39l-67.8 67.81a6.17 6.17 0 11-8.72-8.72l67.81-67.81a6.16 6.16 0 018.71 8.72zM60.58 60.58H262.5v116.56l60.57-43.76v-6.72a30.3 30.3 0 00-11.6-23.84c-5.58-4.38-10.88-8.5-18.68-14.39V60.58a30.29 30.29 0 00-30.29-30.29h-48.94l-5.7-4.14C197.25 18.41 176.18-.22 161.54 0c-14.64-.22-35.71 18.41-46.33 26.15l-5.7 4.14H60.58a30.29 30.29 0 00-30.29 30.29v27.85c-7.81 5.89-13.1 10-18.69 14.39A30.3 30.3 0 000 126.66v6.72l60.58 43.76z">
                </path>
                <rect x="100.96" y="106.01" width="121.15" height="30.29" rx="10.1"></rect>
                <path
                  d="M323.07 213.81V158.3l-132 95.33a50.43 50.43 0 01-59.14 0L0 158.3v134.49a30.29 30.29 0 0030.29 30.29h183.52s109.26-101.52 109.26-109.27z">
                </path>
                <rect x="100.96" y="166.59" width="121.15" height="30.29" rx="10.1"></rect>
              </svg>

              <div class="text-sm">
                <p>Support service</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon guide-icon" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 500.04 500">
                <path
                  d="M256.36 500l-12.01-.06c-1.68-.04-10.22-.5-11.9-.6-16.63-1-28.09-3.45-36.43-5.21C96.36 473 17.17 388.21 2.81 287.34c-.07-.48-3.19-25.17-2.77-43.67 0 0 0-9.5.88-17.5 1.16-10.6 2.93-20.74 5.17-31.16C27.33 96.02 112.26 17.06 212.7 2.77 224.26 1.22 239.04 0 247.2 0h9.8c3.5 0 13.74.73 14.04.75 30.51 2.37 59.74 10.07 87.01 23.86 78.11 39.5 124.8 102.43 139.29 188.99.1.58.18 1.16.26 1.74 1.44 11.01 2.44 22.08 2.44 33.18v7.35c0 5.03-.25 10.05-.75 15.05-1.2 12.13-2.03 17.56-3.52 25.32-20.21 105.19-103.23 184.98-208.88 201.03-3.82.58-7.67 1.33-11.63 1.66-6.33.55-18.9 1.07-18.9 1.07zM178.4 220.44c-1.29 5.6 3.92 10.2 9.58 9.17 2.4-.43 7.61-1.25 9.72-1.43 16.84-1.24 25.59 4.18 22.71 26.26-2.61 20.01-9.67 38.99-14.96 58.37-4.46 16.36-9.82 32.5-9.65 49.77.17 16.97 8.26 28.72 23.46 35.66 11.41 5.21 23.56 5.46 35.77 4.31 16.16-1.52 38.56-11.22 44.7-14.06 1.89-.99 3.55-2.7 3.74-5.19.51-6.65-4.87-9.87-11.39-8.43-1.44.32-2.89.56-4.36.69-21.91 1.97-30.18-4.12-26.99-26.33 2.87-20.01 9.67-38.99 14.97-58.37 4.52-16.52 9.82-32.85 9.47-50.29-.33-16.73-8.23-28.5-23.36-35.17-10.61-4.68-21.95-5.24-33.35-3.92-16.37 1.9-30.43 7.63-45.71 13.82-2.44.98-3.92 3.28-4.35 5.14zM272.7 167c26.06-.03 44.29-23.47 36.25-46.63-5.36-15.44-21.48-25.35-39.54-24.31-22.43 1.29-38.42 21.2-34.38 42.8 2.96 15.78 19.53 28.16 37.67 28.14z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Plattform Guild</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon thumbs-up" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512">
                <path
                  d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Reviews</p>
              </div>
            </div>

            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon svg-icon-vip-chat-2"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 444.53 355.67">
                <path
                  d="M254.29 146.64q4.17-4.72 4.16-13.25 0-7.78-4.09-12.62a13.41 13.41 0 00-10.74-4.84h-6.78v34.51a20.52 20.52 0 005.91.94q7.38 0 11.54-4.74z">
                </path>
                <path
                  d="M418.12 144.43H348.7V43.59A43.63 43.63 0 00305.11 0H43.59A43.63 43.63 0 000 43.59v196.14a43.63 43.63 0 0043.59 43.59H109v57.21a8.19 8.19 0 0013 6.6l85-63.81h26.25v6.34a26.43 26.43 0 0026.4 26.41h59.45l51.53 38.61a4.69 4.69 0 002.93 1 4.93 4.93 0 004.95-4.95v-34.66h39.61a26.44 26.44 0 0026.41-26.41V170.84a26.44 26.44 0 00-26.41-26.41zm-265.39-30.71a15.13 15.13 0 00-7.94 4.06q-3 3.06-6 10.77L114 193.86h-2.63L86 129.36q-3.48-8.79-6.24-11.81a13 13 0 00-7.92-3.83v-2.35h36.58v2.35q-6.72 1.14-6.72 5.83a16.42 16.42 0 001.41 5.69l16 40.85 14.31-38.61a18.49 18.49 0 001.28-6q0-6.37-8.63-7.72v-2.35h26.64zm45.92 0c-4 .9-6.63 2.09-8 3.59s-2.07 4.6-2.07 9.3v51.55q0 5.43 1.93 7.58c1.29 1.43 4 2.67 8.14 3.69v2.35h-38v-2.35a23.9 23.9 0 006.41-2.35 7 7 0 003-3.22c.56-1.29.84-3.78.84-7.45V125q0-5.44-2-7.62t-8.22-3.66v-2.35h38zM239 186c1.46 1.61 4.08 2.75 7.89 3.42v2.35h-38v-2.35q6.11-1.2 8.15-3.65t2.05-8.56v-52.48q0-5.51-2-7.59t-8.25-3.42v-2.35h41.36q12.75 0 20.1 5.91a18.8 18.8 0 017.35 15.37 20.45 20.45 0 01-8.15 16.71q-8.16 6.51-22.46 6.51a79.72 79.72 0 01-10.2-.67v23q0 5.39 2.16 7.8zm185.7 103.65a6.62 6.62 0 01-6.6 6.6h-59.39V321L331 300.22l-5.28-4h-66a6.62 6.62 0 01-6.6-6.6v-6.34h52a43.63 43.63 0 0043.59-43.59v-75.45h69.42a6.62 6.62 0 016.6 6.6z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Support chat</p>
              </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 rounded-md" style="background: #293145">
              <svg width="24" height="24" fill="#8ea5c0" class="svg-icon apps-icon" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 500 510">
                <path
                  d="M500 195.1c0 3.6-.7 7.2-1.7 10.7-.1.2-.1.4-.2.6-4.9 12.3-15.2 19.5-28.4 19.6-20.5.1-41-.1-61.5.1-3.5 0-4.4-.8-4.4-4.3.2-24.1 0-48.3.2-72.4 0-6.3.4-11.5-4.8-16.8-.6-.6-.2-1.6.6-1.6 5.7-.2 4.2-4.4 4.1-8.4-.2-10.8-.1-21.7 0-32.5 0-4.8 0-6.5-9.3-5.6-14.7 1.5-19.9 1.7-33.3 2.7-5 .3-5.5.7-5.5 3.4.1 7.2-.1 14.3.1 21.5.1 2.8-1 3-4.3 3-8.4 0-15.2.1-24.4 0-3.5 0-4.4-.2-4.4-3 .1-13.3-.2-26.7.2-40 .5-14.8 13.3-27.8 28.1-27.9 40.1-.3 80.3-.3 120.4 0 12.6.1 23.4 9.2 27.2 21.9 0 .1 0 .2.1.3.6 3.1 1 6.3 1 9.5l.2 119.2zM465.1 80.9c0-2.4-.5-3.1-3-2.9-15.2 1.5-30.4 2.8-45.6 4.2-2.3.2-3 .9-3.1 4.5 0 14 0 22.1.1 37.7 0 4.8.3 5.7 3.6 5.6 14.8-.2 29.6-.2 44.4-.2 2.8 0 3.7-.7 3.7-3.6-.1-14.6-.1-31.6-.1-45.3zm-25.9 59c-7.6 0-15.3.1-23 0-2.1 0-3 0-3 2.8 0 13.4-.1 26.6 0 39.9 0 2.6.6 4.2 3.7 4.4 14.7 1.2 29.4 2.6 44.1 4 3.5.3 3.8-.4 3.8-6.4 0-14.6.1-24.8 0-38 0-6.1-.1-6.9-3.7-6.8-7.3.3-14.6.1-21.9.1z">
                </path>
                <path
                  d="M164 245v-80c0-19.5 12.6-32.1 32.2-32.1h157.4c19.9 0 32.3 12.5 32.3 32.4 0 35.3 0 70.6.1 106 0 3.5-.6 4.8-4.5 4.7-11.5-.3-23-.2-34.5-.1-3 0-5.6-.6-8-2.2-11.9-7.5-17-18.5-16.7-32.4.3-12.9 4.7-23.7 14.9-31.8 2.6-2.1 1-3.3-.4-4.7-10.1-10-24.8-18.3-43.1-10.6-10.5 4.4-20.8 4.6-31.5.9-23.8-8.4-47.1 4.7-52 30.3-6.3 33 3.7 61.5 27.3 85.2 7.1 7.2 17.7 8.2 26.8 3.7 12.3-6 13.6-5.3 13.6 7.8 0 10.5-.1 21 .1 31.5 0 2.6-.7 3.3-3.3 3.3-27.3-.1-54.6.1-82-.2-16-.2-28.7-13.6-28.8-29.9 0-27.1.1-54.5.1-81.8zm143.1-84.5c-.4-2.1 1.5-5.9-3.9-4.5-15.4 4-27 17.4-28.2 33-.3 3.5 1 4.3 4.2 3.8 14.1-2.2 27.7-17.8 27.9-32.3z">
                </path>
                <path
                  d="M368.1 437h-41c-18.2-.1-31.1-12.9-31.1-31.2v-80.5c0-18.5 12.9-31.3 31.4-31.3h81.5c18.2 0 31.1 13 31.1 31.2v80.5c0 18.6-12.8 31.3-31.4 31.3h-40.5zM333 340.4c1.1 1.2 1.1 3 0 4.2-1.8 1.9-3.5 3.8-5.1 5.8-9.9 12.3-10.6 26.8-10 41.6.1 2.3 2.1 2 3.5 2 10 .1 20 0 30 0 20.8 0 41.6 0 62.4-.1 1.4 0 3.9.3 4.1-1.8 1.1-17.5-1-34-14.5-46.8-2.1-2-2.5-3.2-.2-5.4 3.1-3.2 5.8-6.3 8.9-9.4 2.3-2.2 2.1-3.3-.2-5.3-1-.9-1.9-1.6-3-2.7-1.9-2-3.1-2.3-5 0-3.2 3.8-6.8 7.6-10.1 11.1-1.5 1.6-3.7 2.3-5.7 1.4-10.5-4.9-22.2-6.5-32.6-2.3-8.2 3.3-10.2 3.6-11.9 2.1-3-2.6-5.6-5.6-9.1-9.2-5.2-5.4-4.9-5.9-10.5-.6-2.6 2.3-2.4 3.6-.1 5.8 3.3 3 6.2 6.3 9.1 9.6z">
                </path>
                <path
                  d="M349.3 376.9c-2 1.2-4.5 1.2-6.5 0-1.8-1-2.8-2.7-2.7-5.2.1-3.6 2.5-5.9 6.2-5.7 3.4.2 5.6 2.4 5.6 5.8 0 2.5-1 4.1-2.6 5.1zM391 366c3.4.6 5.2 2.6 4.9 6.8-.2 2.6-2.1 4.7-4.7 5-4.1.6-6.8-1.7-7-5.5-.2-3.1 1.4-5.6 4.9-6.3 0 0 1.3-.1 1.9 0zM225.4 0c3.9 0 7.8.3 11.5 1.6C248.8 5.9 257 17.1 257 29.9c.1 27 0 54 .1 81 0 3.8-.4 4-7.7 4-8.8.1-16.6 0-24.9 0-6.9 0-7.6 0-7.6-3.7.1-21.5.1-43 .1-64.5 0-6.6 0-6.6-6.4-6.6-54.8 0-109.7.1-164.5-.1-4.7 0-6.3.7-6.3 6 .2 117.3.2 234.7 0 352 0 4.7.7 6.3 6 6.3 55.2-.2 110.3-.2 165.5-.1 4.3 0 6.1-.7 5.8-5.5-.5-6.6 0-13.3-.2-20-.1-2.7.5-3.7 3.5-3.7 11 .2 22 .2 33 0 2.7 0 3.7.5 3.7 3.5-.1 34.2.1 68.3-.2 102.5-.1 15.9-13.2 29-29.1 29-66.2.1-132.3.2-198.5 0-12.9 0-21.8-7-27.2-18.7C.6 487.7 0 483.8 0 480V31.4c0-4.1.5-8.3 2-12.1 3.3-8.2 9.1-14 17.3-17.3 3.6-1.4 7.6-2 11.5-2h194.6zm-96.9 488.9c18.4 1.2 33.6-14 32.4-32.4-1-15.2-13.3-27.5-28.4-28.5-18.4-1.2-33.6 14-32.4 32.4.9 15.3 13.2 27.6 28.4 28.5z">
                </path>
              </svg>
              <div class="text-sm">
                <p>Applications</p>
              </div>
            </div>
          </div>

          <div class="my-5">
            Follow us
            <div class="grid grid-cols-3 gap-2 mt-2">
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M18 9C18 13.9706 13.9706 18 9 18C4.02944 18 0 13.9706 0 9C0 4.02944 4.02944 0 9 0C13.9706 0 18 4.02944 18 9ZM9.32252 6.6442C8.44714 7.0083 6.6976 7.7619 4.07392 8.90499C3.64787 9.07442 3.42469 9.24016 3.40438 9.40223C3.37004 9.67612 3.71303 9.78397 4.1801 9.93084C4.24364 9.95082 4.30947 9.97152 4.37695 9.99346C4.83648 10.1428 5.45462 10.3176 5.77597 10.3245C6.06746 10.3308 6.3928 10.2106 6.75198 9.964C9.20337 8.30925 10.4688 7.47286 10.5482 7.45483C10.6043 7.44211 10.6819 7.42611 10.7346 7.47288C10.7872 7.51966 10.782 7.60824 10.7765 7.632C10.7425 7.77685 9.39609 9.02857 8.69934 9.67633C8.48213 9.87827 8.32806 10.0215 8.29656 10.0542C8.226 10.1275 8.1541 10.1968 8.08499 10.2635C7.65808 10.675 7.33793 10.9836 8.10271 11.4876C8.47023 11.7298 8.76432 11.9301 9.05772 12.1299C9.37813 12.3481 9.69772 12.5657 10.1112 12.8367C10.2166 12.9058 10.3172 12.9775 10.4152 13.0474C10.7881 13.3132 11.1231 13.5521 11.5369 13.514C11.7774 13.4918 12.0258 13.2657 12.152 12.5913C12.4502 10.9974 13.0362 7.54384 13.1717 6.12073C13.1835 5.99605 13.1686 5.83648 13.1566 5.76643C13.1446 5.69638 13.1196 5.59658 13.0285 5.5227C12.9207 5.4352 12.7542 5.41675 12.6798 5.41806C12.3413 5.42403 11.8219 5.60461 9.32252 6.6442Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M15.2378 3.11668C14.0905 2.59027 12.8602 2.20243 11.5739 1.9803C11.5505 1.97601 11.5271 1.98673 11.515 2.00816C11.3568 2.28957 11.1815 2.65669 11.0588 2.94525C9.6753 2.73812 8.29889 2.73812 6.94374 2.94525C6.82099 2.65028 6.63936 2.28957 6.48043 2.00816C6.46836 1.98744 6.44496 1.97673 6.42154 1.9803C5.13593 2.20172 3.90567 2.58956 2.7577 3.11668C2.74776 3.12096 2.73924 3.12811 2.73359 3.13739C0.400044 6.62366 -0.239213 10.0242 0.0743851 13.3827C0.075804 13.3991 0.0850274 13.4148 0.0977985 13.4248C1.63741 14.5555 3.12878 15.2419 4.59246 15.6968C4.61588 15.704 4.6407 15.6954 4.65561 15.6761C5.00184 15.2033 5.31048 14.7048 5.57511 14.1805C5.59072 14.1498 5.57582 14.1133 5.5439 14.1012C5.05435 13.9155 4.5882 13.6891 4.1398 13.4319C4.10433 13.4112 4.10149 13.3605 4.13412 13.3362C4.22848 13.2655 4.32286 13.1919 4.41297 13.1177C4.42927 13.1041 4.45198 13.1012 4.47115 13.1098C7.41696 14.4548 10.6062 14.4548 13.5172 13.1098C13.5364 13.1005 13.5591 13.1034 13.5761 13.1169C13.6662 13.1912 13.7606 13.2655 13.8557 13.3362C13.8883 13.3605 13.8862 13.4112 13.8507 13.4319C13.4023 13.6941 12.9361 13.9155 12.4459 14.1005C12.414 14.1126 12.3998 14.1498 12.4154 14.1805C12.6857 14.704 12.9943 15.2026 13.3342 15.6754C13.3484 15.6954 13.3739 15.704 13.3973 15.6968C14.8681 15.2419 16.3595 14.5555 17.8991 13.4248C17.9126 13.4148 17.9211 13.3998 17.9225 13.3834C18.2978 9.50067 17.2939 6.12798 15.2612 3.1381C15.2562 3.12811 15.2477 3.12096 15.2378 3.11668ZM6.01502 11.3377C5.12812 11.3377 4.39735 10.5235 4.39735 9.52354C4.39735 8.52358 5.11395 7.70934 6.01502 7.70934C6.92315 7.70934 7.64686 8.53073 7.63266 9.52354C7.63266 10.5235 6.91606 11.3377 6.01502 11.3377ZM11.9961 11.3377C11.1092 11.3377 10.3784 10.5235 10.3784 9.52354C10.3784 8.52358 11.095 7.70934 11.9961 7.70934C12.9042 7.70934 13.6279 8.53073 13.6137 9.52354C13.6137 10.5235 12.9042 11.3377 11.9961 11.3377Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
            </div>
          </div>

          <div>
            Official chanels:
            <div class="grid grid-cols-3 gap-2 mt-2">
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9 0C4.02948 0 0 4.02948 0 9C0 13.2206 2.90592 16.7623 6.82596 17.735V11.7504H4.97016V9H6.82596V7.81488C6.82596 4.75164 8.21232 3.3318 11.2198 3.3318C11.79 3.3318 12.7739 3.44376 13.1764 3.55536V6.04836C12.964 6.02604 12.595 6.01488 12.1367 6.01488C10.661 6.01488 10.0908 6.57396 10.0908 8.02728V9H13.0306L12.5255 11.7504H10.0908V17.9341C14.5472 17.3959 18.0004 13.6015 18.0004 9C18 4.02948 13.9705 0 9 0Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M18 9C18 13.9706 13.9706 18 9 18C4.02944 18 0 13.9706 0 9C0 4.02944 4.02944 0 9 0C13.9706 0 18 4.02944 18 9ZM9.32252 6.6442C8.44714 7.0083 6.6976 7.7619 4.07392 8.90499C3.64787 9.07442 3.42469 9.24016 3.40438 9.40223C3.37004 9.67612 3.71303 9.78397 4.1801 9.93084C4.24364 9.95082 4.30947 9.97152 4.37695 9.99346C4.83648 10.1428 5.45462 10.3176 5.77597 10.3245C6.06746 10.3308 6.3928 10.2106 6.75198 9.964C9.20337 8.30925 10.4688 7.47286 10.5482 7.45483C10.6043 7.44211 10.6819 7.42611 10.7346 7.47288C10.7872 7.51966 10.782 7.60824 10.7765 7.632C10.7425 7.77685 9.39609 9.02857 8.69934 9.67633C8.48213 9.87827 8.32806 10.0215 8.29656 10.0542C8.226 10.1275 8.1541 10.1968 8.08499 10.2635C7.65808 10.675 7.33793 10.9836 8.10271 11.4876C8.47023 11.7298 8.76432 11.9301 9.05772 12.1299C9.37813 12.3481 9.69772 12.5657 10.1112 12.8367C10.2166 12.9058 10.3172 12.9775 10.4152 13.0474C10.7881 13.3132 11.1231 13.5521 11.5369 13.514C11.7774 13.4918 12.0258 13.2657 12.152 12.5913C12.4502 10.9974 13.0362 7.54384 13.1717 6.12073C13.1835 5.99605 13.1686 5.83648 13.1566 5.76643C13.1446 5.69638 13.1196 5.59658 13.0285 5.5227C12.9207 5.4352 12.7542 5.41675 12.6798 5.41806C12.3413 5.42403 11.8219 5.60461 9.32252 6.6442Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9 1.6207C11.4047 1.6207 11.6895 1.63125 12.6352 1.67344C13.5141 1.71211 13.9887 1.85977 14.3051 1.98281C14.7234 2.14453 15.0258 2.34141 15.3387 2.6543C15.6551 2.9707 15.8484 3.26953 16.0102 3.68789C16.1332 4.0043 16.2809 4.48242 16.3195 5.35781C16.3617 6.30703 16.3723 6.5918 16.3723 8.99297C16.3723 11.3977 16.3617 11.6824 16.3195 12.6281C16.2809 13.507 16.1332 13.9816 16.0102 14.298C15.8484 14.7164 15.6516 15.0188 15.3387 15.3316C15.0223 15.648 14.7234 15.8414 14.3051 16.0031C13.9887 16.1262 13.5105 16.2738 12.6352 16.3125C11.6859 16.3547 11.4012 16.3652 9 16.3652C6.59531 16.3652 6.31055 16.3547 5.36484 16.3125C4.48594 16.2738 4.01133 16.1262 3.69492 16.0031C3.27656 15.8414 2.97422 15.6445 2.66133 15.3316C2.34492 15.0152 2.15156 14.7164 1.98984 14.298C1.8668 13.9816 1.71914 13.5035 1.68047 12.6281C1.63828 11.6789 1.62773 11.3941 1.62773 8.99297C1.62773 6.58828 1.63828 6.30352 1.68047 5.35781C1.71914 4.47891 1.8668 4.0043 1.98984 3.68789C2.15156 3.26953 2.34844 2.96719 2.66133 2.6543C2.97773 2.33789 3.27656 2.14453 3.69492 1.98281C4.01133 1.85977 4.48945 1.71211 5.36484 1.67344C6.31055 1.63125 6.59531 1.6207 9 1.6207ZM9 0C6.55664 0 6.25078 0.0105469 5.29102 0.0527344C4.33477 0.0949219 3.67734 0.249609 3.10781 0.471094C2.51367 0.703125 2.01094 1.00898 1.51172 1.51172C1.00898 2.01094 0.703125 2.51367 0.471094 3.1043C0.249609 3.67734 0.0949219 4.33125 0.0527344 5.2875C0.0105469 6.25078 0 6.55664 0 9C0 11.4434 0.0105469 11.7492 0.0527344 12.709C0.0949219 13.6652 0.249609 14.3227 0.471094 14.8922C0.703125 15.4863 1.00898 15.9891 1.51172 16.4883C2.01094 16.9875 2.51367 17.2969 3.1043 17.5254C3.67734 17.7469 4.33125 17.9016 5.2875 17.9438C6.24727 17.9859 6.55312 17.9965 8.99648 17.9965C11.4398 17.9965 11.7457 17.9859 12.7055 17.9438C13.6617 17.9016 14.3191 17.7469 14.8887 17.5254C15.4793 17.2969 15.982 16.9875 16.4813 16.4883C16.9805 15.9891 17.2898 15.4863 17.5184 14.8957C17.7398 14.3227 17.8945 13.6688 17.9367 12.7125C17.9789 11.7527 17.9895 11.4469 17.9895 9.00352C17.9895 6.56016 17.9789 6.2543 17.9367 5.29453C17.8945 4.33828 17.7398 3.68086 17.5184 3.11133C17.2969 2.51367 16.991 2.01094 16.4883 1.51172C15.9891 1.0125 15.4863 0.703125 14.8957 0.474609C14.3227 0.253125 13.6688 0.0984375 12.7125 0.05625C11.7492 0.0105469 11.4434 0 9 0Z"
                    fill="#8ea5c0"></path>
                  <path
                    d="M9 4.37695C6.44766 4.37695 4.37695 6.44766 4.37695 9C4.37695 11.5523 6.44766 13.623 9 13.623C11.5523 13.623 13.623 11.5523 13.623 9C13.623 6.44766 11.5523 4.37695 9 4.37695ZM9 11.9988C7.34414 11.9988 6.00117 10.6559 6.00117 9C6.00117 7.34414 7.34414 6.00117 9 6.00117C10.6559 6.00117 11.9988 7.34414 11.9988 9C11.9988 10.6559 10.6559 11.9988 9 11.9988Z"
                    fill="currentColor"></path>
                  <path
                    d="M14.8852 4.19417C14.8852 4.79182 14.4 5.27346 13.8059 5.27346C13.2082 5.27346 12.7266 4.78831 12.7266 4.19417C12.7266 3.59651 13.2117 3.11487 13.8059 3.11487C14.4 3.11487 14.8852 3.60003 14.8852 4.19417Z"
                    fill="currentColor"></path>
                </svg>
              </div>
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M13.7447 1.42798H16.2748L10.7473 7.7456L17.25 16.3425H12.1584L8.17053 11.1285L3.60746 16.3425H1.07582L6.98808 9.58505L0.75 1.42798H5.97083L9.57555 6.19373L13.7447 1.42798ZM12.8567 14.8281H14.2587L5.20905 2.86283H3.7046L12.8567 14.8281Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.8207 5.39998C17.8207 5.39998 17.6449 4.15896 17.1035 3.61404C16.418 2.89685 15.6516 2.89333 15.3 2.85115C12.7828 2.66833 9.00352 2.66833 9.00352 2.66833H8.99648C8.99648 2.66833 5.21719 2.66833 2.7 2.85115C2.34844 2.89333 1.58203 2.89685 0.896484 3.61404C0.355078 4.15896 0.182812 5.39998 0.182812 5.39998C0.182812 5.39998 0 6.85896 0 8.31443V9.67849C0 11.134 0.179297 12.5929 0.179297 12.5929C0.179297 12.5929 0.355078 13.834 0.892969 14.3789C1.57852 15.0961 2.47852 15.0715 2.8793 15.1488C4.3207 15.2859 9 15.3281 9 15.3281C9 15.3281 12.7828 15.3211 15.3 15.1418C15.6516 15.0996 16.418 15.0961 17.1035 14.3789C17.6449 13.834 17.8207 12.5929 17.8207 12.5929C17.8207 12.5929 18 11.1375 18 9.67849V8.31443C18 6.85896 17.8207 5.39998 17.8207 5.39998ZM7.14023 11.3344V6.27537L12.0023 8.81365L7.14023 11.3344Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
              <div class="flex items-center justify-center rounded-md py-2.5" style="background: #293145">
                <svg class="svg-icon" width="24" height="24" fill="#8ea5c0" viewBox="0 0 18 18"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M12.8044 0H9.77086V12.2608C9.77086 13.7218 8.60414 14.9218 7.1522 14.9218C5.70025 14.9218 4.53352 13.7218 4.53352 12.2608C4.53352 10.8261 5.67433 9.65215 7.07443 9.6V6.52175C3.98904 6.5739 1.5 9.10435 1.5 12.2608C1.5 15.4435 4.04089 18 7.17814 18C10.3153 18 12.8562 15.4174 12.8562 12.2608V5.9739C13.997 6.8087 15.3971 7.30435 16.875 7.33045V4.25217C14.5934 4.17391 12.8044 2.29565 12.8044 0Z"
                    fill="#8ea5c0"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="top-nav">
        @include('layouts.mobile.components.top-nav')
    </div>
    <!-- Restore original single container -->
    <div id="main-content" class="h-full overflow-y-auto"></div>
    <div class="bottom-nav">
        @include('layouts.mobile.components.bottom-nav')
    </div>
  </div>

  <!-- Replace welcome template with forex graph template -->
  <template id="welcome-template">
    <div class="h-full relative">
      <!-- Full screen chart -->
      <div class="chart-container">
        <div id="chart"></div>
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
      <div class="trading-actions">
        <div class="flex justify-between items-center">
          <div id="timeContainer" class="input-container cursor-pointer">
            <p class="text-sm text-gray-400">Time</p>
            <div>
              <span id="timeDisplay" class="text-white">00:01:00</span>
              <span class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"
                  class="injected-svg" data-src="/themes/cabinet/svg/icons/trading-panel/exp-mode-1.svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
                  <path
                    d="M10.4 7.2H8.8V4C8.8 3.78783 8.71572 3.58434 8.56569 3.43431C8.41566 3.28428 8.21217 3.2.5 8 3.2C7.78783 3.2 7.58435 3.28428 7.43432 3.43431C7.28429 3.58434 7.2 3.78783 7.2 4V8C7.2 8.21217 7.28429 8.41565 7.43432 8.56568C7.58435 8.71571 7.78783 8.8 8 8.8H10.4C10.6122 8.8 10.8157 8.71571 10.9657 8.56568C11.1157 8.41565 11.2 8.21217 11.2 8C11.2 7.78782 11.1157 7.58434 10.9657 7.43431C10.8157 7.28428 10.6122 7.2 10.4 7.2ZM8 0C6.41775 0 4.87103 0.469192 3.55544 1.34824C2.23985 2.22729 1.21447 3.47672 0.608967 4.93853C0.00346627 6.40034 -0.15496 8.00887 0.153721 9.56072C0.462403 11.1126 1.22433 12.538 2.34315 13.6569C3.46197 14.7757 4.88743 15.5376 6.43928 15.8463C7.99113 16.155 9.59966 15.9965 11.0615 15.391C12.5233 14.7855 13.7727 13.7602 14.6518 12.4446C15.5308 11.129 16 9.58225 16 8C16 6.94942 15.7931 5.90914 15.391 4.93853C14.989 3.96793 14.3997 3.08601 13.6569 2.34315C12.914 1.60028 12.0321 1.011 11.0615 0.608964C10.0909 0.206926 9.05058 0 8 0ZM8 14.4C6.7342 14.4 5.49683 14.0246 4.44435 13.3214C3.39188 12.6182 2.57157 11.6186 2.08717 10.4492C1.60277 9.27972 1.47603 7.9929 1.72298 6.75142C1.96992 5.50994 2.57946 4.36957 3.47452 3.47452C4.36958 2.57946 5.50995 1.96992 6.75142 1.72297C7.9929 1.47603 9.27973 1.60277 10.4492 2.08717C11.6186 2.57157 12.6182 3.39187 13.3214 4.44435C14.0246 5.49682 14.4 6.7342 14.4 8C14.4 9.69738 13.7257 11.3252 12.5255 12.5255C11.3253 13.7257 9.69739 14.4 8 14.4Z"
                    fill="currentColor"></path>
                </svg>
              </span>
            </div>
          </div>

          <div id="amountContainer" class="input-container cursor-pointer">
            <p class="text-sm text-gray-400">Amount</p>
            <div>
              <span id="amountDisplay" class="text-white">10</span>
              <span class="text-white"><svg class="currency-icon currency-icon--ngn" width="16" height="16"
                  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_1076_1427)">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24Z"
                      fill="currentColor"></path>
                    <path
                      d="M7.2934 6.5C7.2934 5.94771 7.74111 5.5 8.2934 5.5L8.73917 5.50001C9.50989 5.50006 10.212 5.94297 10.544 6.63853L12.1336 9.96905H14.7954V6.54328C14.7954 5.9671 15.2625 5.50002 15.8387 5.50005C16.4148 5.50007 16.8819 5.96714 16.8819 6.54328V9.96588L17.4612 9.9673C17.8558 9.96826 18.1752 10.2884 18.1752 10.683C18.1752 11.0784 17.8547 11.3988 17.4594 11.3988H16.8819V12.3939H17.4609C17.8554 12.3939 18.1752 12.7137 18.1752 13.1083C18.1752 13.5028 17.8554 13.8226 17.4609 13.8226H16.8819V17.1949C16.8819 17.9157 16.2975 18.5 15.5767 18.5C15.1095 18.5 14.6779 18.2502 14.4451 17.8451L12.1336 13.8226H9.51735V17.4233C9.51735 18.0155 9.03811 18.4959 8.44597 18.4974C7.86103 18.4988 7.38248 18.0319 7.36952 17.4471L7.28917 13.8226H6.71438C6.31985 13.8226 6.00002 13.5028 6.00003 13.1082C6.00005 12.7137 6.31987 12.3939 6.71438 12.3939H7.2934V11.3988H6.71491C6.3201 11.3988 6.00005 11.0788 6.00003 10.684C6.00001 10.2891 6.32008 9.96905 6.71491 9.96905H7.2934V6.5ZM9.51735 11.3988V12.3939H11.3151L10.8498 11.3988H9.51735ZM13.4026 12.3939H14.7954V11.3988H12.9458L13.4026 12.3939ZM14.7954 13.8226H14.236L14.7954 14.9859V13.8226ZM9.51735 8.96441V9.96905H10.0006L9.51735 8.96441Z"
                      fill="currentColor"></path>
                  </g>
                  <defs>
                    <clipPath id="clip0_1076_1427">
                      <rect width="24" height="24" fill="currentColor"></rect>
                    </clipPath>
                  </defs>
                </svg>
              </span>
            </div>
          </div>
        </div>
        <div class="flex justify-between text-green-400 text-lg font-semibold">
          <p>Payout<br /><span class="text-white text-sm">$16.20</span></p>
          <p>+62%</p>
          <p>Profit<br /><span class="text-white text-sm">+$6.20</span></p>
        </div>
        <div class="flex space-x-4">
          <button class="bg-green-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2">
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
          <button class="bg-red-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2">
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
      </div>
    </div>
  </template>

  <!-- NEW: Time Input Modal -->
  <div id="timeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-gray-800 p-4 rounded-lg w-80">
      <div>
        <div class="flex items-center justi text-sm ween gap-5">
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
        </div>

        <div class="text-white flex items-center justify-between">
          <div class="set_time_time" id="timeInput">01</div>
          :
          <div class="set_time_time">12</div>
          :
          <div class="set_time_time">33</div>
        </div>

        <div class="flex items-center justify-between gap-5">
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
        </div>
      </div>

      <!-- <input
          type="text"
          id="timeInput"
          class="w-full p-2 rounded-lg mb-4"
          placeholder="e.g. 00:01:00"
        /> -->

      <div class="flex justify-end space-x-2 mt-3">
        <button id="cancelTime" class="px-4 py-2 bg-gray-600 text-white rounded">
          Cancel
        </button>
        <button id="saveTime" class="px-4 py-2 bg-blue-500 text-white rounded">
          Save
        </button>
      </div>
    </div>
  </div>

  <!-- NEW: Calculator Modal for Amount (updated with basic operations) -->
  <div id="calculatorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-gray-800 p-4 rounded-lg w-80">
      <h3 class="text-white text-lg mb-4">Calculator</h3>
      <div class="flex items-center gap-3">
        <input type="text" id="calcDisplay"
          class="w-3/5 p-3 rounded-lg mb-4 text-right bg-gray-900 text-white border-gray-700 border" readonly
          value="10" />

        <div class="w-2/5 flex items-center gap-1">
          <div class="flex flex-col gap-1 mb-2">
            <button class="px-2 bg-gray-900 text-white rounded">*</button>
            <button class="px-2 bg-gray-900 text-white rounded">/</button>
          </div>

          <div>
            <input type="text" id="calcDisplay" class="p-3 w-full rounded-lg mb-4 text-right bg-gray-900 text-white"
              readonly value="2" />
          </div>
        </div>
      </div>
      <div id="calcButtons" class="grid grid-cols-4 gap-2 mb-4">
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">7</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">8</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">9</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">/</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">4</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">5</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">6</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">*</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">1</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">2</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">3</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">-</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">0</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">.</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">=</button>
        <button class="calc-btn bg-gray-900 text-white p-2 rounded">+</button>
        <!-- <button
            id="calcClear"
            class="col-span-4 bg-gray-700 text-white p-2 rounded"
          >
            Clear
          </button> -->
      </div>
      <div class="flex justify-end space-x-2">
        <button id="cancelCalc" class="px-4 py-2 bg-gray-600 text-white rounded">
          Cancel
        </button>
        <button id="confirmCalc" class="px-4 py-2 bg-blue-500 text-white rounded">
          Confirm
        </button>
      </div>
    </div>
  </div>

  <!-- Move scripts to end of body -->
  <!-- <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script> -->
  <!-- <script src="./js/custom-chart.js"></script> -->
  <script src="{{ asset('mobile/js/navigation.js') }}"></script>
  <script src="{{ asset('mobile/js/dropdown.js') }}"></script>
  <script src="{{ asset('mobile/js/account-dropdown.js') }}"></script>
  <script src="{{ asset('mobile/js/wallet-modal.js') }}"></script>
  <script src="{{ asset('mobile/js/tabs.js') }}"></script>
  <script src="{{ asset('mobile/js/custom.js') }}"></script>
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