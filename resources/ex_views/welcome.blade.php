@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="container mx-auto px-4 py-4 lg:p-8 xl:max-w-7xl">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-6">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-emerald-500">
                        <span>3%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-up inline-block size-4 rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">2.7%</dt>
                        <dd class="text-xs font-medium text-slate-500">
                            Conversion Rate
                        </dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000">
                        <path fill="#6366f11a"
                            d="M0 623.854c80-59.416 240-298.176 400-297.076 160 1.1 240 343.78 400 302.574 160-41.206 240-415.29 400-508.605 160-93.314 240-22.06 400 42.033 160 64.095 320 222.751 400 278.439V1000H0Z" />
                        <path fill="none" stroke="#6366f1" stroke-width="30"
                            d="M0 623.854c80-59.416 240-298.176 400-297.076 160 1.1 240 343.78 400 302.574 160-41.206 240-415.29 400-508.605 160-93.314 240-22.06 400 42.033 160 64.095 320 222.751 400 278.439" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-emerald-500">
                        <span>15%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-up inline-block size-4 rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">4.7k</dt>
                        <dd class="text-xs font-medium text-slate-500">
                            Visitors
                        </dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000">
                        <path fill="#6366f11a"
                            d="M0 602.49c80-41.832 240-108.916 400-209.159C560 293.09 640 21.354 800 101.278c160 79.923 240 652.286 400 691.67 160 39.384 240-473.81 400-494.752 160-20.943 320 312.03 400 390.038V1000H0Z" />
                        <path fill="none" stroke="#6366f1" stroke-width="30"
                            d="M0 602.49c80-41.832 240-108.916 400-209.159C560 293.09 640 21.354 800 101.278c160 79.923 240 652.286 400 691.67 160 39.384 240-473.81 400-494.752 160-20.943 320 312.03 400 390.038" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-emerald-500">
                        <span>9%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-up inline-block size-4 rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">3.6k</dt>
                        <dd class="text-xs font-medium text-slate-500">
                            Unique Visitors
                        </dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000">
                        <path fill="#6366f11a"
                            d="M0 449.109c80 24.34 240 157.918 400 121.701 160-36.216 240-233.648 400-302.785 160-69.137 240-128.846 400-42.9s240 420.493 400 472.63 320-169.555 400-211.943V1000H0Z" />
                        <path fill="none" stroke="#6366f1" stroke-width="30"
                            d="M0 449.109c80 24.34 240 157.918 400 121.701 160-36.216 240-233.648 400-302.785 160-69.137 240-128.846 400-42.9s240 420.493 400 472.63 320-169.555 400-211.943" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-emerald-500">
                        <span>8%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-up inline-block size-4 rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">2.6k</dt>
                        <dd class="text-xs font-medium text-slate-500">
                            Sessions
                        </dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000">
                        <path fill="#6366f11a"
                            d="M0 736.31c80 9.994 240 136.225 400 49.971 160-86.254 240-448.72 400-481.24 160-32.52 240 312.003 400 318.64 160 6.637 240-309.293 400-285.454 160 23.84 320 323.722 400 404.652V1000H0Z" />
                        <path fill="none" stroke="#6366f1" stroke-width="30"
                            d="M0 736.31c80 9.994 240 136.225 400 49.971 160-86.254 240-448.72 400-481.24 160-32.52 240 312.003 400 318.64 160 6.637 240-309.293 400-285.454 160 23.84 320 323.722 400 404.652" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-emerald-500">
                        <span>12%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-up inline-block size-4 rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">5m</dt>
                        <dd class="text-xs font-medium text-slate-500">
                            Session Duration
                        </dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000">
                        <path fill="#6366f11a"
                            d="M0 131.28c80 52.463 240 237.611 400 262.315 160 24.703 240-137.143 400-138.8 160-1.656 240 25.982 400 130.517s240 438.49 400 392.16c160-46.33 320-499.048 400-623.81V1000H0Z" />
                        <path fill="none" stroke="#6366f1" stroke-width="30"
                            d="M0 131.28c80 52.463 240 237.611 400 262.315 160 24.703 240-137.143 400-138.8 160-1.656 240 25.982 400 130.517s240 438.49 400 392.16c160-46.33 320-499.048 400-623.81" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-rose-500">
                        <span>2.7%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-down inline-block size-4 -rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 2a.75.75 0 0 1 .75.75v8.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.22 3.22V2.75A.75.75 0 0 1 8 2Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">15,6k</dt>
                        <dd class="text-xs font-medium text-slate-500">
                            Page Views
                        </dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg viewBox="0 0 2000 1000" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 376.848c80 19.983 240 96.286 400 99.915 160 3.63 240-132.422 400-81.768 160 50.655 240 296.222 400 335.04 160 38.82 240-142.179 400-140.944 160 1.235 320 117.695 400 147.119V1000H0Z"
                            fill="#6366f11a" />
                        <path
                            d="M0 376.848c80 19.983 240 96.286 400 99.915 160 3.63 240-132.422 400-81.768 160 50.655 240 296.222 400 335.04 160 38.82 240-142.179 400-140.944 160 1.235 320 117.695 400 147.119"
                            fill="none" stroke="#6366f1" stroke-width="30" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-emerald-500">
                        <span>12%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-up inline-block size-4 rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">320</dt>
                        <dd class="text-xs font-medium text-slate-500">Sales</dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000">
                        <path fill="#6366f11a"
                            d="M0 736.31c80 9.994 240 136.225 400 49.971 160-86.254 240-448.72 400-481.24 160-32.52 240 312.003 400 318.64 160 6.637 240-309.293 400-285.454 160 23.84 320 323.722 400 404.652V1000H0Z" />
                        <path fill="none" stroke="#6366f1" stroke-width="30"
                            d="M0 736.31c80 9.994 240 136.225 400 49.971 160-86.254 240-448.72 400-481.24 160-32.52 240 312.003 400 318.64 160 6.637 240-309.293 400-285.454 160 23.84 320 323.722 400 404.652" />
                    </svg>
                </div>
            </div>
        </a>
        <a href="javascript:void(0)"
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 transition-opacity duration-100 hover:opacity-70 active:opacity-100 dark:bg-slate-900 dark:ring-slate-700/60">
            <div class="flex items-center justify-between gap-3 p-5">
                <div class="grow">
                    <div class="flex items-center gap-0.5 text-sm font-medium text-rose-500">
                        <span>1.2%</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                            fill="currentColor"
                            class="hi-micro hi-arrow-down inline-block size-4 -rotate-45">
                            <path fill-rule="evenodd"
                                d="M8 2a.75.75 0 0 1 .75.75v8.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.22 3.22V2.75A.75.75 0 0 1 8 2Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <dl>
                        <dt class="text-2xl font-extrabold">$7,315</dt>
                        <dd class="text-xs font-medium text-slate-500">Wallet</dd>
                    </dl>
                </div>
                <div class="relative w-full max-w-28">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent dark:from-slate-900">
                    </div>
                    <svg viewBox="0 0 2000 1000" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 376.848c80 19.983 240 96.286 400 99.915 160 3.63 240-132.422 400-81.768 160 50.655 240 296.222 400 335.04 160 38.82 240-142.179 400-140.944 160 1.235 320 117.695 400 147.119V1000H0Z"
                            fill="#6366f11a" />
                        <path
                            d="M0 376.848c80 19.983 240 96.286 400 99.915 160 3.63 240-132.422 400-81.768 160 50.655 240 296.222 400 335.04 160 38.82 240-142.179 400-140.944 160 1.235 320 117.695 400 147.119"
                            fill="none" stroke="#6366f1" stroke-width="30" />
                    </svg>
                </div>
            </div>
        </a>
        <!-- END Mini Stats -->

        <!-- Pageviews -->
        <div
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 dark:bg-slate-900 dark:ring-slate-700/60 xl:col-span-2">
            <div class="mb-6 flex items-center justify-between gap-4 p-6">
                <h2 class="text-xl font-extrabold">
                    Pageviews
                    <small class="font-semibold text-slate-500">All websites</small>
                </h2>
                <button type="button"
                    class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-arrow-up-right inline-block size-5">
                        <path fill-rule="evenodd"
                            d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Example SVG Chart -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000" class="-mb-10">
                <path fill="#6366f11a"
                    d="M0 696.907c57.2 15.62 171.6 192.875 286 78.1C400.4 660.23 457.6 173.848 572 123.03c114.4-50.818 171.6 276.048 286 397.888 114.4 121.84 171.6 232.368 286 211.31 114.4-21.06 171.6-303.438 286-316.604 114.4-13.166 171.6 306.71 286 250.775 114.4-55.935 228.8-424.36 286-530.45L2000 1000H0Z" />
                <path fill="none" stroke="#6366f1" stroke-width="6"
                    d="M0 696.907c57.2 15.62 171.6 192.875 286 78.1C400.4 660.23 457.6 173.848 572 123.03c114.4-50.818 171.6 276.048 286 397.888 114.4 121.84 171.6 232.368 286 211.31 114.4-21.06 171.6-303.438 286-316.604 114.4-13.166 171.6 306.71 286 250.775 114.4-55.935 228.8-424.36 286-530.45" />
            </svg>
            <!-- END Example SVG Chart -->
        </div>
        <!-- END Pageviews -->

        <!-- Sales -->
        <div
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white ring-1 ring-slate-200/50 dark:bg-slate-900 dark:ring-slate-700/60 xl:col-span-2">
            <div class="mb-6 flex items-center justify-between gap-4 p-6">
                <h2 class="text-xl font-extrabold">
                    Sales
                    <small class="font-semibold text-slate-500">All websites</small>
                </h2>
                <button type="button"
                    class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-arrow-up-right inline-block size-5">
                        <path fill-rule="evenodd"
                            d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Example SVG Chart -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1000" class="-mb-10">
                <path fill="#6366f11a"
                    d="M0 376.688c57.2-32.13 171.6-243.36 286-160.654 114.4 82.707 171.6 547.54 286 574.185 114.4 26.645 171.6-345.693 286-440.959 114.4-95.266 171.6-22.052 286-35.37 114.4-13.318 171.6-84.844 286-31.221C1544.4 336.29 1601.6 570.835 1716 582c114.4 11.167 228.8-194.8 286-243.5l-2 661.5H0Z" />
                <path fill="none" stroke="#6366f1" stroke-width="6"
                    d="M0 376.688c57.2-32.13 171.6-243.36 286-160.654 114.4 82.707 171.6 547.54 286 574.185 114.4 26.645 171.6-345.693 286-440.959 114.4-95.266 171.6-22.052 286-35.37 114.4-13.318 171.6-84.844 286-31.221C1544.4 336.29 1601.6 570.835 1716 582c114.4 11.167 228.8-194.8 286-243.5" />
            </svg>
            <!-- END Example SVG Chart -->
        </div>
        <!-- END Sales -->

        <!-- Popular Pages -->
        <div
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white p-6 ring-1 ring-slate-200/50 dark:bg-slate-900 dark:ring-slate-700/60 xl:col-span-2">
            <div class="mb-6 flex items-center justify-between gap-4">
                <h2 class="text-xl font-extrabold">Popular Pages</h2>
                <button type="button"
                    class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-arrow-up-right inline-block size-5">
                        <path fill-rule="evenodd"
                            d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th
                            class="py-2 pe-2 text-start font-medium text-slate-500 dark:text-slate-400">
                            Path
                        </th>
                        <th class="py-2 ps-2 text-end font-medium text-slate-500 dark:text-slate-400">
                            Pageviews
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 42%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/blog/how-to-build-a-laravel-app</a>
                        </td>
                        <td class="text-end">6849</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 30%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/</a>
                        </td>
                        <td class="text-end">4216</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 28%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/blog/working-from-home-has-never-been-easier</a>
                        </td>
                        <td class="text-end">3895</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 25%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/products/dark-tailwind-dashboard</a>
                        </td>
                        <td class="text-end">2863</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 22%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/freebies/landing-page</a>
                        </td>
                        <td class="text-end">2552</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 12%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/blog/bootstrap-5-new-features</a>
                        </td>
                        <td class="text-end">1236</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 10%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/about</a>
                        </td>
                        <td class="text-end">1054</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 8%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">/blog/inspiring-results-marketing</a>
                        </td>
                        <td class="text-end">869</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- END Popular Pages -->

        <!-- Referrers -->
        <div
            class="flex flex-col justify-center overflow-hidden rounded-lg bg-white p-6 ring-1 ring-slate-200/50 dark:bg-slate-900 dark:ring-slate-700/60 xl:col-span-2">
            <div class="mb-6 flex items-center justify-between gap-4">
                <h2 class="text-xl font-extrabold">Referrers</h2>
                <button type="button"
                    class="flex items-center justify-between gap-1.5 rounded-lg bg-slate-100 px-2 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-200/75 hover:text-slate-950 active:bg-slate-100 dark:bg-slate-700/50 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:text-white dark:active:bg-slate-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="hi-mini hi-arrow-up-right inline-block size-5">
                        <path fill-rule="evenodd"
                            d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th
                            class="py-2 pe-2 text-start font-medium text-slate-500 dark:text-slate-400">
                            Referrers
                        </th>
                        <th class="py-2 ps-2 text-end font-medium text-slate-500 dark:text-slate-400">
                            Pageviews
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 76%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">Google</a>
                        </td>
                        <td class="text-end">25630</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 14%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">Bing</a>
                        </td>
                        <td class="text-end">3260</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 13%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">Facebook</a>
                        </td>
                        <td class="text-end">3158</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 12%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">X (Twitter)</a>
                        </td>
                        <td class="text-end">2800</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 8%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">DuckDuckGo</a>
                        </td>
                        <td class="text-end">2769</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 8%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">Yahoo</a>
                        </td>
                        <td class="text-end">2200</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 6%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">example.com</a>
                        </td>
                        <td class="text-end">856</td>
                    </tr>
                    <tr>
                        <td class="relative p-2">
                            <div class="absolute bottom-0 start-0 top-0 my-px w-3/4 bg-slate-100 dark:bg-slate-800"
                                style="width: 6%"></div>
                            <a class="relative font-medium hover:underline"
                                href="javascript:void(0)">example.io</a>
                        </td>
                        <td class="text-end">736</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- END Referrers -->
    </div>
</div>
@endsection