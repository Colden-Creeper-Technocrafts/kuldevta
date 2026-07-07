@extends('layouts.app')
@section('title', __('participant.login'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-5">

            <div class="card shadow-sm">
                <div class="card-header py-3 text-white" style="background:#8B0000">
                    <h4 class="mb-0"><i class="bi bi-person-badge me-2"></i>{{ __('participant.login') }}</h4>
                    <p class="mb-0 small opacity-75">{{ __('app.temple_name') }}</p>
                </div>
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <p class="text-muted mb-4">{{ __('participant.enter_mobile') }}</p>

                    <form method="POST" action="{{ route('participant.login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('app.mobile') }}</label>
                            <input type="tel" name="mobile"
                                class="form-control form-control-lg @error('mobile') is-invalid @enderror"
                                placeholder="10-digit mobile"
                                maxlength="10"
                                value="{{ old('mobile') }}"
                                autofocus required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-lg w-100 text-white" style="background:#8B0000">
                            <i class="bi bi-send me-1"></i>{{ __('participant.send_otp') }}
                        </button>
                    </form>

                    @if(app()->environment() !== 'production')
                        <div class="alert alert-info mt-3 mb-0 small">
                            <i class="bi bi-info-circle me-1"></i>{{ __('participant.demo_otp_hint') }}
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
