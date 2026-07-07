@extends('layouts.app')
@section('title', __('participant.verify_otp'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-5">

            <div class="card shadow-sm">
                <div class="card-header py-3 text-white" style="background:#8B0000">
                    <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i>{{ __('participant.verify_otp') }}</h4>
                    <p class="mb-0 small opacity-75">{{ __('participant.otp_sent', ['mobile' => $mobile]) }}</p>
                </div>
                <div class="card-body p-4">

                    <p class="text-muted mb-4">{{ __('participant.enter_otp') }}</p>

                    <form method="POST" action="{{ route('participant.verify.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">OTP</label>
                            <input type="text" name="otp"
                                class="form-control form-control-lg text-center fw-bold tracking-widest @error('otp') is-invalid @enderror"
                                placeholder="——"
                                maxlength="4"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                autofocus required>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-lg w-100 text-white" style="background:#8B0000">
                            <i class="bi bi-check2-circle me-1"></i>{{ __('participant.verify_otp') }}
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('participant.login.post') }}" class="text-muted small"
                            onclick="event.preventDefault(); document.getElementById('resendForm').submit()">
                            {{ __('participant.resend') }}
                        </a>
                    </div>

                    <form id="resendForm" method="POST" action="{{ route('participant.login.post') }}" class="d-none">
                        @csrf
                        <input type="hidden" name="mobile" value="{{ $mobile }}">
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
