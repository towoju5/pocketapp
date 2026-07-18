@extends('layouts.desktop.trading')

@section('title', 'Social Trading')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-xl font-bold text-white mb-6">Social Trading</h1>

        <div class="flex gap-2 mb-6">
            <button type="button" class="social-tab-btn social-tab-btn--active" data-tab="24h">Top performers (24h)</button>
            <button type="button" class="social-tab-btn" data-tab="all">Top ranked</button>
            <button type="button" class="social-tab-btn" data-tab="100">Top 100</button>
        </div>

        @foreach(['24h' => $traders24hours, 'all' => $tradersTopRanked, '100' => $tradersTop100] as $key => $traders)
            <div class="social-tab-panel {{ $key === '24h' ? '' : 'hidden' }}" data-panel="{{ $key }}">
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
                    @forelse($traders as $trader)
                        @php
                            $total = $trader->trades->count();
                            $wins = $trader->trades->where('trade_status', 'win')->count();
                            $winRate = $total > 0 ? round(($wins / $total) * 100) : 0;
                        @endphp
                        <div class="flex items-center justify-between px-5 py-3.5 border-t border-[#1c243c] first:border-t-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#33406b] flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($trader->username ?? $trader->first_name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white">{{ $trader->username ?? $trader->first_name }}</div>
                                    <div class="text-xs text-[#7c86a3]">{{ $total }} trades · {{ $winRate }}% win rate</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-[#16c087] font-semibold text-sm">+${{ number_format($trader->total_profit, 2) }}</span>
                                <button type="button" disabled title="Coming soon" class="bg-[#1c243c] text-[#7c86a3] text-xs font-bold px-4 py-2 rounded-lg cursor-not-allowed">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-[#7c86a3] py-12">No traders in this leaderboard yet.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.social-tab-btn { background:#1c243c; border:1px solid #2a3350; color:#7c86a3; font-size:13px; font-weight:600; padding:8px 14px; border-radius:8px; cursor:pointer; }
.social-tab-btn--active { background:rgba(79,142,247,0.15); color:#4f8ef7; border-color:#4f8ef7; }
</style>

@push('js')
<script>
    document.querySelectorAll('.social-tab-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.social-tab-btn').forEach((b) => b.classList.remove('social-tab-btn--active'));
            btn.classList.add('social-tab-btn--active');
            document.querySelectorAll('.social-tab-panel').forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== btn.dataset.tab));
        });
    });
</script>
@endpush
@endsection
