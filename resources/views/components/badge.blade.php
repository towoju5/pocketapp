@props(['status' => 'default'])

@php
    $map = [
        // generic
        'default' => 'bg-white/5 text-slate-300 ring-white/10',
        'success' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'warning' => 'bg-brand-amber/10 text-brand-amber ring-brand-amber/20',
        'danger' => 'bg-brand-danger/10 text-brand-danger ring-brand-danger/20',
        'info' => 'bg-brand-blue/10 text-brand-blue ring-brand-blue/20',

        // common domain statuses map onto the palette above
        'active' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'completed' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'verified' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'approved' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'matured' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'released' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'paid' => 'bg-brand-emerald/10 text-brand-emerald ring-brand-emerald/20',
        'pending' => 'bg-brand-amber/10 text-brand-amber ring-brand-amber/20',
        'pending_payment' => 'bg-brand-amber/10 text-brand-amber ring-brand-amber/20',
        'open' => 'bg-brand-blue/10 text-brand-blue ring-brand-blue/20',
        'unverified' => 'bg-white/5 text-slate-300 ring-white/10',
        'rejected' => 'bg-brand-danger/10 text-brand-danger ring-brand-danger/20',
        'banned' => 'bg-brand-danger/10 text-brand-danger ring-brand-danger/20',
        'disputed' => 'bg-brand-danger/10 text-brand-danger ring-brand-danger/20',
        'cancelled' => 'bg-white/5 text-slate-400 ring-white/10',
        'closed' => 'bg-white/5 text-slate-400 ring-white/10',
    ];
    $classes = $map[strtolower($status ?? 'default')] ?? $map['default'];
@endphp

<span {{ $attributes->class(['inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset', $classes]) }}>
    {{ $slot->isEmpty() ? str_replace('_', ' ', $status) : $slot }}
</span>
