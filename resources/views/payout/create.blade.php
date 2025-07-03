@extends('layouts.app')

@section('title', 'Init Payout')

@section('content')
@if(is_mobile())
<div class="mx-2">
    <div class="bg-[#131628] rounded lg p-4">
        <p class="text-white text-xl font-semibold mb-5">Withdrawal</p>

        <div class="group my-3">
            <p class="my-1">Free balance:</p>
            <p class="my-1 font-semibold">{{ formatPrice($wallet_balance['balance'] ?? 0) }}</p>
        </div>
        <div class="group my-3">
            <p class="my-1">Minimum withdrawal amount:</p>
            <p class="my-1 font-semibold">$10</p>
        </div>
        <div class="group my-3">
            <p class="my-1">Maximum withdrawal amount::</p>
            <p class="my-1 font-semibold">$30,000</p>
        </div>
    </div>

    <form action="{{ route('payout.create.submit') }}" method="post" class="mt-6 space-y-4">
        @csrf

        <div class="grid gap-4 items-center">
            <label for="payment_method" class="text-right font-medium">Payment Method:</label>
            <select name="payment_method" id="payment_method" class="w-full bg-gray-800 border border-gray-600 rounded-md px-3 py-2 text-white">
                <option disabled selected>Select Payment Mode</option>
                @foreach($methods as $method)
                <option value="{{ $method->id }}">{{ $method->wallet_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-4 items-center">
            <label for="amount" class="text-right font-medium">Amount:</label>
            <input type="number" min="10" max="30000" name="amount" id="amount" placeholder="Enter withdrawal amount"
                class="w-full bg-gray-800 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid gap-4 items-center">
            <label for="address" class="text-right font-medium">Tether (USDT) TRC-20 Address:</label>
            <input type="text" name="address" id="address" placeholder="Enter your USDT TRC-20 address"
                class="w-full bg-gray-800 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-center pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-md text-white font-semibold transition">
                Continue
            </button>
        </div>
    </form>
</div>
@else
<div class="w-full m-8">
    <div class="m-4">
        @include('partials.finance-header')
    </div>
    <h1 class="text-white text-3xl bg-[#434858] py-3 rounded-t-2xl mx-5 px-5">Withdrawal</h1>
    <div class="w-full flex justify-center bg-[#131628] px-4 py-8">
        <div class="w-full max-w-3xl bg-[#1a1f3b] text-white rounded-b-3xl shadow-2xl p-6 lg:p-10 transition duration-300 hover:shadow-gray-700">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <span class="text-right font-medium">Free balance:</span>
                    <span>{{ formatPrice($wallet_balance['balance'] ?? 0) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <span class="text-right font-medium">Minimum withdrawal amount:</span>
                    <span>$10</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <span class="text-right font-medium">Commission:</span>
                    <span>$30,000</span>
                </div>

                <!-- Withdrawal info alert -->
                <!--
                <div class="mt-6 border border-dashed border-gray-500 bg-[rgba(57,74,116,0.3)] rounded-lg p-4 text-sm leading-relaxed">
                    Withdrawal process becomes available after you deposit on your account. Typically, a withdrawal request is processed automatically and takes a few minutes, and in some cases up to 3 business days.
                </div>
                -->

                <form action="{{ route('payout.create.submit') }}" method="post" class="mt-6 space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 items-center">
                        <label for="payment_method" class="text-right font-medium">Payment Method:</label>
                        <select name="payment_method" id="payment_method" class="w-full bg-gray-800 border border-gray-600 rounded-md px-3 py-2 text-white">
                            <option disabled selected>Select Payment Mode</option>
                            @foreach($methods as $method)
                            <option value="{{ $method->id }}">{{ $method->wallet_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-center">
                        <label for="amount" class="text-right font-medium">Amount:</label>
                        <input type="number" min="10" max="30000" name="amount" id="amount" placeholder="Enter withdrawal amount"
                            class="w-full bg-gray-800 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-center">
                        <label for="address" class="text-right font-medium">Tether (USDT) TRC-20 Address:</label>
                        <input type="text" name="address" id="address" placeholder="Enter your USDT TRC-20 address"
                            class="w-full bg-gray-800 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-center pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-md text-white font-semibold transition">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endif

<style>
    .text-gradient {
        background: linear-gradient(to right, #38bdf8, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

@endsection