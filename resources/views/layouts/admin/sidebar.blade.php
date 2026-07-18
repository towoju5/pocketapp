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
            ['admin.cashbacks.index', 'Cashback Rules', 'heroicon-o-receipt-percent'],
        ],
        'Trading & Yield' => [
            ['admin.signals.index', 'Signals', 'heroicon-o-signal'],
            ['admin.plans.index', 'Plans', 'heroicon-o-banknotes'],
            ['admin.plan-subscriptions.index', 'Plan Subscriptions', 'heroicon-o-arrow-path'],
            ['admin.p2p-offers.index', 'P2P Offers', 'heroicon-o-arrows-right-left'],
            ['admin.p2p-trades.index', 'P2P Trades', 'heroicon-o-shield-check'],
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
    class="fixed inset-y-0 left-0 z-50 flex w-[290px] flex-col border-r border-glass-border bg-glass-surface backdrop-blur-xl transition-transform duration-200 lg:translate-x-0"
>
    <div class="flex h-20 items-center gap-2 px-6">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-amber/10 text-lg">🌌</span>
        <div>
            <p class="text-sm font-extrabold text-white">{{ config('app.name') }}</p>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-brand-amber">Admin Console</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-4 pb-6">
        @foreach ($navGroups as $group => $links)
            @php $visibleLinks = collect($links)->filter(fn($l) => Route::has($l[0])); @endphp
            @continue($visibleLinks->isEmpty())

            <div>
                <button type="button" @click="openGroup = (openGroup === '{{ $group }}' ? '' : '{{ $group }}')"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-400 hover:text-white">
                    {{ $group }}
                    <x-heroicon-o-chevron-down class="h-3.5 w-3.5 shrink-0 transition-transform" x-bind:class="openGroup === '{{ $group }}' ? 'rotate-180' : ''" />
                </button>

                <div x-show="openGroup === '{{ $group }}'" x-collapse class="mt-1 space-y-0.5">
                    @foreach ($visibleLinks as [$routeName, $label, $icon])
                        @php
                            $parts = explode('.', $routeName);
                            $isActive = request()->routeIs($parts[0] . '.' . $parts[1] . '.*');
                        @endphp
                        <a href="{{ route($routeName) }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition {{ $isActive ? 'bg-brand-amber/10 text-brand-amber' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <x-dynamic-component :component="$icon" class="h-4.5 w-4.5 shrink-0" />
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>
