@extends('layouts.admin.app')

@section('title', 'Referrals')

@section('content')
    <x-page-header title="Referrals" subtitle="Users who joined via a referral link." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>User</th><th>Referred By</th><th>Joined</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td class="font-semibold text-white">{{ $u->first_name }} {{ $u->last_name }}</td>
                        <td>{{ $u->referrer->first_name ?? '—' }} {{ $u->referrer->last_name ?? '' }}</td>
                        <td>{{ $u->created_at->format('d M, Y') }}</td>
                        <td class="text-right"><a href="{{ route('admin.referrals.show', $u->referred_by ?? $u->id) }}" class="text-brand-blue hover:underline">View Upline</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-10 text-slate-400">No referrals yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $users->links() }}</div>
    </x-glass-card>
@endsection
