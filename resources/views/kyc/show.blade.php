@extends('layouts.desktop.trading')

@section('title', 'Identity Vault')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-1">Identity Vault</h2>
            <p class="text-sm text-[#7c86a3] mb-6">KYC compliance status for your account.</p>

            @if (!$kyc)
                <div class="inline-block px-4 py-2 rounded-full bg-[#1c243c] text-[#7c86a3] text-xs font-bold uppercase mb-5">
                    &#9679; Unverified
                </div>
                <p class="text-sm text-[#7c86a3] mb-6">You have not yet submitted identity documents. Verification is required before withdrawals are unlocked.</p>
                <a href="{{ route('kyc.create') }}" class="inline-flex bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm px-6 py-3 rounded-lg">Start Verification</a>
            @elseif ($kyc->status === 'pending')
                <div class="inline-block px-4 py-2 rounded-full bg-[#f7b84f]/10 text-[#f7b84f] text-xs font-bold uppercase mb-5">
                    &#9679; Awaiting Review
                </div>
                <p class="text-sm text-[#7c86a3]">Your documents were submitted on {{ $kyc->submitted_at->format('d M, Y H:i') }} and are pending review.</p>
            @elseif ($kyc->status === 'verified')
                <div class="inline-block px-4 py-2 rounded-full bg-[#16c087]/10 text-[#16c087] text-xs font-bold uppercase mb-5">
                    &#9679; Verified
                </div>
                <p class="text-sm text-[#7c86a3]">Your account is fully verified. Withdrawals are unlocked.</p>
            @else
                <div class="inline-block px-4 py-2 rounded-full bg-[#f4534a]/10 text-[#f4534a] text-xs font-bold uppercase mb-5">
                    &#9679; Rejected
                </div>
                <p class="text-sm text-[#7c86a3] mb-4">Reason: {{ $kyc->rejection_reason }}</p>
                <a href="{{ route('kyc.create') }}" class="inline-flex bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm px-6 py-3 rounded-lg">Resubmit Documents</a>
            @endif
        </div>
    </div>
</div>
@endsection
