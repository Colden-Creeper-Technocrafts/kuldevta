@extends('layouts.admin')
@section('title', __('sangh.manage'))
@section('page-title', __('sangh.manage'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ __('sangh.manage') }}</h4>
    <a href="{{ route('admin.sangh.create') }}" class="btn btn-sm" style="background:#FF6B00; color:#fff">
        <i class="bi bi-plus-lg me-1"></i>{{ __('sangh.create') }}
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($sanghs->isEmpty())
            <p class="text-muted text-center py-5">
                {{ __('sangh.no_sangh') }}
                <a href="{{ route('admin.sangh.create') }}">{{ __('sangh.create_first') }}</a>
            </p>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('app.year') }}</th>
                        <th>{{ __('app.name') }} ({{ __('app.en') }})</th>
                        <th>{{ __('app.name') }} ({{ __('app.gu') }})</th>
                        <th>{{ __('sangh.start_date') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('sangh.registrations') }}</th>
                        <th>{{ __('app.event') }}</th>
                        <th>{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sanghs as $sangh)
                    <tr>
                        <td><strong>{{ $sangh->year }}</strong></td>
                        <td>{{ $sangh->event?->title_en ?? '—' }}</td>
                        <td>{{ $sangh->event?->title_gu ?? '—' }}</td>
                        <td>{{ $sangh->startDate()?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <span class="badge
                                @if($sangh->status === 'registration_open') bg-success
                                @elseif($sangh->status === 'in_progress') bg-warning text-dark
                                @elseif($sangh->status === 'completed') bg-primary
                                @else bg-secondary @endif">
                                {{ ucfirst(str_replace('_', ' ', $sangh->status)) }}
                            </span>
                        </td>
                        <td>{{ $sangh->registeredCount() }}</td>
                        <td>
                            @if($sangh->event)
                                <span class="badge bg-success" title="Event #{{ $sangh->event_id }}">
                                    <i class="bi bi-link-45deg"></i> #{{ $sangh->event_id }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark" title="No event linked">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-outline-primary" title="{{ __('app.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.sangh.edit', $sangh) }}" class="btn btn-outline-secondary" title="{{ __('app.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.sangh.participants', $sangh) }}" class="btn btn-outline-info" title="{{ __('sangh.participants') }}">
                                    <i class="bi bi-people"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
