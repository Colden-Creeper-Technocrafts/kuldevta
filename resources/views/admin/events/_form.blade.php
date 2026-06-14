<div class="row g-3">
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('app.title') }} ({{ __('app.en') }}) <span class="text-danger">*</span></label>
        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
            value="{{ old('title_en', $event->title_en ?? '') }}" required>
        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('app.title') }} ({{ __('app.gu') }}) <span class="text-danger">*</span></label>
        <input type="text" name="title_gu" class="form-control @error('title_gu') is-invalid @enderror"
            value="{{ old('title_gu', $event->title_gu ?? '') }}" required>
        @error('title_gu')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('events.event_type') }} <span class="text-danger">*</span></label>
        <select name="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
            @foreach(['havan','monthly_havan','sangh','special'] as $t)
                <option value="{{ $t }}" {{ old('event_type', $event->event_type ?? '') == $t ? 'selected' : '' }}>
                    {{ __('events.' . $t) }}
                </option>
            @endforeach
        </select>
        @error('event_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('events.event_date') }} <span class="text-danger">*</span></label>
        <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror"
            value="{{ old('event_date', isset($event) ? $event->event_date?->format('Y-m-d') : '') }}" required>
        @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('events.event_time') }}</label>
        <input type="time" name="event_time" class="form-control"
            value="{{ old('event_time', isset($event) ? substr($event->event_time ?? '', 0, 5) : '') }}">
    </div>

    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('events.venue') }} ({{ __('app.en') }})</label>
        <input type="text" name="venue_en" class="form-control" value="{{ old('venue_en', $event->venue_en ?? '') }}">
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('events.venue') }} ({{ __('app.gu') }})</label>
        <input type="text" name="venue_gu" class="form-control" value="{{ old('venue_gu', $event->venue_gu ?? '') }}">
    </div>

    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('app.description') }} ({{ __('app.en') }})</label>
        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $event->description_en ?? '') }}</textarea>
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('app.description') }} ({{ __('app.gu') }})</label>
        <textarea name="description_gu" class="form-control" rows="3">{{ old('description_gu', $event->description_gu ?? '') }}</textarea>
    </div>

    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('app.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(['upcoming','ongoing','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ old('status', $event->status ?? 'upcoming') == $s ? 'selected' : '' }}>
                    {{ __('app.' . $s) }}
                </option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold d-block">{{ __('events.featured') }}</label>
        <div class="form-check form-switch mt-1">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                {{ old('is_featured', isset($event) && $event->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">{{ __('events.show_on_home') }}</label>
        </div>
    </div>
</div>
