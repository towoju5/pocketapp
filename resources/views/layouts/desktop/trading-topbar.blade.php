<div class="h-16 border-b border-[#2a3350] flex items-center justify-between gap-2 sm:gap-3.5 px-3 sm:px-5 flex-shrink-0 relative box-border w-full">

    <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-[10px] flex items-center justify-center font-bold text-white text-xs flex-shrink-0" style="background:linear-gradient(135deg,#4f8ef7,#a855f7);">TX</a>

    <div class="flex items-center gap-2 sm:gap-3.5 ml-auto">
    <div class="relative">
        <button type="button" id="balanceMenuBtn" class="bg-transparent border-0 cursor-pointer text-right px-1 sm:px-2 py-1 rounded-lg flex items-center gap-1.5 sm:gap-2 text-[#d7dcea]">
            <span>
                <span class="hidden sm:block text-[11px] text-[#7c86a3]">{{ ucfirst($wallet_balance['name'] ?? 'Wallet') }}</span>
                <span class="block text-[13px] sm:text-[15px] font-semibold">{{ formatPrice($wallet_balance['balance'] ?? 0) }}</span>
            </span>
            <i class="fa fa-chevron-down text-[#7c86a3]" style="font-size:11px;"></i>
        </button>
        <div id="balanceMenu" class="hidden absolute top-14 right-0 z-40 w-[260px] bg-[#171e33] border border-[#2a3350] rounded-xl p-1.5" style="box-shadow:0 20px 60px rgba(0,0,0,0.4);">
            @foreach(($_user->wallets ?? []) as $wallet)
                <button type="button" onclick="location.href='{{ route('wallet.change.default', $wallet->slug) }}'" class="w-full flex justify-between p-2.5 rounded-lg bg-transparent border-0 cursor-pointer text-left text-[#d7dcea]">
                    <span>
                        <span class="block text-[13px] font-medium">{{ ucfirst($wallet->name) }}</span>
                        <span class="block text-[11px] text-[#7c86a3]">{{ $wallet->slug }}</span>
                    </span>
                    <span class="text-[13px] font-semibold">{{ formatPrice($wallet->balance) }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <a href="{{ route('wallet.index') }}" class="bg-[#16c087] text-white rounded-[9px] px-2.5 sm:px-[18px] py-2.5 sm:py-[11px] font-bold text-[13px] flex items-center gap-1.5" style="text-decoration:none;">
        <i class="fa fa-wallet"></i><span class="hidden sm:inline">&nbsp;TOP UP</span>
    </a>

    <div class="relative">
        <button type="button" id="avatarMenuBtn" class="w-[34px] h-[34px] rounded-full flex items-center justify-center border-0 cursor-pointer" style="background:#33406b;">
            <i class="fa fa-user text-[#9aa4c2]"></i>
        </button>
        <div id="avatarMenu" class="hidden absolute top-14 right-0 z-40 w-[190px] bg-[#171e33] border border-[#2a3350] rounded-xl p-1.5" style="box-shadow:0 20px 60px rgba(0,0,0,0.4);">
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Profile</a>
            <a href="{{ route('profile.security') }}" class="block px-3 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Security</a>
            <a href="{{ route('support-tickets.index') }}" class="block px-3 py-2.5 rounded-lg text-sm text-[#7c86a3] hover:text-white">Support</a>
            <hr class="border-[#2a3350] my-1.5">
            <a href="#" onclick="event.preventDefault(); document.getElementById('UserLogoutForm').submit();" class="block px-3 py-2.5 rounded-lg text-sm" style="color:#f4534a;">Log Out</a>
        </div>
    </div>
    </div>
</div>
