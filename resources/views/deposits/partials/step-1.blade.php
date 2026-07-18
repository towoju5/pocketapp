<!-- Main Content -->
<div class="mx-auto py-2">
    <!-- Search and Filter -->
    <div class="flex items-center justify-between mb-5">
        <input type="text" id="search-box"
            class="bg-[#1c243c] text-white w-full lg:w-1/2 p-3 rounded-lg border border-[#2a3350] placeholder-[#7c86a3]"
            placeholder="Search payment methods">
        <div class="flex items-center space-x-2 ml-4">
            <button id="list-view" type="button" class="bg-[#1c243c] border border-[#2a3350] text-white px-4 py-2 rounded-lg">
                <i class="fas fa-th-list"></i>
            </button>
            <button id="grid-view" type="button" class="bg-[#1c243c] border border-[#2a3350] text-white px-4 py-2 rounded-lg">
                <i class="fas fa-th-large"></i>
            </button>
        </div>
    </div>

    <!-- Payment Methods -->
    <form action="{{ route('deposit.step_1') }}" id="payment-methods" class="payinForm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        @csrf
        <input type="hidden" name="deposit_step" value="1">
        @forelse ($methods as $method)
            <label class="payment-method bg-[#171e33] border border-[#2a3350] rounded-xl p-4 flex flex-col justify-between cursor-pointer" style="min-width: 200px;">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-white">{{ $method->wallet_name }}</span>
                    <img src="{{ $method->coin_logo }}" alt="{{ $method->wallet_ticker }}" class="h-6">
                </div>
                <div class="flex justify-between text-xs text-[#7c86a3] mt-4">
                    <span>Min: ${{ $method->min_deposit }}</span>
                    <span>Instantly</span>
                </div>
                <input type="radio" name="deposit_method" value="{{ $method->id }}" class="hidden">
            </label>
        @empty
            <div class="col-span-4 text-center text-[#7c86a3] py-10">No deposit methods are configured yet.</div>
        @endforelse
    </form>
</div>
<div class="flex justify-between mt-6">
    <a href="{{ route('wallet.index') }}" class="bg-[#1c243c] border border-[#2a3350] hover:bg-[#232c47] text-white font-semibold text-sm py-2.5 px-5 rounded-lg">
        Cancel
    </a>
    <button type="button" id="submitBtn" class="bg-[#16c087] hover:bg-[#13a876] text-white font-semibold text-sm py-2.5 px-6 rounded-lg">
        Continue
    </button>
</div>

<style>
    .payment-method.selected { background: rgba(79,142,247,0.15) !important; border-color: #4f8ef7 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchBox = document.getElementById('search-box');
        const paymentMethods = document.querySelectorAll('.payment-method');
        const listViewButton = document.getElementById('list-view');
        const gridViewButton = document.getElementById('grid-view');
        const paymentMethodsContainer = document.getElementById('payment-methods');

        searchBox?.addEventListener('input', () => {
            const keyword = searchBox.value.toLowerCase();
            paymentMethods.forEach(method => {
                method.style.display = method.innerText.toLowerCase().includes(keyword) ? '' : 'none';
            });
        });

        listViewButton?.addEventListener('click', () => {
            paymentMethodsContainer.classList.remove('grid-cols-2', 'lg:grid-cols-4');
            paymentMethodsContainer.classList.add('grid-cols-1');
        });

        gridViewButton?.addEventListener('click', () => {
            paymentMethodsContainer.classList.remove('grid-cols-1');
            paymentMethodsContainer.classList.add('md:grid-cols-2', 'lg:grid-cols-4');
        });

        paymentMethods.forEach(method => {
            method.addEventListener('click', () => {
                paymentMethods.forEach(m => m.classList.toggle('selected', m === method));
            });
        });
    });
</script>
