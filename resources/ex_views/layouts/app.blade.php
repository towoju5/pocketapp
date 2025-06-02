<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    @include('components.preloader')
    <div x-data="{ darkMode: false, mobileSidebarOpen: false, activeTab: 'Analytics' }" x-bind:class="{ 'dark': darkMode }">
        <!-- Page Container -->
        <div id="page-container"
            class="relative mx-auto flex min-h-screen min-w-[320px] flex-col bg-white dark:bg-slate-900 dark:text-slate-100 lg:ms-80">
            <!-- Page Sidebar -->
            @include('layouts.header')
            <!-- Page Sidebar -->

            <!-- Page Header -->
            @include('layouts.sidebar')
            <!-- END Page Header -->

            <!-- Page Content -->
            <main id="page-content" class="grow bg-slate-100 pt-16 dark:bg-slate-950">
                @yield('content')
            </main>
            <!-- END Page Content -->

            <!-- Page Footer -->
            @include('layouts.footer')
            <!-- END Page Footer -->
        </div>
        <!-- END Page Container -->

        <script src="{{ asset('assets/js/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        @stack('js')
    </div>
</body>

</html>
