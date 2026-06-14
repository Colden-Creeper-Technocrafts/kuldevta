@extends('layouts.admin')
@section('title', __('events.events'))
@section('page-title', __('events.events'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ __('events.events') }}</h4>
    <a href="{{ route('admin.events.create') }}" class="btn btn-sm" style="background:#FF6B00; color:#fff">
        <i class="bi bi-plus-lg me-1"></i>{{ __('app.add_new') }}
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($events->isEmpty())
            <p class="text-muted text-center py-5">
                {{ __('events.no_events_yet') }}
                <a href="{{ route('admin.events.create') }}">{{ __('events.add_first') }}</a>
            </p>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('events.event_type') }}</th>
                        <th>{{ __('app.name') }} ({{ __('app.en') }})</th>
                        <th>{{ __('app.name') }} ({{ __('app.gu') }})</th>
                        <th>{{ __('events.event_date') }}</th>
                        <th>{{ __('events.venue') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td><span class="badge {{ $event->typeBadgeClass() }}">{{ __("events.{$event->event_type}") }}</span></td>
                        <td>
                            {{ $event->title_en }}
                            @if($event->is_featured) <i class="bi bi-star-fill text-warning"></i> @endif
                        </td>
                        <td class="text-muted">{{ $event->title_gu }}</td>
                        <td>{{ $event->event_date->format('d M Y') }}</td>
                        <td class="text-muted small">{{ $event->venue_en ?? '—' }}</td>
                        <td>
                            <span class="badge
                                @if($event->status === 'upcoming') bg-primary
                                @elseif($event->status === 'ongoing') bg-success
                                @elseif($event->status === 'completed') bg-secondary
                                @else bg-danger @endif">
                                {{ __('app.' . $event->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                @if($event->event_type === 'sangh' && $event->sangh)
                                    <a href="{{ route('admin.sangh.show', $event->sangh) }}"
                                       class="btn btn-sm btn-success"
                                       title="{{ __('events.manage_sangh') }}">
                                        <i class="bi bi-people-fill me-1"></i>{{ __('events.manage_sangh') }}
                                    </a>
                                @endif
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                    onsubmit="return confirm('{{ __('events.delete_event') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $events->links() }}</div>
        @endif
    </div>
</div>
@endsection
