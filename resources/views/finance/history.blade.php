@extends('layouts.desktop.trading')

@section('title', 'Transaction History')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @include('partials.finance-header')

        <div class="flex items-center justify-between mt-6 mb-4">
            <h1 class="text-xl font-bold text-white">Transaction History</h1>
            <div class="flex items-center bg-[#1c243c] border border-[#2a3350] rounded-lg p-1 text-xs font-bold">
                <a href="{{ route('finance.history', array_merge(request()->except('page'), ['mode' => 'real'])) }}"
                    class="px-3 py-1.5 rounded-md {{ $mode === 'real' ? 'bg-[#4f8ef7] text-white' : 'text-[#7c86a3]' }}">Real</a>
                <a href="{{ route('finance.history', array_merge(request()->except('page'), ['mode' => 'demo'])) }}"
                    class="px-3 py-1.5 rounded-md {{ $mode === 'demo' ? 'bg-[#4f8ef7] text-white' : 'text-[#7c86a3]' }}">Demo</a>
            </div>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl">
            <div class="p-4 border-b border-[#2a3350] flex flex-wrap items-center justify-between gap-3">
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ url()->current() }}?type=deposit&mode={{ $mode }}"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white text-xs font-semibold rounded-lg">Deposits</button></a>
                    <a href="{{ url()->current() }}?type=withdraw&mode={{ $mode }}"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white text-xs font-semibold rounded-lg">Withdrawal</button></a>
                    <a href="{{ url()->current() }}?type=transfer&mode={{ $mode }}"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white text-xs font-semibold rounded-lg">Internal Transfers</button></a>
                    <a href="{{ route('finance.history', ['mode' => $mode]) }}"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white text-xs font-semibold rounded-lg">All Types</button></a>
                </div>
                <form method="GET" action="{{ route('finance.history') }}" class="flex items-center gap-2">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="mode" value="{{ $mode }}">
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="px-3 py-2 rounded-lg bg-[#1c243c] border border-[#2a3350] text-white text-xs" />
                    <span class="text-[#7c86a3] text-xs">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="px-3 py-2 rounded-lg bg-[#1c243c] border border-[#2a3350] text-white text-xs" />
                    <button type="submit" class="px-4 py-2 bg-[#4f8ef7] text-white text-xs font-semibold rounded-lg">Apply</button>
                </form>
            </div>

            <div class="p-4 border-b border-[#2a3350]">
                <div class="relative max-w-sm">
                    <i class="fa fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#7c86a3] text-xs"></i>
                    <input type="text" id="historySearch" placeholder="Search by transaction ID, type, or amount..."
                        class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg pl-9 pr-3 py-2.5 text-sm text-white outline-none focus:border-[#4f8ef7]">
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="responsive-table">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th class="border-b border-[#2a3350] px-4 py-3 text-[#7c86a3] text-xs uppercase">Transaction ID</th>
                            <th class="border-b border-[#2a3350] px-4 py-3 text-[#7c86a3] text-xs uppercase">Date</th>
                            <th class="border-b border-[#2a3350] px-4 py-3 text-[#7c86a3] text-xs uppercase">Amount</th>
                            <th class="border-b border-[#2a3350] px-4 py-3 text-[#7c86a3] text-xs uppercase">Type</th>
                            <th class="border-b border-[#2a3350] px-4 py-3 text-[#7c86a3] text-xs uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="text-[#d7dcea]">
                        @include('finance.partials.history-rows')
                    </tbody>
                </table>
                </div>
            </div>
            <div id="historyPagination" class="p-4">
                {{ $transactions->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    (function () {
        const input = document.getElementById('historySearch');
        const tbody = document.getElementById('historyTableBody');
        const pagination = document.getElementById('historyPagination');
        let debounceTimer = null;

        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                params.set('q', input.value.trim());
                params.delete('page');

                fetch(`{{ route('finance.history') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        tbody.innerHTML = data.rows;
                        pagination.innerHTML = data.pagination;
                    })
                    .catch(() => {});
            }, 300);
        });
    })();
</script>
@endpush
@endsection
