<div class="signal-card">
    <div class="signal-header">
        <div class="signal-title gap-3">
            <i class="fas fa-star text[#ff9706]"></i>
            <span class="text-[#8ea5c0]"> {{ $trade->trade_currency }}</span>
            <span class="signal-percentage">+{{ $trade->trade_percentage }}%</span>
        </div>
        <div class="countdown-timer text-white" id="countdown-{{ $trade->id }}" data-timestamp="{{ $trade->trade_duration }}"></div>
    </div>
    <div class="signal-body">
        <div class="signal-price text-[#8ea5c0]">
            @if($trade->trade_direction == 'up')
                <i class="fas fa-arrow-up"></i>
            @else
                <i class="fas fa-arrow-down"></i>
            @endif
            ${{ ($trade->trade_amount) }}
        </div>
        <div class="signal-profit total_amount base_plus_profit">${{ ($trade->trade_profit) }}</div>
        <div class="signal-profit">+${{ $trade->trade_profit - $trade->trade_amount }}</div>
    </div>
    <div class="signal-button">
        <i class="fas fa-angle-double-up"></i> Double Up
    </div>
</div>
