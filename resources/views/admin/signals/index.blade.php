@extends('layouts.admin.app')

@section('title', 'Signals')

@section('content')
    <x-page-header title="Signals" subtitle="Trade signals broadcast to users.">
        <x-slot:actions>
            <a href="{{ route('admin.signals.create') }}" class="brand-btn-primary">+ New Signal</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Asset</th><th>Direction</th><th>Amount</th><th>Duration</th><th>Created</th></tr>
            </thead>
            <tbody>
                @forelse ($signals as $signal)
                    <tr>
                        <td class="font-semibold text-white">{{ $signal->asset }}</td>
                        <td class="capitalize">{{ $signal->direction }}</td>
                        <td>{{ $signal->amount }}</td>
                        <td>{{ $signal->duration }} sec</td>
                        <td>{{ $signal->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No signals found.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
