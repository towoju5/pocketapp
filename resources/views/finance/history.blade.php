@extends('layouts.app')

@section('content')
<div class="w-full" style="margin: 1rem">
    @include('partials.finance-header')
    <div class="w-full">
        <div class="p-6">
            <div class="panel-heading rounded-t-xl py-2 px-3">
                <div class="panel-title">Balance history</div>
            </div>
            <div class="p-4 bg-gray-900 text-white">
                <div class="flex flex-wrap items-center justify-between mb-4">
                    <div class="flex gap-2">
                        <a href="{{ url()->current() }}?type=deposit"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Deposits</button></a>
                        <a href="{{ url()->current() }}?type=payout"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Withdrawal</button></a>
                        <a href="{{ url()->current() }}?type=deposit"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Internal Transfers</button></a>
                        <a href="{{ route('finance.history') }}"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">All Types</button></a>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" name="daterange"
                            class="px-4 py-2 rounded bg-gray-700 border-b border-[#292d4a] text-white" />
                        <button class="px-4 py-2 bg-[#292d4a] text-white rounded hover:bg-[#293145]">Apply</button>
                    </div>
                </div>
                <table class="table-auto w-full border-collapse border shadow-xl text-sm">
                    <thead>
                        <tr class="py-2">
                            <th class="border-b border-[#292d4a] px-4 py-2">ID</th>
                            <th class="border-b border-[#292d4a] px-4 py-2">Date</th>
                            <th class="border-b border-[#292d4a] px-4 py-2">Amount</th>
                            <th class="border-b border-[#292d4a] px-4 py-2">Method</th>
                            <th class="border-b border-[#292d4a] px-4 py-2">Type</th>
                            <th class="border-b border-[#292d4a] px-4 py-2">Status</th>
                            <th class="border-b border-[#292d4a] px-4 py-2">Bonus Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                        <tr class="py-2 text-center">
                            <td class="border-b border-gray-800 px-4 py-2">60859570</td>
                            <td class="border-b border-gray-800 px-4 py-2">2025-01-08 11:48:33</td>
                            <td class="border-b border-gray-800 px-4 py-2">$10</td>
                            <td class="border-b border-gray-800 px-4 py-2">QafPay</td>
                            <td class="border-b border-gray-800 px-4 py-2">Deposit</td>
                            <td class="border-b border-gray-800 px-4 py-2">
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">Expired</span>
                            </td>
                            <td class="border-b border-gray-800 px-4 py-2">$0</td>
                        </tr>
                        
                        @empty
                        <tr class="flex items-center">
                            <td colspan="6" class="py-2 text-center text-xl lg:text-2xl"> No Record found </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'right',
            startDate: '12/21/2024',
            endDate: '01/09/2025',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>
@endpush
