<!-- Payment Details Section -->
<form action="{{ route('gateway.checkout', $provider) }}" method="POST" class="mx-auto py-2">
    @csrf

    <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 max-w-md">
        <div class="flex items-center mb-6">
            @if ($provider->logo_url)
                <img src="{{ $provider->logo_url }}" alt="{{ $provider->display_name }}" class="h-12 mr-4">
            @endif
            <div>
                <h2 class="text-white font-semibold">{{ $provider->display_name }}</h2>
                <p class="text-xs text-[#7c86a3] mt-1">
                    Minimum deposit: ${{ $provider->min_deposit ?? '1.00' }}<br>
                    Processing time: instantly
                </p>
            </div>
        </div>

        <label for="amount" class="block text-xs text-[#7c86a3] mb-2">Amount</label>
        <div class="relative mb-4">
            <input type="number" id="amount" value="{{ $provider->min_deposit ?? 100 }}" min="{{ $provider->min_deposit ?? '0.01' }}"
                @if ($provider->max_deposit) max="{{ $provider->max_deposit }}" @endif step="0.01"
                name="amount"
                class="w-full bg-[#1c243c] text-white p-3 rounded-lg border border-[#2a3350]">
            <span class="absolute top-0 right-0 bottom-0 flex items-center justify-center px-3 text-[#7c86a3]">USD</span>
        </div>

        <div class="flex gap-2 mb-6">
            @foreach([150, 200, 300, 500] as $quick)
                <button type="button" onclick="_updateDepositAmount({{ $quick }})" class="bg-[#1c243c] border border-[#2a3350] text-white text-xs font-semibold px-3 py-2 rounded-lg">${{ $quick }}</button>
            @endforeach
        </div>
    </div>

    <div class="flex justify-between mt-6 max-w-md">
        <button type="button" onclick="window.history.back()" class="bg-[#1c243c] border border-[#2a3350] hover:bg-[#232c47] text-white font-semibold text-sm py-2.5 px-5 rounded-lg">
            Back
        </button>
        <button type="submit" class="bg-[#16c087] hover:bg-[#13a876] text-white font-semibold text-sm py-2.5 px-6 rounded-lg">
            Continue and pay $<span class="deposit_price">{{ $provider->min_deposit ?? 100 }}</span>
        </button>
    </div>
</form>

<script>
    function _updateDepositAmount(amount) {
        document.querySelectorAll('.deposit_price').forEach((el) => el.textContent = amount);
        document.getElementById('amount').value = amount;
    }
</script>
