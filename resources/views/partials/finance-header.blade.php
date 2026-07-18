<ul>
    <li class="flex flex-wrap gap-2 uppercase my-3">
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-lg transition border @if(Route::is('deposit.create')) bg-[#4f8ef7] text-white border-[#4f8ef7] @else bg-[#1c243c] text-[#7c86a3] border-[#2a3350] hover:border-[#4f8ef7] @endif" href="{{ route('deposit.create') }}">Deposit</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-lg transition border @if(Route::is('payout.create')) bg-[#4f8ef7] text-white border-[#4f8ef7] @else bg-[#1c243c] text-[#7c86a3] border-[#2a3350] hover:border-[#4f8ef7] @endif" href="{{ route('payout.create') }}">Withdrawal</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-lg transition border @if(Route::is('finance.history')) bg-[#4f8ef7] text-white border-[#4f8ef7] @else bg-[#1c243c] text-[#7c86a3] border-[#2a3350] hover:border-[#4f8ef7] @endif" href="{{ route('finance.history') }}">History</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-lg transition border @if(Route::is('finance.cashback')) bg-[#4f8ef7] text-white border-[#4f8ef7] @else bg-[#1c243c] text-[#7c86a3] border-[#2a3350] hover:border-[#4f8ef7] @endif" href="{{ route('finance.cashback') }}">Cashback</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-lg transition border @if(Route::is('finance.promo-codes')) bg-[#4f8ef7] text-white border-[#4f8ef7] @else bg-[#1c243c] text-[#7c86a3] border-[#2a3350] hover:border-[#4f8ef7] @endif" href="{{ route('finance.promo-codes') }}">Promo codes</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-lg transition border @if(Route::is('finance.my-safe')) bg-[#4f8ef7] text-white border-[#4f8ef7] @else bg-[#1c243c] text-[#7c86a3] border-[#2a3350] hover:border-[#4f8ef7] @endif" href="{{ route('finance.my-safe') }}">My Safe</a>
    </li>
</ul>
