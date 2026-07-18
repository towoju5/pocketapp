@props(['label', 'value', 'icon' => null, 'accent' => 'blue', 'hint' => null])

@php
    $accents = [
        'blue' => 'bg-brand-blue/10 text-brand-blue',
        'emerald' => 'bg-brand-emerald/10 text-brand-emerald',
        'amber' => 'bg-brand-amber/10 text-brand-amber',
        'danger' => 'bg-brand-danger/10 text-brand-danger',
    ];
    $accentClass = $accents[$accent] ?? $accents['blue'];
@endphp

<div {{ $attributes->class(['glass-panel p-5']) }}>
    <div class="flex items-start justify-between">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
        @if ($icon)
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $accentClass }}">
                <x-dynamic-component :component="$icon" class="h-5 w-5" />
            </span>
        @endif
    </div>
    <p class="mt-3 font-mono text-2xl font-bold text-white">{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>
    @endif
</div>
