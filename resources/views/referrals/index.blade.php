@extends('layouts.desktop.trading')

@section('title', 'Affiliate Program')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <h1 class="text-xl font-bold text-white mb-1">Affiliate Program</h1>
        <p class="text-sm text-[#7c86a3] mb-6">Earn commission across 3 levels of your referral network.</p>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-6">
            <p class="text-xs font-bold text-[#7c86a3] uppercase mb-3">Your Referral Link</p>
            <div class="flex gap-2.5 flex-wrap items-center">
                <code id="refLink" class="bg-[#1c243c] border border-[#2a3350] px-4 py-3 rounded-lg text-[#4f8ef7] text-sm flex-1 min-w-[200px] break-words">{{ url('/register?ref=' . $user->referral_code) }}</code>
                <button onclick="navigator.clipboard.writeText(document.getElementById('refLink').innerText)" class="bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold px-5 py-3 rounded-lg">Copy</button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 text-center">
                <div class="text-2xl font-bold text-white">{{ $directCount }}</div>
                <div class="text-xs text-[#7c86a3] uppercase mt-1.5">Direct (L1)</div>
            </div>
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 text-center">
                <div class="text-2xl font-bold text-white">{{ $level2Count }}</div>
                <div class="text-xs text-[#7c86a3] uppercase mt-1.5">Level 2</div>
            </div>
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 text-center">
                <div class="text-2xl font-bold text-white">{{ $level3Count }}</div>
                <div class="text-xs text-[#7c86a3] uppercase mt-1.5">Level 3</div>
            </div>
            <div class="bg-[#171e33] border border-[#16c087]/30 rounded-xl p-5 text-center">
                <div class="text-2xl font-bold text-[#16c087]">{{ formatPrice($totalEarned) }}</div>
                <div class="text-xs text-[#7c86a3] uppercase mt-1.5">Total Earned</div>
            </div>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-6">
            <h3 class="text-white font-bold mb-4">Commission History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase">
                        <tr>
                            <th class="py-2 px-3">From</th>
                            <th class="py-2 px-3">Level</th>
                            <th class="py-2 px-3">Activity</th>
                            <th class="py-2 px-3">Commission</th>
                        </tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse ($commissions as $c)
                            <tr class="border-t border-[#1c243c]">
                                <td class="py-3 px-3 font-semibold text-white">{{ $c->referredUser->first_name ?? 'Trader' }}</td>
                                <td class="py-3 px-3">L{{ $c->level }}</td>
                                <td class="py-3 px-3 capitalize">{{ $c->activity_type }}</td>
                                <td class="py-3 px-3 text-[#16c087] font-semibold">{{ formatPrice($c->commission_amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-10 text-[#7c86a3] font-semibold">No commissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $commissions->links() }}</div>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h3 class="text-white font-bold mb-4">Invite Registry</h3>
            <div class="flex flex-col gap-2.5">
                @forelse ($directReferrals as $ref)
                    <div class="flex justify-between bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3.5">
                        <span class="text-white">{{ $ref->first_name }} {{ $ref->last_name }}</span>
                        <span class="text-[#7c86a3] text-sm">{{ $ref->created_at->format('d M, Y') }}</span>
                    </div>
                @empty
                    <p class="text-[#7c86a3] text-center py-5">No direct referrals yet. Share your link above!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
