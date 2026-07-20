@extends('layouts.desktop.trading')

@section('title', 'My Safe')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        @include('partials.finance-header')

        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mt-6">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mt-6">{{ session('error') }}</div>
        @endif

        @if(!$enabled)
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-8 text-center mt-6">
                <div class="w-14 h-14 rounded-full bg-[#1c243c] border border-[#2a3350] flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-vault text-[#7c86a3] text-xl"></i>
                </div>
                <h2 class="text-white font-bold text-lg mb-2">My Safe</h2>
                <p class="text-[#7c86a3] text-sm max-w-md mx-auto leading-relaxed">This feature is currently disabled by the platform.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#1c243c] border border-[#2a3350] flex items-center justify-center mx-auto mb-3">
                        <i class="fa fa-vault text-[#4f8ef7]"></i>
                    </div>
                    <div class="text-xs text-[#7c86a3] mb-1">Safe Balance</div>
                    <div class="text-2xl font-bold text-white">${{ number_format($safeBalance, 2) }}</div>
                    <p class="text-xs text-[#7c86a3] mt-3">Funds here are set aside from your trading balance and can't be used to place trades.</p>
                </div>

                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <h3 class="text-sm font-bold text-white mb-1">Move to Safe</h3>
                    <p class="text-xs text-[#7c86a3] mb-4">Real balance: ${{ number_format($realBalance, 2) }}</p>
                    <form method="POST" action="{{ route('finance.my-safe.deposit') }}" class="space-y-3">
                        @csrf
                        <input type="number" step="0.01" min="0.01" name="amount" placeholder="0.00" required
                            class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-white outline-none focus:border-[#4f8ef7]">
                        <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-2.5 rounded-lg">Move to Safe</button>
                    </form>
                </div>

                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <h3 class="text-sm font-bold text-white mb-1">Withdraw from Safe</h3>
                    <p class="text-xs text-[#7c86a3] mb-4">Moves funds back to your Real account.</p>
                    <form method="POST" action="{{ route('finance.my-safe.withdraw') }}" class="space-y-3">
                        @csrf
                        <input type="number" step="0.01" min="0.01" name="amount" placeholder="0.00" required
                            class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-white outline-none focus:border-[#4f8ef7]">
                        <button type="submit" class="w-full bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white font-semibold text-sm py-2.5 rounded-lg">Withdraw to Real</button>
                    </form>
                </div>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl mt-6">
                <div class="p-4 border-b border-[#2a3350]">
                    <h3 class="text-sm font-bold text-white">Safe Activity</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-[#7c86a3] text-xs uppercase">
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-[#d7dcea]">
                            @forelse($history as $item)
                                <tr class="border-t border-[#1c243c]">
                                    <td class="px-4 py-3 text-[#7c86a3]">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($item->type) }}</td>
                                    <td class="px-4 py-3 font-semibold {{ $item->type === 'deposit' ? 'text-[#16c087]' : 'text-[#f4534a]' }}">
                                        {{ $item->type === 'deposit' ? '+' : '-' }}${{ number_format($item->amountFloat, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-10 text-center text-[#7c86a3]">No Safe activity yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($history->hasPages())
                    <div class="p-4">{{ $history->links('pagination::tailwind') }}</div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
