@extends('layouts.admin.app')

@section('title', 'Signals')

@section('content')
    <x-page-header title="Signals" subtitle="Trade signals broadcast to users.">
        <x-slot:actions>
            <form method="POST" action="{{ route('admin.signals.generate-ai') }}">
                @csrf
                <button type="submit" class="brand-btn-secondary">
                    <i class="fa fa-wand-magic-sparkles"></i> Generate with AI
                </button>
            </form>
            <a href="{{ route('admin.signals.create') }}" class="brand-btn-primary">+ New Signal</a>
        </x-slot:actions>
    </x-page-header>

    @if (!config('services.deepseek.api_key'))
        <div class="mb-6 rounded-xl border border-brand-amber/20 bg-brand-amber/10 px-4 py-3 text-sm text-brand-amber">
            No <code>DEEPSEEK_API_KEY</code> configured — "Generate with AI" currently falls back to a local trend
            heuristic (picks the asset with the largest recent move). Add a DeepSeek API key to <code>.env</code> to
            use real AI-written picks and rationale instead.
        </div>
    @endif

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Asset</th><th>Direction</th><th>Amount</th><th>Duration</th><th>Notes</th><th>Created</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($signals as $signal)
                    <tr>
                        <td class="font-semibold text-white">{{ $signal->asset }}</td>
                        <td class="capitalize {{ $signal->direction === 'up' ? 'text-brand-emerald' : 'text-brand-danger' }}">{{ $signal->direction }}</td>
                        <td>{{ $signal->amount }}</td>
                        <td>{{ $signal->duration }} sec</td>
                        <td class="max-w-xs truncate text-slate-400" title="{{ $signal->notes }}">{{ $signal->notes ?? '—' }}</td>
                        <td>{{ $signal->created_at->diffForHumans() }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('admin.signals.destroy', $signal) }}" onsubmit="return confirm('Remove this signal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-10 text-slate-400">No signals found.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $signals->links() }}</div>
    </x-glass-card>
@endsection
