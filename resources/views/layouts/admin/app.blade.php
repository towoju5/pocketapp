<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') &mdash; {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-brand-deep font-sans text-white antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-full">
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

        @include('layouts.admin.sidebar')

        <div class="flex min-h-screen flex-1 flex-col lg:pl-[290px]">
            @include('layouts.admin.navbar')

            <main class="mx-auto w-full max-w-[1400px] flex-1 px-6 py-8 sm:px-10">
                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-brand-emerald/20 bg-brand-emerald/10 px-4 py-3 text-sm text-brand-emerald">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-xl border border-brand-danger/20 bg-brand-danger/10 px-4 py-3 text-sm text-brand-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
