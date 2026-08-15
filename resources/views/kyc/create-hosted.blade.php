@extends('layouts.desktop.trading')

@section('title', 'Verify Your Identity')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 text-center">
            <h2 class="text-xl font-bold text-white mb-1">Verify Your Identity</h2>
            <p class="text-sm text-[#7c86a3] mb-6">
                Identity verification for this platform is handled by {{ $provider->display_name }}.
                You'll be redirected to complete a quick, secure verification flow, then brought back here.
            </p>

            <form method="POST" action="{{ route('kyc.start') }}">
                @csrf
                <button type="submit" class="inline-flex bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm px-6 py-3 rounded-lg">
                    Start Verification with {{ $provider->display_name }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
