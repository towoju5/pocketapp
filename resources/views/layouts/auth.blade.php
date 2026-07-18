<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') &mdash; {{ config('app.name') }}</title>
    <style>
        :root { --p-blue: #2563eb; --p-dark: #0f172a; --white: #ffffff; }
        * { box-sizing: border-box; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; font-family: 'Inter', system-ui, sans-serif; }
        body { background: var(--white); overflow-x: hidden; }

        .nav-bar {
            position: fixed; top: 0; left: 0; width: 100%; height: 80px;
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 clamp(20px, 5vw, 60px); z-index: 9999;
        }
        .nav-brand { font-size: 22px; font-weight: 900; color: var(--p-dark); text-decoration: none; letter-spacing: -1.5px; z-index: 10001; }
        .nav-menu { display: flex; align-items: center; gap: 30px; }
        .nav-link { color: #475569; text-decoration: none; font-weight: 700; font-size: 14px; transition: 0.3s; }
        .nav-link:hover { color: var(--p-blue); }

        @media (max-width: 1024px) {
            .nav-menu {
                position: fixed; top: 0; right: 0; width: 280px; height: 100vh;
                background: var(--white); flex-direction: column; justify-content: center;
                padding: 40px; box-shadow: -10px 0 30px rgba(0,0,0,0.1);
                transform: translateX(100%); transition: 0.4s cubic-bezier(0.8, 0, 0.2, 1);
                visibility: hidden; z-index: 10000;
            }
            .nav-menu.active { transform: translateX(0); visibility: visible; }
            .nav-menu a { font-size: 18px; font-weight: 800; width: 100%; text-align: center; margin: 12px 0;}
        }

        .menu-btn { display: none; width: 45px; height: 45px; background: #f1f5f9; border: none; border-radius: 12px; cursor: pointer; z-index: 10001; }
        @media (max-width: 1024px) { .menu-btn { display: flex; align-items: center; justify-content: center; } }

        .auth-grid { display: flex; min-height: 100vh; padding-top: 80px; }
        .auth-visual {
            flex: 1.2; background: var(--p-dark); padding: 60px;
            display: flex; flex-direction: column; justify-content: center; color: var(--white);
            position: relative; overflow: hidden;
        }
        .auth-visual::after { content: ''; position: absolute; bottom: -5%; right: -5%; width: 300px; height: 300px; background: var(--p-blue); filter: blur(100px); opacity: 0.2; }

        .auth-form-side { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px; }
        .auth-card { width: 100%; max-width: 400px; }

        .form-group { margin-bottom: 20px; }
        label.field-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px; }

        .input-box { width: 100%; padding: 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 16px; margin-bottom: 20px; outline: none; }
        .input-box:focus { border-color: var(--p-blue); background: var(--white); box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }

        .btn-go { width: 100%; padding: 18px; border-radius: 12px; border: none; background: var(--p-dark); color: var(--white); font-weight: 800; cursor: pointer; transition: 0.3s; }
        .btn-go:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }

        .alert { padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 10px; border: 1px solid transparent; }
        .alert-error { background: #fff1f2; color: #e11d48; border-color: #fda4af; }
        .alert-success { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }

        .nav-mask { position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); visibility:hidden; opacity:0; transition:0.3s; z-index:9998; }
        .nav-mask.active { visibility:visible; opacity:1; }

        @media (max-width: 1024px) { .auth-visual { display: none; } .auth-grid { padding-top: 100px; } }
    </style>
</head>
<body>

<div class="nav-mask" id="mask"></div>

<header class="nav-bar">
    <a href="{{ Route::has('marketing.home') ? route('marketing.home') : url('/') }}" class="nav-brand">
        <span style="color:var(--p-blue)">&#9679;</span> {{ config('app.name') }}
    </a>
    <button class="menu-btn" id="tgl" aria-label="Toggle Menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>
    <nav class="nav-menu" id="menu">
        @if (Route::has('marketing.home'))
            <a href="{{ route('marketing.home') }}" class="nav-link">Home</a>
            <a href="{{ route('marketing.features') }}" class="nav-link">Features</a>
            <a href="{{ route('marketing.pricing') }}" class="nav-link">Pricing</a>
            <a href="{{ route('marketing.faq') }}" class="nav-link">FAQ</a>
        @endif
        @yield('nav-cta')
    </nav>
</header>

<div class="auth-grid">
    <div class="auth-visual">
        <h1 style="font-size: clamp(32px, 5vw, 55px); font-weight: 950; letter-spacing: -3px; line-height: 1;">@yield('visual-title')</h1>
        <p style="color: #94a3b8; font-size: 18px; margin-top: 25px; max-width: 450px;">@yield('visual-subtitle')</p>
    </div>
    <div class="auth-form-side">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>
</div>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    const tgl = document.getElementById('tgl');
    const menu = document.getElementById('menu');
    const mask = document.getElementById('mask');
    tgl.onclick = () => {
        menu.classList.toggle('active');
        mask.classList.toggle('active');
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    };
    mask.onclick = () => {
        menu.classList.remove('active');
        mask.classList.remove('active');
        document.body.style.overflow = '';
    };
</script>
</body>
</html>
