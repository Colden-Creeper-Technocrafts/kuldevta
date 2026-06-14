<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — {{ __('app.app_name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg, #8B0000 0%, #FF6B00 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,.25); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="card-header text-center py-4" style="background:#8B0000; color:#fff">
                    <h4 class="mb-0">{{ __('app.app_name') }}</h4>
                    <p class="mb-0 small opacity-75">{{ __('app.admin') }}</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 small">
                            @foreach($errors->all() as $error) {{ $error }} @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('app.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('app.password') }}</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">{{ __('app.remember_me') }}</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn fw-bold" style="background:#FF6B00; color:#fff">
                                {{ __('app.login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-white small opacity-75">← {{ __('app.home') }}</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
