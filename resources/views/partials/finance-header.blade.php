<ul>
    <li class="flex flex-wrap gap-2 uppercase text-white my-3">
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-xl shadow-md transition @if(Route::is('deposit.create')) bg-brand-blue @else bg-[rgba(15,23,42,0.6)] border border-glass-border hover:border-brand-blue @endif" href="{{ route('deposit.create') }}">Deposit</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-xl shadow-md transition @if(Route::is('payout.create')) bg-brand-blue @else bg-[rgba(15,23,42,0.6)] border border-glass-border hover:border-brand-blue @endif" href="{{ route('payout.create') }}">Withdrawal</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-xl shadow-md transition @if(Route::is('finance.history')) bg-brand-blue @else bg-[rgba(15,23,42,0.6)] border border-glass-border hover:border-brand-blue @endif" href="{{ route('finance.history') }}">History</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-xl shadow-md transition @if(Route::is('finance.cashback')) bg-brand-blue @else bg-[rgba(15,23,42,0.6)] border border-glass-border hover:border-brand-blue @endif" href="{{ route('finance.cashback') }}">Cashback</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-xl shadow-md transition @if(Route::is('finance.promo-codes')) bg-brand-blue @else bg-[rgba(15,23,42,0.6)] border border-glass-border hover:border-brand-blue @endif" href="{{ route('finance.promo-codes') }}">Promo codes</a>
        <a class="py-2 px-6 text-[11px] font-bold tracking-wide rounded-xl shadow-md transition @if(Route::is('finance.my-safe')) bg-brand-blue @else bg-[rgba(15,23,42,0.6)] border border-glass-border hover:border-brand-blue @endif" href="{{ route('finance.my-safe') }}">My Safe</a>
    </li>
</ul>