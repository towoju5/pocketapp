@extends('layouts.desktop.trading')

@section('title', 'Edit P2P Offer')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        @if ($errors->any())
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ $errors->first() }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Edit Offer</h2>

            <form method="POST" action="{{ route('p2p-offers.update', $offer) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Price per Unit</label>
                    <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit', $offer->price_per_unit) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Min Limit</label>
                        <input type="number" step="0.01" name="min_limit" value="{{ old('min_limit', $offer->min_limit) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Max Limit</label>
                        <input type="number" step="0.01" name="max_limit" value="{{ old('max_limit', $offer->max_limit) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Available Amount</label>
                    <input type="number" step="0.01" name="available_amount" value="{{ old('available_amount', $offer->available_amount) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Terms</label>
                    <textarea name="terms" rows="3" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">{{ old('terms', $offer->terms) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-3 rounded-lg">Update Offer</button>
            </form>
        </div>
    </div>
</div>
@endsection
