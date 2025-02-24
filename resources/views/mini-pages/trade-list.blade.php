
    <div class="signal-card">
        <div class="signal-header">
            <div class="signal-title gap-3">
                <i class="fas fa-star-o text[#ff9706]"></i> <span class="text-[#8ea5c0]"> {{ $trade->trade_currency }}</span> <span class="signal-percentage">+85%</span>
            </div>
            <div class="signal-time">03:59:40</div>
        </div>
        <div class="signal-body">
            <div class="signal-price text-[#8ea5c0]">
                @if($trade->trade_direction == 'up')
                    <i class="fas fa-arrow-up"></i>
                @else
                    <i class="fas fa-arrow-down"></i>
                @endif
                ${{ formatPrice($trade->trade_amount) }}
            </div>
            <div class="signal-profit total_amount base_plus_profit">${{ formatPrice($trade->trade_amount) }}</div>
            <div class="signal-profit">+$1,705.10</div>
        </div>
        <div class="signal-button">
            <i class="fas fa-angle-double-up"></i> Double Up
        </div>
    </div>