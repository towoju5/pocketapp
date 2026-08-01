@extends('layouts.desktop.trading')

@section('title', 'Signals')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Signals</h1>
            <span class="flex items-center gap-2 text-xs text-[#7c86a3]">
                <span class="w-2 h-2 rounded-full bg-[#16c087]"></span>
                Live
            </span>
        </div>

        <div class="signals-panel bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
            <div class="flex w-full">
                <button onclick="toggleTradeMenu(this, 'active')" class="trade-open-close relative py-2.5 text-gray-500 bg-[#0b1120] font-thin text-sm w-6/12 active-tab">
                    Active
                    <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
                </button>
                <button onclick="toggleTradeMenu(this, 'closed')" class="trade-open-close relative py-2.5 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
                    Expired
                    <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
                </button>
            </div>

            <div class="trade-open-content trade_list-page trade-tab-content" data-tab="active">
                <div class="trade-list-stack">
                    @forelse ($active as $signal)
                        @include('signals._signal-card', ['signal' => $signal, 'expired' => false])
                    @empty
                        <p class="trade-list-empty">No active signals right now.</p>
                    @endforelse
                </div>
            </div>

            <div class="trade-closed-content trade_list-page trade-tab-content hidden" data-tab="closed">
                <div class="trade-list-stack">
                    @forelse ($expired as $signal)
                        @include('signals._signal-card', ['signal' => $signal, 'expired' => true])
                    @empty
                        <p class="trade-list-empty">No expired signals yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Same technique _tradings.blade.php uses to override toggleTradeMenu()'s
       hardcoded gray tab classes — scoped to .signals-panel, recolored to
       this page's own navy palette instead of the dashboard widget's purple. */
    .signals-panel .trade-open-close {
        color: #7c86a3 !important;
        background: #1c243c !important;
    }
    .signals-panel .trade-open-close.active-tab {
        color: #fff !important;
        background: #171e33 !important;
    }
    .signals-panel .trade-open-close .tab-indicator {
        background: #4f8ef7 !important;
    }

    .signals-panel .trade-list-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 12px;
    }

    .signals-panel .trade-list-empty {
        color: #7c86a3;
        font-size: 13px;
        text-align: center;
        padding: 40px 12px;
    }

    .signals-panel .trade-card {
        background: #1c243c;
        border: 1px solid #2a3350;
        border-left-width: 3px;
        border-radius: 10px;
        padding: 10px 12px;
    }

    .signals-panel .trade-card__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .signals-panel .trade-card__row--figures {
        display: flex;
        margin-top: 10px;
        gap: 8px;
    }

    .signals-panel .trade-card__row--figures > div {
        flex: 1;
    }

    .signals-panel .trade-card__asset {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .signals-panel .trade-card__dir {
        display: inline-flex;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .signals-panel .trade-card__dir--up {
        background: rgba(22, 192, 135, 0.15);
        color: #16c087;
    }

    .signals-panel .trade-card__dir--down {
        background: rgba(244, 83, 74, 0.15);
        color: #f4534a;
    }

    .signals-panel .trade-card__symbol {
        color: #fff;
        font-weight: 600;
        font-size: 13px;
    }

    .signals-panel .trade-card__badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 999px;
    }

    .signals-panel .trade-card__badge--void {
        color: #7c86a3;
        background: rgba(124, 134, 163, 0.15);
    }

    .signals-panel .trade-card__countdown {
        color: #fff;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .signals-panel .trade-card__label {
        color: #7c86a3;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 2px;
    }

    .signals-panel .trade-card__value {
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }
</style>

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
