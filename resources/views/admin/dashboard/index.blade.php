@extends('layouts.admin')
@section('title', __('app.dashboard'))
@section('page-title', __('app.dashboard'))

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <p class="text-muted small mb-1">{{ __('sangh.total_registered') }}</p>
            <h3 class="mb-0 fw-bold">{{ $stats['registered'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3" style="border-left-color:#198754">
            <p class="text-muted small mb-1">{{ __('sangh.total_confirmed') }}</p>
            <h3 class="mb-0 fw-bold text-success">{{ $stats['confirmed'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3" style="border-left-color:#0d6efd">
            <p class="text-muted small mb-1">{{ __('sangh.total_completed') }}</p>
            <h3 class="mb-0 fw-bold text-primary">{{ $stats['completed'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3" style="border-left-color:#ffc107">
            <p class="text-muted small mb-1">{{ __('events.events') }}</p>
            <h3 class="mb-0 fw-bold">{{ $stats['total_events'] }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Active Sangh --}}
    <div class="col-lg-5">
        @if($activeSangh)
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ __('sangh.sangh') }}</strong>
                <span class="badge bg-success">{{ __('app.' . ($activeSangh->status === 'registration_open' ? 'upcoming' : $activeSangh->status)) }}</span>
            </div>
            <div class="card-body">
                <h5>{{ $activeSangh->title() }}</h5>
                <table class="table table-sm table-borderless mb-3">
                    <tr><td class="text-muted">{{ __('sangh.start_date') }}</td><td>{{ $activeSangh->startDate()?->format('d M Y') ?? '—' }}</td></tr>
                    <tr><td class="text-muted">{{ __('sangh.end_date') }}</td><td>{{ $activeSangh->end_date?->format('d M Y') ?? '—' }}</td></tr>
                    <tr><td class="text-muted">{{ __('sangh.distance') }}</td><td>{{ $activeSangh->total_distance_km }} km</td></tr>
                    <tr><td class="text-muted">{{ __('sangh.stoppages') }}</td><td>{{ $activeSangh->stoppages->count() }}</td></tr>
                </table>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.sangh.participants', $activeSangh) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-people me-1"></i>{{ __('sangh.registrations') }}
                    </a>
                    <a href="{{ route('admin.sangh.stoppages', $activeSangh) }}" class="btn btn-sm btn-outline-secondary">
                        {{ __('sangh.stoppages') }}
                    </a>
                    <a href="{{ route('admin.sangh.report', $activeSangh) }}" class="btn btn-sm btn-outline-secondary">
                        {{ __('app.report') }}
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="card h-100 text-center p-4">
            <i class="bi bi-people display-3 text-muted mb-3"></i>
            <p class="text-muted">{{ __('sangh.no_sangh') }} <a href="{{ route('admin.sangh.create') }}">{{ __('sangh.create_first') }}</a></p>
        </div>
        @endif
    </div>

    {{-- Recent Registrations --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ __('sangh.registrations') }} (Recent)</strong>
                @if($activeSangh)
                    <a href="{{ route('admin.sangh.participants', $activeSangh) }}" class="btn btn-sm btn-outline-primary">
                        {{ __('app.view') }} {{ __('app.all') }}
                    </a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($recentRegistrations->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.mobile') }}</th>
                                <th>{{ __('app.token') }}</th>
                                <th>{{ __('app.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRegistrations as $reg)
                            <tr>
                                <td>{{ $reg->name }}</td>
                                <td>{{ $reg->mobile }}</td>
                                <td><code>{{ $reg->token }}</code></td>
                                <td><span class="badge {{ $reg->statusBadgeClass() }}">{{ __('app.' . $reg->status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted text-center py-4">{{ __('sangh.no_participants') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
