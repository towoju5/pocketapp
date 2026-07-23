@extends('layouts.desktop.trading')

@section('title', 'New P2P Offer')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @if ($errors->any())
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ $errors->first() }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">New P2P Offer</h2>

            <form method="POST" action="{{ route('p2p-offers.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Type</label>
                    <select name="type" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                        <option value="sell" {{ old('type', 'sell') === 'sell' ? 'selected' : '' }}>Sell</option>
                        <option value="buy" {{ old('type') === 'buy' ? 'selected' : '' }}>Buy</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Sell Currency</label>
                        <select name="sell_currency" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency }}" {{ old('sell_currency', 'USDT') === $currency ? 'selected' : '' }}>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Buy Currency</label>
                        <select name="buy_currency" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency }}" {{ old('buy_currency', 'USD') === $currency ? 'selected' : '' }}>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-xs text-[#7c86a3] -mt-2">e.g. sell USDT and buy USD — the price per unit below is in {{ old('buy_currency', 'USD') }} for each 1 {{ old('sell_currency', 'USDT') }}.</p>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Price per Unit</label>
                    <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit') }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Min Limit</label>
                        <input type="number" step="0.01" name="min_limit" value="{{ old('min_limit') }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Max Limit</label>
                        <input type="number" step="0.01" name="max_limit" value="{{ old('max_limit') }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Available Amount</label>
                    <input type="number" step="0.01" name="available_amount" value="{{ old('available_amount') }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Accepted Payment Methods</label>
                    @if ($paymentMethods->isEmpty())
                        <p class="text-sm text-[#7c86a3]">No payment methods have been configured yet. Contact support.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @foreach ($paymentMethods as $method)
                                <label class="flex items-center gap-2 bg-[#1c243c] border border-[#2a3350] rounded-lg px-3 py-2.5 text-sm text-white cursor-pointer">
                                    <input type="checkbox" name="payment_methods[]" value="{{ $method->name }}" {{ in_array($method->name, old('payment_methods', [])) ? 'checked' : '' }}>
                                    {{ $method->name }}
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Terms (optional)</label>
                    <textarea name="terms" rows="3" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">{{ old('terms') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-3 rounded-lg">Publish Offer</button>
            </form>
        </div>
    </div>
</div>
@endsection
