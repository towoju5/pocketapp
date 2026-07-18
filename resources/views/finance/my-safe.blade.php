@extends('layouts.desktop.trading')

@section('title', 'My Safe')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-3xl mx-auto">
        @include('partials.finance-header')

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-8 text-center mt-6">
            <div class="w-14 h-14 rounded-full bg-[#1c243c] border border-[#2a3350] flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-vault text-[#4f8ef7] text-xl"></i>
            </div>
            <h2 class="text-white font-bold text-lg mb-2">My Safe</h2>
            <p class="text-[#7c86a3] text-sm max-w-md mx-auto leading-relaxed">
                A dedicated savings vault for setting aside profits away from your active trading balance is on the way.
            </p>
            <span class="inline-block mt-4 text-[11px] font-bold uppercase tracking-wide text-[#7c86a3] bg-[#1c243c] border border-[#2a3350] rounded-full px-3 py-1">Coming soon</span>
        </div>
    </div>
</div>
@endsection
