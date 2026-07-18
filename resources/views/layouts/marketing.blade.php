<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', config('app.name'))</title>

    <style>
        :root { --p-blue: #2563eb; --p-dark: #0f172a; --glass: rgba(255, 255, 255, 0.9); --neon: #00FFA3; }
        * { box-sizing: border-box; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f8fafc; overflow-x: hidden; width: 100%; }

        .site-header {
            position: fixed; top: 0; left: 0; width: 100%; height: 80px;
            background: var(--glass); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 clamp(20px, 5vw, 80px); z-index: 9999;
        }

        .brand-nexus {
            font-size: 24px; font-weight: 900; color: var(--p-dark);
            text-decoration: none; display: flex; align-items: center; gap: 10px;
            letter-spacing: -1.5px; z-index: 10001;
        }

        .nav-matrix { display: flex; align-items: center; gap: 30px; }
        .nav-link { color: #475569; text-decoration: none; font-weight: 700; font-size: 14px; transition: 0.3s; }
        .nav-link:hover { color: var(--p-blue); }

        .btn-prime {
            background: var(--p-dark); color: #fff !important; padding: 12px 24px;
            border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 13px;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .menu-trigger {
            display: none; width: 45px; height: 45px; border: none; background: rgba(0,0,0,0.03);
            border-radius: 12px; cursor: pointer; position: relative; z-index: 10001; transition: 0.3s;
        }

        .burger-icon { width: 22px; height: 2px; background: var(--p-dark); position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); transition: 0.3s; }
        .burger-icon::before, .burger-icon::after { content: ''; position: absolute; width: 100%; height: 100%; background: var(--p-dark); left: 0; transition: 0.3s; }
        .burger-icon::before { transform: translateY(-7px); }
        .burger-icon::after { transform: translateY(7px); }

        .menu-trigger.is-active .burger-icon { background: transparent; }
        .menu-trigger.is-active .burger-icon::before { transform: rotate(45deg); }
        .menu-trigger.is-active .burger-icon::after { transform: rotate(-45deg); }

        @media (max-width: 1024px) {
            .menu-trigger { display: block; }
            .nav-matrix {
                position: fixed; top: 0; right: 0; width: 100%; height: 100vh;
                background: #fff; flex-direction: column; justify-content: center;
                gap: 20px; padding: 40px; transform: translate3d(100%, 0, 0);
                transition: 0.5s cubic-bezier(0.8, 0, 0.2, 1); visibility: hidden;
            }
            .nav-matrix.is-active { transform: translate3d(0, 0, 0); visibility: visible; }
            .nav-matrix .nav-link { font-size: 28px; font-weight: 900; color: var(--p-dark); width: 100%; text-align: center; }
            .nav-matrix .btn-prime { width: 100%; padding: 22px; font-size: 18px; text-align: center; }
        }

        .nexus-mask {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(8px);
            opacity: 0; visibility: hidden; transition: 0.4s; z-index: 9998;
        }
        .nexus-mask.is-active { opacity: 1; visibility: visible; }

        main { margin-top: 80px; width: 100%; }

        .footer-grid { display: grid; gap: 40px; grid-template-columns: repeat(4, 1fr); max-width: 1200px; margin: 0 auto; }
        @media (max-width: 768px) { .footer-grid { grid-template-columns: repeat(1, 1fr); text-align: center; } }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #e2e8f0; text-decoration: none; font-size: 14px; }
        .footer-links a:hover { color: var(--p-blue); }
        .footer-heading { font-size: 14px; font-weight: 600; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="nexus-mask" id="nexusMask"></div>

    <header class="site-header">
        <a href="{{ Route::has('marketing.home') ? route('marketing.home') : url('/') }}" class="brand-nexus">
            <div style="width:10px; height:10px; background:var(--p-blue); border-radius:50%; box-shadow: 0 0 10px var(--p-blue);"></div>
            {{ config('app.name') }}
        </a>

        <button class="menu-trigger" id="menuBtn" aria-label="Toggle Navigation">
            <span class="burger-icon"></span>
        </button>

        <nav class="nav-matrix" id="navMatrix">
            @php
                $navLinks = [
                    'marketing.home' => 'Home',
                    'marketing.about' => 'About Core',
                    'marketing.features' => 'Neural Features',
                    'marketing.pricing' => 'Pricing',
                    'marketing.contact' => 'Support',
                ];
            @endphp
            @foreach ($navLinks as $routeName => $label)
                @if (Route::has($routeName))
                    <a href="{{ route($routeName) }}" class="nav-link">{{ $label }}</a>
                @endif
            @endforeach

            @auth
                <a href="{{ route('dashboard') }}" class="btn-prime">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-link" style="color:var(--p-blue);">Sign In</a>
                <a href="{{ route('register') }}" class="btn-prime">SIGN UP</a>
            @endauth
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer style="background:#0f172a; color:white; padding: 60px 40px">
        <div class="footer-grid">
            <div>
                <h4 style="font-size:18px; font-weight:700; margin-bottom:16px; color:#fff;">{{ config('app.name') }}</h4>
                <p style="color:#94a3b8; font-size:14px; line-height:1.6;">The premier algorithmic Omni-Layer framework.</p>
            </div>
            <div>
                <h4 class="footer-heading">Network</h4>
                <ul class="footer-links">
                    @if (Route::has('marketing.about'))<li><a href="{{ route('marketing.about') }}">About Core</a></li>@endif
                    @if (Route::has('marketing.features'))<li><a href="{{ route('marketing.features') }}">Neural Features</a></li>@endif
                    @if (Route::has('marketing.pricing'))<li><a href="{{ route('marketing.pricing') }}">Matrix Pricing</a></li>@endif
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Resources</h4>
                <ul class="footer-links">
                    @if (Route::has('marketing.faq'))<li><a href="{{ route('marketing.faq') }}">Documentation</a></li>@endif
                    @if (Route::has('marketing.blog'))<li><a href="{{ route('marketing.blog') }}">Dev Blog</a></li>@endif
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Legal</h4>
                <ul class="footer-links">
                    @if (Route::has('marketing.contact'))<li><a href="{{ route('marketing.contact') }}">Contact Trust &amp; Safety</a></li>@endif
                    <li><a href="{{ route('login') }}">Sign In</a></li>
                    <li><a href="{{ route('register') }}">Create Account</a></li>
                </ul>
            </div>
        </div>
        <div style="border-top:1px solid #334155; margin-top:40px; padding-top:24px; text-align:center; color:#64748b; font-size:13px;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved by the Omni-Layer Protocol.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('menuBtn');
            const nav = document.getElementById('navMatrix');
            const mask = document.getElementById('nexusMask');
            const body = document.body;

            function toggleSupreme() {
                const isOpen = btn.classList.toggle('is-active');
                nav.classList.toggle('is-active');
                mask.classList.toggle('is-active');

                if (isOpen) {
                    const scrollY = window.pageYOffset;
                    body.style.position = 'fixed';
                    body.style.top = `-${scrollY}px`;
                    body.style.width = '100%';
                } else {
                    const scrollY = body.style.top;
                    body.style.position = '';
                    body.style.top = '';
                    window.scrollTo(0, parseInt(scrollY || '0') * -1);
                }
            }

            btn.addEventListener('click', (e) => { e.stopPropagation(); toggleSupreme(); });
            mask.addEventListener('click', toggleSupreme);

            nav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (nav.classList.contains('is-active')) toggleSupreme();
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && nav.classList.contains('is-active')) toggleSupreme();
            });
        });
    </script>
</body>
</html>
