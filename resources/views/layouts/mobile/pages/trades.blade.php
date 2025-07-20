@foreach(get_my_trades() as $trade)
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
@endforeach

@push('css')
<style>
    .signal-card {
        padding: 10px;
        width: 330px;
    }

    .signal-card:nth-child(even) {
        background-color: #292d41;
    }

    .signal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* margin-bottom: 10px; */
    }

    .signal-title {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #A0AEC0;
        gap: .75rem;
    }

    .signal-title i {
        color: #FFA500;
        margin-right: 5px;
    }

    .signal-percentage {
        color: #4CAF50;
        font-weight: bold;
    }

    .signal-time {
        color: white;
        /* font-size: 10px; */
    }

    .signal-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .signal-price {
        display: flex;
        align-items: center;
        font-size: 14px;
        font-weight: bold;
    }

    .signal-price i {
        color: #4CAF50;
        margin-right: 5px;
    }

    .signal-profit {
        color: #4CAF50;
        font-size: 14px;
        font-weight: bold;
    }

    .signal-button {
        background-color: #19222D;
        border: 1px solid #0F5D42;
        border-radius: 5px;
        padding: 4px;
        text-align: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .signal-button i {
        margin-right: 5px;
    }
    .fa-arrow-up {
        color: rgb(73, 167, 68);
        margin-right: 2px;
        transform: rotate(45deg);
    }
</style>
@endpush