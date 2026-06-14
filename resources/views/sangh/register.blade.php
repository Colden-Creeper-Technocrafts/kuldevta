@extends('layouts.app')
@section('title', __('sangh.register'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            @if(!$sangh || !$sangh->isRegistrationOpen())
                <div class="card text-center p-5">
                    <i class="bi bi-calendar-x display-3 text-muted mb-3"></i>
                    <h4>{{ __('sangh.registration_closed') }}</h4>
                    <a href="{{ route('sangh.status') }}" class="btn btn-outline-secondary mt-2">
                        {{ __('sangh.check_status') }}
                    </a>
                </div>
            @else

            <div class="card">
                <div class="card-header py-3" style="background: #8B0000; color:#fff">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>{{ __('sangh.register') }}</h4>
                    <p class="mb-0 small opacity-75">{{ $sangh->title() }} — {{ $sangh->startDate()?->format('d M Y') }}</p>
                </div>
                <div class="card-body p-4">

                    {{-- Info box about multiple registrations --}}
                    <div class="alert alert-info py-2 small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ __('sangh.family_info') }}
                    </div>

                    <form method="POST" action="{{ route('sangh.register.store') }}">
                        @csrf

                        <h6 class="text-muted mb-3 border-bottom pb-2">{{ __('app.details') }}</h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('app.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">
                                    {{ __('app.mobile') }} <span class="text-danger">*</span>
                                    <small class="text-muted fw-normal">({{ __('sangh.mobile_hint') }})</small>
                                </label>
                                <input type="tel" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}" maxlength="10" required>
                                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label fw-semibold">{{ __('app.age') }}</label>
                                <input type="number" name="age" class="form-control @error('age') is-invalid @enderror"
                                    value="{{ old('age') }}" min="1" max="100">
                                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label fw-semibold">{{ __('app.gender') }} <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>{{ __('app.male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
                                    <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>{{ __('app.other') }}</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('app.village') }}</label>
                            <input type="text" name="village" class="form-control" value="{{ old('village') }}">
                        </div>

                        <h6 class="text-muted mb-3 border-bottom pb-2 mt-4">{{ __('sangh.emergency_contact') }}</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">{{ __('sangh.emergency_name') }}</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">{{ __('sangh.emergency_mobile') }}</label>
                                <input type="tel" name="emergency_contact_mobile" class="form-control" value="{{ old('emergency_contact_mobile') }}" maxlength="10">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-saffron btn-lg fw-bold">
                                <i class="bi bi-check-circle me-1"></i> {{ __('app.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @endif

            <div class="text-center mt-3">
                <a href="{{ route('sangh.status') }}" class="text-muted small">
                    {{ __('sangh.check_status') }} &rarr;
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
