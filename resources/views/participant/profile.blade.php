@extends('layouts.app')
@section('title', __('participant.my_registrations'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0" style="color:#8B0000">
                        <i class="bi bi-person-badge me-2"></i>{{ __('participant.my_registrations') }}
                    </h4>
                    <span class="text-muted small"><i class="bi bi-phone me-1"></i>{{ $mobile }}</span>
                </div>
                <form method="POST" action="{{ route('participant.logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-box-arrow-right me-1"></i>{{ __('participant.logout') }}
                    </button>
                </form>
            </div>

            @if($registrations->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ __('participant.no_registrations') }}
                </div>
            @else
                @foreach($registrations as $primary)
                {{-- Sangh label --}}
                <div class="mb-1">
                    <span class="text-muted small fw-semibold text-uppercase">
                        <i class="bi bi-signpost me-1"></i>{{ $primary->sangh->title() }}
                        &nbsp;&bull;&nbsp;
                        {{ $primary->sangh->startDate()?->format('d M Y') }}
                        @if($primary->sangh->event)
                            &nbsp;&bull;&nbsp;{{ $primary->sangh->event->venue() ?? '' }}
                        @endif
                    </span>
                </div>

                {{-- Primary member card --}}
                <div class="card mb-4 border" style="border-left: 4px solid #8B0000 !important">
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
</div>
@endsection
