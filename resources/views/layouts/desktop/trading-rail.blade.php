<div class="relative flex-shrink-0">
    <div class="flex max-sm:hidden w-[84px] h-full bg-[#171e33] border-r border-[#2a3350] flex-col items-center py-3 overflow-y-auto box-border">
        <div class="flex flex-col items-center gap-1 w-full">
            <a href="{{ route('dashboard') }}" class="rail-nav-btn rail-nav-btn--active" data-nav="trading" style="background:#1c243c;border:1px solid #4f8ef7;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#4f8ef7;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-chart-line" style="font-size:16px;"></i>Trading
            </a>
            <button type="button" class="rail-nav-btn" data-nav="finance" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;margin-bottom:4px;cursor:pointer;">
                <i class="fa fa-wallet" style="font-size:16px;"></i>Finance
            </button>
            <button type="button" class="rail-nav-btn" data-nav="profile" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;margin-bottom:4px;cursor:pointer;">
                <i class="fa fa-user" style="font-size:16px;"></i>Profile
            </button>
            <button type="button" class="rail-nav-btn" data-nav="market" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;margin-bottom:4px;cursor:pointer;">
                <i class="fa fa-chart-column" style="font-size:16px;"></i>Market
            </button>
            <button type="button" class="rail-nav-btn" data-nav="achievements" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;margin-bottom:4px;cursor:pointer;">
                <i class="fa fa-trophy" style="font-size:16px;"></i>Rewards
            </button>
            <a href="{{ route('tournaments.index') }}" class="rail-nav-btn" data-nav="tournaments" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-medal" style="font-size:16px;"></i>Tournaments
            </a>
            <a href="{{ route('chat.index') }}" class="rail-nav-btn" data-nav="chat" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-comments" style="font-size:16px;"></i>Chat
            </a>
            <button type="button" class="rail-nav-btn" data-nav="help" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 4px;width:58px;display:flex;flex-direction:column;align-items:center;gap:4px;font-size:9.5px;font-weight:700;color:#7c86a3;cursor:pointer;">
                <i class="fa fa-circle-question" style="font-size:16px;"></i>Help
            </button>
        </div>

        <div class="mt-auto w-full px-2 pb-1 flex flex-col gap-1 flex-shrink-0">
            <a href="{{ route('referrals.index') }}" class="rounded-md text-center text-[8.5px] font-bold p-1 text-white block" style="background:linear-gradient(180deg,#a855f7,#4c1d78);line-height:1.2;text-decoration:none;">
                <span class="block text-xs">🤝</span>REFER
            </a>
            <a href="{{ route('finance.promo-codes') }}" class="rounded-md text-center text-[8.5px] font-bold p-1 text-white block" style="background:linear-gradient(180deg,#2563eb,#1e2f6b);line-height:1.2;text-decoration:none;">
                <span class="block text-xs">🎁</span>BONUS
            </a>
        </div>
    </div>

    <div id="navFlyout" class="hidden fixed inset-x-0 bottom-14 z-30 max-h-[70vh] overflow-y-auto rounded-t-2xl border-t border-[#2a3350] bg-[#171e33] sm:absolute sm:inset-auto sm:bottom-auto sm:top-0 sm:left-[84px] sm:z-30 sm:h-full sm:w-[260px] sm:max-h-none sm:overflow-visible sm:rounded-none sm:border-t-0 sm:border-r" style="box-shadow:0 20px 60px rgba(0,0,0,0.4);">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#2a3350]">
            <h3 id="navFlyoutTitle" class="text-[15px] font-semibold m-0 text-white">Menu</h3>
            <button type="button" id="navFlyoutClose" class="bg-transparent border-0 text-[#7c86a3] cursor-pointer">
                <i class="fa fa-xmark"></i>
            </button>
        </div>
        <div class="p-2">
            <div class="flyout-section hidden" data-flyout-for="finance">
                <a href="{{ route('wallet.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Deposit</a>
                <a href="{{ route('wallet.index', ['tab' => 'withdraw']) }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Withdraw</a>
                <a href="{{ route('finance.history') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Transaction History</a>
                <a href="{{ route('finance.cashback') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Cashback</a>
                @if(($u_option['safebox_enabled'] ?? '1') === '1')
                    <a href="{{ route('finance.my-safe') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">My Safe</a>
                @endif
                <a href="{{ route('finance.promo-codes') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Promo Codes</a>
            </div>
            <div class="flyout-section hidden" data-flyout-for="profile">
                <a href="{{ route('profile.edit', ['tab' => 'account']) }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Account Settings</a>
                <a href="{{ route('profile.edit', ['tab' => 'verification']) }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Verification</a>
                <a href="{{ route('profile.edit', ['tab' => 'security']) }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Security</a>
                <a href="{{ route('profile.edit', ['tab' => 'trading']) }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Trading Stats</a>
                <a href="{{ route('profile.edit', ['tab' => 'loyalty']) }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Loyalty</a>
                <a href="{{ route('referrals.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Referrals</a>
            </div>
            <div class="flyout-section hidden" data-flyout-for="achievements">
                <a href="{{ route('achievements.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Achievements</a>
                <a href="{{ route('plans.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Investment Plans</a>
                <a href="{{ route('tasks.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Tasks</a>
            </div>
            <div class="flyout-section hidden" data-flyout-for="help">
                <a href="{{ route('support-tickets.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Support Tickets</a>
                <a href="{{ route('notifications.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Notifications</a>
            </div>
            {{-- Consolidated "More" menu — mobile bottom nav only shows the
                 major tabs (Trading/Finance/Settings/Profile); everything
                 else lives behind this one entry. --}}
            <div class="flyout-section hidden" data-flyout-for="more">
                <a href="{{ route('achievements.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Achievements / Rewards</a>
                <a href="{{ route('plans.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Investment Plans</a>
                <a href="{{ route('tasks.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Tasks</a>
                <a href="{{ route('tournaments.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Tournaments</a>
                <a href="{{ route('chat.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Chat</a>
                <a href="{{ route('referrals.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Referrals</a>
                <a href="{{ route('support-tickets.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Support Tickets</a>
                <a href="{{ route('notifications.index') }}" class="block px-3.5 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Notifications</a>
            </div>
        </div>
    </div>
</div>
