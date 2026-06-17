@extends('layouts.app')
@section('title', __('sangh.register'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            @if(!$sangh || !$sangh->isRegistrationOpen())
                <div class="card text-center p-5">
                    <i class="bi bi-calendar-x display-3 text-muted mb-3"></i>
                    <h4>{{ __('sangh.registration_closed') }}</h4>
                    <a href="{{ route('sangh.status') }}" class="btn btn-outline-secondary mt-2">
                        {{ __('sangh.check_status') }}
                    </a>
                </div>
            @else

            @php $oldMembers = old('members', []); @endphp

            <div class="card shadow-sm">
                <div class="card-header py-3" style="background:#8B0000; color:#fff">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>{{ __('sangh.register') }}</h4>
                    <p class="mb-0 small opacity-75">{{ $sangh->title() }} — {{ $sangh->startDate()?->format('d M Y') }}</p>
                </div>

                <div class="card-body p-4">

                    <div class="alert alert-info py-2 small mb-4">
                        <i class="bi bi-info-circle me-1"></i>{{ __('sangh.family_info') }}
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sangh.register.store') }}">
                        @csrf

                        {{-- ── Primary Member ───────────────────────────── --}}
                        <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
                            <i class="bi bi-person-circle me-1"></i>{{ __('sangh.primary') }} {{ __('sangh.member') }}
                        </h6>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-5">
                                <label class="form-label fw-semibold">{{ __('app.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label fw-semibold">{{ __('app.age') }}</label>
                                <input type="number" name="age"
                                    class="form-control @error('age') is-invalid @enderror"
                                    value="{{ old('age') }}" min="1" max="100">
                                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label fw-semibold">{{ __('app.gender') }} <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="male"   {{ old('gender', 'male') == 'male'   ? 'selected' : '' }}>{{ __('app.male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
                                    <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>{{ __('app.other') }}</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-4">
                                <label class="form-label fw-semibold">
                                    {{ __('app.mobile') }} <span class="text-danger">*</span>
                                    <small class="text-muted fw-normal">({{ __('sangh.mobile_hint') }})</small>
                                </label>
                                <input type="tel" name="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}" maxlength="10" required>
                                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-5">
                                <label class="form-label fw-semibold">{{ __('app.village') }}</label>
                                <input type="text" name="village" class="form-control"
                                    value="{{ old('village') }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-sm-5">
                                <label class="form-label fw-semibold">{{ __('sangh.emergency_name') }}</label>
                                <input type="text" name="emergency_contact_name" class="form-control"
                                    value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label fw-semibold">{{ __('sangh.emergency_mobile') }}</label>
                                <input type="tel" name="emergency_contact_mobile" class="form-control"
                                    value="{{ old('emergency_contact_mobile') }}" maxlength="10">
                            </div>
                        </div>

                        {{-- ── Additional Members ───────────────────────── --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 mb-2 border-top pt-3">
                            <h6 class="text-uppercase text-muted fw-semibold small mb-0">
                                <i class="bi bi-people me-1"></i>{{ __('sangh.additional_members') }}
                                <span class="text-muted fw-normal normal-case" style="text-transform:none">({{ __('app.optional') }})</span>
                            </h6>
                            <button type="button" id="addMember"
                                class="btn btn-sm btn-outline-success fw-semibold">
                                <i class="bi bi-plus-lg me-1"></i>{{ __('sangh.add_member') }}
                            </button>
                        </div>

                        {{-- Column headers --}}
                        <div id="memberHeaders" class="row g-2 mb-1 text-muted small fw-semibold px-2 {{ count($oldMembers) ? '' : 'd-none' }}" id="memberHeaders">
                            <div class="col-md-4">{{ __('app.name') }} <span class="text-danger">*</span></div>
                            <div class="col-md-1">{{ __('app.age') }}</div>
                            <div class="col-md-2">{{ __('app.gender') }} <span class="text-danger">*</span></div>
                            <div class="col-md-4">{{ __('app.mobile') }} <small class="text-muted fw-normal">({{ __('app.optional') }})</small></div>
                            <div class="col-md-1"></div>
                        </div>

                        {{-- Additional member rows (empty on first load, repopulated on validation fail) --}}
                        <div id="membersContainer">
                            @foreach($oldMembers as $idx => $m)
                            <div class="member-row row g-2 mb-2 align-items-center">
                                <div class="col-12 col-md-4">
                                    <input type="text" name="members[{{ $idx }}][name]"
                                        class="form-control @error("members.$idx.name") is-invalid @enderror"
                                        placeholder="{{ __('app.name') }} *" required
                                        value="{{ $m['name'] ?? '' }}">
                                </div>
                                <div class="col-4 col-md-1">
                                    <input type="number" name="members[{{ $idx }}][age]"
                                        class="form-control" placeholder="{{ __('app.age') }}"
                                        min="1" max="100" value="{{ $m['age'] ?? '' }}">
                                </div>
                                <div class="col-8 col-md-2">
                                    <select name="members[{{ $idx }}][gender]" class="form-select" required>
                                        <option value="male"   {{ ($m['gender'] ?? 'male') == 'male'   ? 'selected' : '' }}>{{ __('app.male') }}</option>
                                        <option value="female" {{ ($m['gender'] ?? '') == 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
                                        <option value="other"  {{ ($m['gender'] ?? '') == 'other'  ? 'selected' : '' }}>{{ __('app.other') }}</option>
                                    </select>
                                </div>
                                <div class="col-10 col-md-4">
                                    <input type="tel" name="members[{{ $idx }}][mobile]"
                                        class="form-control @error("members.$idx.mobile") is-invalid @enderror"
                                        placeholder="{{ __('app.mobile') }}"
                                        maxlength="10" value="{{ $m['mobile'] ?? '' }}">
                                </div>
                                <div class="col-2 col-md-1 d-flex justify-content-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-member"
                                        title="{{ __('app.remove') }}">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn btn-saffron btn-lg fw-bold">
                                <i class="bi bi-check-circle me-1"></i>{{ __('app.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @endif

            <div class="text-center mt-3">
                <a href="{{ route('sangh.status') }}" class="text-muted small">
                    {{ __('sangh.check_status') }} &rarr;
                </a>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
let memberCount = {{ count($oldMembers ?? []) }};

const headers  = document.getElementById('memberHeaders');
const container = document.getElementById('membersContainer');

const genderOptions = `
    <option value="male">{{ __('app.male') }}</option>
    <option value="female">{{ __('app.female') }}</option>
    <option value="other">{{ __('app.other') }}</option>
`;

function showHeaders() {
    headers.classList.remove('d-none');
}

function checkHeaders() {
    if (!container.querySelector('.member-row')) {
        headers.classList.add('d-none');
    }
}

function makeRow(index) {
    const row = document.createElement('div');
    row.className = 'member-row row g-2 mb-2 align-items-center';
    row.innerHTML = `
        <div class="col-12 col-md-4">
            <input type="text" name="members[${index}][name]" class="form-control"
                placeholder="{{ __('app.name') }} *" required>
        </div>
        <div class="col-4 col-md-1">
            <input type="number" name="members[${index}][age]" class="form-control"
                placeholder="{{ __('app.age') }}" min="1" max="100">
        </div>
        <div class="col-8 col-md-2">
            <select name="members[${index}][gender]" class="form-select" required>
                ${genderOptions}
            </select>
        </div>
        <div class="col-10 col-md-4">
            <input type="tel" name="members[${index}][mobile]" class="form-control"
                placeholder="{{ __('app.mobile') }}" maxlength="10">
        </div>
        <div class="col-2 col-md-1 d-flex justify-content-center">
            <button type="button" class="btn btn-outline-danger btn-sm remove-member"
                title="{{ __('app.remove') }}">
                <i class="bi bi-dash-lg"></i>
            </button>
        </div>
    `;
    row.querySelector('.remove-member').addEventListener('click', function () {
        row.remove();
        checkHeaders();
    });
    return row;
}

document.getElementById('addMember').addEventListener('click', function () {
    showHeaders();
    container.appendChild(makeRow(memberCount++));
});

// Attach remove to server-rendered rows (validation failure)
document.querySelectorAll('.remove-member').forEach(btn => {
    btn.addEventListener('click', function () {
        btn.closest('.member-row').remove();
        checkHeaders();
    });
});
</script>
@endpush

@endsection
