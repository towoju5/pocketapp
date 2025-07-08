@extends('layouts.app')

@section('content')
    <div class="w-full" @if(!is_mobile()) style="margin: 1rem" @endif>
        @include('partials.finance-header')
        <div class="grid lg:grid-cols-3 gap-5 mt-5">
            <div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
                <div class="text-center">
                    <h2 class="text-xl font-semibold">Your cashback</h2>
                    <p class="text-4xl font-bold text-blue-400">0%</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 text-center">
                    <div class="border border-dashed border-gray-700 p-4 rounded-lg bg-[#1f2334]">Last payout<br>-</div>
                    <div class="border border-dashed border-gray-700 p-4 rounded-lg bg-[#1f2334]">Total cashback<br>-</div>
                    <div class="border border-dashed border-gray-700 p-4 rounded-lg bg-[#1f2334]">Max. payout<br>-</div>
                    <div class="border border-dashed border-gray-700 p-4 rounded-lg bg-[#1f2334]">Next payout<br>-</div>
                    <div class="border border-dashed border-gray-700 p-4 rounded-lg bg-[#1f2334]">Date of activation<br>-</div>
                    <div class="border border-dashed border-gray-700 p-4 rounded-lg bg-[#1f2334]">Valid until<br>-</div>
                </div>

                <div class="mt-6 text-sm text-gray-400">
                    <h3 class="font-semibold">Terms and Conditions</h3>
                    <ul class="list-decimal list-inside space-y-2 mt-2">
                        <li>Cashback will expire automatically in 1 year after activation.</li>
                        <li>You can prolong your cashback at any moment if you have purchased cashback with the same payout percentage.</li>
                        <li>You can increase cashback percentage (maximum 10%) at any moment if you have purchased cashback with a higher payout percentage.</li>
                        <li>Cashback is calculated if the total amount of losses is greater than the gains for the previous month.</li>
                        <li>You can withdraw your refunded cashback at any moment if you have enough funds on your Real account’s balance.</li>
                        <li>The Company has the right to amend the bonus terms or terminate this promotion at any time without notice.</li>
                        <li>Cashback activation time and validity period is displayed in accordance with the server time (UTC+2).</li>
                    </ul>
                </div>
            </div>
            <div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
                <div class="mt-6 p-4 bg-[#202540] border border-dashed border-blue-500 rounded-lg">
                    <p class="text-sm text-gray-400">In case of losses the Cashback returns a part of lost funds every month. You can return up to 10% of your losses.</p>
                </div>
                <div class="mt-4 p-3 bg-[#31262b] border border-dashed border-[#a34b19] text-white rounded-lg text-left gap-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#b15017" width="30" height="30"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480L40 480c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24l0 112c0 13.3 10.7 24 24 24s24-10.7 24-24l0-112c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>
                    You don't have active Cashback at the moment.
                </div>
                <div class="mt-4 text-center">
                    <button class="bg-[#1b2832] border border-[#o25b44] hover:bg-green-500 text-white py-2 px-4 rounded">Buy it now</button>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Cashback payouts</h3>
                    <div class="border border-gray-700 p-4 rounded-lg mt-2 text-center">No data</div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Activation history</h3>
                    <div class="border border-gray-700 p-4 rounded-lg mt-2 text-center">No data</div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('js')
@endpush
