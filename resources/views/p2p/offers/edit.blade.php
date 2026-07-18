@extends('layouts.desktop.trading')

@section('title', 'Edit P2P Offer')

@section('content')
<style>
    .p2p-shell { width: 100%; max-width: 600px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 32px; }
    .btn-go { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 16px; border-radius: 14px; font-weight: 900; cursor: pointer; width: 100%; }
</style>

<div class="p2p-shell">
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <div class="cyber-card">
        <h2 style="color:#fff; font-weight:900; margin:0 0 25px 0;">Edit Offer</h2>

        <form method="POST" action="{{ route('p2p-offers.update', $offer) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Price per Unit</label>
                <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit', $offer->price_per_unit) }}" class="brand-input-dark" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Min Limit</label>
                    <input type="number" step="0.01" name="min_limit" value="{{ old('min_limit', $offer->min_limit) }}" class="brand-input-dark" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Max Limit</label>
                    <input type="number" step="0.01" name="max_limit" value="{{ old('max_limit', $offer->max_limit) }}" class="brand-input-dark" required>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Available Amount</label>
                <input type="number" step="0.01" name="available_amount" value="{{ old('available_amount', $offer->available_amount) }}" class="brand-input-dark" required>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Terms</label>
                <textarea name="terms" rows="3" class="brand-input-dark">{{ old('terms', $offer->terms) }}</textarea>
            </div>

            <button type="submit" class="btn-go">Update Offer</button>
        </form>
    </div>
</div>
@endsection
