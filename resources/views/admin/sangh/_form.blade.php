@php
    $ev        = $sangh->event ?? null;
    $isEditing = $sangh->exists;
@endphp

{{-- ── Event Details ───────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
    <h6 class="text-uppercase text-muted fw-semibold small mb-0">
        <i class="bi bi-calendar-event me-1"></i> {{ __('sangh.event_details') }}
    </h6>
    @if($isEditing && $ev)
        <a href="{{ route('admin.events.edit', $ev) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="bi bi-pencil me-1"></i>{{ __('sangh.edit_event') }}
        </a>
    @endif
</div>

@if($isEditing)
    {{-- Read-only display on edit --}}
    <div class="card bg-light border-0 mb-4">
        <div class="card-body py-3">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="text-muted small mb-1">{{ __('app.title') }} ({{ __('app.en') }})</div>
                    <div class="fw-semibold">{{ $ev->title_en ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small mb-1">{{ __('app.title') }} ({{ __('app.gu') }})</div>
                    <div class="fw-semibold">{{ $ev->title_gu ?? '—' }}</div>
                </div>
                <div class="col-sm-3">
                    <div class="text-muted small mb-1">{{ __('sangh.start_date') }}</div>
                    <div>{{ $ev?->event_date?->format('d M Y') ?? '—' }}</div>
                </div>
                <div class="col-sm-3">
                    <div class="text-muted small mb-1">{{ __('sangh.start_time') }}</div>
                    <div>{{ $ev ? substr($ev->event_time ?? '05:00', 0, 5) : '—' }}</div>
                </div>
                <div class="col-sm-3">
                    <div class="text-muted small mb-1">{{ __('events.venue') }} ({{ __('app.en') }})</div>
                    <div>{{ $ev->venue_en ?? '—' }}</div>
                </div>
                <div class="col-sm-3">
                    <div class="text-muted small mb-1">{{ __('events.venue') }} ({{ __('app.gu') }})</div>
                    <div>{{ $ev->venue_gu ?? '—' }}</div>
                </div>
                @if($ev?->description_en)
                <div class="col-sm-6">
                    <div class="text-muted small mb-1">{{ __('app.description') }} ({{ __('app.en') }})</div>
                    <div class="small">{{ $ev->description_en }}</div>
                </div>
                @endif
                @if($ev?->description_gu)
                <div class="col-sm-6">
                    <div class="text-muted small mb-1">{{ __('app.description') }} ({{ __('app.gu') }})</div>
                    <div class="small">{{ $ev->description_gu }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
@else
    {{-- Editable on create --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('app.title') }} ({{ __('app.en') }}) <span class="text-danger">*</span></label>
            <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                value="{{ old('title_en', $ev->title_en ?? '') }}" required>
            @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('app.title') }} ({{ __('app.gu') }}) <span class="text-danger">*</span></label>
            <input type="text" name="title_gu" class="form-control @error('title_gu') is-invalid @enderror"
                value="{{ old('title_gu', $ev->title_gu ?? '') }}" required>
            @error('title_gu')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label fw-semibold">{{ __('sangh.start_date') }} <span class="text-danger">*</span></label>
            <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror"
                value="{{ old('event_date', $ev?->event_date?->format('Y-m-d') ?? '') }}" required>
            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-4">
            <label class="form-label fw-semibold">{{ __('sangh.start_time') }}</label>
            <input type="time" name="event_time" class="form-control"
                value="{{ old('event_time', substr($ev->event_time ?? '05:00', 0, 5)) }}">
        </div>
        <div class="col-sm-4">
            <label class="form-label fw-semibold">{{ __('events.venue') }} ({{ __('app.en') }})</label>
            <input type="text" name="venue_en" class="form-control"
                value="{{ old('venue_en', $ev->venue_en ?? '') }}" placeholder="e.g. Village to Temple">
        </div>

        <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('events.venue') }} ({{ __('app.gu') }})</label>
            <input type="text" name="venue_gu" class="form-control"
                value="{{ old('venue_gu', $ev->venue_gu ?? '') }}">
        </div>

        <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('app.description') }} ({{ __('app.en') }})</label>
            <textarea name="description_en" class="form-control" rows="2">{{ old('description_en', $ev->description_en ?? '') }}</textarea>
        </div>
        <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('app.description') }} ({{ __('app.gu') }})</label>
            <textarea name="description_gu" class="form-control" rows="2">{{ old('description_gu', $ev->description_gu ?? '') }}</textarea>
        </div>
    </div>
@endif

{{-- ── Sangh-only Fields ──────────────────────────────────── --}}
<h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
    <i class="bi bi-people-fill me-1"></i> {{ __('sangh.sangh_details') }}
</h6>

<div class="row g-3">
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('sangh.end_date') }} <span class="text-danger">*</span></label>
        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date', $sangh->end_date?->format('Y-m-d') ?? '') }}" required>
        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('sangh.distance') }} (km) <span class="text-danger">*</span></label>
        <input type="number" name="total_distance_km" class="form-control @error('total_distance_km') is-invalid @enderror"
            value="{{ old('total_distance_km', $sangh->total_distance_km ?? 35) }}" min="1" required>
        @error('total_distance_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('app.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(['draft','registration_open','registration_closed','in_progress','completed'] as $s)
                <option value="{{ $s }}" {{ old('status', $sangh->status ?? 'draft') == $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                </option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('sangh.registration_open_from') }}</label>
        <input type="date" name="registration_open_from" class="form-control"
            value="{{ old('registration_open_from', $sangh->registration_open_from?->format('Y-m-d') ?? '') }}">
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('sangh.registration_open_until') }}</label>
        <input type="date" name="registration_open_until" class="form-control"
            value="{{ old('registration_open_until', $sangh->registration_open_until?->format('Y-m-d') ?? '') }}">
    </div>

    <div class="col-12"><hr class="my-1"></div>

    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('sangh.source') }} ({{ __('app.en') }})</label>
        <input type="text" name="source_en" class="form-control"
            value="{{ old('source_en', $sangh->source_en ?? '') }}"
            placeholder="e.g. Rampur Village">
        <div class="form-text">{{ __('sangh.source_hint') }}</div>
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('sangh.source') }} ({{ __('app.gu') }})</label>
        <input type="text" name="source_gu" class="form-control"
            value="{{ old('source_gu', $sangh->source_gu ?? '') }}"
            placeholder="દા.ત. રામપુર ગામ">
    </div>
    <div class="col-12">
        <div class="alert alert-light border small py-2 mb-0">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
            {{ __('sangh.destination_note') }}
        </div>
    </div>
</div>
