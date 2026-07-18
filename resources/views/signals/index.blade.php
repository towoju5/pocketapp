@extends('layouts.desktop.trading')

@section('title', 'Signals')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Signals</h1>
            <span class="flex items-center gap-2 text-xs text-[#7c86a3]">
                <span class="w-2 h-2 rounded-full bg-[#16c087]"></span>
                Live
            </span>
        </div>

        <div class="flex flex-col gap-3">
            @forelse($signals as $signal)
                <div class="flex items-center justify-between px-4 py-3.5 rounded-xl bg-[#171e33] border border-[#2a3350] signal-row"
                    data-id="{{ $signal->id }}" data-duration="{{ $signal->duration }}"
                    data-created-at="{{ $signal->created_at->toIso8601String() }}">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold {{ $signal->direction === 'up' ? 'bg-[#16c087]/15 text-[#16c087]' : 'bg-[#f4534a]/15 text-[#f4534a]' }}">
                            {{ $signal->direction === 'up' ? '▲' : '▼' }}
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-white">{{ $signal->asset }}</div>
                            <div class="text-xs text-[#7c86a3]">
                                Suggested expiry {{ gmdate('i', $signal->duration) }}m ·
                                Expires <span class="signal-countdown" id="countdown-{{ $signal->id }}">--:--</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="signal-copy-btn bg-[#16c087] hover:bg-[#13a876] text-white text-xs font-bold px-4 py-2 rounded-lg"
                        data-id="{{ $signal->id }}">
                        Copy
                    </button>
                </div>
            @empty
                <div class="text-center text-[#7c86a3] text-sm py-16">No active signals right now.</div>
            @endforelse
        </div>
    </div>
</div>

@push('js')
<script>
    document.querySelectorAll('.signal-row').forEach((row) => {
        const id = row.dataset.id;
        const createdAt = new Date(row.dataset.createdAt);
        const durationMs = parseInt(row.dataset.duration, 10) * 1000;
        const expireAt = new Date(createdAt.getTime() + durationMs);
        const el = document.getElementById(`countdown-${id}`);
        if (!el) return;

        const interval = setInterval(() => {
            const remaining = expireAt - Date.now();
            if (remaining <= 0) {
                clearInterval(interval);
                row.remove();
                return;
            }
            const m = String(Math.floor(remaining / 1000 / 60)).padStart(2, '0');
            const s = String(Math.floor((remaining / 1000) % 60)).padStart(2, '0');
            el.textContent = `${m}:${s}`;
        }, 1000);
    });

    document.querySelectorAll('.signal-copy-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            this.disabled = true;
            const original = this.textContent;
            this.textContent = 'Copying...';

            fetch(`/signals/${id}/copy`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
                .then((res) => res.json())
                .then((res) => {
                    if (res.status) {
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.errors || res.message || 'Error placing trade.');
                    }
                })
                .catch(() => toastr.error('Failed to copy signal.'))
                .finally(() => {
                    this.disabled = false;
                    this.textContent = original;
                });
        });
    });
</script>
@endpush
@endsection
