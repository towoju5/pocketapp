<body>
  <div class="text-white h-full">
    <!-- Signals Header -->
    <div class="h-full pb-20">
      <h1 class="text-lg font-medium mb-4 px-4">Signals</h1>

      <!-- Filter Buttons -->
      <div class="flex items-center mb-6 px-4">
        <div class="flex-1 flex space-x-3">
          <button
            onclick="switchTab('updates')"
            class="tab-btn px-6 py-2 bg-blue-600 rounded-lg text-sm font-medium transition-colors duration-200"
          >
            Updates
          </button>
          <button
            onclick="switchTab('all-updates')"
            class="tab-btn px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors duration-200"
          >
            All
          </button>
        </div>
        <div class="flex space-x-2">
          <button
            onclick="switchTab('settings-icon')"
            class="tab-btn p-2.5 rounded-lg hover:bg-gray-700"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              />
            </svg>
          </button>
          <button
            onclick="switchTab('telegram-bot')"
            class="tab-btn p-2.5 rounded-lg hover:bg-gray-700"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
              />
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
        <div
          class="flex items-center justify-between px-3 py-1"
          style="background: #292d41"
        >
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
        <div
          class="flex items-center justify-between px-3 py-1"
          style="background: #292d41"
        >
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
        <div
          class="flex items-center justify-between px-3 py-1"
          style="background: #292d41"
        >
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
        <div
          class="flex justify-between items-center mb-2 gap-2 rounded-lg py-2 px-4"
          style="background: #1d2130; border: 1px solid #454a56"
        >
          <svg
            class="w-8 h-8"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                d="M16.2429 5.75708C18.586 8.10023 18.586 11.8992 16.2429 14.2424M7.75758 14.2424C5.41443 11.8992 5.41443 8.10023 7.75758 5.75708M4.92893 17.0708C1.02369 13.1656 1.02369 6.83395 4.92893 2.92871M19.0715 2.92871C22.9768 6.83395 22.9768 13.1656 19.0715 17.0708M12.0002 11.9998C13.1048 11.9998 14.0002 11.1043 14.0002 9.99976C14.0002 8.89519 13.1048 7.99976 12.0002 7.99976C10.8957 7.99976 10.0002 8.89519 10.0002 9.99976C10.0002 11.1043 10.8957 11.9998 12.0002 11.9998ZM12.0002 11.9998V20.9998"
                stroke="#b5b5b5"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></path>
            </g>
          </svg>
          <select class="w-full text-white text-xs bg-transparent">
            <option>M1</option>
            <option>M2</option>
            <option>M3</option>
          </select>
        </div>
        <h2
          class="text-lg font-semibold text-center border-b border-gray-600 pb-2 px-4"
        >
          Currencies
        </h2>
        <div class="mt-4">
          <div class="flex justify-between p-2 text-xs">
            <span>AED/CNY OTC</span>
            <span class="text-red-500">↓↓</span>
          </div>
          <div
            class="flex justify-between p-2 text-xs"
            style="background: #292d41"
          >
            <span>AUD/CAD OTC</span>
            <span class="text-green-400">↑</span>
          </div>
          <div class="flex justify-between p-2 text-xs">
            <span>AUD/CHF OTC</span>
            <span class="text-green-400">↑↑</span>
          </div>
          <div
            class="flex justify-between p-2 text-xs"
            style="background: #292d41"
          >
            <span>AUD/JPY OTC</span>
            <span class="text-red-400">↓</span>
          </div>
          <div class="flex justify-between p-2 text-xs">
            <span>AED/CNY OTC</span>
            <span class="text-red-500">↓↓</span>
          </div>
          <div
            class="flex justify-between p-2 text-xs"
            style="background: #292d41"
          >
            <span>AUD/CAD OTC</span>
            <span class="text-green-400">↑</span>
          </div>
          <div class="flex justify-between p-2 text-xs">
            <span>AUD/CHF OTC</span>
            <span class="text-green-400">↑↑</span>
          </div>
          <div
            class="flex justify-between p-2 text-xs"
            style="background: #292d41"
          >
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
            <div
              class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
            ></div>
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
        <a
          href="https://t.me/YOUR_TELEGRAM_BOT"
          target="_blank"
          class="mt-4 inline-block bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg text-center text-xs"
        >
          Telegram Bot
        </a>
      </div>
    </div>
  </div>
</body>
