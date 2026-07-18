@extends('layouts.desktop.trading')

@section('title', 'Duty Registry')

@section('content')
<style>
    .btn-go { background: #4f8ef7; color: #fff; border: none; padding: 12px 18px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration:none; display:inline-block; font-size:13px; text-align:center; }
    .btn-go:hover { background: #3d7de0; }
    .btn-disabled { background: #1c243c; color: #7c86a3; border: 1px solid #2a3350; padding: 12px 18px; border-radius: 8px; font-weight: 700; cursor: not-allowed; font-size:13px; }
</style>

<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-white m-0">Duty <span class="text-[#4f8ef7]">Registry</span></h1>
                <p class="text-[#7c86a3] mt-2 text-sm">Complete daily tasks for bonus wallet credits.</p>
            </div>
            <a href="{{ route('tasks.submissions') }}" class="text-[#4f8ef7] font-semibold text-sm no-underline">My Submissions &rarr;</a>
        </div>

        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-5">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-5">{{ session('error') }}</div>
        @endif

        <div class="grid gap-5" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            @forelse ($tasks as $task)
                @php $done = ($todaysCounts[$task->id] ?? 0) >= $task->daily_limit; @endphp
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <h3 class="text-white font-bold mb-2">{{ $task->title }}</h3>
                    <p class="text-[#7c86a3] text-xs mb-4">{{ $task->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[#16c087] font-bold text-base">{{ formatPrice($task->reward_amount) }}</span>
                        <span class="text-[#7c86a3] text-xs uppercase">{{ $todaysCounts[$task->id] ?? 0 }} / {{ $task->daily_limit }} today</span>
                    </div>

                    <a href="{{ $task->external_url }}" target="_blank" class="btn-go w-full box-border mb-3">Start Duty</a>

                    @if ($done)
                        <button class="btn-disabled w-full box-border" disabled>Limit Reached</button>
                    @else
                        <form method="POST" action="{{ route('tasks.submit', $task) }}">
                            @csrf
                            <input type="url" name="proof_url" placeholder="Proof URL" class="brand-input-dark mb-2.5" required>
                            <button type="submit" class="btn-go w-full box-border">Submit Proof</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="col-span-full p-12 bg-[#171e33] border border-dashed border-[#2a3350] rounded-xl text-center">
                    <p class="text-[#7c86a3] font-semibold">No duties available right now.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
