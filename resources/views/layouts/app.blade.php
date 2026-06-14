<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('app.temple_name')) — {{ __('app.app_name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --saffron: #FF6B00;
            --saffron-dark: #d45a00;
            --saffron-light: #fff3e0;
            --maroon: #8B0000;
        }
        body { font-family: 'Segoe UI', sans-serif; background: #fafafa; }
        .navbar-kuldevta { background: var(--maroon); }
        .navbar-kuldevta .navbar-brand, .navbar-kuldevta .nav-link { color: #fff !important; }
        .navbar-kuldevta .nav-link:hover { color: #FFD700 !important; }
        .navbar-kuldevta .nav-link.active { color: #FFD700 !important; font-weight: 600; }
        .hero-banner {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--saffron) 100%);
            color: #fff;
            padding: 60px 0;
        }
        .card-event { border-left: 4px solid var(--saffron); transition: transform .15s; }
        .card-event:hover { transform: translateY(-2px); }
        .badge-saffron { background: var(--saffron); color: #fff; }
        .btn-saffron { background: var(--saffron); color: #fff; border: none; }
        .btn-saffron:hover { background: var(--saffron-dark); color: #fff; }
        .section-title { border-left: 4px solid var(--saffron); padding-left: 12px; }
        .footer-kuldevta { background: var(--maroon); color: #fff; padding: 30px 0; }
        .lang-switcher .btn { font-size: .8rem; padding: 2px 10px; }
        @media (max-width: 768px) { .hero-banner { padding: 30px 0; } }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-kuldevta">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="bi bi-flower1 me-1"></i> {{ __('app.app_name') }}
        </a>
        <button class="navbar-toggler border-light" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        {{ __('app.home') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        {{ __('app.events') }}
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('sangh.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
                        {{ __('app.sangh') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('sangh.register') }}">{{ __('sangh.register') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('sangh.status') }}">{{ __('sangh.check_status') }}</a></li>
                    </ul>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <div class="lang-switcher">
                    @if(app()->getLocale() === 'en')
                        <a href="{{ route('lang.switch', 'gu') }}" class="btn btn-outline-light btn-sm">{{ __('app.language') }}</a>
                    @else
                        <a href="{{ route('lang.switch', 'en') }}" class="btn btn-outline-light btn-sm">{{ __('app.language') }}</a>
                    @endif
                </div>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-warning ms-1">
                        <i class="bi bi-gear"></i> {{ __('app.admin') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="alert alert-success alert-dismissible m-0 rounded-0" role="alert">
        <div class="container">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible m-0 rounded-0" role="alert">
        <div class="container">{{ session('info') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@yield('content')

<footer class="footer-kuldevta mt-5">
    <div class="container text-center">
        <p class="mb-1 fw-bold">{{ __('app.jai_shree') }}</p>
        <p class="mb-0 small opacity-75">{{ __('app.temple_name') }} &copy; {{ date('Y') }}</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
