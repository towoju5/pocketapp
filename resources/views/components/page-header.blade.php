@props(['title', 'subtitle' => null])

<div {{ $attributes->class(['mb-8 flex flex-wrap items-start justify-between gap-4']) }}>
    <div>
        <h1 class="text-2xl font-extrabold text-white">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-400">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex items-center gap-3">{{ $actions }}</div>
    @endisset
</div>
