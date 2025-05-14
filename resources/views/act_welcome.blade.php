<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/favicon.html" type="image/x-icon" />
    <title>Polaris Option</title>
    <link href="{{ asset('front/assets/css/app.css') }}" rel="stylesheet">
    <script defer src="{{ asset('front/assets/js/app.js') }}"></script>
</head>

<body>

    <!-- preloader start -->
    <div class="preloader">
        @include('components.preloader-main')
    </div>
    <!-- preloader end -->

    <!-- scroll to top button start -->
    <div class="scroll-to-top show" id="scrollToTop">
        <i class="ph ph-arrow-fat-lines-up"></i>
    </div>
    <!-- scroll to top button end -->

    <!-- header start -->
    <header id="header" class="absolute w-full z-[999]">
        <div class="mx-auto relative">
            <div id="header-nav"
                class="w-full xxl:px-[60px] xl:px-10 lg:px-8 md:px-7 sm:px-6 px-4 border-b border-white/10 relative">
                <div class="flex items-center justify-between gap-x-2 mx-auto py-24p">
                    <nav class="relative w-full flex justify-between items-center gap-24p text-semibold">
                        <div class="flex items-center xxl:gap-x-40p gap-x-20p">
                            <a href="{{ url('/') }}">
                                <img class="md:w-[159px] sm:w-30 w-25 h-auto shrink-0"
                                    src="{{ asset('assets/images/logo.svg') }}" alt="brand" />
                            </a>
                            <!-- mega menu start -->
                            <div x-data="{ open: false }" class="group">
                                <button @click="open = !open"
                                    class="btn-md btn-secondary-2 group whitespace-nowrap md:flex hidden">
                                    <span class="flex justify-center items-center icon-24">
                                        <i class="ph ph-squares-four"></i>
                                    </span>
                                    Top Companys
                                    <span :class="{ 'rotate-180 text-primary': open }"
                                        class="flex justify-center items-center icon-16 transition-1">
                                        <i class="ph ph-caret-down"></i>
                                    </span>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:leave="transition ease-in duration-200 transform"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute left-0 top-20 text-white transition-1 max-[1296px]">
                                    <div
                                        class="bg-BG-100 border border-stroke-1 border-opacity-40 shadow-3 grid lg:grid-cols-12 gap-y-0  gap-y-24p rounded-lg w-full">
                                        <div class="lg:col-span-8">
                                            <div class="p-32p">
                                                <h5 class="text-20 font-semibold text-white mb-24p">Top Crypto Currency
                                                    Companys</h5>
                                                <div x-data="dataList"
                                                    class="grid xxl:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-24p mb-24p">
                                                    <template x-for="(item, idx) in topCurrency" :key="idx">
                                                        <a href="crypto-details.html"
                                                            class="group py-3 px-24p rounded-lg bg-transparent hover:bg-primary text-white hover:text-n-800  border border-glass-3 flex items-center gap-3 cursor-pointer transition-1">
                                                            <img class="size-28p shrink-0" :src="item.image"
                                                                alt="brand" />
                                                            <h5 class="text-16 font-medium text-center"
                                                                x-text="item.name">
                                                            </h5>
                                                        </a>
                                                    </template>
                                                </div>
                                                <a href="market-watch.html"
                                                    class="inline-flex items-center gap-2 text-primary transition-1">
                                                    <span class="hover:underline">More Companys</span>
                                                    <i class="ph ph-arrow-right icon-20"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div
                                            class="lg:col-span-4 bg-glass-1 lg:rounded-r-lg lg:rounded-b-none rounded-b-lg">
                                            <div class="p-32p">
                                                <h5 class="text-20 font-semibold text-white mb-24p">Top Crypto Currency
                                                    Companys</h5>
                                                <div x-data="dataList"
                                                    class="grid lg:grid-cols-1 sm:grid-cols-2 grid-cols-1 gap-24p mb-24p">
                                                    <template x-for="(item, idx) in newCurrency.slice(7, 12)"
                                                        :key="idx">
                                                        <a href="crypto-details.html"
                                                            class="group py-3 px-24p rounded-lg bg-transparent hover:bg-primary text-white hover:text-n-800 border border-glass-3 flex items-center gap-3 cursor-pointer transition-1">
                                                            <img class="size-28p shrink-0" :src="item.image"
                                                                alt="brand" />
                                                            <h5 class="text-16 font-medium text-center"
                                                                x-text="item.name">
                                                            </h5>
                                                        </a>
                                                    </template>
                                                </div>
                                                <a href="compare-companies.html"
                                                    class="inline-flex items-center gap-2 text-primary transition-1">
                                                    <span class="hover:underline">Compare Companys</span>
                                                    <i class="ph ph-arrow-right icon-20"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- mega menu end -->
                        </div>
                        <div class="flex items-center xxl:gap-24p gap-20p relative">
                            <div class="xl:block hidden">
                                <ul class="menus">
                                    <li class="">
                                        <a href="{{ url('/') }}">Home</a>
                                    </li>
                                    
                                    <li class="">
                                        <a class="text-primary" href="{{ route('trade.index') }}">Trade</a>
                                    </li>
                                    
                                    <li class="">
                                        <a class="text-primary" href="{{ url('/') }}#!">About</a>
                                    </li>
                                    
                                    <li class="">
                                        <a class="text-primary" href="{{ url('/') }}#!">Support</a>
                                    </li>
                                    
                                </ul>
                            </div>
                            <div x-data="{ open: false }" class="relative xl:border-l border-n-50 xl:pl-6">
                                <button @click="open = !open"
                                    class="btn-lg-c btn-icon-secondary shrink-0 lg:flex hidden">
                                    <i class="ph ph-magnifying-glass"></i>
                                </button>
                                <form x-show="open" @click.outside="open = false"
                                    class="absolute top-15 left-0 w-80 box-input-1 pl-32p pr-2 lg:flex hidden items-center justify-between gap-3">
                                    <input autocomplete="off" class="bg-transparent w-full placeholder:text-white"
                                        type="text" name="search" id="search" placeholder="Search"
                                        required />
                                    <button
                                        class="btn-icon-primary md:size-8 sm:size-7 size-6 flex-c rounded-full text-base shrink-0">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </button>
                                </form>
                            </div>
                            <a href="#" class="btn-lg-c btn-icon-secondary shrink-0 lg:flex hidden">
                                <i class="ph ph-globe"></i>
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn-lg btn-primary shrink-0">
                                Dashboard
                                <i class="ph ph-arrow-right icon-20"></i>
                            </a>
                            <button
                                class="nav-toggole cursor-pointer text-3xl text-white hover:text-primary transition-1 xl:hidden block">
                                <i class="ph ph-list"></i>
                            </button>
                        </div>
                    </nav>
                </div>
                <nav class="w-full flex justify-between items-center ">
                    <div class="relative">
                        <div
                            class="small-nav fixed top-0 left-0 h-screen w-full shadow-lg z-[999] transform transition-transform ease-in-out -translate-x-full duration-500 flex xxl:hidden justify-start items-start gap-8 flex-col">
                            <div
                                class="absolute overflow-y-scroll scrollbar scrollbar-sm top-0 bottom-0 z-[2000] xsm:w-[400px] w-full  bg-BG-200 sm:p-12 p-8">
                                <div class="relative flex justify-between items-center mb-80p">
                                    <a href="{{ url('/') }}">
                                        <img class="w-[142px]" src="{{ asset('assets/images/logo.svg') }}"
                                            alt="Polarisoption" />
                                    </a>
                                    <button class="nav-close text-xl cursor-pointer text-white hover:text-primary">
                                        <i class="ph ph-x"></i>
                                    </button>
                                </div>

                                <ul class="flex flex-col justify-center items-start gap-5 text-white">
                                    <li class="sub-menu mobail-submenu">
                                        <span class="mobail-submenu-btn">
                                            <span class="text-xl submenu-btn">Home</span>
                                            <span class="collapse-icon mobail-submenu-icon">
                                                <i class="ph ph-caret-down "></i>
                                            </span>
                                        </span>
                                        <ul class="grid gap-y-2 px-16p">
                                            <li class="pt-2">
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="{{ url('/') }}">
                                                    - Home One
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="index-2.html">
                                                    - Home Two
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu mobail-submenu">
                                        <span class="mobail-submenu-btn">
                                            <span class="text-xl submenu-btn">Categories</span>
                                            <span class="collapse-icon mobail-submenu-icon">
                                                <i class="ph ph-caret-down "></i>
                                            </span>
                                        </span>
                                        <ul class="grid gap-y-2 px-16p">
                                            <li class="pt-2">
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-exchanges.html">
                                                    - Exchanges
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-projects.html">
                                                    - Projects
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-wallets.html">
                                                    - Wallets
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-media.html">
                                                    - Media
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-coins.html">
                                                    - Coins
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-platforms.html">
                                                    - Platforms
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-mining.html">
                                                    - Mining
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-games.html">
                                                    - Games
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-software.html">
                                                    - Software
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-ecosystems.html">
                                                    - Ecosystems
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="categories-defi.html">
                                                    - DeFi
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="mobail-menu">
                                        <a href="about.html">About</a>
                                    </li>
                                    <li class="sub-menu mobail-submenu">
                                        <span class="mobail-submenu-btn">
                                            <span class="text-xl submenu-btn">Platforms</span>
                                            <span class="collapse-icon mobail-submenu-icon">
                                                <i class="ph ph-caret-down "></i>
                                            </span>
                                        </span>
                                        <ul class="grid gap-y-2 px-16p">
                                            <li class="pt-2">
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="compare-companies.html">
                                                    - Compare Crypto
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="cryptocurrency-market.html">
                                                    - Crypto Market
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="market-watch.html">
                                                    - Market Watch
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="cryptocurrency-address.html">
                                                    - Crypto Address
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="add-company.html">
                                                    - Add New Company
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu mobail-submenu">
                                        <span class="mobail-submenu-btn">
                                            <span class="text-xl submenu-btn">Blogs</span>
                                            <span class="collapse-icon mobail-submenu-icon">
                                                <i class="ph ph-caret-down "></i>
                                            </span>
                                        </span>
                                        <ul class="grid gap-y-2 px-16p">
                                            <li class="pt-2">
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="blogs.html">
                                                    - Blogs
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="blog-details.html">
                                                    - Blog Details
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu mobail-submenu">
                                        <span class="mobail-submenu-btn">
                                            <span class="text-xl submenu-btn">Pages</span>
                                            <span class="collapse-icon mobail-submenu-icon">
                                                <i class="ph ph-caret-down "></i>
                                            </span>
                                        </span>
                                        <ul class="grid gap-y-2 px-16p">
                                            <li class="pt-2">
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="crypto-details.html">
                                                    - Crypto Details
                                                </a>
                                            </li>
                                            <li class="pt-2">
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="privacy-policy.html">
                                                    - Privacy Policy
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="faqs.html">
                                                    - Faqs
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="contact-us.html">
                                                    - Contact Us
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="not-found.html">
                                                    - Not Found
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="{{ route('register') }}">
                                                    - Sign Up
                                                </a>
                                            </li>
                                            <li>
                                                <a aria-label="item" class="text-18 hover:text-primary transition-1"
                                                    href="{{ route('login') }}">
                                                    - Sign In
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div id="nav-overly" class="min-h-[200vh] overly-1"></div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- header end -->

    <!-- main start -->
    <main class="min-h-screen">

        <!-- Home two hero banner start -->
        <section class="section-pt relative overflow-x-hidden bg-BG-200">
            <div class="container relative section-pb pt-10 md:pt-15 lg:pt-18 xl:pt-20">
                <div class="grid xl:grid-cols-12 grid-cols-1 3xl:items-center lg:gap-x-24p gap-y-60p">
                    <div class="xl:col-start-1 xxl:col-end-7 xl:col-end-8">
                        <span class="text-secondary-gradient text-20 font-medium mb-16p">
                            The Ultimate Trading Solution
                        </span>
                        <h2 class="text-64 text-white mb-24p text-split-left">
                            Top Cryptocurrency & Forex <span class="text-primary">Trading</span> Platform
                        </h2>
                        <div class="flex md:flex-row flex-col gap-24p mb-40p divide-x divide-dashed divide-warning/50">
                            <div class="shrink-0">
                                <h5 class="text-base text-white mb-16p">5M+ Active Traders</h5>
                                <div class="shrink-0 flex-y *:size-48p *:rounded-full *:md:-ml-4 md:ml-4 *:-ml-2 ml-2">
                                    <img src="{{ url('front') }}/assets/images/photos/user1.png" alt="user" />
                                    <img src="{{ url('front') }}/assets/images/photos/user2.png" alt="user" />
                                    <img src="{{ url('front') }}/assets/images/photos/user3.png" alt="user" />
                                    <img src="{{ url('front') }}/assets/images/photos/user4.png" alt="user" />
                                    <div class="bg-warning text-BG-200 icon-24 flex-c">
                                        <i class="ph ph-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-18 text-white max-w-[520px] px-24p">
                                Discover PolarisOption’s powerful platform for comparing cryptocurrencies, forex, and
                                binary options with precision and ease. Our expert analyses and advanced platforms give
                                you an edge in the financial markets.
                            </p>
                        </div>
                        <div class="flex-y flex-wrap gap-24p">
                            <a href="compare-crypto.html" class="btn-lg btn-primary">
                                Compare Cryptos
                                <i class="ph ph-arrow-right icon-20"></i>
                            </a>
                            <a href="compare-forex.html" class="btn-lg btn-primary-outline">
                                Compare Forex
                                <i class="ph ph-arrow-right icon-20"></i>
                            </a>
                            <a href="compare-binary.html" class="btn-lg btn-primary-outline">
                                Compare Binary Options
                                <i class="ph ph-arrow-right icon-20"></i>
                            </a>
                            <div class="xxl+:absolute 3xl:bottom-[30vh] 3xl:-left-[8vw] xxl:-left-[5vw]">
                                <button id="modal-open-btn" class="relative">
                                    <img class="absolute bottom-20 left-1/2 xxl+:block hidden"
                                        src="{{ url('front') }}/assets/images/icons/vidoLine.svg" alt="line" />
                                    <span class="btn-play-1 animate-zoom-in-out icon-24">
                                        <i class="ph ph-play-fill"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="xl:col-start-8 xl:col-end-13">
                        <img class="lg:max-w-[unset] animate-bounce-slow-top"
                            src="{{ url('front') }}/assets/images/photos/homeBanner2.png" alt="banner" />
                    </div>
                </div>
            </div>
            <div id="modal" class="fixed inset-0 items-center justify-center z-[999] hidden">
                <!-- Modal Backdrop -->
                <div id="modal-backdrop" class="overly-1"></div>
                <!-- Modal Content -->
                <div
                    class="relative z-[999] rounded-lg shadow-lg w-full lg:max-w-screen-md max-w-screen-sm h-auto sm:mx-6 mx-5">
                    <!-- Modal Body -->
                    <div class="modal-body relative">
                        <!-- Close Button -->
                        <button id="modal-close-btn"
                            class="absolute -top-5 -right-5 text-n-800 sm:size-9 size-7 flex justify-center items-center rounded-full bg-primary hover:bg-warning transition-1">
                            <i class="ph ph-x icon-24"></i>
                        </button>
                        <iframe class="w-full lg:h-[420px] sm:h-[320px] xsm:h-[260px] h-[220px] border-none"
                            src="https://www.youtube.com/embed/dVYl5ImNjow?si=RFNnIna5CiRMT-K5"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Home two hero banner start -->

        <!-- Best Crypto Companies two section start -->
        <section class="section-py bg-Bg">
            <div class="container">
                <div class="flex-col-c text-center mb-60p">
                    <div class="max-w-[708px]">
                        <span class="text-secondary-gradient text-20 font-medium mb-16p" data-aos="fade-left">
                            Why We Are The Best
                        </span>
                        <h2 class="text-40 text-white text-split-bottom">
                            Discover The Leading Platform For Crypto Trading, Binary Options, & Forex
                        </h2>
                    </div>
                </div>
                <div class="flex-c mb-40p">
                    <p class="text-18 text-white max-w-[760px] mx-auto mb-24p">
                        At PolarisOption, we provide an unparalleled trading experience. Whether you're a seasoned
                        trader or just getting started, our platform offers cutting-edge features, intuitive design, and
                        a range of trading platforms that set us apart as the best in the market.
                    </p>
                    <div class="grid xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 gap-16p">
                        <div class="flex-col-c text-center bg-BG-100 p-32p rounded-xl shadow-lg">
                            <img src="{{ url('front') }}/assets/images/icons/reliability.svg" alt="Reliability"
                                class="size-64p mb-16p" />
                            <h3 class="text-24 text-white mb-8p">Unmatched Reliability</h3>
                            <p class="text-18 text-n-50">Our platform offers rock-solid reliability with seamless
                                connectivity, ensuring that your trades are executed flawlessly every time. We boast an
                                uptime rate that is second to none, allowing you to trade with confidence.</p>
                        </div>
                        <div class="flex-col-c text-center bg-BG-100 p-32p rounded-xl shadow-lg">
                            <img src="{{ url('front') }}/assets/images/icons/secure.svg" alt="Security"
                                class="size-64p mb-16p" />
                            <h3 class="text-24 text-white mb-8p">Top-Notch Security</h3>
                            <p class="text-18 text-n-50">We prioritize the security of your funds and data with
                                state-of-the-art encryption and multi-factor authentication. Our platform is designed to
                                keep your investments and personal information safe from threats.</p>
                        </div>
                        <div class="flex-col-c text-center bg-BG-100 p-32p rounded-xl shadow-lg">
                            <img src="{{ url('front') }}/assets/images/icons/expertise.svg" alt="Expertise"
                                class="size-64p mb-16p" />
                            <h3 class="text-24 text-white mb-8p">Expert Insights & Platforms</h3>
                            <p class="text-18 text-n-50">With in-depth market analyses and expert insights, we provide
                                the platforms you need to make informed trading decisions. From crypto to forex, our
                                advanced charts and real-time data empower you to trade like a pro.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-c mb-40p">
                    <p class="text-18 text-white max-w-[760px] mx-auto mb-24p">
                        Whether you're looking for binary options trading, forex, or crypto trading, we provide the
                        perfect environment for growth and success. Our user-friendly interface ensures that all types
                        of traders, from beginners to experts, can easily navigate and execute trades with precision.
                    </p>
                </div>
                <div class="flex-c">
                    <a href="about-us.html" class="btn-lg btn-primary-outline">
                        Learn More About Why We Are The Best
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>


        <!-- Best Crypto Companies two section end -->

        <!-- Why Choose Us One section start -->
        <section class="section-py bg-BG-200 overflow-hidden">
            <div class="container">
                <div class="grid lg:grid-cols-12 gap-x-24p gap-y-40p items-center">
                    <div class="lg:col-start-1 lg:col-end-7" data-aos="fade-right">
                        <span class="text-secondary-gradient text-20 font-medium mb-16p">
                            Why Choose Our Trading Platforms
                        </span>
                        <h2 class="text-40 text-white mb-16p text-split-left">
                            We Provide All <span class="text-primary span">Trading Platform</span> Reviews and Tradings
                        </h2>
                        <p class="text-base text-white mb-40p">
                            We offer comprehensive reviews and Tradings for all trading platforms. Our expert team
                            meticulously analyzes each platform, including Binary Trading, Forex, and Indices.
                        </p>
                        <div class="grid gap-32p mb-40p">
                            <div x-data="{
                                stats: [
                                    { title: 'Platforms Reviewed Per Day', end: 88 },
                                    { title: 'Happy Traders Per Day', end: 96 },
                                    { title: 'Platform Ratings Per Day', end: 91 },
                                ],
                            }" class="grid gap-2">
                                <template x-for="(item, idx) in stats" :key="idx">
                                    <div x-data="progressBar(0, item.end)" x-init="init()" class="mb-16p">
                                        <div
                                            class="flex justify-between items-center text-18 font-medium text-white mb-16p">
                                            <h6 x-text="item.title"></h6>
                                            <h6><span x-text="progress" class="span"></span>%</h6>
                                        </div>
                                        <div x-intersect.once="$dispatch('start-progress')"
                                            class="relative w-full h-[5px] overflow-hidden bg-n-600 rounded-full">
                                            <span :style="'width:' + progress + '%'"
                                                class="absolute h-full bg-primary duration-500"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <a href="categories-platforms.html" class="btn-lg btn-primary">
                            Read More
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                    <div class="3xl:col-start-8 lg:col-start-7 lg:col-end-13" data-aos="fade-left">
                        <img class="lg:max-w-[unset] lg:animate-bounce-slow-top animate-bounce-slow-right"
                            src="{{ url('front') }}/assets/images/photos/whyChooseUsBanner.png" alt="banner" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us One section end -->

        <!-- What We Offer section start -->
        <section class="section-py">
            <div class="container">
                <div class="flex-col-c text-center mb-60p">
                    <div class="max-w-[708px]">
                        <span class="text-secondary-gradient text-20 font-medium mb-16p" data-aos="fade-right">
                            What We Offer
                        </span>
                        <h2 class="text-40 text-white mb-24p text-split-left">
                            Explore Premier Trading Platforms
                        </h2>
                        <p class="text-base text-white" data-aos="fade-right">
                            We offer comprehensive reviews and Tradings for all major trading platforms. Our expert team
                            meticulously analyzes each platform, including Binary Trading, Forex, and Indices.
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-24p text-white">
                    <div class="lg:col-span-8 col-span-12" data-aos="zoom-in">
                        <div
                            class="grid md:grid-cols-8 grid-cols-1 gap-24p xl:items-start items-center bg-primary text-n-900 p-32p rounded-xl h-full">
                            <div class="md:col-span-5">
                                <h3 class="text-32 mb-16p">
                                    Trading Platform Reviews & Tradings
                                </h3>
                                <p class="text-sm font-normal mb-24p">
                                    In-depth analyses and unbiased reviews of various trading platforms to help you make
                                    informed trading decisions.
                                </p>
                                <ul class="list-inside text-base font-medium *:flex-y *:gap-2 grid gap-3">
                                    <li><span class="size-2 rounded-full bg-n-900"></span> Examine Platform Features
                                    </li>
                                    <li><span class="size-2 rounded-full bg-n-900"></span> Analyze Market Performance
                                    </li>
                                </ul>
                            </div>
                            <div class="md:col-span-3 flex md:justify-end justify-center">
                                <img class="size-[234px] animate-bounce-slow-left"
                                    src="{{ url('front') }}/assets/images/photos/provideServiceBanner.png"
                                    alt="we offer" />
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 sm:col-span-6 col-span-12 " data-aos="zoom-in">
                        <div
                            class="bg-BG-200 p-32p rounded-xl border-transparent border hover:border-primary h-full transition-1">
                            <span class="size-72p bg-primary rounded-xl flex-c mb-32p">
                                <img class="size-48p" src="{{ url('front') }}/assets/images/icons/offer1.svg"
                                    alt="offer" />
                            </span>
                            <h4 class="text-24 mb-16p">Market Analysis & Trading Insights</h4>
                            <p class="text-sm">
                                Comprehensive market research and trend analysis to keep you updated on the latest
                                trading developments.
                            </p>
                        </div>
                    </div>
                    <div class="lg:col-span-4 sm:col-span-6 col-span-12 " data-aos="zoom-in">
                        <div
                            class="bg-BG-200 p-32p rounded-xl border-transparent border hover:border-primary h-full transition-1">
                            <span class="size-72p bg-primary rounded-xl flex-c mb-32p">
                                <img class="size-48p" src="{{ url('front') }}/assets/images/icons/offer2.svg"
                                    alt="offer" />
                            </span>
                            <h4 class="text-24 mb-16p">Forex & Binary Trading Reviews</h4>
                            <p class="text-sm">
                                Comprehensive reviews of different Forex and Binary trading platforms to help you choose
                                the best one for your needs.
                            </p>
                        </div>
                    </div>
                    <div class="lg:col-span-4 sm:col-span-6 col-span-12 " data-aos="zoom-in">
                        <div
                            class="bg-BG-200 p-32p rounded-xl border-transparent border hover:border-primary h-full transition-1">
                            <span class="size-72p bg-primary rounded-xl flex-c mb-32p">
                                <img class="size-48p" src="{{ url('front') }}/assets/images/icons/offer3.svg"
                                    alt="offer" />
                            </span>
                            <h4 class="text-24 mb-16p">Exchange Tradings</h4>
                            <p class="text-sm">
                                Detailed Tradings of different trading exchanges, including fees, features, and
                                usability.
                            </p>
                        </div>
                    </div>
                    <div class="lg:col-span-4 sm:col-span-6 col-span-12 " data-aos="zoom-in">
                        <div
                            class="bg-BG-200 p-32p rounded-xl border-transparent border hover:border-primary h-full transition-1">
                            <span class="size-72p bg-primary rounded-xl flex-c mb-32p">
                                <img class="size-48p" src="{{ url('front') }}/assets/images/icons/offer4.svg"
                                    alt="offer" />
                            </span>
                            <h4 class="text-24 mb-16p">Trading Strategies & Insights</h4>
                            <p class="text-sm">
                                In-depth analysis and reviews of trading strategies, with a focus on maximizing returns
                                in different markets.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Offer section end -->

        <!-- Trusted Solution section start -->
        <section class="section-py bg-BG-200">
            <div class="container">
                <div class="flex-col-c text-center mb-40p" data-aos="fade-up">
                    <div class="max-w-[708px]">
                        <span class="text-secondary-gradient text-20 font-medium mb-16p">
                            Your Trusted Trading Platform Solution
                        </span>
                        <h2 class="text-40 text-white mb-24p text-split-left">
                            Your Trading Platform Review & Trading Hub
                        </h2>
                        <p class="text-base text-white">
                            Welcome to Polarisoption, your trusted resource for trading platform reviews and Tradings.
                            Our
                            mission is to empower both seasoned investors and newcomers.
                        </p>
                    </div>
                </div>
                <div x-data="{ activeTab: 'journey' }">
                    <div class="flex-c mb-60p">
                        <div
                            class="xsm:rounded-full rounded-xl bg-BG-100 border border-n-500 sm:p-3 p-2 flex-y sm:gap-3 gap-2 overflow-x-auto scrollbar-0">
                            <button @click=" activeTab = 'journey'"
                                :class="activeTab === 'journey' ? 'bg-primary border-primary' :
                                    'bg-BG-200 text-white border-n-500'"
                                class="btn-sm border hover:border-n-200">
                                <i class="ph ph-paper-plane-right icon-20"></i>
                                Journey
                            </button>
                            <button @click=" activeTab = 'mission'"
                                :class="activeTab === 'mission' ? 'bg-primary border-primary' :
                                    'bg-BG-200 text-white border-n-500'"
                                class="btn-sm border hover:border-n-200">
                                <i class="ph ph-target icon-20"></i>
                                Mission
                            </button>
                            <button @click=" activeTab = 'values'"
                                :class="activeTab === 'values' ? 'bg-primary border-primary' :
                                    'bg-BG-200 text-white border-n-500'"
                                class="btn-sm border hover:border-n-200">
                                <i class="ph ph-chart-pie-slice icon-20"></i>
                                Values
                            </button>
                        </div>
                    </div>
                    <div>
                        <div x-show="activeTab === 'journey'"
                            class="grid lg:grid-cols-2 grid-cols-1 items-center gap-x-24p gap-y-40p">
                            <div class="lg:order-1 order-2">
                                <img class="animate-bounce-slow-down"
                                    src="{{ url('front') }}/assets/images/photos/trustedBanner.png"
                                    alt="banner" />
                            </div>
                            <div class="order-1 lg:order-2">
                                <h3 class="text-32 text-white mb-24p">
                                    The Journey of Polarisoption: Our Path to Success and Innovation
                                </h3>
                                <p class="text-base text-white mb-40p">
                                    Polarisoption's journey began with a simple yet ambitious vision: to empower
                                    individuals
                                    with the
                                    knowledge and platforms to navigate the world of trading platforms confidently.
                                </p>
                                <div class="flex sm:flex-row flex-col gap-y-24p text-white mb-40p">
                                    <ul class="list-none list-inside grid grid-cols-1 gap-24p *:flex-y *:gap-3">
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Founded in 2021
                                        </li>
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Rapid Growth
                                        </li>
                                    </ul>
                                    <div class="w-px h-15 bg-opacity-50 bg-white mx-40p shrink-0 sm:block hidden">
                                    </div>
                                    <ul class="list-none list-inside grid grid-cols-1 gap-24p *:flex-y *:gap-3">
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Continuous Innovation
                                        </li>
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Community Focus
                                        </li>
                                    </ul>
                                </div>
                                <a href="about.html" class="btn-lg btn-primary">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div x-show="activeTab === 'mission'"
                            class="grid lg:grid-cols-2 grid-cols-1 items-center gap-x-24p gap-y-40p">
                            <div class="lg:order-1 order-2">
                                <img class="animate-bounce-slow-down"
                                    src="{{ url('front') }}/assets/images/photos/trustedBanner.png"
                                    alt="mission" />
                            </div>
                            <div class="order-1 lg:order-2">
                                <h3 class="text-32 text-white mb-24p">
                                    Our Mission: Empowering Trading Enthusiasts Worldwide
                                </h3>
                                <p class="text-base text-white mb-40p">
                                    At Polarisoption, our mission is to provide reliable and unbiased reviews of trading
                                    platforms, ensuring our
                                    community makes informed decisions with confidence.
                                </p>
                                <div class="flex sm:flex-row flex-col gap-y-24p text-white mb-40p">
                                    <ul class="list-none list-inside grid grid-cols-1 gap-24p *:flex-y *:gap-3">
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Trusted Reviews
                                        </li>
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Investor Education
                                        </li>
                                    </ul>
                                    <div class="w-px h-15 bg-opacity-50 bg-white mx-40p shrink-0 sm:block hidden">
                                    </div>
                                    <ul class="list-none list-inside grid grid-cols-1 gap-24p *:flex-y *:gap-3">
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Transparent Information
                                        </li>
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Community Empowerment
                                        </li>
                                    </ul>
                                </div>
                                <a href="market-watch.html" class="btn-lg btn-primary">
                                    Learn More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div x-show="activeTab === 'values'"
                            class="grid lg:grid-cols-2 grid-cols-1 items-center gap-x-24p gap-y-40p">
                            <div class="lg:order-1 order-2">
                                <img class="animate-bounce-slow-down"
                                    src="{{ url('front') }}/assets/images/photos/trustedBanner.png"
                                    alt="values" />
                            </div>
                            <div class="order-1 lg:order-2">
                                <h3 class="text-32 text-white mb-24p">
                                    Our Core Values: Integrity, Transparency, and Excellence
                                </h3>
                                <p class="text-base text-white mb-40p">
                                    Polarisoption is built on a foundation of strong values that guide every decision we
                                    make.
                                    We believe in integrity, transparency, and a commitment to excellence in all our
                                    endeavors.
                                </p>
                                <div class="flex sm:flex-row flex-col gap-y-24p text-white mb-40p">
                                    <ul class="list-none list-inside grid grid-cols-1 gap-24p *:flex-y *:gap-3">
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Integrity in Reporting
                                        </li>
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Unwavering Transparency
                                        </li>
                                    </ul>
                                    <div class="w-px h-15 bg-opacity-50 bg-white mx-40p shrink-0 sm:block hidden">
                                    </div>
                                    <ul class="list-none list-inside grid grid-cols-1 gap-24p *:flex-y *:gap-3">
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Commitment to Excellence
                                        </li>
                                        <li>
                                            <img src="{{ url('front') }}/assets/images/icons/check.svg"
                                                alt="icon" />
                                            Community Trust
                                        </li>
                                    </ul>
                                </div>
                                <a href="compare-companies.html" class="btn-lg btn-primary">
                                    Discover More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trusted Solution section end -->

        <!-- Steps Process section start -->
        <section class="section-py">
            <div class="container">
                <div class="flex-col-c text-center mb-60p">
                    <div class="max-w-[642px]">
                        <span class="text-secondary-gradient text-20 font-medium mb-16p">
                            Step-by-Step Process
                        </span>
                        <h2 class="text-40 text-white mb-24p text-split-left">
                            How PolarisOption Works
                        </h2>
                        <p class="text-base text-white">
                            At PolarisOption, we provide a seamless trading experience for binary options, currencies,
                            forex, and cryptocurrencies. Our platform is designed to empower both novice and experienced
                            traders to make informed decisions and maximize their potential.
                        </p>
                    </div>
                </div>
                <div
                    class="grid lg:grid-cols-3 sm:grid-cols-2 gap-24p mb-40p *:p-20p *:lg:border-none *:border *:border-n-500 *:rounded-xl *:transition-1 text-center">
                    <div class="xxl:-mt-10 xl:-mt-8 lg:-mt-6 hover:border-primary">
                        <div class="flex justify-center items-end mb-32p">
                            <span class="xl:text-120 text-96 text-warning text-opacity-40">
                                1
                            </span>
                            <div class="p-2 size-80p bg-primary border border-dashed border-BG-300 rounded-xl">
                                <div class="size-full bg-BG-300 rounded-xl flex-c">
                                    <img class="size-40p"
                                        src="{{ url('front') }}/assets/images/icons/userCircle.svg"
                                        alt="offer" />
                                </div>
                            </div>
                        </div>
                        <h4 class="text-24 text-white mb-16p text-split-left">
                            Create Your Trading Account
                        </h4>
                        <p class="text-sm text-white">
                            Start by creating your account on PolarisOption. Gain access to a variety of trading
                            instruments including binary options, forex, and cryptocurrencies, all in one platform.
                        </p>
                    </div>
                    <div class="hover:border-primary">
                        <div class="flex justify-center items-end gap-2.5 mb-32p">
                            <span class="xl:text-120 text-96 text-warning text-opacity-40">
                                2
                            </span>
                            <div class="p-2 size-80p bg-primary border border-dashed border-BG-300 rounded-xl">
                                <div class="size-full bg-BG-300 rounded-xl flex-c">
                                    <img class="size-40p" src="{{ url('front') }}/assets/images/icons/works2.svg"
                                        alt="offer" />
                                </div>
                            </div>
                        </div>
                        <h4 class="text-24 text-white mb-16p text-split-left">
                            Analyze Market Data
                        </h4>
                        <p class="text-sm text-white">
                            Once your account is set up, dive into comprehensive market analysis. Use our advanced
                            platforms to track currency pairs, forex trends, cryptocurrency values, and binary options
                            to make informed trading decisions.
                        </p>
                    </div>
                    <div class="xxl:-mt-10 xl:-mt-8 lg:-mt-6 hover:border-primary">
                        <div class="flex justify-center items-end gap-2.5 mb-32p">
                            <span class="xl:text-120 text-96 text-warning text-opacity-40">
                                3
                            </span>
                            <div class="p-2 size-80p bg-primary border border-dashed border-BG-300 rounded-xl">
                                <div class="size-full bg-BG-300 rounded-xl flex-c">
                                    <img class="size-40p" src="{{ url('front') }}/assets/images/icons/works3.svg"
                                        alt="offer" />
                                </div>
                            </div>
                        </div>
                        <h4 class="text-24 text-white mb-16p text-split-left">
                            Execute Trades & Monitor Performance
                        </h4>
                        <p class="text-sm text-white">
                            Execute your trades on binary options, forex, and cryptocurrencies with ease. Track your
                            performance, adjust strategies, and use our platform’s platforms to optimize your trades and
                            enhance your profitability.
                        </p>
                    </div>
                </div>
                <div class="flex-c">
                    <div
                        class="xl:max-w-[856px] w-full bg-glass-5 flex-y sm:flex-row flex-col justify-between gap-24p rounded-xl p-24p border border-[rgba(255,186,35,0.20)]">
                        <div class="flex-y gap-16p text-warning">
                            <i class="ph ph-lightbulb icon-32"></i>
                            <p class="text-20 font-normal">
                                Don’t have an account yet? Start your trading journey today with PolarisOption!
                            </p>
                        </div>
                        <a href="{{ route('register') }}" class="btn-md btn-warning shrink-0">
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Steps Process section end -->

        <!-- testimonials two section start -->
        <section
            class="section-py relative after:content-[''] after:absolute after:left-0 after:top-0 after:w-full after:h-[60%] after:bg-gradient-to-br from-stroke-1 to-primary after:z-[-1] grid-lines">
            <div class="container">
                <div class="flex-col-c text-center mb-60p">
                    <div class="max-w-[704px]">
                        <span class="text-n-700 text-20 font-medium mb-16p">
                            _____Customer Success Stories
                        </span>
                        <h2 class="text-40 text-n-800 mb-24p text-split-left">
                            What Our Traders Are Saying About PolarisOption
                        </h2>
                        <p class="text-base text-n-800">
                            At PolarisOption, we pride ourselves on offering a platform that empowers traders of all
                            levels.
                            Our users share their success stories and experiences, from navigating binary options to
                            mastering forex and cryptocurrency trading.
                        </p>
                    </div>
                </div>
                <div x-data="dataList">
                    <div class="swiper testimonials-four-carousel">
                        <div class="swiper-wrapper mb-40p">
                            <template x-for="(item, idx) in testimonials.slice(3, 9)" :key="idx">
                                <div class="swiper-slide">
                                    <div
                                        class="p-40p sm:rounded-xl rounded-lg bg-BG-200 text-white border border-glass-6">
                                        <div class="relative">
                                            <div class="grid divide-y divide-dashed divide-white divide-opacity-30">
                                                <div>
                                                    <div class="icon-28 text-base text-warning flex-y gap-2 mb-20p">
                                                        <i class="ph ph-star-fill"></i>
                                                        <i class="ph ph-star-fill"></i>
                                                        <i class="ph ph-star-fill"></i>
                                                        <i class="ph ph-star-fill"></i>
                                                        <i class="ph ph-star-fill"></i>
                                                    </div>
                                                    <p x-text="item.review" class="text-18 pb-28p"></p>
                                                </div>
                                                <div class="flex xl:items-center xl:flex-row flex-col gap-16p pt-32p">
                                                    <img class="size-60p rounded-full" :src="item.image"
                                                        alt="user" />
                                                    <div>
                                                        <h5 x-text="item.name" class="text-20 font-semibold mb-2">
                                                        </h5>
                                                        <span x-text="item.role" class="text-sm"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <img class="max-size-[76px] absolute bottom-0 right-0"
                                                src="{{ url('front') }}/assets/images/icons/comma.svg"
                                                alt="icon" />
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="flex-c gap-2">
                        <button
                            class="testimonials-four-prev btn-lg-c btn-outline text-primary hover:text-n-900 border-primary hover:bg-primary">
                            <i class="ph ph-arrow-left"></i>
                        </button>
                        <button
                            class="testimonials-four-next btn-lg-c btn-outline text-primary hover:text-n-900 border-primary hover:bg-primary">
                            <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- testimonials two section end -->

    </main>
    <!-- main end -->

    <!-- footer two start -->
    <footer class="section-pt bg-BG-100">
        <div class="container">
            <!-- subscribe section start -->
            <div
                class="px-40p py-32p rounded-xl grid md:grid-cols-12 justify-between items-center gap-24p border border-glass-7">
                <div class="md:col-start-1 md:col-end-8">
                    <h5 class="text-40 !font-normal text-white text-split-left">
                        Subscribe to our newsletter for the latest updates
                    </h5>
                </div>
                <div class="lg:col-start-9 md:col-start-8 md:col-end-13">
                    <form class="max-w-[416px] bg-glass-2 rounded-full border border-glass-3 p-2 flex-y">
                        <input autocomplete="off" type="email" name="email" placeholder="Your email address"
                            class="w-full text-base text-white bg-transparent placeholder:text-white px-16p" />
                        <button class="bg-primary rounded-full icon-20 text-n-900 py-3 px-24p flex-c">
                            <i class="ph ph-paper-plane-tilt"></i>
                        </button>
                    </form>
                </div>
            </div>
            <!-- subscribe section end -->
            <div class="container">
                <div class="lg:section-py py-80p grid sm:grid-cols-12 gap-x-24p gap-y-40p">
                    <div class="lg:col-start-1 lg:col-end-4 sm:col-span-6 order-1">
                        <div class="mb-32p">
                            <a href="{{ url('/') }}" class="mb-24p block">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
                            </a>
                            <p class="text-base text-n-30">
                                Compare and review Forex, cryptocurrencies, indices, currencies, and binary trading
                                options easily with PolarisOption's detailed insights and intuitive trading platforms,
                                designed to help you make well-informed decisions across all markets.
                            </p>
                        </div>
                        <div
                            class="flex-y gap-3 *:size-32p *:flex-c icon-16 text-primary *:border *:border-primary *:rounded-full">
                            <a href="#" class="hover:bg-primary hover:text-BG-200 transition-1">
                                <i class="ph ph-facebook-logo"></i>
                            </a>
                            <a href="#" class="hover:bg-primary hover:text-BG-200 transition-1">
                                <i class="ph ph-twitter-logo"></i>
                            </a>
                            <a href="#" class="hover:bg-primary hover:text-BG-200 transition-1">
                                <i class="ph ph-youtube-logo"></i>
                            </a>
                            <a href="#" class="hover:bg-primary hover:text-BG-200 transition-1">
                                <i class="ph ph-instagram-logo"></i>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-start-4 lg:col-end-6 sm:col-span-4 lg:order-2 order-3">
                        <h4 class="text-24 text-white mb-24p">Quick Link</h4>
                        <ul class="list-none grid gap-2 text-base text-n-30 transition-1">
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">About Us</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="{{ route('trade.index') }}">Exchange</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Wallets</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Media</a>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-start-6 lg:col-end-7 sm:col-span-4 lg:order-3 order-4">
                        <h4 class="text-24 text-white mb-24p">Categories</h4>
                        <ul class="list-none grid gap-2 text-base text-n-30 transition-1">
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Coins</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Ecosystems</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Mining</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Games</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Software</a>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-start-8 lg:col-end-10 sm:col-span-4 lg:order-4 order-5">
                        <h4 class="text-24 text-white mb-24p">Platforms</h4>
                        <ul class="list-none grid gap-2 text-base text-n-30 transition-1">
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Compare</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Money Transfer</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Market Watch</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">NFT Markets</a>
                            </li>
                            <li class="hover:list-disc hover:text-primary hover:translate-x-4 transition-1">
                                <a href="#">Crypto Market</a>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-start-10 lg:col-end-13 sm:col-span-6 lg:order-5 order-2">
                        <h4 class="text-24 text-white mb-24p">Contact</h4>
                        <div class="grid gap-24p *:flex-y *:gap-16p text-base text-n-30">
                            <div>
                                <span
                                    class="size-48p rounded-full border border-glass-2 bg-glass-9 flex-c icon-24 text-primary">
                                    <i class="ph ph-phone-call"></i>
                                </span>
                                <div class="*:block">
                                    <a href="tel:+111-555-9999" class="link-1">(111)-555-9999</a>
                                    <a href="tel:+222-555-9999" class="link-1">(222)-555-9999</a>
                                </div>
                            </div>
                            <div>
                                <span
                                    class="size-48p rounded-full border border-glass-2 bg-glass-9 flex-c icon-24 text-primary">
                                    <i class="ph ph-envelope-open"></i>
                                </span>
                                <div class="*:block">
                                    <a href="mailto:info@Polarisoption.com" class="link-1">info@Polarisoption.com</a>
                                    <a href="mailto:demo@Polarisoption.com" class="link-1">demo@Polarisoption.com</a>
                                </div>
                            </div>
                            <div>
                                <span
                                    class="size-48p rounded-full border border-glass-2 bg-glass-9 flex-c icon-24 text-primary">
                                    <i class="ph ph-map-pin-line"></i>
                                </span>
                                <div class="*:block">
                                    <address class="not-italic">
                                        2118 Thornridge Cir.
                                    </address>
                                    <address class="not-italic">
                                        Syracuse, Connecticut
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-BG-100 text-white flex sm:items-center lg:flex-row flex-col justify-between gap-24p py-32p border-t border-white border-opacity-15 sm:text-left text-center">
                <p class="text-base">
                    Copyright © <span class="currentYear span"></span> <a href="{{ url('/') }}"
                        class="text-primary hover:underline transition-1">Polarisoption</a>.
                    All rights reserved.
                </p>
                <ul class="list-none flex sm:flex-row flex-col gap-24p text-base">
                    <li>
                        <a href="#">Help & Support</a>
                    </li>
                    <li>
                        <a href="#">Privacy policy</a>
                    </li>
                    <li>
                        <a href="#">Terms & Conditions</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</body>

</html>
