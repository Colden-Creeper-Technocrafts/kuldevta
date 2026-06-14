@extends('layouts.app')
@section('title', __('events.events'))

@section('content')
<div class="container py-5">
    <h1 class="section-title mb-5">{{ __('events.events') }}</h1>

    @if($upcoming->count())
    <section class="mb-5">
        <h3 class="mb-3 text-muted fw-normal">{{ __('events.upcoming') }}</h3>
        <div class="row g-3">
            @foreach($upcoming as $event)
            <div class="col-md-6">
                <div class="card card-event h-100" style="border-left-color: #FF6B00">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge {{ $event->typeBadgeClass() }} mb-2">{{ __('events.' . $event->event_type) }}</span>
                                <h5 class="card-title mb-1">{{ $event->title() }}</h5>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $event->event_date->format('d M Y') }}
                                    @if($event->event_time) &nbsp; <i class="bi bi-clock me-1"></i>{{ substr($event->event_time, 0, 5) }} @endif
                                </p>
                                @if($event->venue())
                                    <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue() }}</p>
                                @endif
                                @if($event->description())
                                    <p class="text-muted small mb-0">{{ $event->description() }}</p>
                                @endif
                            </div>
                            @if($event->is_featured)
                                <span class="badge bg-warning text-dark ms-2"><i class="bi bi-star-fill"></i></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($past->count())
    <section>
        <h3 class="mb-3 text-muted fw-normal">{{ __('events.past') }}</h3>
        <div class="row g-3">
            @foreach($past as $event)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100" style="opacity:.75">
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

    @if(!$upcoming->count() && !$past->count())
        <p class="text-muted text-center py-5">{{ __('events.no_events') }}</p>
    @endif
</div>
@endsection
