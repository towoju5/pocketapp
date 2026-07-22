@extends('layouts.desktop.trading')

@section('title', 'Wallet')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Wallet</h1>
            <div class="text-right">
                <div class="text-xs text-[#7c86a3]">Available balance</div>
                <div class="text-lg font-bold text-white">{{ formatPrice($wallet_balance['balance'] ?? 0) }}</div>
            </div>
        </div>

        <div class="flex gap-2 mb-6">
            <button type="button" class="wallet-tab-btn wallet-tab-btn--active" data-tab="deposit">Deposit</button>
            <button type="button" class="wallet-tab-btn" data-tab="withdraw">Withdraw</button>
        </div>

        <div class="wallet-tab-panel" data-panel="deposit">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <div id="contentArea">
                    @include('deposits.partials.step-1')
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-[#7c86a3] mb-2">Other payment methods (coming soon)</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach(['Card', 'Bank Transfer', 'UPI', 'PIX'] as $comingSoon)
                        <div class="bg-[#171e33] border border-[#2a3350] rounded-lg p-3 text-center text-xs text-[#7c86a3] opacity-50 cursor-not-allowed">
                            {{ $comingSoon }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="wallet-tab-panel hidden" data-panel="withdraw">
            <form method="POST" action="{{ route('payout.create') }}" id="withdrawForm" class="grid grid-cols-[1fr_340px] gap-5 items-start">
                @csrf
                <input type="hidden" name="payment_method" id="withdrawMethodInput" value="USDT">

                <!-- Left: method selection -->
                <div>
                    @if(!$kycVerified)
                        <div class="bg-[#f4534a]/10 border border-[#f4534a]/35 rounded-xl p-4 mb-4 flex gap-3 items-start">
                            <span class="text-[#f4534a] text-lg leading-none">⚠</span>
                            <div>
                                <div class="font-bold text-sm text-white mb-1">Verification required</div>
                                <div class="text-xs text-[#7c86a3] leading-relaxed">Complete identity verification before withdrawing funds. This keeps your account and payouts secure.</div>
                                <a href="{{ route('kyc.show') }}" class="inline-block mt-2.5 bg-[#f4534a] text-white text-xs font-bold px-4 py-2 rounded-lg">Verify identity</a>
                            </div>
                        </div>
                    @else
                        <div class="bg-[#1c243c] border border-[#2a3350] rounded-xl p-3 mb-4 text-xs text-[#7c86a3] flex gap-2 items-center">
                            <i class="fa fa-lock"></i>
                            For security, withdrawals are only sent to a method you control.
                        </div>
                    @endif

                    <div class="text-xs font-semibold text-[#7c86a3] uppercase tracking-wide mb-2">Crypto</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-4">
                        <button type="button" class="withdraw-method-card withdraw-method-card--active" data-method="USDT" data-address-label="USDT (TRC20) address">
                            <div class="flex items-center gap-2.5">
                                <div class="withdraw-method-card__avatar" style="background:rgba(247,184,79,0.15);color:#f7b84f;">$</div>
                                <div class="text-left">
                                    <div class="text-sm font-semibold text-white">USDT (TRC20)</div>
                                    <div class="text-xs text-[#7c86a3]">Crypto</div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <div class="text-xs font-semibold text-[#7c86a3] uppercase tracking-wide mb-2">Bank</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-4">
                        <button type="button" class="withdraw-method-card" data-method="Bank" data-address-label="Bank account / IBAN">
                            <div class="flex items-center gap-2.5">
                                <div class="withdraw-method-card__avatar" style="background:rgba(124,134,163,0.15);color:#7c86a3;">B</div>
                                <div class="text-left">
                                    <div class="text-sm font-semibold text-white">Bank Transfer</div>
                                    <div class="text-xs text-[#7c86a3]">1-3 business days</div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Right: amount + summary -->
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 sticky top-5">
                    <div class="text-sm font-bold text-white mb-3.5">Withdrawal summary</div>

                    <label id="withdrawAddressLabel" class="block text-xs text-[#7c86a3] mb-1.5">USDT (TRC20) address</label>
                    <input type="text" name="address" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white text-sm mb-4" placeholder="Txxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ old('address') }}" required {{ $kycVerified ? '' : 'disabled' }}>

                    <label class="block text-xs text-[#7c86a3] mb-1.5">Amount (USD)</label>
                    <div class="flex items-center bg-[#1c243c] border border-[#2a3350] rounded-lg px-3 py-2.5 mb-4">
                        <span class="text-[#7c86a3] mr-1">$</span>
                        <input type="number" step="0.01" name="amount" class="bg-transparent border-0 outline-none flex-1 text-white font-semibold" placeholder="0.00" value="{{ old('amount') }}" required {{ $kycVerified ? '' : 'disabled' }}>
                    </div>

                    <div class="flex justify-between text-xs text-[#7c86a3] py-2 border-t border-[#2a3350]">
                        <span>Processing time</span><span id="withdrawTime" class="text-[#d7dcea]">Instant</span>
                    </div>
                    <div class="flex justify-between text-xs text-[#7c86a3] py-2 border-t border-[#2a3350] mb-4">
                        <span>Minimum</span><span class="text-[#d7dcea]">$30</span>
                    </div>

                    <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3d7de0] text-white font-bold text-sm py-3 rounded-lg" {{ $kycVerified ? '' : 'disabled' }}>
                        Confirm withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.wallet-tab-btn { background:#1c243c; border:1px solid #2a3350; color:#7c86a3; font-size:13px; font-weight:600; padding:8px 16px; border-radius:8px; cursor:pointer; }
.wallet-tab-btn--active { background:rgba(79,142,247,0.15); color:#4f8ef7; border-color:#4f8ef7; }
.withdraw-method-card { display:flex; align-items:center; justify-content:space-between; gap:8px; text-align:left; background:#171e33; border:1px solid #2a3350; border-radius:12px; padding:12px 14px; cursor:pointer; width:100%; box-sizing:border-box; }
.withdraw-method-card--active { background:rgba(79,142,247,0.12); border-color:#4f8ef7; }
.withdraw-method-card__avatar { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
</style>

@push('js')
<script>
    document.querySelectorAll('.wallet-tab-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.wallet-tab-btn').forEach((b) => b.classList.remove('wallet-tab-btn--active'));
            btn.classList.add('wallet-tab-btn--active');
            document.querySelectorAll('.wallet-tab-panel').forEach((p) => {
                p.classList.toggle('hidden', p.dataset.panel !== btn.dataset.tab);
            });
        });
    });

    if (window.location.search.includes('tab=withdraw')) {
        document.querySelector('.wallet-tab-btn[data-tab="withdraw"]')?.click();
    }

    document.querySelectorAll('.withdraw-method-card').forEach((card) => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.withdraw-method-card').forEach((c) => c.classList.remove('withdraw-method-card--active'));
            card.classList.add('withdraw-method-card--active');
            document.getElementById('withdrawMethodInput').value = card.dataset.method;
            document.getElementById('withdrawAddressLabel').textContent = card.dataset.addressLabel;
            document.getElementById('withdrawTime').textContent = card.dataset.method === 'Bank' ? '1-3 business days' : 'Instant';
        });
    });

    $(document).ready(function () {
        $(document).on('submit', '.payinForm', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: this.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (response) {
                    if (response && typeof response === 'object' && response.redirect) {
                        window.location.href = response.redirect;
                        return;
                    }
                    $('#contentArea').html(response);
                },
                error: function (error) {
                    toastr.error(error.responseJSON?.message || 'An error occurred.');
                },
            });
        });

        $(document).on('click', '#submitBtn', function () {
            $('.payinForm').trigger('submit');
        });
    });
</script>
@endpush
@endsection
