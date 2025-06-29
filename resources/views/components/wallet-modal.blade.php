<div
    id="balance-info2"
    class="absolute right-0 mt-1 w-[330px] bg-[#262c41] text-slate-400 rounded-lg p-2 flex flex-col gap-2 hidden border border-gray-700 z-30">

    <div class="bg-[#262c41] p-4 space-y-6 rounded-md">
        <!-- Header -->
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-white text-md font-medium mb-2">Quick account top-up</h1>
                <p class="text-gray-400 text-sm">Make a deposit to start earning</p>
            </div>
            <button onclick="hideCard()" class="text-gray-400 hover:text-white p-1">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Payment Method -->
        <div class="max-w-md mx-auto">
            <div>
                <label class="block text-white text-sm mb-3">Payment Method</label>
                <div class="relative">
                    <button
                        id="paymentButton"
                        class="w-full bg-[#162032] rounded-lg p-2 flex items-center justify-between text-gray-300">
                        <div class="flex items-center gap-3" id="selectedOption">
                            <span class="text-sm">Choose another...</span>
                        </div>
                        <svg id="dropdownArrow" class="w-5 h-5 transition-transform duration-200" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.9433 11.2266L12.5714 14.7891C12.4339 14.9297 12.2556 15 12.0748 15C11.8951 15 11.7152 14.9296 11.579 14.7888L8.20709 11.2263C7.765 10.776 8.07784 10 8.70447 10L15.4457 10C16.0706 10 16.3839 10.776 15.9433 11.2266Z" />
                        </svg>
                    </button>

                    <div id="dropdownMenu" class="hidden absolute w-full mt-2 p-2 bg-[#262c41] border border-[#202537] rounded-lg overflow-hidden z-10">
                        <div class="space-y-1">
                            <button data-option="tether-trc20" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <img src="https://pocketoption.com/images/payments/logo2/usdt-trc20.png" alt="USDT" class="w-8 object-cover h-8" />
                                <span class="text-sm">Tether (USDT) TRC-20</span>
                            </button>

                            <button data-option="bank-transfer" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <img src="https://pocketoption.com/images/payments/logo2/banking-ng.png" alt="Bank" class="w-8 object-cover h-8" />
                                <span class="text-sm">Bank Transfer (NGN)</span>
                            </button>

                            <button data-option="qafpay" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <img src="https://pocketoption.com/images/payments/logo2/qafpay-ng.png" alt="QafPay" class="w-8 object-cover h-8" />
                                <span class="text-sm">QafPay</span>
                            </button>

                            <button data-option="cards" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <img src="https://pocketoption.com/images/payments/logo2/monetix-nigeria-cc.png" alt="Cards" class="w-8 object-cover h-8" />
                                <span class="text-sm">Visa, Mastercard, Verve</span>
                            </button>

                            <button data-option="cards" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <img src="https://pocketoption.com/images/payments/logo2/usdt-ton.png" alt="Cards" class="w-8 object-cover h-8" />
                                <span class="text-sm">Tether (USDT) TON</span>
                            </button>

                            <button data-option="cards" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <img src="https://pocketoption.com/images/payments/logo2/usdt-bep20.png" alt="Cards" class="w-8 object-cover h-8" />
                                <span class="text-sm">Tether (USDT) BEP20</span>
                            </button>

                            <button data-option="choose-another" class="border border-[#393d4a] payment-option w-full p-2 bg-[#293145] flex items-center gap-3 rounded-md hover:bg-[#1e2538] text-gray-300">
                                <span class="text-sm">Choose another...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const paymentButton = document.getElementById('paymentButton');
                const dropdownMenu = document.getElementById('dropdownMenu');
                const dropdownArrow = document.getElementById('dropdownArrow');
                const selectedOption = document.getElementById('selectedOption');
                const paymentOptions = document.querySelectorAll('.payment-option');

                function toggleDropdown() {
                    dropdownMenu.classList.toggle('hidden');
                    dropdownArrow.classList.toggle('rotate-180');
                }

                function selectOption(button) {
                    const option = button.dataset.option;
                    const buttonContent = button.innerHTML;
                    selectedOption.innerHTML = buttonContent;
                    toggleDropdown();
                }

                paymentButton.addEventListener('click', toggleDropdown);

                paymentOptions.forEach(option => {
                    option.addEventListener('click', () => selectOption(option));
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!paymentButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownArrow.classList.remove('rotate-180');
                    }
                });
            });
        </script>

        <!-- Amount -->
        <div>
            <label class="block text-white text-sm mb-3">Amount</label>
            <div class="overflow-hidden relative bg-[#162032] rounded-lg text-white text-lg focus:outline-none focus:ring-1 focus:ring-gray-500 flex flex-row">
                <input type="text" value="200"
                    class="w-full pl-2" />
                <span class="bg-[#232f44] p-2 w-[20%] justify-center items-center text-center  text-gray-400 text-sm">$</span>
            </div>
        </div>

        <!-- Promo Code Toggle -->
        <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative w-6 h-6 border border-gray-600 items-center justify-center text-center rounded">
                <input type="checkbox" class="absolute w-full h-full opacity-0 cursor-pointer" onchange="togglePromoCode()" />
                <div class="check-mark hidden">✓</div>
            </div>
            <span class="text-white text-sm">I have a promo code</span>
        </label>

        <!-- Promo Code Input -->
        <div id="promoCodeSection" class="hidden">
            <label class="block text-white text-sm mb-3">Promo code</label>
            <input type="text"
                class="w-full bg-[#162032] rounded-lg p-2 text-white text-lg focus:outline-none focus:ring-1 focus:ring-gray-500" />
        </div>

        <!-- Continue Button -->
        <button class="w-full bg-[#065f46] hover:bg-green-700 text-white rounded-lg p-2 text-sm font-medium transition-colors">
            Continue and pay $200
        </button>
    </div>
</div>