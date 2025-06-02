<div class="mx-auto px-4 lg:px-10">
    <!-- Back Button -->
    <button class="text-teal-500 flex items-center mb-6">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back
    </button>

    <!-- Payment Summary -->
    <div class="bg-gray-800 rounded-lg p-6">
        <!-- Deposit Information -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <img src="{{ $deposit_method->coin_logo }}" alt="{{ $deposit_method->wallet_ticker }}" class="h-12 mr-4">
                <div>
                    <h2 class="text-lg font-semibold">{{ $deposit_method->wallet_name }}</h2>
                    <p class="text-sm text-gray-400">Deposit amount</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold">${{ $deposit_amount }}</p>
                <p class="text-sm text-gray-400">instantly</p>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-gray-700 rounded-lg p-4 border border-orange-500 mb-6">
            <h3 class="text-orange-400 font-semibold mb-2">In order to get your {{ $deposit_method->wallet_ticker }} payment processed automatically:</h3>
            <ul class="text-sm text-gray-300 space-y-2 list-inside list-disc">
                <li>The exact USD amount should reach the specified address.</li>
                <li>Use only {{ $deposit_method->wallet_ticker }} network for your transfer.</li>
                <li>Generate a new payment form for each new deposit.</li>
            </ul>
            <p class="text-orange-400 mt-4">Failure to meet one of the requirements will result in the loss of funds.</p>
        </div>

        <!-- Transfer Details -->
        <div class="space-y-4">
            <!-- Amount to Transfer -->
            <div>
                <label for="amount" class="block text-sm text-gray-400 mb-1">To complete the payment, please transfer:</label>
                <div class="relative">
                    <input type="text" id="amount" value="${{ $deposit_amount }}" readonly class="w-full bg-gray-700 text-white p-3 rounded-lg border border-gray-600">
                    <button class="absolute right-3 top-2 bg-teal-500 text-white px-3 py-1 rounded-lg hover:bg-teal-600">
                        Copy
                    </button>
                </div>
            </div>

            <!-- Wallet Address -->
            <div>
                <label for="wallet-address" class="block text-sm text-gray-400 mb-1">to the address:</label>
                <div class="relative">
                    <input type="text" id="wallet-address" value="{{ $wallet->address }}" readonly class="w-full bg-gray-700 text-white p-3 rounded-lg border border-gray-600">
                    <button class="absolute right-3 top-2 bg-teal-500 text-white px-3 py-1 rounded-lg hover:bg-teal-600">
                        Copy
                    </button>
                </div>
            </div>

            <!-- QR Code -->
            <div class="text-center mt-6">
                <img src="https://quickchart.io/qr?text={{ $wallet->address }}&margin=0&size=400" alt="QR Code" class="w-32 h-32 mx-auto mb-4">
                <p class="text-sm text-gray-400">Or simply scan the generated QR code to make your payment.</p>
            </div>
        </div>
    </div>
</div>