<header id="page-header"
    class="fixed end-0 start-0 top-0 z-30 flex h-16 flex-none items-center border-b border-slate-200/75 bg-white backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900 lg:start-80">
    <div class="container mx-auto flex justify-between gap-2 px-4 lg:px-8 xl:max-w-7xl">
        <!-- Left Section -->
        <div class="flex items-center gap-2">
            <!-- Brand -->
            <a href="javascript:void(0)"
                class="group flex items-center justify-center text-slate-500 hover:text-slate-700 dark:hover:text-slate-400 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49 48" fill="currentColor"
                    class="w-6 -rotate-6 transition group-active:rotate-0">
                    <path
                        d="M24.5 12.75C24.5 18.963 19.463 24 13.25 24H2V12.75C2 6.537 7.037 1.5 13.25 1.5S24.5 6.537 24.5 12.75ZM24.5 35.25C24.5 29.037 29.537 24 35.75 24H47v11.25c0 6.213-5.037 11.25-11.25 11.25S24.5 41.463 24.5 35.25ZM2 35.25C2 41.463 7.037 46.5 13.25 46.5H24.5V35.25C24.5 29.037 19.463 24 13.25 24S2 29.037 2 35.25ZM47 12.75C47 6.537 41.963 1.5 35.75 1.5H24.5v11.25C24.5 18.963 29.537 24 35.75 24S47 18.963 47 12.75Z" />
                </svg>
            </a>
            <!-- END Brand -->

            <!-- Search -->
            <div>
                <input type="text" id="search" name="search"
                    class="w-full rounded-lg border-slate-200 bg-white text-sm focus:border-slate-400/75 focus:ring focus:ring-slate-300/30 dark:border-slate-700/75 dark:bg-transparent dark:ring-slate-300/15 dark:placeholder:text-slate-400"
                    placeholder="Search.." />
            </div>
            <!-- END Search -->
        </div>
        <!-- END Left Section -->

        <!-- Right Section -->
        <div class="flex items-center gap-2">
            <!-- Dark Mode Toggle -->
            <button type="button"
                class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50"
                x-on:click="darkMode = !darkMode">
                <svg x-show="darkMode" class="hi-mini hi-sun inline-block h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 001.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 15.657a.75.75 0 001.06-1.06l-1.06-1.061a.75.75 0 10-1.06 1.06l1.06 1.06zM5.404 6.464a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.061 1.06l1.06 1.06z" />
                </svg>
                <svg x-cloak x-show="!darkMode" class="hi-mini hi-moon inline-block h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M7.455 2.004a.75.75 0 01.26.77 7 7 0 009.958 7.967.75.75 0 011.067.853A8.5 8.5 0 116.647 1.921a.75.75 0 01.808.083z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <!-- END Dark Mode Toggle -->

            <!-- Notifications -->
            <button type="button"
                class="relative flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50">
                <div
                    class="absolute -end-1.5 -top-2.5 inline-flex h-5 min-w-5 items-center justify-center rounded-lg bg-slate-700 px-1 text-xs font-medium text-white dark:bg-slate-300 dark:font-semibold dark:text-slate-900">
                    3
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="hi-mini hi-bell-alert inline-block size-5">
                    <path
                        d="M4.214 3.227a.75.75 0 0 0-1.156-.955 8.97 8.97 0 0 0-1.856 3.825.75.75 0 0 0 1.466.316 7.47 7.47 0 0 1 1.546-3.186ZM16.942 2.272a.75.75 0 0 0-1.157.955 7.47 7.47 0 0 1 1.547 3.186.75.75 0 0 0 1.466-.316 8.971 8.971 0 0 0-1.856-3.825Z" />
                    <path fill-rule="evenodd"
                        d="M10 2a6 6 0 0 0-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 0 0 .515 1.076 32.91 32.91 0 0 0 3.256.508 3.5 3.5 0 0 0 6.972 0 32.903 32.903 0 0 0 3.256-.508.75.75 0 0 0 .515-1.076A11.448 11.448 0 0 1 16 8a6 6 0 0 0-6-6Zm0 14.5a2 2 0 0 1-1.95-1.557 33.54 33.54 0 0 0 3.9 0A2 2 0 0 1 10 16.5Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <!-- END Notifications -->

            <!-- Toggle Sidebar on Mobile -->
            <button type="button"
                class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50 lg:hidden"
                x-on:click="mobileSidebarOpen = true">
                <svg class="hi-solid hi-menu-alt-1 inline-block size-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <!-- END Toggle Sidebar on Mobile -->
        </div>
        <!-- END Right Section -->
    </div>
</header>
