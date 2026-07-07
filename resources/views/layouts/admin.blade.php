<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ __('app.app_name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --maroon: #8B0000; --saffron: #FF6B00; }
        body { background: #f4f6f9; }
        .sidebar {
            width: 240px; min-height: 100vh; background: var(--maroon);
            position: fixed; top: 0; left: 0; z-index: 100; padding-top: 56px;
        }
        .sidebar .nav-link { color: rgba(255,255,255,.8); padding: 10px 20px; font-size: .9rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #FFD700; background: rgba(255,255,255,.08); }
        .sidebar .nav-link i { width: 20px; margin-right: 6px; }
        .sidebar-section { font-size: .7rem; color: rgba(255,255,255,.4); padding: 14px 20px 4px; text-transform: uppercase; letter-spacing: 1px; }
        .top-navbar { position: fixed; top: 0; left: 240px; right: 0; z-index: 99; background: #fff; border-bottom: 1px solid #dee2e6; height: 56px; display: flex; align-items: center; padding: 0 20px; justify-content: space-between; }
        .main-content { margin-left: 240px; padding: 76px 24px 24px; }
        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .stat-card { border-left: 4px solid var(--saffron); }
        .page-header { background: #fff; border-bottom: 1px solid #dee2e6; padding: 16px 0; margin-bottom: 24px; }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .top-navbar { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="d-block text-white text-decoration-none px-3 py-2 fw-bold" style="font-size:1.1rem; margin-top: -10px;">
        <i class="bi bi-flower1 me-2"></i>{{ __('app.app_name') }}
    </a>

    <div class="sidebar-section">{{ __('app.dashboard') }}</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> {{ __('app.dashboard') }}
    </a>

    <div class="sidebar-section">{{ __('app.sangh') }}</div>
    <a href="{{ route('admin.sangh.index') }}" class="nav-link {{ request()->routeIs('admin.sangh.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> {{ __('sangh.manage') }}
    </a>

    <div class="sidebar-section">{{ __('app.events') }}</div>
    <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i> {{ __('events.events') }}
    </a>
    <a href="{{ route('admin.sponsors.index') }}" class="nav-link {{ request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">
        <i class="bi bi-award"></i> {{ __('events.sponsors') }}
    </a>

    <div class="sidebar-section">{{ __('family.parivar') }}</div>
    <a href="{{ route('admin.family.index') }}" class="nav-link {{ request()->routeIs('admin.family.*') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> {{ __('family.family') }}
    </a>
</div>

{{-- Top Navbar --}}
<div class="top-navbar">
    <span class="fw-semibold text-muted">@yield('page-title', __('app.admin'))</span>
    <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-right me-1"></i> {{ __('app.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <div>
            @if(app()->getLocale() === 'en')
                <a href="{{ route('lang.switch', 'gu') }}" class="btn btn-outline-secondary btn-sm">ગુ</a>
            @else
                <a href="{{ route('lang.switch', 'en') }}" class="btn btn-outline-secondary btn-sm">EN</a>
            @endif
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
