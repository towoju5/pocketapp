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
                                @if($trader->id === auth()->id())
                                    <span class="text-[#7c86a3] text-xs px-4 py-2">You</span>
                                @elseif($following->has($trader->id))
                                    <button type="button" class="copy-btn copy-btn--active text-xs font-bold px-4 py-2 rounded-lg" data-trader-id="{{ $trader->id }}" data-stake="{{ $following->get($trader->id) }}">
                                        Copying ${{ number_format($following->get($trader->id), 2) }} &middot; Stop
                                    </button>
                                @else
                                    <button type="button" class="copy-btn text-xs font-bold px-4 py-2 rounded-lg" data-trader-id="{{ $trader->id }}">
                                        Copy
                                    </button>
                                @endif
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

<div id="copyStakeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
    <div class="bg-[#171e33] border border-[#2a3350] rounded-2xl p-5 w-[320px]">
        <h3 class="text-white font-bold text-sm mb-1">Copy this trader</h3>
        <p class="text-[#7c86a3] text-xs mb-4">Every trade they place will be mirrored into your account at a fixed stake, from your currently active wallet.</p>
        <label class="block text-xs text-[#7c86a3] mb-1.5">Stake per copied trade</label>
        <input type="number" id="copyStakeInput" min="1" step="0.01" value="10" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-3 py-2 text-sm text-white mb-4 outline-none">
        <div class="flex gap-2">
            <button type="button" id="copyStakeCancel" class="flex-1 py-2 rounded-lg bg-[#1c243c] border border-[#2a3350] text-[#7c86a3] text-xs font-bold">Cancel</button>
            <button type="button" id="copyStakeConfirm" class="flex-1 py-2 rounded-lg bg-[#4f8ef7] text-white text-xs font-bold">Start Copying</button>
        </div>
    </div>
</div>

<style>
.social-tab-btn { background:#1c243c; border:1px solid #2a3350; color:#7c86a3; font-size:13px; font-weight:600; padding:8px 14px; border-radius:8px; cursor:pointer; }
.social-tab-btn--active { background:rgba(79,142,247,0.15); color:#4f8ef7; border-color:#4f8ef7; }
.copy-btn { background:#1c243c; border:1px solid #2a3350; color:#d7dcea; cursor:pointer; }
.copy-btn:hover { border-color:#4f8ef7; color:#4f8ef7; }
.copy-btn--active { background:rgba(22,192,135,0.12); border-color:#16c087; color:#16c087; }
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

    const modal = document.getElementById('copyStakeModal');
    const stakeInput = document.getElementById('copyStakeInput');
    let pendingTraderId = null;

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content;
    }

    document.querySelectorAll('.copy-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const traderId = btn.dataset.traderId;
            if (btn.classList.contains('copy-btn--active')) {
                fetch(`/social-trading/${traderId}/unfollow`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
                })
                    .then((r) => r.json())
                    .then((res) => {
                        window.toastr?.success(res.message);
                        setTimeout(() => window.location.reload(), 600);
                    })
                    .catch(() => window.toastr?.error('Something went wrong.'));
                return;
            }

            pendingTraderId = traderId;
            stakeInput.value = '10';
            modal.classList.remove('hidden');
        });
    });

    document.getElementById('copyStakeCancel').addEventListener('click', () => {
        modal.classList.add('hidden');
        pendingTraderId = null;
    });

    document.getElementById('copyStakeConfirm').addEventListener('click', () => {
        if (!pendingTraderId) return;
        const amount = parseFloat(stakeInput.value);
        if (!amount || amount < 1) {
            window.toastr?.error('Enter a valid stake amount.');
            return;
        }

        fetch(`/social-trading/${pendingTraderId}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ copy_stake_amount: amount }),
        })
            .then((r) => r.json())
            .then((res) => {
                if (res.status) {
                    window.toastr?.success(res.message);
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    window.toastr?.error(res.message || 'Unable to copy this trader.');
                }
            })
            .catch(() => window.toastr?.error('Something went wrong.'))
            .finally(() => {
                modal.classList.add('hidden');
                pendingTraderId = null;
            });
    });
</script>
@endpush
@endsection
