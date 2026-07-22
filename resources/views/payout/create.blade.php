@extends('layouts.desktop.trading')

@section('title', 'Withdraw Funds')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto space-y-6">
        <div>
            @include('partials.finance-header')
        </div>

        <div>
            <h1 class="text-2xl font-bold text-white">Withdraw <span class="text-[#4f8ef7]">Funds</span></h1>
            <p class="text-sm text-[#7c86a3] mt-1">Request a withdrawal from your trading account</p>
        </div>

        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg">{{ $errors->first() }}</div>
        @endif

        @if(!($kycVerified ?? false))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg">
                Identity verification is required before you can withdraw.
                <a href="{{ route('kyc.show') }}" class="text-white underline">Verify identity</a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-[#171e33] border border-[#2a3350] rounded-2xl p-8">
                <h2 class="text-lg font-bold text-white mb-6">Withdrawal Details</h2>
                <form method="POST" action="{{ route('payout.create') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Method</label>
                        <select name="payment_method" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">
                            <option value="USDT">USDT (TRC20)</option>
                            <option value="Bank">Bank Transfer</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#4f8ef7] font-bold text-xl">$</span>
                            <input type="number" step="0.01" name="amount" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg pl-9 pr-4 py-4 text-2xl font-bold text-[#4f8ef7] focus:outline-none focus:border-[#4f8ef7]" placeholder="0.00" value="{{ old('amount') }}" required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">USDT (TRC20) Address</label>
                        <input type="text" name="address" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-[#4f8ef7]" placeholder="Txxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ old('address') }}" required>
                    </div>

                    <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-bold text-sm uppercase tracking-wide py-4 rounded-lg transition">Submit Withdrawal</button>
                </form>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] rounded-2xl p-6 h-fit">
                <h3 class="text-sm font-bold text-white mb-4">Withdrawal Info</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#7c86a3]">Processing time</span>
                        <span class="text-white font-semibold">Up to 24h</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#7c86a3]">Network fee</span>
                        <span class="text-white font-semibold">Deducted at payout</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#7c86a3]">Minimum amount</span>
                        <span class="text-white font-semibold">$1.00</span>
                    </div>
                </div>
                <hr class="border-[#2a3350] my-4">
                <p class="text-xs text-[#7c86a3] leading-relaxed">Withdrawals are sent to the address you provide — double check it before submitting, transactions on the blockchain can't be reversed.</p>
            </div>
        </div>
    </div>
</div>
@endsection
