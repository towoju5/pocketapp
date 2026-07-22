@extends('layouts.desktop.trading')

@section('title', 'My Plan Subscriptions')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-white">My Plan Subscriptions</h1>
                <p class="text-sm text-[#7c86a3] mt-1">Your active and matured plan subscriptions.</p>
            </div>
            <a href="{{ route('plans.index') }}" class="text-[#4f8ef7] font-semibold text-sm">&larr; Browse Plans</a>
        </div>

        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase">
                        <tr>
                            <th class="py-2 px-3">Plan</th>
                            <th class="py-2 px-3">Stake</th>
                            <th class="py-2 px-3">Projected Payout</th>
                            <th class="py-2 px-3">Matures</th>
                            <th class="py-2 px-3">Status</th>
                            <th class="py-2 px-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse ($subscriptions as $sub)
                            <tr class="border-t border-[#1c243c]">
                                <td class="py-3 px-3 font-semibold text-white">{{ $sub->plan->name ?? 'Deleted plan' }}</td>
                                <td class="py-3 px-3">{{ formatPrice($sub->stake_amount) }}</td>
                                <td class="py-3 px-3 text-[#16c087] font-semibold">{{ formatPrice($sub->expected_payout) }}</td>
                                <td class="py-3 px-3 text-xs text-[#7c86a3]">
                                    @if ($sub->status === 'active')
                                        {{ $sub->matures_at->diffForHumans() }}
                                    @else
                                        {{ $sub->matures_at->format('d M, Y') }}
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-[#4f8ef7]/10 text-[#4f8ef7]',
                                            'matured' => 'bg-[#16c087]/10 text-[#16c087]',
                                            'cancelled' => 'bg-[#7c86a3]/10 text-[#7c86a3]',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase {{ $statusColors[$sub->status] ?? 'bg-[#7c86a3]/10 text-[#7c86a3]' }}">{{ $sub->status }}</span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if ($sub->status === 'matured' && $sub->plan && (is_null($sub->plan->max_reinvest_count) || $sub->reinvest_count < $sub->plan->max_reinvest_count))
                                        <form method="POST" action="{{ route('plans.reinvest', $sub) }}">
                                            @csrf
                                            <button type="submit" class="bg-transparent border border-[#4f8ef7] text-[#4f8ef7] text-xs font-bold uppercase px-3 py-1.5 rounded-lg">Reinvest</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-12 text-[#7c86a3] font-semibold">No subscriptions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">{{ $subscriptions->links() }}</div>
        </div>
    </div>
</div>
@endsection
