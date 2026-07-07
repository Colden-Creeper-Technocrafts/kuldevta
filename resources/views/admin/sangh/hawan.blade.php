@extends('layouts.admin')
@section('title', __('sangh.hawan'))
@section('page-title', $sangh->title() . ' — ' . __('sangh.hawan_participants'))

@section('content')

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">

    {{-- 5 role slots --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <strong><i class="bi bi-fire me-1"></i>{{ __('sangh.hawan_participants') }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:130px">{{ __('app.role') }}</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.email') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\SanghParticipant::HAWAN_ROLES as $role)
                        @php $a = $assigned->get($role); @endphp
                        <tr>
                            <td class="fw-semibold">
                                @if($role === 'main')
                                    <span class="badge bg-danger">{{ __('sangh.hawan_role_main') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('sangh.hawan_role_' . $role) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($a)
                                    <i class="bi bi-person-check text-success me-1"></i>{{ $a->user->name }}
                                @else
                                    <span class="text-muted fst-italic">{{ __('sangh.hawan_empty_slot') }}</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $a?->user->email ?? '—' }}</td>
                            <td class="text-end">
                                @if($a)
                                    <form method="POST" action="{{ route('admin.sangh.hawan.remove', $sangh) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="role" value="{{ $role }}">
                                        <button class="btn btn-sm btn-outline-danger" title="{{ __('app.remove') }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-success assign-btn"
                                        data-role="{{ $role }}"
                                        data-label="{{ __('sangh.hawan_role_' . $role) }}">
                                        <i class="bi bi-plus-lg me-1"></i>{{ __('sangh.hawan_assign') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Assign panel --}}
    <div class="col-lg-5">
        <div class="card" id="assignCard" style="display:none !important">
            <div class="card-header">
                <strong><i class="bi bi-person-plus me-1"></i>{{ __('sangh.hawan_assign') }}: <span id="assignRoleLabel"></span></strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sangh.hawan.assign', $sangh) }}">
                    @csrf
                    <input type="hidden" name="role" id="assignRoleInput">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('app.user') }} <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">— {{ __('app.select') }} —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">{{ __('sangh.hawan_assign_hint') }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check2 me-1"></i>{{ __('sangh.hawan_assign') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="cancelAssign">
                            {{ __('app.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.assign-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('assignRoleInput').value = this.dataset.role;
        document.getElementById('assignRoleLabel').textContent = this.dataset.label;
        document.getElementById('assignCard').style.removeProperty('display');
        document.getElementById('assignCard').scrollIntoView({behavior: 'smooth', block: 'nearest'});
    });
});
document.getElementById('cancelAssign').addEventListener('click', function () {
    document.getElementById('assignCard').style.display = 'none';
});
</script>
@endpush
