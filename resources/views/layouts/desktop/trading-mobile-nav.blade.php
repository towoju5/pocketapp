{{-- Mobile-only bottom tab bar. Reuses the exact same .rail-nav-btn class and
     data-nav attributes as the desktop rail — TradingShell.js wires up nav
     buttons by class/attribute (not by a fixed element), so this set is
     picked up automatically with zero JS changes. Only the major
     destinations show directly; everything else lives behind "Menu". --}}
@php
    $mobileActiveNav = match(true) {
        request()->routeIs('dashboard*') => 'trading',
        request()->routeIs('wallet.*', 'finance.*') => 'finance',
        request()->routeIs('profile.*') => 'settings',
        request()->routeIs('p2p-offers.*', 'p2p-trades.*') => 'market',
        default => null,
    };
    $mobileNavBorder = fn ($key) => $mobileActiveNav === $key ? '#4f8ef7' : '#2a3350';
    $mobileNavColor = fn ($key) => $mobileActiveNav === $key ? '#4f8ef7' : '#7c86a3';
    $mobileNavClass = fn ($key) => 'rail-nav-btn flex-1' . ($mobileActiveNav === $key ? ' rail-nav-btn--active' : '');
@endphp
<div class="flex sm:hidden h-14 flex-shrink-0 items-center gap-1.5 border-t border-[#2a3350] bg-[#171e33] px-2 box-border">
    <a href="{{ route('dashboard') }}" class="{{ $mobileNavClass('trading') }}" data-nav="trading" style="background:#1c243c;border:1px solid {{ $mobileNavBorder('trading') }};border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:{{ $mobileNavColor('trading') }};text-decoration:none;">
        <i class="fa fa-chart-line" style="font-size:14px;"></i>Trading
    </a>
    <button type="button" class="{{ $mobileNavClass('finance') }}" data-nav="finance" style="background:#1c243c;border:1px solid {{ $mobileNavBorder('finance') }};border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:{{ $mobileNavColor('finance') }};cursor:pointer;">
        <i class="fa fa-wallet" style="font-size:14px;"></i>Finance
    </button>
    <a href="{{ route('profile.edit', ['tab' => 'account']) }}" class="{{ $mobileNavClass('settings') }}" data-nav="settings" style="background:#1c243c;border:1px solid {{ $mobileNavBorder('settings') }};border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:{{ $mobileNavColor('settings') }};text-decoration:none;">
        <i class="fa fa-gear" style="font-size:14px;"></i>Settings
    </a>
    <a href="{{ route('p2p-offers.index') }}" class="{{ $mobileNavClass('market') }}" data-nav="market" style="background:#1c243c;border:1px solid {{ $mobileNavBorder('market') }};border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:{{ $mobileNavColor('market') }};text-decoration:none;">
        <i class="fa fa-arrow-right-arrow-left" style="font-size:14px;"></i>P2P
    </a>
    <button type="button" class="rail-nav-btn flex-1" data-nav="more" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-bars" style="font-size:14px;"></i>Menu
    </button>
</div>
