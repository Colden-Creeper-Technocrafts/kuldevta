@extends('layouts.admin')
@section('title', __('sangh.report'))
@section('page-title', $sangh->title() . ' — ' . __('sangh.report'))

@section('content')
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
    </a>
</div>

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3 text-center">
            <div class="text-muted small">{{ __('sangh.total_registered') }}</div>
            <div class="fs-2 fw-bold">{{ $stats['registered'] }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3 text-center" style="border-left-color:#198754">
            <div class="text-muted small">{{ __('sangh.total_confirmed') }}</div>
            <div class="fs-2 fw-bold text-success">{{ $stats['confirmed'] }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3 text-center" style="border-left-color:#0d6efd">
            <div class="text-muted small">{{ __('sangh.total_completed') }}</div>
            <div class="fs-2 fw-bold text-primary">{{ $stats['completed'] }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3 text-center" style="border-left-color:#dc3545">
            <div class="text-muted small">{{ __('sangh.total_dropped') }}</div>
            <div class="fs-2 fw-bold text-danger">{{ $stats['dropped'] }}</div>
        </div>
    </div>
</div>

{{-- Stoppage Summary --}}
<div class="card mb-4">
    <div class="card-header"><strong>{{ __('sangh.stoppages') }} — {{ __('sangh.service_log') }}</strong></div>
    <div class="card-body p-0">
        @if($stoppageSummary->isEmpty())
            <p class="text-muted text-center py-4">No stoppages recorded.</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('sangh.stoppage') }}</th>
                        <th>KM</th>
                        <th class="text-center text-info">{{ __('sangh.water') }}</th>
                        <th class="text-center text-warning">{{ __('sangh.tea') }}</th>
                        <th class="text-center text-success">{{ __('sangh.food') }}</th>
                        <th class="text-center text-danger">{{ __('sangh.medical') }}</th>
                        <th class="text-center text-secondary">{{ __('sangh.rest') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stoppageSummary as $row)
                    <tr>
                        <td><strong>{{ $row['stoppage']->name_en }}</strong><br><small class="text-muted">{{ $row['stoppage']->name_gu }}</small></td>
                        <td>{{ $row['stoppage']->km_marker }}</td>
                        <td class="text-center">{{ $row['water'] ?: '—' }}</td>
                        <td class="text-center">{{ $row['tea'] ?: '—' }}</td>
                        <td class="text-center">{{ $row['food'] ?: '—' }}</td>
                        <td class="text-center">{{ $row['medical'] ?: '—' }}</td>
                        <td class="text-center">{{ $row['rest'] ?: '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Volunteer Summary --}}
<div class="card">
    <div class="card-header"><strong>{{ __('sangh.volunteers') }}</strong> ({{ $sangh->volunteers->count() }})</div>
    <div class="card-body p-0">
        @if($sangh->volunteers->isEmpty())
            <p class="text-muted text-center py-3">No volunteers.</p>
        @else
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('app.name') }}</th>
                    <th>{{ __('app.mobile') }}</th>
                    <th>{{ __('sangh.role') }}</th>
                    <th>{{ __('sangh.assigned_to_stoppage') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sangh->volunteers as $vol)
                <tr>
                    <td>{{ $vol->name }}</td>
                    <td>{{ $vol->mobile }}</td>
                    <td>{{ __('sangh.role_' . $vol->role) }}</td>
                    <td>{{ optional($vol->assignedStoppage)->name_en ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
