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
                <div class="input-group">
                    <input type="tel" id="confirmMobile" class="form-control"
                        placeholder="{{ __('app.mobile') }}" maxlength="10">
                    <button class="btn btn-success" type="button" id="confirmLookupBtn">
                        <i class="bi bi-search"></i> {{ __('app.check') }}
                    </button>
                </div>
                <div id="confirmError" class="text-danger small mt-1 d-none"></div>
            </div>
        </div>

        {{-- Confirm Members Modal --}}
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="confirmModalLabel">
                            <i class="bi bi-check-circle me-2"></i>{{ __('sangh.confirm_presence') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.sangh.participants.confirm', $sangh) }}">
                        @csrf
                        <div class="modal-body" id="confirmModalBody">
                            {{-- Populated by JS --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check2-all me-1"></i>{{ __('app.confirm') }}
                            </button>
                        </div>
                    </form>
                </div>
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

@push('scripts')
<script>
const lookupUrl = '{{ route('admin.sangh.participants.lookup', $sangh) }}';
const csrfToken = '{{ csrf_token() }}';

document.getElementById('confirmLookupBtn').addEventListener('click', function () {
    const mobile = document.getElementById('confirmMobile').value.trim();
    const errorEl = document.getElementById('confirmError');
    errorEl.classList.add('d-none');

    if (!/^\d{10}$/.test(mobile)) {
        errorEl.textContent = '10-digit mobile number required.';
        errorEl.classList.remove('d-none');
        return;
    }

    fetch(lookupUrl + '?mobile=' + encodeURIComponent(mobile), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.found) {
            errorEl.textContent = data.message || 'Not found.';
            errorEl.classList.remove('d-none');
            return;
        }
        populateModal(data.participants);
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    })
    .catch(() => {
        errorEl.textContent = 'Request failed. Please try again.';
        errorEl.classList.remove('d-none');
    });
});

document.getElementById('confirmMobile').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') document.getElementById('confirmLookupBtn').click();
});

function populateModal(participants) {
    const body = document.getElementById('confirmModalBody');
    const hasPending = participants.some(p => p.status !== 'confirmed');

    if (!hasPending) {
        body.innerHTML = '<div class="alert alert-success mb-0"><i class="bi bi-check-circle-fill me-2"></i>All members are already confirmed.</div>';
        return;
    }

    let html = '<p class="text-muted small mb-3">Checked members will be confirmed. Uncheck to skip.</p><ul class="list-group">';
    participants.forEach(p => {
        const label = [
            '<strong>' + escHtml(p.name) + '</strong>',
            p.is_primary ? '<span class="badge bg-dark ms-1" style="font-size:.6rem">Primary</span>' : '<span class="badge bg-secondary ms-1" style="font-size:.6rem">Member</span>',
            p.age ? p.age + 'y' : '',
            p.gender ? p.gender : '',
            '<code class="ms-1" style="font-size:.75rem">' + escHtml(p.token) + '</code>',
        ].filter(Boolean).join(' ');

        if (p.status === 'confirmed') {
            html += `<li class="list-group-item d-flex align-items-center gap-2 text-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>${label}</span>
                <small class="ms-auto text-muted">${p.confirmed_at ?? ''}</small>
            </li>`;
        } else {
            html += `<li class="list-group-item d-flex align-items-center gap-2">
                <input class="form-check-input mt-0" type="checkbox" name="ids[]" value="${p.id}" id="pm_${p.id}" checked>
                <label class="form-check-label flex-grow-1" for="pm_${p.id}">${label}</label>
            </li>`;
        }
    });
    html += '</ul>';
    body.innerHTML = html;
}

function escHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
</script>
@endpush
