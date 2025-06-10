@extends('layouts.app')

@section('content')
    <div class="bg-[#15182a] rounded-lg w-full m-6 max-h-[90%] text-white">
        <!-- Multi-Step Wizard -->
        <div class="max-w-6xl mx-auto py-10 px-4 lg:px-20">
            <div class="shadow-lg p-6 lg:p-10">
                <!-- Step Progress -->
                <div class="flex items-center justify-between mb-8">
                    <div class="text-teal-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center font-bold">1</span>
                        <span class="ml-2">Deposit method</span>
                    </div>
                    <div class="text-white flex items-center">
                        <span
                            class="w-8 h-8 bg-gray-700 text-white rounded-full flex items-center justify-center font-bold">2</span>
                        <span class="ml-2">Payment details</span>
                    </div>
                    <div class="text-gray-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-gray-700 text-white rounded-full flex items-center justify-center font-bold">3</span>
                        <span class="ml-2">Payment process</span>
                    </div>
                    <div class="text-gray-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-gray-700 text-white rounded-full flex items-center justify-center font-bold">4</span>
                        <span class="ml-2">Payment execution</span>
                    </div>
                </div>

                <!-- Deposit Form -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <div class="flex gap-2 p-3">
                            <img src="//pocketoption.com/images/payments/logo2/usdt-trc20.webp" alt="Method Image" class="w-20 h-20">
                            <div class="flex-right">
                                <h2 class="text-lg font-semibold mb-4">Tether (USDT) TRC-20</h2>
                                <p class="text-sm text-gray-400">Commission: <span class="text-white">0%</span></p>
                                <p class="text-sm text-gray-400">Minimum deposit amount: <span class="text-white">$30</span>
                                </p>
                                <p class="text-sm text-gray-400">Processing time: <span class="text-white">Instantly</span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="amount" class="text-sm font-medium text-gray-300">Amount:</label>
                            <input type="number" id="amount"
                                class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg p-3 mt-2"
                                placeholder="$30">
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button
                                class="w-full bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-lg">$150</button>
                            <button
                                class="w-full bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-lg">$200</button>
                            <button
                                class="w-full bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-lg">$300</button>
                            <button
                                class="w-full bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-lg">$500</button>
                        </div>
                        <div class="mt-4 flex items-center">
                            <input type="checkbox" id="promo" class="bg-gray-700 border-gray-600 rounded-md mr-2">
                            <label for="promo" class="text-sm text-gray-300">I have a promo code</label>
                        </div>
                        <!-- Gift Selection -->
                        <div class="mt-10">
                            <h2 class="text-lg font-semibold mb-4">Choose your Gift for deposit:</h2>
                            <div class="grid grid-cols-5 gap-4">
                                <div
                                    class="w-full h-20 bg-green-600 rounded-lg flex items-center justify-center text-white font-medium">
                                    Gift 1</div>
                                <div
                                    class="w-full h-20 bg-red-600 rounded-lg flex items-center justify-center text-white font-medium">
                                    Gift 2</div>
                                <div
                                    class="w-full h-20 bg-yellow-600 rounded-lg flex items-center justify-center text-white font-medium">
                                    Gift 3</div>
                                <div
                                    class="w-full h-20 bg-purple-600 rounded-lg flex items-center justify-center text-white font-medium">
                                    Gift 4</div>
                                <div
                                    class="w-full h-20 bg-blue-600 rounded-lg flex items-center justify-center text-white font-medium">
                                    Gift 5</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h2 class="text-lg font-semibold mb-4">You Receive:</h2>
                        <p class="text-xl font-bold">$30</p>
                        <p class="text-gray-400">+ Trader's box</p>
                        <button
                            class="mt-6 w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 rounded-lg">Continue
                            and pay $30</button>
                        <p class="text-sm text-gray-400 mt-6">Do you have questions or need help with account top-up?</p>
                        <div class="mt-2 flex space-x-4">
                            <a href="#" class="text-teal-500 text-sm hover:underline">View our User Guide</a>
                            <a href="#" class="text-teal-500 text-sm hover:underline">Contact Support Service</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        {{-- <div class="w-full bg-[#15182a] text-gray-500 text-sm py-4 px-6 mt-10">
            <div class="flex justify-between">
                <p>© 2025 Pocket Option. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:underline">About us</a>
                    <a href="#" class="hover:underline">Help</a>
                    <a href="#" class="hover:underline">Terms and Conditions</a>
                    <a href="#" class="hover:underline">AML and KYC policy</a>
                    <a href="#" class="hover:underline">Privacy policy</a>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
