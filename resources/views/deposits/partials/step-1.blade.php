<!-- Main Content -->
<div class="container mx-auto px-4 lg:px-10 py-6">
    <!-- Trader's Box -->
    <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Open Trader's Box</h2>
            <p class="text-gray-400">For a first deposit of $25 or more, you receive a random reward from the Trader's
                Box.</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="flex items-center justify-between mb-6">
        <input type="text" id="search-box"
            class="bg-gray-800 text-white w-full lg:w-1/2 p-3 rounded-lg border border-gray-700 placeholder-gray-400"
            placeholder="Search Payment Methods">
        <div class="flex items-center space-x-4 ml-4">
            <button id="list-view" class="appearance bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-th-list"></i>
            </button>
            <button id="grid-view" class="appearance bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-th-large"></i>
            </button>
        </div>
    </div>

    <!-- Payment Methods -->
    <div id="payment-methods" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Example Method -->
        @foreach ($methods as $method)
            <label class="payment-method bg-gray-800 rounded-lg p-4 flex flex-col justify-between cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-medium">{{ $method->wallet_name }}</span>
                    <img src="{{ $method->coin_logo }}" alt="USDT" class="h-6">
                </div>
                <div class="flex justify-between text-sm text-gray-400 mt-4">
                    <span>Min: $30</span>
                    <span>Instantly</span>
                </div>
                <input type="radio" name="deposit_method" value="{{ $method->id }}" class="hidden">
            </label>
        @endforeach

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchBox = document.getElementById('search-box');
        const paymentMethods = document.querySelectorAll('.payment-method');
        const listViewButton = document.getElementById('list-view');
        const gridViewButton = document.getElementById('grid-view');
        const paymentMethodsContainer = document.getElementById('payment-methods');

        // Search functionality
        searchBox.addEventListener('input', () => {
            const keyword = searchBox.value.toLowerCase();
            paymentMethods.forEach(method => {
                const text = method.innerText.toLowerCase();
                if (text.includes(keyword)) {
                    method.style.display = '';
                } else {
                    method.style.display = 'none';
                }
            });
        });

        // Toggle grid/list view
        listViewButton.addEventListener('click', () => {
            paymentMethodsContainer.classList.remove('grid-cols-2', 'grid-cols-4');
            paymentMethodsContainer.classList.add('grid-cols-1');
        });

        gridViewButton.addEventListener('click', () => {
            paymentMethodsContainer.classList.remove('grid-cols-1');
            paymentMethodsContainer.classList.add('grid-cols-2', 'lg:grid-cols-4');
        });

        // Radio selection style
        paymentMethods.forEach(method => {
            method.addEventListener('click', () => {
                paymentMethods.forEach(m => {
                    if (m === method) {
                        m.classList.add('bg-blue-500');
                        m.classList.remove('bg-gray-800');
                    } else {
                        m.classList.add('bg-gray-800');
                        m.classList.remove('bg-blue-500');
                    }
                });
                alert("DO NOT USE, THIS SERVICE IS IN DEVELOPMENT")
            });
        });

        alert("DO NOT USE, THIS SERVICE IS IN DEVELOPMENT")
    });
</script>
