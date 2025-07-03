
  @include("components.chart", ["data" => $data])

    <template id="welcome-template">
    <div class="h-full relative">
      <!-- Full screen chart -->
      <div class="chart-container">
        <!-- <div id="chart"></div> -->
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