@extends('layouts.admin')
@section('title', __('sangh.volunteers'))
@section('page-title', $sangh->title() . ' — ' . __('sangh.volunteers'))

@section('content')
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><strong>{{ __('sangh.add_volunteer') }}</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sangh.volunteers.store', $sangh) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('app.name') }} *" required>
                    </div>
                    <div class="mb-2">
                        <input type="tel" name="mobile" class="form-control form-control-sm" placeholder="{{ __('app.mobile') }} *" maxlength="10" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="village_city" class="form-control form-control-sm" placeholder="{{ __('app.village') }}">
                    </div>
                    <div class="mb-2">
                        <select name="role" class="form-select form-select-sm" required>
                            @foreach(['coordinator','registration_desk','stoppage_service','medical','security','general'] as $r)
                                <option value="{{ $r }}">{{ __('sangh.role_' . $r) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="assigned_stoppage_id" class="form-select form-select-sm">
                            <option value="">{{ __('app.no_specific_stoppage') }}</option>
                            @foreach($stoppages as $stop)
                                <option value="{{ $stop->id }}">{{ $stop->km_marker }}km — {{ $stop->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm w-100" style="background:#FF6B00; color:#fff">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('sangh.add_volunteer') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><strong>{{ $volunteers->count() }} {{ __('sangh.volunteers') }}</strong></div>
            <div class="card-body p-0">
                @if($volunteers->isEmpty())
                    <p class="text-muted text-center py-4">{{ __('sangh.no_volunteers') }}</p>
                @else
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.mobile') }}</th>
                            <th>{{ __('sangh.role') }}</th>
                            <th>{{ __('sangh.assigned_to_stoppage') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($volunteers as $vol)
                        <tr>
                            <td>{{ $vol->name }}<br><small class="text-muted">{{ $vol->village_city }}</small></td>
                            <td>{{ $vol->mobile }}</td>
                            <td><span class="badge bg-secondary">{{ __('sangh.role_' . $vol->role) }}</span></td>
                            <td>
                                @if($vol->assignedStoppage)
                                    {{ $vol->assignedStoppage->name_en }}
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.sangh.volunteers.destroy', [$sangh, $vol]) }}"
                                    onsubmit="return confirm('{{ __('sangh.remove_volunteer') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
