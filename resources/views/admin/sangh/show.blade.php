@extends('layouts.admin')
@section('title', $sangh->title())
@section('page-title', $sangh->title() . ' (' . $sangh->year . ')')

@section('content')
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.sangh.edit', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-pencil me-1"></i>{{ __('app.edit') }}
    </a>
    <a href="{{ route('admin.sangh.participants', $sangh) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-people me-1"></i>{{ __('sangh.participants') }}
    </a>
    <a href="{{ route('admin.sangh.stoppages', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-geo-alt me-1"></i>{{ __('sangh.stoppages') }}
    </a>
    <a href="{{ route('admin.sangh.volunteers', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-person-badge me-1"></i>{{ __('sangh.volunteers') }}
    </a>
    <a href="{{ route('admin.sangh.report', $sangh) }}" class="btn btn-sm btn-outline-info">
        <i class="bi bi-bar-chart me-1"></i>{{ __('app.report') }}
    </a>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><strong>{{ __('app.details') }}</strong></div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">{{ __('app.year') }}</td><td><strong>{{ $sangh->year }}</strong></td></tr>
                    <tr><td class="text-muted">{{ __('app.status') }}</td>
                        <td>
                            <span class="badge
                                @if($sangh->status === 'registration_open') bg-success
                                @elseif($sangh->status === 'in_progress') bg-warning text-dark
                                @elseif($sangh->status === 'completed') bg-primary
                                @elseif($sangh->status === 'draft') bg-secondary
                                @else bg-info text-dark @endif">
                                {{ ucfirst(str_replace('_', ' ', $sangh->status)) }}
                            </span>
                        </td></tr>
                    @if($sangh->event)
                    <tr>
                        <td class="text-muted">{{ __('app.event') }}</td>
                        <td>
                            <a href="{{ route('admin.events.edit', $sangh->event) }}" class="text-decoration-none small">
                                <i class="bi bi-calendar-event me-1"></i>#{{ $sangh->event_id }} — {{ $sangh->event->title_en }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr><td class="text-muted">{{ __('sangh.start_date') }}</td><td>{{ $sangh->startDate()?->format('d M Y') ?? '—' }}</td></tr>
                    <tr><td class="text-muted">{{ __('sangh.end_date') }}</td><td>{{ $sangh->end_date?->format('d M Y') ?? '—' }}</td></tr>
                    <tr><td class="text-muted">{{ __('sangh.start_time') }}</td><td>{{ substr($sangh->startTime(), 0, 5) }}</td></tr>
                    <tr><td class="text-muted">{{ __('sangh.distance') }}</td><td>{{ $sangh->total_distance_km }} km</td></tr>
                    <tr>
                        <td class="text-muted">{{ __('app.route') }}</td>
                        <td>
                            <span class="text-success fw-semibold">{{ $sangh->source_en ?: '—' }}</span>
                            <i class="bi bi-arrow-right mx-1 text-muted"></i>
                            <span class="text-danger fw-semibold">{{ __('sangh.destination') }}</span>
                            @if($sangh->source_gu)
                                <div class="text-muted small">{{ $sangh->source_gu }} <i class="bi bi-arrow-right mx-1"></i> {{ __('app.temple_name') }}</div>
                            @endif
                        </td>
                    </tr>
                    @if($sangh->registration_open_from)
                    <tr><td class="text-muted">{{ __('sangh.reg_opens') }}</td><td>{{ $sangh->registration_open_from->format('d M Y') }}</td></tr>
                    @endif
                    @if($sangh->registration_open_until)
                    <tr><td class="text-muted">{{ __('sangh.reg_closes') }}</td><td>{{ $sangh->registration_open_until->format('d M Y') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="row g-3">
            <div class="col-6">
                <div class="card stat-card text-center p-3">
                    <div class="text-muted small">{{ __('sangh.total_registered') }}</div>
                    <div class="fs-2 fw-bold">{{ $stats['registered'] }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card stat-card p-3 text-center" style="border-left-color:#198754">
                    <div class="text-muted small">{{ __('sangh.total_confirmed') }}</div>
                    <div class="fs-2 fw-bold text-success">{{ $stats['confirmed'] }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card stat-card p-3 text-center" style="border-left-color:#0d6efd">
                    <div class="text-muted small">{{ __('sangh.total_completed') }}</div>
                    <div class="fs-2 fw-bold text-primary">{{ $stats['completed'] }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card stat-card p-3 text-center" style="border-left-color:#dc3545">
                    <div class="text-muted small">{{ __('sangh.total_dropped') }}</div>
                    <div class="fs-2 fw-bold text-danger">{{ $stats['dropped'] }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><strong>{{ __('sangh.stoppages') }}</strong></div>
            <div class="card-body p-0">
                @forelse($sangh->stoppages as $stop)
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <span class="badge bg-secondary me-2">{{ $stop->km_marker }} km</span>
                    <div>
                        <strong>{{ $stop->name_en }}</strong> / {{ $stop->name_gu }}
                        <div class="mt-1">
                            @if($stop->has_water) <span class="badge bg-info text-dark me-1">{{ __('sangh.water') }}</span> @endif
                            @if($stop->has_tea) <span class="badge bg-warning text-dark me-1">{{ __('sangh.tea') }}</span> @endif
                            @if($stop->has_food) <span class="badge bg-success me-1">{{ __('sangh.food') }}</span> @endif
                            @if($stop->has_medical) <span class="badge bg-danger me-1">{{ __('sangh.medical') }}</span> @endif
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center py-3 mb-0">
                        {{ __('sangh.no_stoppages') }}
                        <a href="{{ route('admin.sangh.stoppages', $sangh) }}">{{ __('sangh.add_stoppage') }}</a>
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
