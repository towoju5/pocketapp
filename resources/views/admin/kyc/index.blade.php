@extends('layouts.admin.app')

@section('title', 'KYC Requests')

@section('content')
    <x-page-header title="KYC Requests" subtitle="Review submitted identity documents." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Document Type</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $kyc)
                    <tr>
                        <td>{{ $kyc->user->first_name }} {{ $kyc->user->last_name }}</td>
                        <td class="capitalize">{{ str_replace('_', ' ', $kyc->document_type) }}</td>
                        <td>{{ $kyc->submitted_at?->format('d M, Y H:i') }}</td>
                        <td><x-badge :status="$kyc->status" /></td>
                        <td class="text-right">
                            <a href="{{ route('admin.kyc.show', $kyc) }}" class="text-brand-blue hover:underline">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No KYC submissions yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $submissions->links() }}</div>
    </x-glass-card>
@endsection
