@extends('layouts.app')
@section('title', __('sangh.check_status'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow-sm">
                <div class="card-header py-3" style="background:#8B0000; color:#fff">
                    <h4 class="mb-0"><i class="bi bi-search me-2"></i>{{ __('sangh.check_status') }}</h4>
                    @if($sangh)
                        <p class="mb-0 small opacity-75">{{ $sangh->title() }} — {{ $sangh->startDate()?->format('d M Y') }}</p>
                    @endif
                </div>
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <p class="text-muted mb-3">{{ __('sangh.enter_mobile') }}</p>

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

                    {{-- Not found --}}
                    @if(request()->filled('mobile') && $registrations->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ __('sangh.not_found') }}
                        </div>
                        @if($sangh && $sangh->isRegistrationOpen())
                            <div class="text-center">
                                <a href="{{ route('sangh.register') }}" class="btn btn-saffron">
                                    <i class="bi bi-pencil-square me-1"></i>{{ __('sangh.register') }}
                                </a>
                            </div>
                        @endif
                    @endif

                    {{-- Results --}}
                    @if($registrations->isNotEmpty())
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small fw-semibold">
                                <i class="bi bi-people me-1"></i>
                                {{ $totalCount }} {{ __('sangh.member_registered') }}
                            </span>
                            @if($sangh && $sangh->isRegistrationOpen())
                                <a href="{{ route('sangh.register') }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-person-plus me-1"></i>{{ __('sangh.add_member') }}
                                </a>
                            @endif
                        </div>

                        @foreach($registrations as $primary)
                        {{-- Primary member card --}}
                        <div class="card mb-3 border" style="border-left: 4px solid #8B0000 !important">
                            <div class="card-body py-3 px-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="bi bi-person-fill text-muted"></i>
                                            <strong>{{ $primary->name }}</strong>
                                            <span class="badge bg-dark" style="font-size:.65rem">{{ __('sangh.primary') }}</span>
                                        </div>
                                        <div class="small text-muted">
                                            @if($primary->age) {{ $primary->age }} {{ __('app.age') }} &bull; @endif
                                            {{ __('app.' . $primary->gender) }}
                                            @if($primary->village) &bull; {{ $primary->village }} @endif
                                            @if($primary->mobile) &bull; <i class="bi bi-phone"></i> {{ $primary->mobile }} @endif
                                        </div>
                                    </div>
                                    <span class="badge {{ $primary->statusBadgeClass() }} fs-6 px-3">
                                        {{ __('app.' . $primary->status) }}
                                    </span>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mt-2 small">
                                    <div>
                                        <span class="text-muted">{{ __('app.token') }}:</span>
                                        <code class="ms-1 fw-bold">{{ $primary->token }}</code>
                                    </div>
                                    @if($primary->confirmed_at)
                                    <div class="text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        {{ __('sangh.confirmed_at') }}: {{ $primary->confirmed_at->format('d M, H:i') }}
                                    </div>
                                    @endif
                                    @if($primary->emergency_contact_name)
                                    <div class="text-muted">
                                        <i class="bi bi-telephone me-1"></i>
                                        {{ $primary->emergency_contact_name }}
                                        @if($primary->emergency_contact_mobile)
                                            ({{ $primary->emergency_contact_mobile }})
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                {{-- Group members --}}
                                @if($primary->groupMembers->isNotEmpty())
                                <div class="mt-3 border-top pt-3">
                                    <p class="small text-muted fw-semibold mb-2">
                                        <i class="bi bi-people me-1"></i>
                                        {{ $primary->groupMembers->count() }} {{ __('sangh.additional_members') }}
                                    </p>
                                    @foreach($primary->groupMembers as $member)
                                    <div class="d-flex justify-content-between align-items-center py-2
                                        {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="text-muted mt-1" style="font-size:.85rem">↳</span>
                                            <div>
                                                <span class="fw-semibold">{{ $member->name }}</span>
                                                <span class="badge bg-secondary ms-1" style="font-size:.6rem">{{ __('sangh.member') }}</span>
                                                <div class="small text-muted">
                                                    @if($member->age) {{ $member->age }} {{ __('app.age') }} &bull; @endif
                                                    {{ __('app.' . $member->gender) }}
                                                    @if($member->mobile && $member->mobile !== $primary->mobile)
                                                        &bull; <i class="bi bi-phone"></i> {{ $member->mobile }}
                                                    @endif
                                                    &bull; <code style="font-size:.75rem">{{ $member->token }}</code>
                                                    @if($member->confirmed_at)
                                                        &bull; <span class="text-success"><i class="bi bi-check-circle-fill"></i> {{ $member->confirmed_at->format('H:i') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge {{ $member->statusBadgeClass() }}">
                                            {{ __('app.' . $member->status) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

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
