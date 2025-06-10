<nav id="page-sidebar"
    class="fixed bottom-0 start-0 top-0 z-40 flex h-full w-full border-slate-200/75 bg-white transition-transform duration-500 ease-out dark:border-slate-700/60 dark:bg-slate-900 sm:w-80 lg:translate-x-0 lg:shadow-none ltr:border-r ltr:lg:translate-x-0 rtl:border-l rtl:lg:translate-x-0"
    x-bind:class="{
        'ltr:-translate-x-full rtl:translate-x-full': !mobileSidebarOpen,
        'translate-x-0 shadow-lg': mobileSidebarOpen,
    }"
    aria-label="Main Sidebar Navigation" x-cloak>
    <!-- Mini Sidebar -->
    <div
        class="w-16 flex-none border-slate-200/75 bg-slate-50 dark:border-slate-700/60 dark:bg-slate-900/75 ltr:border-r rtl:border-l">
        <!-- Brand -->
        <a href="javascript:void(0)"
            class="group flex h-16 items-center justify-center border-b border-slate-200/50 text-slate-500 hover:bg-slate-100 active:bg-slate-50 dark:border-slate-700/60 dark:hover:bg-slate-800 dark:hover:text-slate-400 dark:active:bg-slate-800/50">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49 48" fill="currentColor"
                class="w-6 -rotate-6 transition group-active:rotate-0">
                <path
                    d="M24.5 12.75C24.5 18.963 19.463 24 13.25 24H2V12.75C2 6.537 7.037 1.5 13.25 1.5S24.5 6.537 24.5 12.75ZM24.5 35.25C24.5 29.037 29.537 24 35.75 24H47v11.25c0 6.213-5.037 11.25-11.25 11.25S24.5 41.463 24.5 35.25ZM2 35.25C2 41.463 7.037 46.5 13.25 46.5H24.5V35.25C24.5 29.037 19.463 24 13.25 24S2 29.037 2 35.25ZM47 12.75C47 6.537 41.963 1.5 35.75 1.5H24.5v11.25C24.5 18.963 29.537 24 35.75 24S47 18.963 47 12.75Z" />
            </svg>
        </a>
        <!-- END Brand -->

        <!-- App Navigation -->
        <nav class="relative flex flex-col items-center gap-3 py-6"
            x-on:keydown.right.prevent.stop="$focus.wrap().next()"
            x-on:keydown.left.prevent.stop="$focus.wrap().previous()"
            x-on:keydown.down.prevent.stop="$focus.wrap().next()"
            x-on:keydown.up.prevent.stop="$focus.wrap().previous()" x-on:keydown.home.prevent.stop="$focus.first()"
            x-on:keydown.end.prevent.stop="$focus.last()">
            <button x-on:click="activeTab = 'Analytics'" x-on:focus="activeTab = 'Analytics'" id="analytics-tab"
                role="tab" aria-controls="analytics-tab-pane"
                x-bind:aria-selected="activeTab === 'Analytics' ? 'true' : 'false'"
                x-bind:tabindex="activeTab === 'Analytics' ? '0' : '-1'" type="button"
                class="flex size-9 items-center justify-center rounded-xl bg-rose-700 text-white hover:bg-rose-600 active:bg-rose-700"
                x-bind:class="{
                    'ring-4 ring-rose-400/50 dark:ring-rose-600/50': activeTab === 'Analytics'
                }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="hi-micro hi-chart-bar inline-block size-4">
                    <path
                        d="M12 2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-1ZM6.5 6a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V6ZM2 9a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V9Z" />
                </svg>
            </button>
            <button x-on:click="activeTab = 'Projects'" x-on:focus="activeTab = 'Projects'" id="projects-tab"
                role="tab" aria-controls="projects-tab-pane"
                x-bind:aria-selected="activeTab === 'Projects' ? 'true' : 'false'"
                x-bind:tabindex="activeTab === 'Projects' ? '0' : '-1'" type="button"
                class="flex size-9 items-center justify-center rounded-xl bg-indigo-800 text-white hover:bg-indigo-700 active:bg-indigo-800"
                x-bind:class="{
                    'ring-4 ring-indigo-400/50 dark:ring-indigo-600/50': activeTab === 'Projects'
                }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="hi-micro hi-briefcase inline-block size-4">
                    <path fill-rule="evenodd"
                        d="M11 4V3a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v1H4a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1ZM9 2.5H7a.5.5 0 0 0-.5.5v1h3V3a.5.5 0 0 0-.5-.5ZM9 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"
                        clip-rule="evenodd" />
                    <path
                        d="M3 11.83V12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-.17c-.313.11-.65.17-1 .17H4c-.35 0-.687-.06-1-.17Z" />
                </svg>
            </button>
            <button x-on:click="activeTab = 'Settings'" x-on:focus="activeTab = 'Settings'" id="settings-tab"
                role="tab" aria-controls="settings-tab-pane"
                x-bind:aria-selected="activeTab === 'Settings' ? 'true' : 'false'"
                x-bind:tabindex="activeTab === 'Settings' ? '0' : '-1'" type="button"
                class="flex size-9 items-center justify-center rounded-xl bg-slate-700 text-white hover:bg-slate-600 active:bg-slate-700"
                x-bind:class="{
                    'ring-4 ring-slate-400/50 dark:ring-slate-600/50': activeTab === 'Settings'
                }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="hi-micro hi-cog-8-tooth inline-block size-4">
                    <path fill-rule="evenodd"
                        d="M6.955 1.45A.5.5 0 0 1 7.452 1h1.096a.5.5 0 0 1 .497.45l.17 1.699c.484.12.94.312 1.356.562l1.321-1.081a.5.5 0 0 1 .67.033l.774.775a.5.5 0 0 1 .034.67l-1.08 1.32c.25.417.44.873.561 1.357l1.699.17a.5.5 0 0 1 .45.497v1.096a.5.5 0 0 1-.45.497l-1.699.17c-.12.484-.312.94-.562 1.356l1.082 1.322a.5.5 0 0 1-.034.67l-.774.774a.5.5 0 0 1-.67.033l-1.322-1.08c-.416.25-.872.44-1.356.561l-.17 1.699a.5.5 0 0 1-.497.45H7.452a.5.5 0 0 1-.497-.45l-.17-1.699a4.973 4.973 0 0 1-1.356-.562L4.108 13.37a.5.5 0 0 1-.67-.033l-.774-.775a.5.5 0 0 1-.034-.67l1.08-1.32a4.971 4.971 0 0 1-.561-1.357l-1.699-.17A.5.5 0 0 1 1 8.548V7.452a.5.5 0 0 1 .45-.497l1.699-.17c.12-.484.312-.94.562-1.356L2.629 4.107a.5.5 0 0 1 .034-.67l.774-.774a.5.5 0 0 1 .67-.033L5.43 3.71a4.97 4.97 0 0 1 1.356-.561l.17-1.699ZM6 8c0 .538.212 1.026.558 1.385l.057.057a2 2 0 0 0 2.828-2.828l-.058-.056A2 2 0 0 0 6 8Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <button type="button"
                class="flex size-9 items-center justify-center rounded-xl border-2 border-dashed border-slate-200 text-slate-500 hover:border-slate-300 active:border-slate-200 dark:border-slate-700 dark:text-slate-400 dark:hover:border-slate-600 dark:active:border-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="hi-micro hi-plus inline-block size-4">
                    <path
                        d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                </svg>
            </button>
        </nav>
        <!-- END App Navigation -->
    </div>
    <!-- END Mini Sidebar -->

    <!-- Sidebar Content -->
    <div class="grow overflow-auto">
        <!-- Sidebar Header -->
        <div class="flex h-16 items-center justify-between border-b border-slate-100 px-6 dark:border-slate-700/60">
            <!-- Heading -->
            <div class="grow">
                <h1 x-text="activeTab" class="mb-0.5 text-xl font-bold"></h1>
            </div>
            <!-- END Heading -->

            <!-- Close Sidebar on Mobile -->
            <div class="lg:hidden">
                <button type="button"
                    class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50"
                    x-on:click="mobileSidebarOpen = false">
                    <svg class="hi-solid hi-x inline-block size-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <!-- END Close Sidebar on Mobile -->
        </div>
        <!-- END Sidebar Header -->

        <!-- Main Navigation -->
        <div class="grow overflow-auto p-6">
            <!-- Analytics -->
            <div x-cloak x-show="activeTab === 'Analytics'" class="-mx-2.5 flex flex-col gap-2" id="analytics-tab-pane"
                tab="tabpanel" aria-labelledby="analytics-tab" tabindex="0">
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg bg-slate-100 px-2.5 py-1.5 text-sm font-medium text-slate-950 dark:bg-slate-800/75 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cube inline-block size-5 text-slate-600">
                        <path
                            d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 4v8.25l6.862-3.786A.75.75 0 0 0 18 14.25V6.443ZM9.25 18.693v-8.25l-7.25-4v7.807a.75.75 0 0 0 .388.657l6.862 3.786Z" />
                    </svg>
                    <span class="grow">Dashboard</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-globe-alt inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M16.555 5.412a8.028 8.028 0 0 0-3.503-2.81 14.899 14.899 0 0 1 1.663 4.472 8.547 8.547 0 0 0 1.84-1.662ZM13.326 7.825a13.43 13.43 0 0 0-2.413-5.773 8.087 8.087 0 0 0-1.826 0 13.43 13.43 0 0 0-2.413 5.773A8.473 8.473 0 0 0 10 8.5c1.18 0 2.304-.24 3.326-.675ZM6.514 9.376A9.98 9.98 0 0 0 10 10c1.226 0 2.4-.22 3.486-.624a13.54 13.54 0 0 1-.351 3.759A13.54 13.54 0 0 1 10 13.5c-1.079 0-2.128-.127-3.134-.366a13.538 13.538 0 0 1-.352-3.758ZM5.285 7.074a14.9 14.9 0 0 1 1.663-4.471 8.028 8.028 0 0 0-3.503 2.81c.529.638 1.149 1.199 1.84 1.66ZM17.334 6.798a7.973 7.973 0 0 1 .614 4.115 13.47 13.47 0 0 1-3.178 1.72 15.093 15.093 0 0 0 .174-3.939 10.043 10.043 0 0 0 2.39-1.896ZM2.666 6.798a10.042 10.042 0 0 0 2.39 1.896 15.196 15.196 0 0 0 .174 3.94 13.472 13.472 0 0 1-3.178-1.72 7.973 7.973 0 0 1 .615-4.115ZM10 15c.898 0 1.778-.079 2.633-.23a13.473 13.473 0 0 1-1.72 3.178 8.099 8.099 0 0 1-1.826 0 13.47 13.47 0 0 1-1.72-3.178c.855.151 1.735.23 2.633.23ZM14.357 14.357a14.912 14.912 0 0 1-1.305 3.04 8.027 8.027 0 0 0 4.345-4.345c-.953.542-1.971.981-3.04 1.305ZM6.948 17.397a8.027 8.027 0 0 1-4.345-4.345c.953.542 1.971.981 3.04 1.305a14.912 14.912 0 0 0 1.305 3.04Z" />
                    </svg>
                    <span class="grow">Domains</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-arrows-right-left inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path fill-rule="evenodd"
                            d="M13.2 2.24a.75.75 0 0 0 .04 1.06l2.1 1.95H6.75a.75.75 0 0 0 0 1.5h8.59l-2.1 1.95a.75.75 0 1 0 1.02 1.1l3.5-3.25a.75.75 0 0 0 0-1.1l-3.5-3.25a.75.75 0 0 0-1.06.04Zm-6.4 8a.75.75 0 0 0-1.06-.04l-3.5 3.25a.75.75 0 0 0 0 1.1l3.5 3.25a.75.75 0 1 0 1.02-1.1l-2.1-1.95h8.59a.75.75 0 0 0 0-1.5H4.66l2.1-1.95a.75.75 0 0 0 .04-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Sessions</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-chart-bar-square inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path fill-rule="evenodd"
                            d="M4.25 2A2.25 2.25 0 0 0 2 4.25v11.5A2.25 2.25 0 0 0 4.25 18h11.5A2.25 2.25 0 0 0 18 15.75V4.25A2.25 2.25 0 0 0 15.75 2H4.25ZM15 5.75a.75.75 0 0 0-1.5 0v8.5a.75.75 0 0 0 1.5 0v-8.5Zm-8.5 6a.75.75 0 0 0-1.5 0v2.5a.75.75 0 0 0 1.5 0v-2.5ZM8.584 9a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5a.75.75 0 0 1 .75-.75Zm3.58-1.25a.75.75 0 0 0-1.5 0v6.5a.75.75 0 0 0 1.5 0v-6.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Sales</span>
                </a>
                <h4 class="ps-3 pt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Quick Links
                </h4>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cursor-arrow-rays inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M10 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 1ZM5.05 3.05a.75.75 0 0 1 1.06 0l1.062 1.06A.75.75 0 1 1 6.11 5.173L5.05 4.11a.75.75 0 0 1 0-1.06ZM14.95 3.05a.75.75 0 0 1 0 1.06l-1.06 1.062a.75.75 0 0 1-1.062-1.061l1.061-1.06a.75.75 0 0 1 1.06 0ZM3 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 3 8ZM14 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 14 8ZM7.172 10.828a.75.75 0 0 1 0 1.061L6.11 12.95a.75.75 0 0 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM10.766 7.51a.75.75 0 0 0-1.37.365l-.492 6.861a.75.75 0 0 0 1.204.65l1.043-.799.985 3.678a.75.75 0 0 0 1.45-.388l-.978-3.646 1.292.204a.75.75 0 0 0 .74-1.16l-3.874-5.764Z" />
                    </svg>
                    <span class="grow">example1.com</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cursor-arrow-rays inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M10 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 1ZM5.05 3.05a.75.75 0 0 1 1.06 0l1.062 1.06A.75.75 0 1 1 6.11 5.173L5.05 4.11a.75.75 0 0 1 0-1.06ZM14.95 3.05a.75.75 0 0 1 0 1.06l-1.06 1.062a.75.75 0 0 1-1.062-1.061l1.061-1.06a.75.75 0 0 1 1.06 0ZM3 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 3 8ZM14 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 14 8ZM7.172 10.828a.75.75 0 0 1 0 1.061L6.11 12.95a.75.75 0 0 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM10.766 7.51a.75.75 0 0 0-1.37.365l-.492 6.861a.75.75 0 0 0 1.204.65l1.043-.799.985 3.678a.75.75 0 0 0 1.45-.388l-.978-3.646 1.292.204a.75.75 0 0 0 .74-1.16l-3.874-5.764Z" />
                    </svg>
                    <span class="grow">example2.com</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cursor-arrow-rays inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M10 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 1ZM5.05 3.05a.75.75 0 0 1 1.06 0l1.062 1.06A.75.75 0 1 1 6.11 5.173L5.05 4.11a.75.75 0 0 1 0-1.06ZM14.95 3.05a.75.75 0 0 1 0 1.06l-1.06 1.062a.75.75 0 0 1-1.062-1.061l1.061-1.06a.75.75 0 0 1 1.06 0ZM3 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 3 8ZM14 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 14 8ZM7.172 10.828a.75.75 0 0 1 0 1.061L6.11 12.95a.75.75 0 0 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM10.766 7.51a.75.75 0 0 0-1.37.365l-.492 6.861a.75.75 0 0 0 1.204.65l1.043-.799.985 3.678a.75.75 0 0 0 1.45-.388l-.978-3.646 1.292.204a.75.75 0 0 0 .74-1.16l-3.874-5.764Z" />
                    </svg>
                    <span class="grow">example3.com</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cursor-arrow-rays inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M10 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 1ZM5.05 3.05a.75.75 0 0 1 1.06 0l1.062 1.06A.75.75 0 1 1 6.11 5.173L5.05 4.11a.75.75 0 0 1 0-1.06ZM14.95 3.05a.75.75 0 0 1 0 1.06l-1.06 1.062a.75.75 0 0 1-1.062-1.061l1.061-1.06a.75.75 0 0 1 1.06 0ZM3 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 3 8ZM14 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 14 8ZM7.172 10.828a.75.75 0 0 1 0 1.061L6.11 12.95a.75.75 0 0 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM10.766 7.51a.75.75 0 0 0-1.37.365l-.492 6.861a.75.75 0 0 0 1.204.65l1.043-.799.985 3.678a.75.75 0 0 0 1.45-.388l-.978-3.646 1.292.204a.75.75 0 0 0 .74-1.16l-3.874-5.764Z" />
                    </svg>
                    <span class="grow">example4.com</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cursor-arrow-rays inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M10 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 1ZM5.05 3.05a.75.75 0 0 1 1.06 0l1.062 1.06A.75.75 0 1 1 6.11 5.173L5.05 4.11a.75.75 0 0 1 0-1.06ZM14.95 3.05a.75.75 0 0 1 0 1.06l-1.06 1.062a.75.75 0 0 1-1.062-1.061l1.061-1.06a.75.75 0 0 1 1.06 0ZM3 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 3 8ZM14 8a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 14 8ZM7.172 10.828a.75.75 0 0 1 0 1.061L6.11 12.95a.75.75 0 0 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM10.766 7.51a.75.75 0 0 0-1.37.365l-.492 6.861a.75.75 0 0 0 1.204.65l1.043-.799.985 3.678a.75.75 0 0 0 1.45-.388l-.978-3.646 1.292.204a.75.75 0 0 0 .74-1.16l-3.874-5.764Z" />
                    </svg>
                    <span class="grow">example5.com</span>
                </a>
            </div>
            <!-- END Analytics -->

            <!-- Projects -->
            <div x-cloak x-show="activeTab === 'Projects'" class="-mx-2.5 flex flex-col gap-2" id="projects-tab-pane"
                tab="tabpanel" aria-labelledby="projects-tab" tabindex="0">
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-squares-plus inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path
                            d="M2 4.25A2.25 2.25 0 014.25 2h2.5A2.25 2.25 0 019 4.25v2.5A2.25 2.25 0 016.75 9h-2.5A2.25 2.25 0 012 6.75v-2.5zM2 13.25A2.25 2.25 0 014.25 11h2.5A2.25 2.25 0 019 13.25v2.5A2.25 2.25 0 016.75 18h-2.5A2.25 2.25 0 012 15.75v-2.5zM11 4.25A2.25 2.25 0 0113.25 2h2.5A2.25 2.25 0 0118 4.25v2.5A2.25 2.25 0 0115.75 9h-2.5A2.25 2.25 0 0111 6.75v-2.5zM15.25 11.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z" />
                    </svg>
                    <span class="grow">Kanban</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-bell-alert inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path
                            d="M4.214 3.227a.75.75 0 00-1.156-.956 8.97 8.97 0 00-1.856 3.826.75.75 0 001.466.316 7.47 7.47 0 011.546-3.186zM16.942 2.271a.75.75 0 00-1.157.956 7.47 7.47 0 011.547 3.186.75.75 0 001.466-.316 8.971 8.971 0 00-1.856-3.826z" />
                        <path fill-rule="evenodd"
                            d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 00.515 1.076 32.94 32.94 0 003.256.508 3.5 3.5 0 006.972 0 32.933 32.933 0 003.256-.508.75.75 0 00.515-1.076A11.448 11.448 0 0116 8a6 6 0 00-6-6zm0 14.5a2 2 0 01-1.95-1.557 33.54 33.54 0 003.9 0A2 2 0 0110 16.5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Notifications</span>
                    <span class="text-slate-500 dark:text-slate-400">&bull;</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-calendar-days inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path
                            d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z" />
                        <path fill-rule="evenodd"
                            d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Calendar</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-archive-box inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path d="M2 3a1 1 0 00-1 1v1a1 1 0 001 1h16a1 1 0 001-1V4a1 1 0 00-1-1H2z" />
                        <path fill-rule="evenodd"
                            d="M2 7.5h16l-.811 7.71a2 2 0 01-1.99 1.79H4.802a2 2 0 01-1.99-1.79L2 7.5zM7 11a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Files</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-list-bullet inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M6 4.75A.75.75 0 016.75 4h10.5a.75.75 0 010 1.5H6.75A.75.75 0 016 4.75zM6 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H6.75A.75.75 0 016 10zm0 5.25a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H6.75a.75.75 0 01-.75-.75zM1.99 4.75a1 1 0 011-1H3a1 1 0 011 1v.01a1 1 0 01-1 1h-.01a1 1 0 01-1-1v-.01zM1.99 15.25a1 1 0 011-1H3a1 1 0 011 1v.01a1 1 0 01-1 1h-.01a1 1 0 01-1-1v-.01zM1.99 10a1 1 0 011-1H3a1 1 0 011 1v.01a1 1 0 01-1 1h-.01a1 1 0 01-1-1V10z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Tasks</span>
                </a>
                <h4 class="ps-3 pt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Labels
                </h4>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-300 dark:hover:bg-emerald-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-emerald-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">development</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-blue-50 hover:text-blue-600 dark:text-slate-300 dark:hover:bg-blue-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">design</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-sky-50 hover:text-sky-600 dark:text-slate-300 dark:hover:bg-sky-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-sky-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">marketing</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-orange-50 hover:text-orange-600 dark:text-slate-300 dark:hover:bg-orange-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">personal</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-rose-50 hover:text-rose-600 dark:text-slate-300 dark:hover:bg-rose-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-rose-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">brand</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-purple-50 hover:text-purple-600 dark:text-slate-300 dark:hover:bg-purple-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-purple-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">business</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1 text-sm font-medium text-slate-800 hover:bg-pink-50 hover:text-pink-600 dark:text-slate-300 dark:hover:bg-pink-800/75 dark:active:text-white">
                    <svg class="hi-mini hi-hashtag inline-block size-5 text-slate-300 group-hover:text-pink-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.493 2.853a.75.75 0 00-1.486-.205L7.545 6H4.198a.75.75 0 000 1.5h3.14l-.69 5H3.302a.75.75 0 000 1.5h3.14l-.435 3.148a.75.75 0 001.486.205L7.955 14h2.986l-.434 3.148a.75.75 0 001.486.205L12.456 14h3.346a.75.75 0 000-1.5h-3.14l.69-5h3.346a.75.75 0 000-1.5h-3.14l.435-3.147a.75.75 0 00-1.486-.205L12.045 6H9.059l.434-3.147zM8.852 7.5l-.69 5h2.986l.69-5H8.852z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">accounting</span>
                </a>
            </div>
            <!-- END Projects -->

            <!-- Settings -->
            <div x-cloak x-show="activeTab === 'Settings'" class="-mx-2.5 flex flex-col gap-2" id="settings-tab-pane"
                tab="tabpanel" aria-labelledby="settings-tab" tabindex="0">
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-user-circle inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Profile</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-shield-check inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path fill-rule="evenodd"
                            d="M9.661 2.237a.531.531 0 0 1 .678 0 11.947 11.947 0 0 0 7.078 2.749.5.5 0 0 1 .479.425c.069.52.104 1.05.104 1.59 0 5.162-3.26 9.563-7.834 11.256a.48.48 0 0 1-.332 0C5.26 16.564 2 12.163 2 7c0-.538.035-1.069.104-1.589a.5.5 0 0 1 .48-.425 11.947 11.947 0 0 0 7.077-2.75Zm4.196 5.954a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Privacy</span>
                </a>
                <a href="javascript:void(0)"
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-800 hover:bg-slate-100 active:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800/75 dark:active:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-cog inline-block size-5 text-slate-300 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300">
                        <path
                            d="M13.024 9.25c.47 0 .827-.433.637-.863a4 4 0 0 0-4.094-2.364c-.468.05-.665.576-.43.984l1.08 1.868a.75.75 0 0 0 .649.375h2.158ZM7.84 7.758c-.236-.408-.79-.5-1.068-.12A3.982 3.982 0 0 0 6 10c0 .884.287 1.7.772 2.363.278.38.832.287 1.068-.12l1.078-1.868a.75.75 0 0 0 0-.75L7.839 7.758ZM9.138 12.993c-.235.408-.039.934.43.984a4 4 0 0 0 4.094-2.364c.19-.43-.168-.863-.638-.863h-2.158a.75.75 0 0 0-.65.375l-1.078 1.868Z" />
                        <path fill-rule="evenodd"
                            d="m14.13 4.347.644-1.117a.75.75 0 0 0-1.299-.75l-.644 1.116a6.954 6.954 0 0 0-2.081-.556V1.75a.75.75 0 0 0-1.5 0v1.29a6.954 6.954 0 0 0-2.081.556L6.525 2.48a.75.75 0 1 0-1.3.75l.645 1.117A7.04 7.04 0 0 0 4.347 5.87L3.23 5.225a.75.75 0 1 0-.75 1.3l1.116.644A6.954 6.954 0 0 0 3.04 9.25H1.75a.75.75 0 0 0 0 1.5h1.29c.078.733.27 1.433.556 2.081l-1.116.645a.75.75 0 1 0 .75 1.298l1.117-.644a7.04 7.04 0 0 0 1.523 1.523l-.645 1.117a.75.75 0 1 0 1.3.75l.644-1.116a6.954 6.954 0 0 0 2.081.556v1.29a.75.75 0 0 0 1.5 0v-1.29a6.954 6.954 0 0 0 2.081-.556l.645 1.116a.75.75 0 0 0 1.299-.75l-.645-1.117a7.042 7.042 0 0 0 1.523-1.523l1.117.644a.75.75 0 0 0 .75-1.298l-1.116-.645a6.954 6.954 0 0 0 .556-2.081h1.29a.75.75 0 0 0 0-1.5h-1.29a6.954 6.954 0 0 0-.556-2.081l1.116-.644a.75.75 0 0 0-.75-1.3l-1.117.645a7.04 7.04 0 0 0-1.524-1.523ZM10 4.5a5.475 5.475 0 0 0-2.781.754A5.527 5.527 0 0 0 5.22 7.277 5.475 5.475 0 0 0 4.5 10a5.475 5.475 0 0 0 .752 2.777 5.527 5.527 0 0 0 2.028 2.004c.802.458 1.73.719 2.72.719a5.474 5.474 0 0 0 2.78-.753 5.527 5.527 0 0 0 2.001-2.027c.458-.802.719-1.73.719-2.72a5.475 5.475 0 0 0-.753-2.78 5.528 5.528 0 0 0-2.028-2.002A5.475 5.475 0 0 0 10 4.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="grow">Account</span>
                </a>
            </div>
            <!-- END Settings -->
        </div>
        <!-- END Main Navigation -->
    </div>
    <!-- END Sidebar Content -->
</nav>
