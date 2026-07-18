<div class="mx-auto py-2">
    <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 max-w-lg">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <img src="{{ $deposit_method->coin_logo }}" alt="{{ $deposit_method->wallet_ticker }}" class="h-10 mr-3">
                <div>
                    <h2 class="text-white font-semibold">{{ $deposit_method->wallet_name }}</h2>
                    <p class="text-xs text-[#7c86a3]">Deposit amount</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xl font-bold text-white">${{ number_format((float) $deposit_amount, 2) }}</p>
                <p class="text-xs text-[#7c86a3]">instantly</p>
            </div>
        </div>

        <div class="mb-2">
            <label class="block text-xs text-[#7c86a3] mb-1">Sent to address</label>
            <input type="text" value="{{ $wallet_address }}" readonly class="w-full bg-[#1c243c] text-white p-3 rounded-lg border border-[#2a3350] font-mono text-sm">
        </div>

        <p class="text-xs text-[#7c86a3] mt-4">
            Your deposit will be credited to your real-money wallet once the transaction is confirmed on the {{ $deposit_method->wallet_ticker }} network.
        </p>
    </div>

    <form action="{{ route('deposit.step_1') }}" id="payment-methods" class="payinForm max-w-lg">
        @csrf
        <input type="hidden" name="deposit_step" value="4">
        <input type="hidden" name="deposit_amount" value="{{ $deposit_amount }}">
        <input type="hidden" name="deposit_method" value="{{ json_encode($deposit_method) }}">

        <div class="flex justify-between mt-6">
            <button type="button" onclick="window.history.back()" class="bg-[#1c243c] border border-[#2a3350] hover:bg-[#232c47] text-white font-semibold text-sm py-2.5 px-5 rounded-lg">
                Back
            </button>
            <button type="button" id="submitBtn" class="bg-[#16c087] hover:bg-[#13a876] text-white font-semibold text-sm py-2.5 px-6 rounded-lg">
                Confirm deposit
            </button>
        </div>
    </form>
</div>
