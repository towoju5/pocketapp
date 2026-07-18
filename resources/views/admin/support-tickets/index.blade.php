@extends('layouts.admin.app')

@section('title', 'Support Tickets')

@section('content')
    <x-page-header title="Support Tickets" subtitle="All user tickets across the platform." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>User</th><th>Subject</th><th>Priority</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</td>
                        <td class="text-white">{{ $ticket->subject }}</td>
                        <td class="capitalize">{{ $ticket->priority }}</td>
                        <td><x-badge :status="$ticket->status" /></td>
                        <td class="text-right"><a href="{{ route('admin.support-tickets.show', $ticket) }}" class="text-brand-blue hover:underline">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No tickets yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $tickets->links() }}</div>
    </x-glass-card>
@endsection
