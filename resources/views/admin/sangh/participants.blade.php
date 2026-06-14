@extends('layouts.admin')
@section('title', __('sangh.participants'))
@section('page-title', $sangh->title() . ' — ' . __('sangh.participants'))

@section('content')

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
    </a>
</div>

<div class="row g-4">

    {{-- Confirm by Mobile --}}
    <div class="col-lg-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <strong><i class="bi bi-check-circle me-1"></i>{{ __('sangh.confirm_presence') }}</strong>
            </div>
            <div class="card-body">
                <p class="text-muted small">{{ __('sangh.confirm_hint') }}</p>
                <form method="POST" action="{{ route('admin.sangh.participants.confirm', $sangh) }}">
                    @csrf
                    <div class="input-group">
                        <input type="tel" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                            placeholder="{{ __('app.mobile') }}" maxlength="10" required>
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-check2"></i> {{ __('app.confirm') }}
                        </button>
                    </div>
                    @error('mobile')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </form>
            </div>
        </div>

        {{-- Add Participant (Admin) --}}
        <div class="card mt-3">
            <div class="card-header"><strong><i class="bi bi-person-plus me-1"></i>{{ __('sangh.add_participant') }}</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sangh.participants.store', $sangh) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('app.name') }} *" required>
                    </div>
                    <div class="mb-2">
                        <input type="tel" name="mobile" class="form-control form-control-sm" placeholder="{{ __('app.mobile') }} *" maxlength="10" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col">
                            <input type="text" name="village" class="form-control form-control-sm" placeholder="{{ __('app.village') }}">
                        </div>
                        <div class="col-4">
                            <input type="number" name="age" class="form-control form-control-sm" placeholder="{{ __('app.age') }}" min="5" max="100">
                        </div>
                    </div>
                    <div class="mb-2">
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="male">{{ __('app.male') }}</option>
                            <option value="female">{{ __('app.female') }}</option>
                            <option value="other">{{ __('app.other') }}</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="tel" name="emergency_contact_mobile" class="form-control form-control-sm" placeholder="{{ __('sangh.emergency_mobile') }}" maxlength="10">
                    </div>
                    <button type="submit" class="btn btn-sm w-100" style="background:#FF6B00; color:#fff">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('app.add') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Participant List --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.sangh.participants', $sangh) }}" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="{{ __('app.search') }} name / mobile / token" value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm" style="max-width:140px">
                        <option value="">{{ __('app.all') }}</option>
                        <option value="registered"  {{ request('status') == 'registered'  ? 'selected' : '' }}>{{ __('app.registered') }}</option>
                        <option value="confirmed"   {{ request('status') == 'confirmed'   ? 'selected' : '' }}>{{ __('app.confirmed') }}</option>
                        <option value="completed"   {{ request('status') == 'completed'   ? 'selected' : '' }}>{{ __('app.completed') }}</option>
                        <option value="dropped"     {{ request('status') == 'dropped'     ? 'selected' : '' }}>{{ __('app.dropped') }}</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('app.filter') }}</button>
                </form>
            </div>
            <div class="card-body p-0">
                @if($participants->isEmpty())
                    <p class="text-center text-muted py-4">{{ __('sangh.no_participants') }}</p>
                @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.mobile') }}</th>
                                <th>{{ __('app.token') }}</th>
                                <th>{{ __('app.village') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $p)
                            <tr @if($p->group_leader_id) style="background:#fafafa" @endif>
                                <td class="text-muted small">{{ $loop->iteration }}</td>
                                <td>
                                    @if($p->group_leader_id)
                                        <span class="text-muted me-1" style="font-size:.8rem">↳</span>
                                    @endif
                                    {{ $p->name }}
                                    <div class="d-flex gap-1 mt-1">
                                        @if(is_null($p->group_leader_id))
                                            <span class="badge bg-secondary" style="font-size:.65rem">{{ __('sangh.primary') }}</span>
                                        @else
                                            <span class="badge bg-light text-muted border" style="font-size:.65rem">{{ __('sangh.member') }}</span>
                                        @endif
                                        @if($p->age)
                                            <span class="badge bg-light text-muted border" style="font-size:.65rem">{{ $p->age }}y</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $p->mobile }}</td>
                                <td><code>{{ $p->token }}</code></td>
                                <td class="text-muted small">{{ $p->village ?? $p->city ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $p->statusBadgeClass() }}">{{ __('app.' . $p->status) }}</span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.sangh.participants.status', [$sangh, $p]) }}">
                                        @csrf @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" style="min-width:100px"
                                            onchange="this.form.submit()">
                                            @foreach(['registered','confirmed','completed','dropped'] as $s)
                                                <option value="{{ $s }}" {{ $p->status == $s ? 'selected' : '' }}>{{ __('app.' . $s) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2">
                    {{ $participants->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
