@extends('layouts.desktop.trading')

@section('title', 'Verification Submitted')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 text-center">
            <h2 class="text-xl font-bold text-white mb-1">Verification Submitted</h2>
            <p class="text-sm text-[#7c86a3] mb-6">
                Thanks — your verification has been submitted. This is reviewed automatically and usually finishes
                within a few minutes; you'll be notified as soon as a decision is made. No need to resubmit.
            </p>
            <a href="{{ route('kyc.show') }}" class="inline-flex bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm px-6 py-3 rounded-lg">
                View Status
            </a>
        </div>
    </div>
</div>
@endsection
