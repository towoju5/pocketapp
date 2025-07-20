 <!-- Payment Details Section -->
 <form action="{{ route('deposit.step_1') }}" id="payment-methods" class="payinForm mx-auto lg:px-10 py-3">
     <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
         @csrf
         <input type="hidden" name="deposit_step" value="2">
         <!-- Left Side -->
         <div class="lg:col-span-2 bg-gray-800 rounded-lg p-3">
             <button class="text-teal-500 flex items-center mb-4">
                 <i class="fas fa-arrow-left mr-2"></i> Back
             </button>

             <!-- Payment Information -->
             <div class="flex items-center mb-6">
                 <img src="{{ $deposit_method->coin_logo }}" alt="Tether" class="h-16 mr-4">
                 <div>
                     <h2 class="text-lg font-semibold">{{ $deposit_method->wallet_name }}</h2>
                     <p class="text-sm text-gray-400">
                         Memo Required: {{ $deposit_method->require_memo ? 'True' : 'False' }} <br>
                         Minimum deposit amount: ${{ $deposit_method->min_deposit }} <br>
                         Processing time: {{ $deposit_method->processing_time }}
                     </p>
                 </div>
             </div>



             <!-- Amount Input -->
             <div class="mb-4">
                 <label for="amount" class="block text-sm text-gray-400 mb-2">Amount:</label>
                 <div class="relative">
                     <input type="number" id="amount" value="{{ $deposit_method->min_deposit }}"
                         name="deposit_amount"
                         class="w-full bg-gray-700 text-white p-3 rounded-lg border border-gray-600">
                     <span
                         class="absolute top-0 right-0 bottom-0 flex items-center justify-center bg-gray-600 text-white px-3 rounded-r-lg">$</span>
                 </div>
             </div>

             <!-- Quick Amount Buttons -->
             <div class="flex justify-between mb-6">
                 <button type="button" onclick="_updateDepositAmount(150)" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600">$150</button>
                 <button type="button" onclick="_updateDepositAmount(200)" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600">$200</button>
                 <button type="button" onclick="_updateDepositAmount(300)" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600">$300</button>
                 <button type="button" onclick="_updateDepositAmount(500)" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600">$500</button>
             </div>

             <!-- Promo Code -->
             <div class="mb-6">
                 <label class="flex items-center text-sm text-gray-400">
                     <!-- <input type="checkbox" class="mr-2 bg-gray-700 border-gray-600 text-teal-500 focus:ring-teal-500"> -->
                    <input type="checkbox" id="usePromo" class="mr-2 bg-gray-700 border-gray-600 text-teal-500 focus:ring-teal-500">
                     <input type="hidden" name="deposit_method" value="{{ $deposit_method }}">
                     <input type="hidden" name="deposit_method_id" value="{{ $deposit_method_id }}">
                     I have a promo code
                 </label>
             </div>

            <!-- promo code Input -->
            <div class="mb-4">
                <div id="promoInputWrapper" class="mt-2 hidden">
                    <label for="amount" class="block text-sm text-gray-400 mb-2">Promo Code:</label>
                    <input type="text" id="promoCode" name="promo_code"
                    class="w-full px-3 py-2 border border-gray-600 bg-gray-800 text-white rounded-md"
                    placeholder="Enter Promo Code">
                </div>
            </div>

             <!-- Gift Selection -->
             <div class="hidden">
                 <p class="text-sm text-gray-400 mb-4">Choose your Gift for deposit:</p>
                 <div class="flex space-x-4">
                     <img src="path/to/gift1.png" alt="Gift 1"
                         class="w-12 h-12 cursor-pointer rounded-lg hover:ring-2 hover:ring-teal-500">
                     <img src="path/to/gift2.png" alt="Gift 2"
                         class="w-12 h-12 cursor-pointer rounded-lg hover:ring-2 hover:ring-teal-500">
                     <img src="path/to/gift3.png" alt="Gift 3"
                         class="w-12 h-12 cursor-pointer rounded-lg hover:ring-2 hover:ring-teal-500">
                     <img src="path/to/gift4.png" alt="Gift 4"
                         class="w-12 h-12 cursor-pointer rounded-lg hover:ring-2 hover:ring-teal-500">
                 </div>
             </div>
         </div>

         <!-- Right Side -->
         <div class="bg-gray-800 rounded-lg p-6">
             <h3 class="text-lg font-semibold mb-4">You receive:</h3>
             <p class="text-2xl font-bold mb-2">$<span class="deposit_price">30</span></p>
             <p class="text-sm text-gray-400 mb-6">+ Trader's box</p>

            <!-- Action Button -->
            <button type="submit" id="bitgoDepositBtn" class="bg-teal-500 text-white w-full py-3 rounded-lg hover:bg-teal-600 flex items-center justify-center gap-2">
                <span class="btn-icon hidden"><i class="fas fa-spinner fa-spin"></i></span>
                <span class="btn-text">Continue and pay $<span class="deposit_price">30</span></span>
            </button>

             <!-- Support Links -->
             <div class="mt-6 text-sm text-gray-400">
                 <p class="mb-2">Do you have questions or need help with account top-up?</p>
                 <a href="#" class="text-teal-500 hover:underline">View our User Guide</a> <br>
                 <a href="#" class="text-teal-500 hover:underline">Contact Support Service</a>
             </div>
         </div>
     </div>
 </form>
 <script>
     function _updateDepositAmount(amount) {
         $('.deposit_price').text(amount)
         $('#amount').val(amount)
     }

    $("#bitgoDepositBtn").on("click", function () {
        const $btn = $(this);
        $btn.find(".btn-icon").removeClass("hidden");
        $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed");
        $("#payment-methods").submit();
    });


    const usePromo = document.getElementById('usePromo');
    const promoInputWrapper = document.getElementById('promoInputWrapper');
    const promoCode = document.getElementById('promoCode');
    const form = document.getElementById('payment-methods');

    usePromo.addEventListener('change', () => {
        if (usePromo.checked) {
            promoInputWrapper.classList.remove('hidden');
            promoCode.setAttribute('required', 'required');
        } else {
            promoInputWrapper.classList.add('hidden');
            promoCode.removeAttribute('required');
        }
    });

    form.addEventListener('submit', (e) => {
        if (usePromo.checked && promoCode.value.trim() === '') {
            e.preventDefault();
            alert('Please enter a promo code.');
        }
    });
</script>
