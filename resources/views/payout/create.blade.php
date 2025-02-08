@extends('layouts.app')

@section('title', 'Init Payout')

@section('content')
<div class="w-full flex justify-center bg-gray-950 px-4">
    <div class="p-4 lg:p-10 bg-gray-900 bg-opacity-60 backdrop-blur-lg text-white rounded-3xl shadow-2xl p-10 max-w-2xl w-full transform transition duration-300 hover:shadow-gray-700">

        <h3 class="text-4xl font-extrabold text-center mb-8 text-gradient">💰 Withdraw Funds</h3>

        <!-- Centered Deposit Warning -->
        @if(auth()->user()->user_deposit()->count() < 1)
            <div class="flex justify-center mb-6">
                <div class="bg-red-600 text-white p-4 rounded-lg shadow-md animate-pulse max-w-md text-center">
                    <strong>🚨 Note:</strong> Withdrawal process becomes available after you deposit on your account. Typically, a withdrawal request is processed automatically and takes a few minutes, and in some cases up to 3 business days.
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Side: Balance Info -->
            <div class="space-y-4">
                <div class="p-5 bg-gray-800 rounded-xl text-center shadow-md transform transition hover:scale-105">
                    <span class="text-gray-400 text-sm">Available Balance</span>
                    <p class="text-2xl font-bold text-green-400">$0</p>
                </div>

                <div class="p-5 bg-gray-800 rounded-xl text-center shadow-md transform transition hover:scale-105">
                    <span class="text-gray-400 text-sm">Minimum Withdrawal</span>
                    <p class="text-2xl font-bold text-yellow-400">$10</p>
                </div>

                <div class="p-5 bg-gray-800 rounded-xl text-center shadow-md transform transition hover:scale-105">
                    <span class="text-gray-400 text-sm">Commission</span>
                    <p class="text-2xl font-bold text-red-400">$0</p>
                </div>
            </div>

            <!-- Right Side: Withdrawal Form -->
            <div class="bg-gray-800 bg-opacity-50 p-6 rounded-xl shadow-md transform transition hover:scale-105">
                <form action="{{ route('payout.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Crypto Gateway -->
                    <div>
                        <label class="block text-gray-300 mb-2 font-semibold">Select Crypto Gateway:</label>
                        <select name="crypto_gateway" class="w-full bg-gray-900 text-white p-3 rounded-lg border border-gray-700 shadow-md focus:ring-2 focus:ring-green-500">
                            <option value="btc">Bitcoin (BTC)</option>
                            <option value="eth">Ethereum (ETH)</option>
                            <option value="usdt">Tether (USDT)</option>
                            <option value="bnb">Binance Coin (BNB)</option>
                        </select>
                    </div>

                    <!-- Wallet Address -->
                    <div>
                        <label class="block text-gray-300 mb-2 font-semibold">Enter Wallet Address:</label>
                        <input type="text" name="wallet_address" class="w-full bg-gray-900 text-white p-3 rounded-lg border border-gray-700 shadow-md focus:ring-2 focus:ring-green-500" placeholder="Paste your wallet address">
                    </div>

                    <!-- Base Network Notification -->
                    <div class="bg-yellow-600 text-white p-4 rounded-lg text-sm shadow-md">
                        <strong>⚠ Important:</strong> Only the base network is supported. Ensure your wallet matches the selected crypto gateway.
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-teal-600 hover:opacity-90 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform transition duration-200 hover:scale-105">
                        🚀 Submit Withdrawal
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(to right, #38bdf8, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

@endsection
