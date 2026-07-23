@extends('layouts.desktop.trading')

@section('title', 'Tournaments')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <h1 class="text-xl font-bold text-white mb-6">Tournaments</h1>

        <div class="flex gap-2 mb-6">
            <button type="button" class="tourney-tab-btn tourney-tab-btn--active" data-tab="active">Active Tournaments</button>
            <button type="button" class="tourney-tab-btn" data-tab="results">My Results</button>
            <button type="button" class="tourney-tab-btn" data-tab="rules">Rules</button>
        </div>

        <div class="tourney-tab-panel" data-panel="active">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($active as $tournament)
                    <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5">
                        <div class="text-white font-semibold mb-1">{{ $tournament->tournament_title }}</div>
                        <div class="text-xs text-[#7c86a3] mb-3">Starts {{ \Carbon\Carbon::parse($tournament->tournament_start_date_time)->diffForHumans() }}</div>
                        <div class="flex justify-between text-xs text-[#7c86a3] mb-1">
                            <span>Prize fund</span><span class="text-white font-semibold">${{ number_format((float) $tournament->tournament_reward, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-[#7c86a3] mb-4">
                            <span>Entry fee</span><span class="text-white font-semibold">${{ number_format((float) $tournament->tournament_participation_fee, 2) }}</span>
                        </div>
                        @if(in_array($tournament->id, $joinedTournamentIds))
                            <button type="button" disabled class="w-full py-2 text-xs rounded-lg bg-[#1c243c] text-[#16c087] font-semibold">Joined</button>
                        @else
                            <form method="POST" action="{{ route('tournaments.join', $tournament) }}">
                                @csrf
                                <button type="submit" class="w-full py-2 text-xs rounded-lg bg-[#4f8ef7] text-white font-semibold">Join tournament</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="col-span-2 text-center text-[#7c86a3] py-16">No active tournaments right now.</div>
                @endforelse
            </div>
        </div>

        <div class="tourney-tab-panel hidden" data-panel="results">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
                <div class="responsive-table">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase bg-[#1c243c]">
                        <tr><th class="px-4 py-3">Tournament</th><th class="px-4 py-3">Joined</th><th class="px-4 py-3">Status</th></tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse($myResults as $entry)
                            <tr class="border-t border-[#1c243c]">
                                <td class="px-4 py-3" data-label="Tournament">{{ $entry->tournament->tournament_title ?? '—' }}</td>
                                <td class="px-4 py-3 text-[#7c86a3]" data-label="Joined">{{ \Carbon\Carbon::parse($entry->tournament_subscription_date_time)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3" data-label="Status">{{ ucfirst($entry->tournament_wining_status) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-10 text-center text-[#7c86a3]">You haven't joined any tournaments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="tourney-tab-panel hidden" data-panel="rules">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 text-sm text-[#d7dcea] space-y-3">
                <p>Tournaments run on a virtual balance separate from your real or demo wallets.</p>
                <p>Entry fees (if any) are pooled into the prize fund and distributed to top performers at the end of the tournament.</p>
                <p>Only one entry per account is allowed per tournament.</p>
            </div>
        </div>
    </div>
</div>

<style>
.tourney-tab-btn { background:#1c243c; border:1px solid #2a3350; color:#7c86a3; font-size:13px; font-weight:600; padding:8px 16px; border-radius:8px; cursor:pointer; }
.tourney-tab-btn--active { background:rgba(79,142,247,0.15); color:#4f8ef7; border-color:#4f8ef7; }
</style>

@push('js')
<script>
    document.querySelectorAll('.tourney-tab-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tourney-tab-btn').forEach((b) => b.classList.remove('tourney-tab-btn--active'));
            btn.classList.add('tourney-tab-btn--active');
            document.querySelectorAll('.tourney-tab-panel').forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== btn.dataset.tab));
        });
    });
</script>
@endpush
@endsection
