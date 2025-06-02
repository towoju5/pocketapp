@php 
  $tradingMenuArray = ['trades.index', 'trades.create'];  
  $financeMenuArray = ['wallet.index', 'deposits.index'];
@endphp

@if(in_array(Route::currentRouteName(), $tradingMenuArray))
<section>
  <div style="background: #202434" class="absolute bottom-0 left-0 right-0 backdrop-blur-sm border-t border-gray-700">
    <div class="flex justify-between px-0.5 py-0.5 gap-1 max-w-[390px] mx-auto">
      <button style="background: #293145; color: #8ea5c0"
        class="nav-item flex rounded-md flex-col items-center p-1 text-gray-400 flex-1" data-target="trades"
        onclick="handleNavigation(this)">
        <!-- Trades SVG -->
        <svg class="w-4 h-4" fill="#8ea5c0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <!-- simplified for clarity -->
          <path d="..."></path>
        </svg>
        <span class="text-xs mt-0.5 truncate w-full text-center">Trades</span>
      </button>

      <button style="background: #293145; color: #8ea5c0"
        class="nav-item flex flex-col rounded-md items-center p-1 text-gray-400 flex-1" data-target="pending"
        onclick="handleNavigation(this)">
        <!-- Pending SVG -->
        <svg class="w-4 h-4" fill="#8ea5c0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 612 612">
          <!-- simplified for clarity -->
          <path d="..."></path>
        </svg>
        <span class="text-xs mt-0.5 truncate w-full text-center">Pending</span>
      </button>
    </div>
  </div>
</section>

@elseif(in_array(Route::currentRouteName(), $financeMenuArray))
<!-- Finance Navigation Example -->
<section>
  <div class="absolute bottom-0 left-0 right-0 bg-[#202434] backdrop-blur-sm border-t border-gray-700">
    <div class="flex justify-between px-0.5 py-0.5 gap-1 max-w-[390px] mx-auto">
      <button style="background: #293145; color: #8ea5c0"
        class="nav-item flex rounded-md flex-col items-center p-1 text-gray-400 flex-1" data-target="signals"
        onclick="handleNavigation(this)">
        <!-- Signals SVG -->
        <svg fill="#8ea5c0" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
          <!-- simplified for clarity -->
          <path d="..."></path>
        </svg>
        <span class="text-xs mt-0.5 truncate w-full text-center">Signals</span>
      </button>
    </div>
  </div>
</section>
@endif
