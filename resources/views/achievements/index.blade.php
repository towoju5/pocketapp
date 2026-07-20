@extends('layouts.desktop.trading')

@section('title', 'Achievements')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        <h1 class="text-xl font-bold text-white mb-6">Achievements</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
            @foreach($achievements as $achievement)
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 text-center {{ $achievement->unlocked ? '' : 'opacity-40' }}">
                    <div class="text-3xl mb-2">{{ $achievement->icon }}</div>
                    <div class="text-white font-semibold text-sm">{{ $achievement->title }}</div>
                    <div class="text-xs text-[#7c86a3] mt-1">{{ $achievement->description }}</div>
                    <div class="text-xs mt-2 {{ $achievement->unlocked ? 'text-[#16c087]' : 'text-[#7c86a3]' }}">
                        {{ $achievement->unlocked ? 'Unlocked · +' . $achievement->points . ' pts' : 'Locked' }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h2 class="text-white font-semibold mb-4">Leaderboard</h2>
            <table class="w-full text-sm text-left">
                <thead class="text-[#7c86a3] text-xs uppercase">
                    <tr><th class="py-2">Rank</th><th class="py-2">Trader</th><th class="py-2">Points</th></tr>
                </thead>
                <tbody class="text-[#d7dcea]">
                    @forelse($leaderboard as $i => $entry)
                        <tr class="border-t border-[#1c243c]">
                            <td class="py-2">#{{ $i + 1 }}</td>
                            <td class="py-2">{{ $entry->user->username ?? $entry->user->first_name }}</td>
                            <td class="py-2 font-semibold text-[#4f8ef7]">{{ $entry->points }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-[#7c86a3]">No achievements unlocked yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
