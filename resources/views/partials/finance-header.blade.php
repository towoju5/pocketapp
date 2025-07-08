@if(!is_mobile())
<ul>
    <li class="flex gap-4 uppercase text-white my-3">
        <a class="py-2 px-6 text-[12px] rounded shadow-md @if(Route::is('deposit.create')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('deposit.create') }}">Deposit</a>
        <a class="py-2 px-6 text-[12px] rounded shadow-md @if(Route::is('payout.create')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('payout.create') }}">Withdrawal</a>
        <a class="py-2 px-6 text-[12px] rounded shadow-md @if(Route::is('finance.history')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('finance.history') }}">History</a>
        <a class="py-2 px-6 text-[12px] rounded shadow-md @if(Route::is('finance.cashback')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('finance.cashback') }}">Cashback</a>
        <a class="py-2 px-6 text-[12px] rounded shadow-md @if(Route::is('finance.promo-codes')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('finance.promo-codes') }}">Promo codes</a>
        <a class="py-2 px-6 text-[12px] rounded shadow-md @if(Route::is('finance.my-safe')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('finance.my-safe') }}">My Safe</a> 
    </li>
</ul>
@endif