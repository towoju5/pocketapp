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

        <div class="bg-[#1c243c] border border-[#d97706] rounded-lg p-4 mb-6">
            <h3 class="text-[#d97706] text-sm font-semibold mb-2">To get your {{ $deposit_method->wallet_ticker }} payment processed automatically:</h3>
            <ul class="text-xs text-[#d7dcea] space-y-1.5 list-inside list-disc">
                <li>The exact USD-equivalent amount should reach the specified address.</li>
                <li>Use only the {{ $deposit_method->wallet_ticker }} network for your transfer.</li>
                <li>Generate a new payment address for each new deposit.</li>
            </ul>
            <p class="text-[#d97706] text-xs mt-3">Failure to meet these requirements may result in loss of funds.</p>
        </div>

        <div class="mb-4">
            <label class="block text-xs text-[#7c86a3] mb-1">Amount to transfer</label>
            <div class="relative">
                <input type="text" value="${{ number_format((float) $deposit_amount, 2) }}" readonly class="w-full bg-[#1c243c] text-white p-3 rounded-lg border border-[#2a3350] font-mono text-sm">
                <button type="button" class="copy-btn absolute right-2 top-2 bg-[#2a3350] text-white text-xs font-semibold px-3 py-1.5 rounded-lg" data-copy="{{ number_format((float) $deposit_amount, 2) }}">Copy</button>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-xs text-[#7c86a3] mb-1">To the address</label>
            <div class="relative">
                <input type="text" id="wallet-address" value="{{ $wallet->address }}" readonly class="w-full bg-[#1c243c] text-white p-3 rounded-lg border border-[#2a3350] font-mono text-sm">
                <button type="button" class="copy-btn absolute right-2 top-2 bg-[#2a3350] text-white text-xs font-semibold px-3 py-1.5 rounded-lg" data-copy="{{ $wallet->address }}">Copy</button>
            </div>
        </div>

        <div class="text-center mt-6">
            <img src="https://quickchart.io/qr?text={{ urlencode($wallet->address) }}&margin=0&size=200" alt="QR code" class="w-32 h-32 mx-auto mb-3 rounded-lg">
            <p class="text-xs text-[#7c86a3]">Or scan the QR code to make your payment.</p>
        </div>
    </div>

    <form action="{{ route('deposit.step_1') }}" id="payment-methods" class="payinForm max-w-lg">
        @csrf
        <input type="hidden" name="deposit_step" value="3">
        <input type="hidden" name="deposit_amount" value="{{ $deposit_amount }}">
        <input type="hidden" name="wallet_address" value="{{ $wallet->address }}">
        <input type="hidden" name="deposit_method" value="{{ json_encode($deposit_method) }}">

        <div class="flex justify-between mt-6">
            <button type="button" onclick="window.history.back()" class="bg-[#1c243c] border border-[#2a3350] hover:bg-[#232c47] text-white font-semibold text-sm py-2.5 px-5 rounded-lg">
                Back
            </button>
            <button type="button" id="submitBtn" class="bg-[#16c087] hover:bg-[#13a876] text-white font-semibold text-sm py-2.5 px-6 rounded-lg">
                I've sent the payment
            </button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.copy-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            navigator.clipboard?.writeText(btn.dataset.copy);
            const original = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(() => btn.textContent = original, 1500);
        });
    });
</script>
