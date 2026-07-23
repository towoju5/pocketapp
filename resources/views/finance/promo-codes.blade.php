@extends('layouts.desktop.trading')

@section('title', 'Promo Codes')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @include('partials.finance-header')

        <div class="flex gap-5 mt-6 flex-col lg:flex-row">
            <div class="flex flex-col bg-[#171e33] border border-[#2a3350] p-5 rounded-xl lg:w-[340px] flex-shrink-0">
                <div class="text-lg font-semibold mb-2 text-white">Have a promo code?</div>
                <p class="text-xs text-[#7c86a3] mb-3">Enter it below to redeem your bonus.</p>
                <input class="bg-[#1c243c] text-white border border-[#2a3350] rounded-lg px-4 py-2.5 w-full outline-none focus:border-[#4f8ef7]" id="promoCodeInput" placeholder="Enter promo code">
                <button type="button" id="promoCodeBtn" class="mt-3 bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold px-4 py-2.5 rounded-lg text-center text-sm">
                    Redeem
                </button>
                <div id="promoCodeError" class="mt-2 text-[#f4534a] text-xs hidden"></div>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] p-5 w-full rounded-xl text-white">
                <div class="text-lg font-semibold mb-3">Available promo codes</div>
                <div class="overflow-x-auto">
                    <div class="responsive-table">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead class="border-b border-[#2a3350] text-[#7c86a3] text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2">Code</th>
                                <th class="px-4 py-2">Bonus</th>
                                <th class="px-4 py-2">Valid from</th>
                                <th class="px-4 py-2">Valid until</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($promoCodes as $promo)
                                <tr class="border-b border-[#1c243c]">
                                    <td class="px-4 py-3 font-mono" data-label="Code">{{ $promo->promo_code }}</td>
                                    <td class="px-4 py-3 text-[#16c087] font-semibold" data-label="Bonus">
                                        {{ $promo->promo_discount_type === 'percentage' ? $promo->promo_discount . '%' : '$' . number_format($promo->promo_discount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-[#7c86a3]" data-label="Valid from">{{ \Illuminate\Support\Carbon::parse($promo->promo_start_date_time)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 text-[#7c86a3]" data-label="Valid until">{{ \Illuminate\Support\Carbon::parse($promo->promo_ends_date_time)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3" data-label="Action">
                                        @if($redeemedIds->contains($promo->id))
                                            <span class="bg-[#7c86a3]/15 text-[#7c86a3] px-3 py-1 rounded text-xs">Redeemed</span>
                                        @else
                                            <button type="button" class="quick-redeem-btn bg-[#4f8ef7] text-white font-medium px-3 py-1.5 rounded-md hover:bg-[#3f7de6] transition text-xs" data-code="{{ $promo->promo_code }}">
                                                Redeem
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-[#7c86a3]">No promo codes are currently available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content;
    }

    function redeemCode(code) {
        const errorEl = document.getElementById('promoCodeError');
        errorEl.classList.add('hidden');

        fetch("{{ route('finance.promo-codes.redeem') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ code }),
        })
            .then((r) => r.json())
            .then((res) => {
                if (res.status) {
                    window.toastr?.success(res.message);
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    window.toastr?.error(res.message);
                    errorEl.textContent = res.message;
                    errorEl.classList.remove('hidden');
                }
            })
            .catch(() => window.toastr?.error('Something went wrong.'));
    }

    document.getElementById('promoCodeBtn').addEventListener('click', () => {
        const code = document.getElementById('promoCodeInput').value.trim();
        if (!code) return;
        redeemCode(code);
    });

    document.querySelectorAll('.quick-redeem-btn').forEach((btn) => {
        btn.addEventListener('click', () => redeemCode(btn.dataset.code));
    });
</script>
@endpush
