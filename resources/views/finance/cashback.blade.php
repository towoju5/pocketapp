@extends('layouts.desktop.trading')

@section('content')
    <div class="w-full" style="margin: 1rem">
        @include('partials.finance-header')
        <div class="grid lg:grid-cols-3 gap-5 mt-5">
            <div class="bg-[#171e33] border border-[#2a3350] text-[#d7dcea] p-6 rounded-xl w-full max-w-4xl mx-auto">
                <div class="text-center">
                    <h2 class="text-xl font-semibold text-white">Your cashback</h2>
                    <p class="text-4xl font-bold text-[#4f8ef7]">0%</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 text-center">
                    <div class="border border-[#2a3350] p-4 rounded-lg">Last payout<br>-</div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">Total cashback<br>-</div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">Max. payout<br>-</div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">Next payout<br>-</div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">Date of activation<br>-</div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">Valid until<br>-</div>
                </div>

                <div class="mt-6 text-sm text-[#7c86a3]">
                    <h3 class="font-semibold text-[#d7dcea]">Terms and Conditions</h3>
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
            <div class="bg-[#171e33] border border-[#2a3350] text-[#d7dcea] p-6 rounded-xl w-full max-w-4xl mx-auto">
                <div class="mt-6 p-4 bg-[#1c243c] border border-dashed border-[#4f8ef7] rounded-lg">
                    <p class="text-sm text-[#7c86a3]">In case of losses the Cashback returns a part of lost funds every month. You can return up to 10% of your losses.</p>
                </div>
                <div class="mt-4 p-3 bg-[#1c243c] border border-dashed border-[#f4534a] text-[#d7dcea] rounded-lg text-left gap-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#f4534a" width="30" height="30"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480L40 480c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24l0 112c0 13.3 10.7 24 24 24s24-10.7 24-24l0-112c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>
                    You don't have active Cashback at the moment.
                </div>
                <div class="mt-4 text-center">
                    <button class="bg-[#1c243c] border border-[#2a3350] hover:bg-[#16c087] text-white py-2 px-4 rounded-lg">Buy it now</button>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-white">Cashback payouts</h3>
                    <div class="border border-[#2a3350] p-4 rounded-lg mt-2 text-center text-[#7c86a3]">No data</div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-white">Activation history</h3>
                    <div class="border border-[#2a3350] p-4 rounded-lg mt-2 text-center text-[#7c86a3]">No data</div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('js')
@endpush
