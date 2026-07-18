<!-- Payment Details Section -->
<form action="{{ route('deposit.step_1') }}" id="payment-methods" class="payinForm mx-auto py-2">
    @csrf
    <input type="hidden" name="deposit_step" value="2">
    <input type="hidden" name="deposit_method" value="{{ $deposit_method }}">
    <input type="hidden" name="deposit_method_id" value="{{ $deposit_method_id }}">

    <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 max-w-md">
        <div class="flex items-center mb-6">
            <img src="{{ $deposit_method->coin_logo }}" alt="{{ $deposit_method->wallet_ticker }}" class="h-12 mr-4">
            <div>
                <h2 class="text-white font-semibold">{{ $deposit_method->wallet_name }}</h2>
                <p class="text-xs text-[#7c86a3] mt-1">
                    Minimum deposit: ${{ $deposit_method->min_deposit }}<br>
                    Processing time: instantly
                </p>
            </div>
        </div>

        <label for="amount" class="block text-xs text-[#7c86a3] mb-2">Amount</label>
        <div class="relative mb-4">
            <input type="number" id="amount" value="{{ $deposit_method->min_deposit }}" min="{{ $deposit_method->min_deposit }}"
                name="deposit_amount"
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
        <button type="button" id="submitBtn" class="bg-[#16c087] hover:bg-[#13a876] text-white font-semibold text-sm py-2.5 px-6 rounded-lg">
            Continue and pay $<span class="deposit_price">{{ $deposit_method->min_deposit }}</span>
        </button>
    </div>
</form>

<script>
    function _updateDepositAmount(amount) {
        document.querySelectorAll('.deposit_price').forEach((el) => el.textContent = amount);
        document.getElementById('amount').value = amount;
    }
</script>
