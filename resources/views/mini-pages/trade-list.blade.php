@php
    $isSettled = in_array($trade->trade_status, ['win', 'lose', 'void']);
    $isWin = $trade->trade_status === 'win';
    $isVoid = $trade->trade_status === 'void';
    $directionUp = $trade->trade_direction === 'up';
    // trade_profit is fixed at trade-open time as the *potential* payout —
    // a loss never touches it, so the actual result here can't be derived
    // from it: a win nets (trade_profit - trade_amount), a loss forfeits the
    // whole stake (trade_amount), a void nets zero (refunded in full).
    $resultAmount = $isVoid ? 0 : ($isWin ? ($trade->trade_profit - $trade->trade_amount) : ($isSettled ? $trade->trade_amount : $trade->trade_profit - $trade->trade_amount));
    // trade_close_time is stored as a naive "Y-m-d H:i:s" string (no
    // timezone marker) — always in UTC since APP_TIMEZONE=UTC, but a plain
    // {{ $trade->trade_close_time }} print gives the browser no way to know
    // that. JS's `new Date(...)` treats a marker-less string as *local*
    // time, so any visitor not in UTC gets a skewed countdown target — e.g.
    // a UTC+1 browser reads a 19:28 UTC close time as 19:28 *local* (=18:28
    // UTC), landing in the past immediately and showing 00:00:00 right
    // after the trade opens. Making the UTC offset explicit here fixes that
    // for every timezone at once instead of guessing the visitor's offset.
    $closeTimeIso = $trade->trade_close_time ? \Carbon\Carbon::parse($trade->trade_close_time, 'UTC')->toIso8601String() : null;
@endphp
<div class="trade-card" id="trade-card-{{ $trade->id }}" style="border-left-color: {{ $isSettled ? ($isVoid ? '#7c86a3' : ($isWin ? '#16c087' : '#f4534a')) : '#f2a93b' }};">
    <div class="trade-card__row">
        <div class="trade-card__asset">
            <span class="trade-card__dir trade-card__dir--{{ $directionUp ? 'up' : 'down' }}">
                <i class="fas fa-arrow-{{ $directionUp ? 'up' : 'down' }}"></i>
            </span>
            <span class="trade-card__symbol">{{ $trade->trade_currency }}</span>
            <span class="trade-card__pct">+{{ number_format($trade->trade_percentage * 100, 0) }}%</span>
        </div>

        @if ($isSettled)
            <div class="trade-card__badge trade-card__badge--{{ $isVoid ? 'void' : ($isWin ? 'win' : 'lose') }}">
                {{ $isVoid ? 'Voided' : ($isWin ? 'Won' : 'Lost') }}
            </div>
        @else
            <div class="trade-card__countdown" id="countdown-{{ $trade->id }}" data-close-time="{{ $closeTimeIso }}">--:--:--</div>
        @endif
    </div>

    <div class="trade-card__row trade-card__row--figures">
        <div>
            <div class="trade-card__label">Stake</div>
            <div class="trade-card__value">{{ formatPrice($trade->trade_amount) }}</div>
        </div>
        <div>
            <div class="trade-card__label">{{ $isSettled ? ($isVoid ? 'Refunded' : 'Payout') : 'Potential Payout' }}</div>
            <div class="trade-card__value">{{ formatPrice($isSettled ? ($isWin ? $trade->trade_profit : ($isVoid ? $trade->trade_amount : 0)) : $trade->trade_profit) }}</div>
        </div>
        <div>
            <div class="trade-card__label">{{ $isSettled ? 'Result' : 'Potential Profit' }}</div>
            <div class="trade-card__value {{ $isSettled && !$isVoid ? ($isWin ? 'trade-card__value--win' : 'trade-card__value--lose') : '' }}">
                @if ($isVoid)
                    —
                @else
                    {{ ($isSettled && !$isWin) ? '-' : '+' }}{{ formatPrice($resultAmount) }}
                @endif
            </div>
        </div>
    </div>
</div>
