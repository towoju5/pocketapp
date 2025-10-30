@extends('layouts.app')

@section('content')
    @if(is_mobile())
    <div class="w-full">
        @foreach ($transactions as $item)
        <div class="bg-[#1b1e35] border border-[#292d4a] text-white p-3 rounded-xl shadow-lg space-y-4 m-4">
            <div class="p-2 text-sm">
                <div class="flex items-center gap-5 border-b border-[#292d4a] pb-2">
                    <span>
                        <svg class="svg-icon info-icon" width="15" height="15" viewBox="0 0 12 12" fill="gray" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                        </svg>
                    </span>
                    <span class="font-medium pt-1">{{ $item->uuid }}</span>
                </div>
                <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                    <span class="text-gray-400">Date:</span>
                    <span class="font-medium">{{ $item->created_at}}</span>
                </div>
                <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                    <span class="text-gray-400">Amount:</span>
                    <span class="font-medium text-green-400">{{ formatPrice($item->amount) }}</span>
                </div>
                <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                    <span class="text-gray-400">Description:</span>
                    <span class="font-medium">{{ $item?->meta['description'] ?? null }}</span>
                </div>
                <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                    <span class="text-gray-400">Type:</span>
                    <span class="font-medium">{{ $item->type }}</span>
                </div>
                <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                    <span class="text-gray-400">Status:</span>
                    <span class="font-medium text-red-400">
                        @if($item->confirmed == true)
                            Completed
                        @else
                            Pending
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-5 pt-2">
                    <span class="text-gray-400">Bonus Amount:</span>
                    <span class="font-medium">$0</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="w-full mx-4 container">
        @include('partials.finance-header')
        <div class="w-full bg-gray-900 text-white">
            <div class="p-6">
                <div class="rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Balance History</h2>
                    <div class="flex flex-wrap items-center justify-between mb-4">
                        <div class="flex gap-2">
                            <a href="{{ url()->current() }}?type=deposit"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Deposits</button></a>
                            <a href="{{ url()->current() }}?type=withdraw"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Withdrawal</button></a>
                            <a href="{{ url()->current() }}?type=transfer"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Internal Transfers</button></a>
                            <a href="{{ route('finance.history') }}"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">All Types</button></a>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" id="daterange" name="daterange"
                                class="px-4 py-2 rounded bg-gray-700 border-b border-[#292d4a] text-white" />
                            <button class="px-4 py-2 bg-[#292d4a] text-white rounded hover:bg-[#293145]">Apply</button>
                        </div>
                    </div>
                    <table class="table-auto w-full border-collapse shadow-xl text-sm">
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
                                    <td class="border-b border-gray-800 px-4 py-2">{{ $item->type }}</td>
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
                <div class="pagination">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'right',
                startDate: "{{ now()->format('Y-m-d') }}",
                endDate: "{{ now()->addDays(30)->format('Y-m-d') }}",
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });
        });
    </script>
@endpush
