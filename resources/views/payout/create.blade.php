@extends('layouts.app')

@section('title', 'Init Payout')

@section('content')
<div class="w-full m-8">
    <div class="m-4">
        @include('partials.finance-header')
    </div>
    <div class="w-full flex justify-center bg-gray-950 px-4">
        <div class="p-4 lg:p-10 bg-gray-900 bg-opacity-60 backdrop-blur-lg text-white rounded-3xl shadow-2xl p-10 max-w-2xl w-full transform transition duration-300 hover:shadow-gray-700">

            <div class="mx-auto min-w-2xl">
                <h1 class="text-white text-3xl">Withdrawal</h1>
                <div class="grid grid-cols-2 my-2 gap-5">
                    <span class="text-right">Free balance:</span>
                    <span>$0</span>
                </div>
                <div class="grid grid-cols-2 my-2 gap-5">
                    <span class="text-right">Minimum withdrawal amount:</span>
                    <span>$0</span>
                </div>
                <div class="grid grid-cols-2 my-2 gap-5">
                    <span class="text-right">Commission:</span>
                    <span>$0</span>
                </div>
                
                <div class="mt-5 border border-dashed  bg-[rgba(57,74, 116, .3)] rounded-lg flex p-4">
                    Withdrawal process becomes available after you deposit on your account. Typically a withdrawal request is processed automaticall and takes a few minutes, and in some cases upto 3 business days
                </div>
            </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(to right, #38bdf8, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

@endsection
