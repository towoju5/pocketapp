@foreach($trades as $trade)
<div class="express-trading-card signal-card max-w-md mx-auto">
    <div class="signal-header">
        <div class="signal-title gap-3">
            <i class="fas fa-star text-[#ff9706]"></i>
            <span class="text-[#8ea5c0]">{{ $trade->trade_extra_info->asset ?? $trade->asset->name ?? 'Unknown Asset' }}</span>
            <span class="signal-percentage">+{{ $trade->trade_percentage }}%</span>
        </div>
        <div class="countdown-timer text-white" id="countdown-{{ $trade->id }}" data-timestamp="{{ $trade->trade_duration }}"></div>
    </div>

    <div class="signal-body">
        <div class="signal-price text-[#8ea5c0]">
            @if($trade->trade_direction === 'up')
            <i class="fas fa-arrow-up text-green-500"></i>
            @else
            <i class="fas fa-arrow-down text-red-500"></i>
            @endif
            ${{ number_format($trade->trade_amount, 2) }}
        </div>

        <div class="signal-profit total_amount base_plus_profit">
            ${{ number_format($trade->trade_profit, 2) }}
        </div>
        <div class="signal-profit">
            +${{ number_format($trade->trade_profit - $trade->trade_amount, 2) }}
        </div>
    </div>

    <div class="signal-button">
        <i class="fas fa-angle-double-up"></i> Double Up
    </div>
</div>
@endforeach