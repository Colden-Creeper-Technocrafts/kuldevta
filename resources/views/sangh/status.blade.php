@extends('layouts.app')
@section('title', __('sangh.check_status'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header py-3" style="background: #8B0000; color:#fff">
                    <h4 class="mb-0"><i class="bi bi-search me-2"></i>{{ __('sangh.check_status') }}</h4>
                </div>
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">{{ __('sangh.enter_mobile') }}</p>

                    <form method="GET" action="{{ route('sangh.status') }}">
                        <div class="input-group mb-4">
                            <input type="tel" name="mobile" class="form-control form-control-lg"
                                placeholder="{{ __('app.mobile') }}" maxlength="10"
                                value="{{ request('mobile') }}" required>
                            <button class="btn btn-saffron btn-lg" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    @if(request()->filled('mobile') && $registrations->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ __('sangh.not_found') }}
                        </div>
                        @if($sangh && $sangh->isRegistrationOpen())
                            <div class="text-center">
                                <a href="{{ route('sangh.register') }}?mobile={{ request('mobile') }}"
                                   class="btn btn-saffron">
                                    <i class="bi bi-pencil-square me-1"></i>{{ __('sangh.register') }}
                                </a>
                            </div>
                        @endif
                    @endif

                    @if($registrations->isNotEmpty())
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-muted">
                            {{ $registrations->count() }} {{ __('sangh.member_registered') }}
                        </h6>
                        @if($sangh && $sangh->isRegistrationOpen())
                            <a href="{{ route('sangh.register') }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-person-plus me-1"></i>{{ __('sangh.add_member') }}
                            </a>
                        @endif
                    </div>

                    @foreach($registrations as $reg)
                    <div class="card mb-2 border-0 bg-light">
                        <div class="card-body py-3 px-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <strong>{{ $reg->name }}</strong>
                                        @if(is_null($reg->group_leader_id))
                                            <span class="badge bg-secondary" style="font-size:.65rem">
                                                <i class="bi bi-person-fill me-1"></i>{{ __('sangh.primary') }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border" style="font-size:.65rem">
                                                <i class="bi bi-person me-1"></i>{{ __('sangh.member') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">
                                        @if($reg->age) {{ $reg->age }} {{ __('app.age') }} &bull; @endif
                                        {{ __('app.' . $reg->gender) }}
                                        @if($reg->village) &bull; {{ $reg->village }} @endif
                                    </div>
                                </div>
                                <span class="badge {{ $reg->statusBadgeClass() }}">
                                    {{ __('app.' . $reg->status) }}
                                </span>
                            </div>
                            <div class="row g-2 mt-2 small">
                                <div class="col-6">
                                    <span class="text-muted">{{ __('app.token') }}:</span>
                                    <strong class="ms-1 font-monospace">{{ $reg->token }}</strong>
                                </div>
                                @if($reg->confirmed_at)
                                <div class="col-6">
                                    <span class="text-muted">{{ __('sangh.confirmed_at') }}:</span>
                                    <strong class="ms-1">{{ $reg->confirmed_at->format('d M H:i') }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('sangh.register') }}" class="text-muted small">
                    {{ __('sangh.register') }} &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
