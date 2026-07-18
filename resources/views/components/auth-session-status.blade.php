@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-brand-emerald/20 bg-brand-emerald/10 px-4 py-2.5 text-sm font-medium text-brand-emerald']) }}>
        {{ $status }}
    </div>
@endif
