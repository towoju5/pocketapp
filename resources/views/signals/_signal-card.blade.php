@php
    $expired = $expired ?? false;
    $directionUp = $signal->direction === 'up';
@endphp
<div class="trade-card signal-row" id="signal-card-{{ $signal->id }}" data-id="{{ $signal->id }}"
    data-duration="{{ $signal->duration }}" data-created-at="{{ $signal->created_at->toIso8601String() }}"
    style="border-left-color: {{ $expired ? '#7c86a3' : '#f2a93b' }};">
    <div class="trade-card__row">
        <div class="trade-card__asset">
            <span class="trade-card__dir trade-card__dir--{{ $directionUp ? 'up' : 'down' }}">
                <i class="fas fa-arrow-{{ $directionUp ? 'up' : 'down' }}"></i>
            </span>
            <span class="trade-card__symbol">{{ $signal->asset }}</span>
        </div>

        @if ($expired)
            <div class="trade-card__badge trade-card__badge--void">Expired</div>
        @else
            <div class="trade-card__countdown signal-countdown" id="countdown-{{ $signal->id }}">--:--</div>
        @endif
    </div>

    <div class="trade-card__row trade-card__row--figures">
        <div>
            <div class="trade-card__label">Suggested Stake</div>
            <div class="trade-card__value">${{ number_format($signal->amount, 2) }}</div>
        </div>
        <div>
            <div class="trade-card__label">Entry Price</div>
            <div class="trade-card__value">{{ $signal->start_price ? number_format($signal->start_price, 5) : '—' }}</div>
        </div>
        <div>
            <div class="trade-card__label">Suggested Expiry</div>
            <div class="trade-card__value">{{ gmdate('i:s', $signal->duration) }}</div>
        </div>
    </div>

    @if ($signal->notes)
        <div class="mt-2 text-xs text-[#7c86a3] truncate" title="{{ $signal->notes }}">{{ $signal->notes }}</div>
    @endif

    @unless ($expired)
        <button type="button" class="signal-copy-btn mt-3 w-full bg-[#16c087] hover:bg-[#13a876] text-white text-xs font-bold px-4 py-2 rounded-lg" data-id="{{ $signal->id }}">
            Copy Signal
        </button>
    @endunless
</div>
