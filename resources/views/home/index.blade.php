@extends('layouts.app')
@section('title', __('app.home'))

@section('content')

{{-- Hero --}}
<div class="hero-banner">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">{{ __('app.jai_shree') }}</h1>
        <p class="lead mb-4 opacity-90">{{ __('app.temple_name') }}</p>
        @if($activeSangh)
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                @if($activeSangh->status === 'registration_open')
                    <a href="{{ route('sangh.register') }}" class="btn btn-warning btn-lg fw-bold">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('sangh.register') }}
                    </a>
                @endif
                <a href="{{ route('sangh.status') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-search me-1"></i> {{ __('sangh.check_status') }}
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Active Sangh Banner --}}
@if($activeSangh)
<div class="bg-warning py-2">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <strong>{{ $activeSangh->title() }}</strong>
            <span class="ms-2 text-muted small">
                {{ $activeSangh->startDate()?->format('d M Y') }} &rarr; {{ $activeSangh->end_date?->format('d M Y') }}
                &bull; {{ $activeSangh->total_distance_km }} {{ __('sangh.km') }}
            </span>
        </div>
        <span class="badge bg-dark">
            @if($activeSangh->status === 'registration_open') {{ __('sangh.registration_open') }}
            @elseif($activeSangh->status === 'in_progress') {{ __('sangh.in_progress') }}
            @else {{ $activeSangh->status }}
            @endif
        </span>
    </div>
</div>
@endif

<div class="container py-5">

    {{-- Featured Events --}}
    @if($featuredEvents->count())
    <section class="mb-5">
        <h2 class="section-title mb-4">{{ __('events.featured') ?? __('events.events') }}</h2>
        <div class="row g-3">
            @foreach($featuredEvents as $event)
            <div class="col-md-4">
                <div class="card card-event h-100">
                    <div class="card-body">
                        <span class="badge {{ $event->typeBadgeClass() }} mb-2">{{ __('events.' . $event->event_type) }}</span>
                        <h5 class="card-title">{{ $event->title() }}</h5>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar3 me-1"></i>{{ $event->event_date->format('d M Y') }}
                            @if($event->event_time) &nbsp;<i class="bi bi-clock me-1"></i>{{ $event->event_time }} @endif
                        </p>
                        @if($event->venue())
                            <p class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue() }}</p>
                        @endif
                        @if($event->description())
                            <p class="card-text text-muted small">{{ Str::limit($event->description(), 80) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Upcoming Events --}}
    @if($upcomingEvents->count())
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">{{ __('events.upcoming') }}</h2>
            <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('app.view') }} {{ __('app.all') }}</a>
        </div>
        <div class="row g-3">
            @foreach($upcomingEvents as $event)
            <div class="col-md-6 col-lg-3">
                <div class="card card-event h-100">
                    <div class="card-body">
                        <span class="badge {{ $event->typeBadgeClass() }} mb-2">{{ __('events.' . $event->event_type) }}</span>
                        <h6 class="card-title">{{ $event->title() }}</h6>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar3 me-1"></i>{{ $event->event_date->format('d M Y') }}
                        </p>
                        @if($event->venue())
                            <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue() }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if(!$featuredEvents->count() && !$upcomingEvents->count() && !$activeSangh)
    <div class="text-center py-5 text-muted">
        <i class="bi bi-flower1 display-1" style="color:#FF6B00; opacity:.3"></i>
        <p class="mt-3">{{ __('app.jai_shree') }}</p>
    </div>
    @endif

</div>
@endsection
