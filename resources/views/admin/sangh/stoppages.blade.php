@extends('layouts.admin')
@section('title', __('sangh.stoppages'))
@section('page-title', $sangh->title() . ' — ' . __('sangh.stoppages'))

@section('content')
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
    </a>
</div>

<div class="row g-4">

    {{-- Add Stoppage form --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><strong><i class="bi bi-geo-alt me-1"></i>{{ __('sangh.add_stoppage') }}</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sangh.stoppages.store', $sangh) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name_en" class="form-control form-control-sm"
                            placeholder="{{ __('app.name') }} ({{ __('app.en') }}) *" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="name_gu" class="form-control form-control-sm"
                            placeholder="{{ __('app.name') }} ({{ __('app.gu') }}) *" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="address_en" class="form-control form-control-sm"
                            placeholder="{{ __('app.address') }} ({{ __('app.en') }})">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="address_gu" class="form-control form-control-sm"
                            placeholder="{{ __('app.address') }} ({{ __('app.gu') }})">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="number" name="km_marker" class="form-control form-control-sm"
                                placeholder="{{ __('sangh.km_marker') }} *" min="0" required>
                        </div>
                        <div class="col-6">
                            <input type="number" name="sort_order" class="form-control form-control-sm"
                                placeholder="{{ __('app.sort_order') }} *" min="0" value="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="form-label mb-1 small fw-semibold">{{ __('sangh.facilities') }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['water','food','tea','medical','rest'] as $f)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_{{ $f }}" id="has_{{ $f }}" value="1" {{ in_array($f,['water','tea','rest']) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="has_{{ $f }}">{{ __('sangh.' . $f) }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm w-100" style="background:#FF6B00; color:#fff">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('sangh.add_stoppage') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Stoppages List --}}
    <div class="col-lg-8">
        @forelse($stoppages as $stop)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <div>
                    <span class="badge bg-secondary me-2">{{ $stop->km_marker }} km</span>
                    <strong>{{ $stop->name_en }}</strong>
                    <span class="text-muted ms-2">/ {{ $stop->name_gu }}</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                        data-bs-target="#edit-{{ $stop->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.sangh.stoppages.destroy', [$sangh, $stop]) }}"
                        onsubmit="return confirm('{{ __('sangh.delete_stoppage') }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
            <div class="card-body py-2">
                @if($stop->address_en)
                    <p class="text-muted small mb-1"><i class="bi bi-geo me-1"></i>{{ $stop->address_en }}</p>
                @endif
                <div class="d-flex gap-1 flex-wrap mb-2">
                    @if($stop->has_water) <span class="badge bg-info text-dark">{{ __('sangh.water') }}</span> @endif
                    @if($stop->has_tea) <span class="badge bg-warning text-dark">{{ __('sangh.tea') }}</span> @endif
                    @if($stop->has_food) <span class="badge bg-success">{{ __('sangh.food') }}</span> @endif
                    @if($stop->has_medical) <span class="badge bg-danger">{{ __('sangh.medical') }}</span> @endif
                    @if($stop->has_rest) <span class="badge bg-secondary">{{ __('sangh.rest') }}</span> @endif
                </div>
                @if($stop->volunteers->count())
                    <p class="small text-muted mb-0">
                        <i class="bi bi-person me-1"></i>{{ $stop->volunteers->count() }} {{ __('sangh.volunteer_count') }}
                    </p>
                @endif

                {{-- Log Service --}}
                @if(in_array($sangh->status, ['in_progress']))
                <form method="POST" action="{{ route('admin.sangh.stoppages.log', [$sangh, $stop]) }}" class="mt-2 d-flex gap-2">
                    @csrf
                    <select name="service_type" class="form-select form-select-sm" style="max-width:120px">
                        @foreach(['water','food','tea','medical','rest'] as $s)
                            <option value="{{ $s }}">{{ __('sangh.' . $s) }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="count" class="form-control form-control-sm" value="1" min="1" style="max-width:70px">
                    <button class="btn btn-sm btn-success">{{ __('sangh.log_service') }}</button>
                </form>
                @endif
            </div>

            {{-- Edit Collapse --}}
            <div class="collapse" id="edit-{{ $stop->id }}">
                <div class="card-footer bg-light">
                    <form method="POST" action="{{ route('admin.sangh.stoppages.update', [$sangh, $stop]) }}">
                        @csrf @method('PUT')
                        <div class="row g-2 mb-2">
                            <div class="col-sm-6"><input type="text" name="name_en" class="form-control form-control-sm" value="{{ $stop->name_en }}" placeholder="{{ __('app.name') }} ({{ __('app.en') }})" required></div>
                            <div class="col-sm-6"><input type="text" name="name_gu" class="form-control form-control-sm" value="{{ $stop->name_gu }}" placeholder="{{ __('app.name') }} ({{ __('app.gu') }})" required></div>
                            <div class="col-sm-6"><input type="text" name="address_en" class="form-control form-control-sm" value="{{ $stop->address_en }}" placeholder="{{ __('app.address') }} ({{ __('app.en') }})"></div>
                            <div class="col-sm-6"><input type="text" name="address_gu" class="form-control form-control-sm" value="{{ $stop->address_gu }}" placeholder="{{ __('app.address') }} ({{ __('app.gu') }})"></div>
                            <div class="col-sm-3"><input type="number" name="km_marker" class="form-control form-control-sm" value="{{ $stop->km_marker }}" required></div>
                            <div class="col-sm-3"><input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $stop->sort_order }}" required></div>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mb-2">
                            @foreach(['water','food','tea','medical','rest'] as $f)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_{{ $f }}" value="1" {{ $stop->{'has_' . $f} ? 'checked' : '' }}>
                                    <label class="form-check-label small">{{ __('sangh.' . $f) }}</label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">{{ __('app.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
            <div class="card text-center p-5 text-muted">
                <i class="bi bi-geo-alt display-3 mb-3"></i>
                <p>{{ __('sangh.no_stoppages') }}</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
