{{-- Mobile-only bottom tab bar. Reuses the exact same .rail-nav-btn class and
     data-nav attributes as the desktop rail — TradingShell.js wires up nav
     buttons by class/attribute (not by a fixed element), so this set is
     picked up automatically with zero JS changes. Only the major
     destinations show directly; everything else lives behind "Menu". --}}
<div class="flex sm:hidden h-14 flex-shrink-0 items-center gap-1.5 border-t border-[#2a3350] bg-[#171e33] px-2 box-border">
    <a href="{{ route('dashboard') }}" class="rail-nav-btn rail-nav-btn--active flex-1" data-nav="trading" style="background:#1c243c;border:1px solid #4f8ef7;border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#4f8ef7;text-decoration:none;">
        <i class="fa fa-chart-line" style="font-size:14px;"></i>Trading
    </a>
    <button type="button" class="rail-nav-btn flex-1" data-nav="finance" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-wallet" style="font-size:14px;"></i>Finance
    </button>
    <a href="{{ route('profile.edit', ['tab' => 'account']) }}" class="rail-nav-btn flex-1" data-nav="settings" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;text-decoration:none;">
        <i class="fa fa-gear" style="font-size:14px;"></i>Settings
    </a>
    <a href="{{ route('p2p-offers.index') }}" class="rail-nav-btn flex-1" data-nav="market" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;text-decoration:none;">
        <i class="fa fa-arrow-right-arrow-left" style="font-size:14px;"></i>P2P
    </a>
    <button type="button" class="rail-nav-btn flex-1" data-nav="more" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-bars" style="font-size:14px;"></i>Menu
    </button>
</div>
