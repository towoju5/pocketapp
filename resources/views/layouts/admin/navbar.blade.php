<header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-glass-border bg-glass-surface px-6 sm:px-10">
    <button type="button" @click="sidebarOpen = !sidebarOpen" class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-300 hover:bg-white/5 lg:hidden">
        <x-heroicon-o-bars-3 class="h-6 w-6" />
    </button>

    <div class="hidden lg:block">
        <p class="text-sm font-semibold text-slate-400">Welcome back,</p>
        <p class="text-base font-bold text-white">{{ auth()->user()->first_name ?? 'Admin' }}</p>
    </div>

    <div x-data="{ open: false }" class="relative ml-auto">
        <button type="button" @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 rounded-full border border-glass-border bg-white/5 py-1.5 pl-1.5 pr-3 text-sm font-semibold text-white hover:bg-white/10">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-amber/20 text-xs font-bold text-brand-amber">
                {{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1)) }}
            </span>
            {{ auth()->user()->first_name ?? 'Admin' }}
            <x-heroicon-o-chevron-down class="h-3.5 w-3.5 text-slate-400" />
        </button>

        <div x-show="open" x-transition x-cloak
             class="absolute right-0 z-40 mt-2 w-56 rounded-xl border border-glass-border bg-brand-navy p-1.5 shadow-2xl">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white">
                <x-heroicon-o-arrow-left-circle class="h-4 w-4" /> Back to app
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-brand-danger hover:bg-brand-danger/10">
                    <x-heroicon-o-arrow-right-start-on-rectangle class="h-4 w-4" /> Sign out
                </button>
            </form>
        </div>
    </div>
</header>
