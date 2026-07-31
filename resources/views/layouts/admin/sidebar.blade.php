@php
    // Each group: label => [ [routeName, label, icon], ... ]. Guarded by
    // Route::has() so nav entries only appear once their feature phase lands.
    $navGroups = [
        'Overview' => [
            ['admin.dashboard', 'Dashboard', 'heroicon-o-squares-2x2'],
        ],
        'Financial' => [
            ['admin.assets.index', 'Assets', 'heroicon-o-chart-bar'],
            ['admin.wallets.index', 'Wallets', 'heroicon-o-wallet'],
            ['admin.deposits.index', 'Deposits', 'heroicon-o-arrow-down-circle'],
            ['admin.payouts.index', 'Withdrawals', 'heroicon-o-arrow-up-circle'],
            ['admin.payment-providers.index', 'Payment Providers', 'heroicon-o-building-library'],
            ['admin.cashbacks.index', 'Cashback Rules', 'heroicon-o-receipt-percent'],
            ['admin.promo-codes.index', 'Promo Codes', 'heroicon-o-gift'],
        ],
        'Trading & Yield' => [
            ['admin.trades.index', 'Trades', 'heroicon-o-presentation-chart-line'],
            ['admin.express-trades.index', 'Express Trades', 'heroicon-o-bolt'],
            ['admin.signals.index', 'Signals', 'heroicon-o-signal'],
            ['admin.plans.index', 'Plans', 'heroicon-o-banknotes'],
            ['admin.plan-subscriptions.index', 'Plan Subscriptions', 'heroicon-o-arrow-path'],
            ['admin.p2p-offers.index', 'P2P Offers', 'heroicon-o-arrows-right-left'],
            ['admin.p2p-trades.index', 'P2P Trades', 'heroicon-o-shield-check'],
            ['admin.payment-methods.index', 'P2P Payment Methods', 'heroicon-o-credit-card'],
        ],
        'People' => [
            ['admin.users.index', 'Users', 'heroicon-o-users'],
            ['admin.kyc.index', 'KYC Requests', 'heroicon-o-identification'],
            ['admin.referrals.index', 'Referrals', 'heroicon-o-share'],
            ['admin.referral-rates.index', 'Referral Rates', 'heroicon-o-adjustments-horizontal'],
        ],
        'Growth' => [
            ['admin.tasks.index', 'Tasks', 'heroicon-o-check-circle'],
            ['admin.task-submissions.index', 'Task Submissions', 'heroicon-o-inbox-stack'],
        ],
        'Support' => [
            ['admin.support-tickets.index', 'Support Tickets', 'heroicon-o-lifebuoy'],
        ],
        'Platform' => [
            ['admin.settings.index', 'Settings', 'heroicon-o-cog-6-tooth'],
        ],
    ];

    $activeGroup = 'Overview';
    foreach ($navGroups as $group => $links) {
        foreach ($links as [$routeName]) {
            if (Route::has($routeName)) {
                $parts = explode('.', $routeName);
                $prefix = $parts[0] . '.' . $parts[1] . '.*';
                if (request()->routeIs($prefix)) {
                    $activeGroup = $group;
                    break 2;
                }
            }
        }
    }
@endphp

<aside
    x-data="{ openGroup: '{{ $activeGroup }}' }"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 flex w-[290px] flex-col border-r border-glass-border bg-gradient-to-b from-glass-surface to-brand-deep transition-transform duration-200 lg:translate-x-0"
>
    <div class="flex h-20 items-center gap-3 border-b border-glass-border/60 px-6">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-amber/25 to-brand-amber/5 text-lg ring-1 ring-inset ring-brand-amber/20">🌌</span>
        <div class="min-w-0">
            <p class="truncate text-sm font-extrabold tracking-tight text-white">{{ config('app.name') }}</p>
            <p class="text-[11px] font-semibold uppercase tracking-wider text-brand-amber/90">Admin Console</p>
        </div>
    </div>

    <nav class="sidebar-nav flex-1 space-y-4 overflow-y-auto px-4 py-5">
        @foreach ($navGroups as $group => $links)
            @php $visibleLinks = collect($links)->filter(fn($l) => Route::has($l[0])); @endphp
            @continue($visibleLinks->isEmpty())

            <div>
                <button type="button" @click="openGroup = (openGroup === '{{ $group }}' ? '' : '{{ $group }}')"
                        class="group flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 transition hover:text-slate-300">
                    <span class="flex items-center gap-2">
                        <span class="h-1 w-1 rounded-full transition" :class="openGroup === '{{ $group }}' ? 'bg-brand-amber' : 'bg-slate-600 group-hover:bg-slate-400'"></span>
                        {{ $group }}
                    </span>
                    <x-heroicon-o-chevron-down class="h-3.5 w-3.5 shrink-0 text-slate-600 transition-transform group-hover:text-slate-400" x-bind:class="openGroup === '{{ $group }}' ? 'rotate-180' : ''" />
                </button>

                <div x-show="openGroup === '{{ $group }}'" x-collapse class="relative mt-1 space-y-0.5 pl-2">
                    <div class="absolute bottom-1 left-[19px] top-1 w-px bg-glass-border/60"></div>

                    @foreach ($visibleLinks as [$routeName, $label, $icon])
                        @php
                            $parts = explode('.', $routeName);
                            $isActive = request()->routeIs($parts[0] . '.' . $parts[1] . '.*');
                        @endphp
                        <a href="{{ route($routeName) }}"
                           class="group/link relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-brand-amber/10 text-brand-amber shadow-[inset_2px_0_0_0] shadow-brand-amber' : 'text-slate-300 hover:translate-x-0.5 hover:bg-white/5 hover:text-white' }}">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg transition {{ $isActive ? 'bg-brand-amber/15' : 'bg-white/5 group-hover/link:bg-white/10' }}">
                                <x-dynamic-component :component="$icon" class="h-4 w-4 shrink-0" />
                            </span>
                            <span class="truncate">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>
