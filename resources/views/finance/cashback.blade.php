@extends('layouts.app')

@section('content')
    <div class="w-full m-8">
        @include('partials.finance-header')
        <div class="grid lg:grid-cols-3 gap-5 mt-10">
            <div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
                <div class="text-center">
                    <h2 class="text-xl font-semibold">Your cashback</h2>
                    <p class="text-4xl font-bold text-blue-400">0%</p>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-6 text-center">
                    <div class="border border-gray-700 p-4 rounded-lg">Last payout<br>-</div>
                    <div class="border border-gray-700 p-4 rounded-lg">Total cashback<br>-</div>
                    <div class="border border-gray-700 p-4 rounded-lg">Max. payout<br>-</div>
                    <div class="border border-gray-700 p-4 rounded-lg">Next payout<br>-</div>
                    <div class="border border-gray-700 p-4 rounded-lg">Date of activation<br>-</div>
                    <div class="border border-gray-700 p-4 rounded-lg">Valid until<br>-</div>
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
                <div class="mt-6 p-4 bg-gray-800 rounded-lg">
                    <p class="text-sm text-gray-400">In case of losses the Cashback returns a part of lost funds every month. You can return up to 10% of your losses.</p>
                    <div class="mt-4 p-3 bg-red-500 text-white rounded-lg text-center">
                        You don’t have active Cashback at the moment.
                    </div>
                    <div class="mt-4 text-center">
                        <button class="bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded">Buy it now</button>
                    </div>
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
