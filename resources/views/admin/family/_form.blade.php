@php
    $member ??= null;
    $isEdit = isset($member) && $member->exists;
    $selectedParent ??= null;
@endphp

<div class="row g-3">

    {{-- Parent --}}
    <div class="col-sm-6">
        <label class="form-label fw-semibold">{{ __('family.parent') }}</label>
        <select name="parent_id" class="form-select">
            <option value="">{{ __('family.no_parent') }}</option>
            @foreach($parents as $p)
                <option value="{{ $p->id }}"
                    {{ old('parent_id', $member?->parent_id ?? $selectedParent?->id) == $p->id ? 'selected' : '' }}>
                    {{ $p->fullName() }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Spouse --}}
    <div class="col-sm-6">
        <label class="form-label fw-semibold">
            <i class="bi bi-hearts text-danger me-1"></i>{{ __('family.spouse') }}
            <span class="text-muted small">({{ __('app.optional') }})</span>
        </label>
        <select name="spouse_id" class="form-select">
            <option value="">{{ __('family.no_spouse') }}</option>
            @foreach($spouseOptions as $s)
                <option value="{{ $s->id }}"
                    {{ old('spouse_id', $member?->spouse_id) == $s->id ? 'selected' : '' }}>
                    {{ $s->fullName() }} ({{ __('app.' . $s->gender) }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Gender (top) --}}
    @php $isFemaleOld = old('gender', $member?->gender) === 'female'; @endphp
    @php $isDauOld    = old('is_daughter', $member?->is_daughter ?? false); @endphp
    <div class="col-sm-3">
        <label class="form-label fw-semibold">{{ __('app.gender') }} <span class="text-danger">*</span></label>
        <select name="gender" id="genderSelect" class="form-select @error('gender') is-invalid @enderror" required>
            <option value="male"   {{ old('gender', $member?->gender ?? 'male') == 'male'   ? 'selected' : '' }}>{{ __('app.male') }}</option>
            <option value="female" {{ old('gender', $member?->gender) == 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
            <option value="other"  {{ old('gender', $member?->gender) == 'other'  ? 'selected' : '' }}>{{ __('app.other') }}</option>
        </select>
        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Female-only checkboxes --}}
    <div class="col-sm-5 d-flex align-items-end gap-4 {{ $isFemaleOld ? '' : 'd-none' }}" id="femaleOptions">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_daughter" id="isDaughter" value="1"
                {{ $isDauOld ? 'checked' : '' }}>
            <label class="form-check-label" for="isDaughter">{{ __('family.is_daughter') }}</label>
        </div>
        <div class="form-check {{ ($isFemaleOld && $isDauOld) ? '' : 'd-none' }}" id="isMarriedWrap">
            <input class="form-check-input" type="checkbox" name="is_married" id="isMarried" value="1"
                {{ old('is_married', $member?->is_married ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="isMarried">{{ __('family.is_married') }}</label>
        </div>
    </div>

    {{-- First Name --}}
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('family.first_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="first_name"
            class="form-control @error('first_name') is-invalid @enderror"
            value="{{ old('first_name', $member?->first_name) }}"
            required autofocus>
        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Middle Name (autocomplete) --}}
    <div class="col-sm-4 position-relative">
        <label class="form-label fw-semibold">{{ __('family.middle_name') }}</label>
        <input type="text" name="middle_name" id="middleName"
            class="form-control @error('middle_name') is-invalid @enderror"
            value="{{ old('middle_name', $member?->middle_name) }}"
            autocomplete="off">
        <div id="middleNameSuggestions" class="list-group position-absolute shadow-sm" style="z-index:1000;min-width:200px;display:none"></div>
        @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Last Name --}}
    <div class="col-sm-4">
        <label class="form-label fw-semibold">{{ __('family.last_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="last_name" id="lastName"
            class="form-control @error('last_name') is-invalid @enderror"
            value="{{ old('last_name', $member?->last_name ?? 'Kotak') }}"
            readonly>
        <div class="form-text text-muted small" id="lastNameHint">{{ __('family.last_name_locked') }}</div>
        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- DOB --}}
    <div class="col-sm-3">
        <label class="form-label fw-semibold">{{ __('family.dob') }} <span class="text-muted small">({{ __('app.optional') }})</span></label>
        <input type="date" name="dob"
            class="form-control @error('dob') is-invalid @enderror"
            value="{{ old('dob', $member?->dob?->format('Y-m-d')) }}"
            max="{{ date('Y-m-d') }}">
        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Suffix --}}
    <div class="col-sm-3">
        <label class="form-label fw-semibold">{{ __('family.suffix') }} <span class="text-muted small">({{ __('app.optional') }})</span></label>
        <input type="text" name="suffix"
            class="form-control"
            value="{{ old('suffix', $member?->suffix) }}"
            placeholder="e.g. Bhai, Ben">
    </div>

</div>

@push('scripts')
<script>
(function () {
    const gender      = document.getElementById('genderSelect');
    const femaleOpts  = document.getElementById('femaleOptions');
    const isDaughter  = document.getElementById('isDaughter');
    const isMarriedW  = document.getElementById('isMarriedWrap');
    const isMarried   = document.getElementById('isMarried');
    const lastName    = document.getElementById('lastName');
    const middleInput = document.getElementById('middleName');
    const suggestions = document.getElementById('middleNameSuggestions');
    const suggestUrl  = '{{ route('admin.family.suggest.middle') }}';

    function refresh() {
        const isFemale = gender.value === 'female';
        const isDau    = isDaughter.checked;
        const isMar    = isMarried.checked;

        femaleOpts.classList.toggle('d-none', !isFemale);
        isMarriedW.classList.toggle('d-none', !isFemale || !isDau);

        const editable = isFemale && isDau && isMar;
        lastName.readOnly = !editable;
        lastName.classList.toggle('bg-light', !editable);
        if (!editable) lastName.value = 'Kotak';
    }

    gender.addEventListener('change', refresh);
    isDaughter.addEventListener('change', refresh);
    isMarried.addEventListener('change', refresh);
    refresh();

    // Middle name autocomplete
    let timer;
    middleInput.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 1) { suggestions.style.display = 'none'; return; }
        timer = setTimeout(() => {
            fetch(suggestUrl + '?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(names => {
                    if (!names.length) { suggestions.style.display = 'none'; return; }
                    suggestions.innerHTML = names.map(n =>
                        `<button type="button" class="list-group-item list-group-item-action py-1 px-2">${n}</button>`
                    ).join('');
                    suggestions.style.display = 'block';
                    suggestions.querySelectorAll('button').forEach(btn => {
                        btn.addEventListener('click', () => {
                            middleInput.value = btn.textContent;
                            suggestions.style.display = 'none';
                        });
                    });
                });
        }, 250);
    });

    document.addEventListener('click', e => {
        if (!suggestions.contains(e.target) && e.target !== middleInput) {
            suggestions.style.display = 'none';
        }
    });
})();
</script>
@endpush
