{{-- Mobile-only bottom tab bar. Reuses the exact same .rail-nav-btn class and
     data-nav attributes as the desktop rail — TradingShell.js wires up nav
     buttons by class/attribute (not by a fixed element), so this set is
     picked up automatically with zero JS changes. --}}
<div class="flex sm:hidden h-14 flex-shrink-0 items-center gap-1.5 overflow-x-auto border-t border-[#2a3350] bg-[#171e33] px-2 box-border">
    <a href="{{ route('dashboard') }}" class="rail-nav-btn rail-nav-btn--active" data-nav="trading" style="background:#1c243c;border:1px solid #4f8ef7;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#4f8ef7;text-decoration:none;">
        <i class="fa fa-chart-line" style="font-size:14px;"></i>Trading
    </a>
    <button type="button" class="rail-nav-btn" data-nav="finance" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-wallet" style="font-size:14px;"></i>Finance
    </button>
    <button type="button" class="rail-nav-btn" data-nav="profile" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-user" style="font-size:14px;"></i>Profile
    </button>
    <a href="{{ route('chat.index') }}" class="rail-nav-btn" data-nav="chat" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;text-decoration:none;">
        <i class="fa fa-comments" style="font-size:14px;"></i>Chat
    </a>
    <button type="button" class="rail-nav-btn" data-nav="achievements" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-trophy" style="font-size:14px;"></i>Rewards
    </button>
    <a href="{{ route('tournaments.index') }}" class="rail-nav-btn" data-nav="tournaments" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;text-decoration:none;">
        <i class="fa fa-medal" style="font-size:14px;"></i>Tourney
    </a>
    <button type="button" class="rail-nav-btn" data-nav="help" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:6px 4px;width:56px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:9px;font-weight:700;color:#7c86a3;cursor:pointer;">
        <i class="fa fa-circle-question" style="font-size:14px;"></i>Help
    </button>
</div>
