@extends('layouts.admin')
@section('title', __('events.sponsors'))
@section('page-title', __('events.sponsors'))

@section('content')
<div class="row g-4">

    {{-- Add Sponsor Form --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><strong><i class="bi bi-award me-1"></i>{{ __('events.add_sponsor') }}</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sponsors.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">
                            {{ __('events.for') }} ({{ __('events.event') }}/{{ __('events.sangh') }}) <span class="text-danger">*</span>
                        </label>
                        <select name="sponsorable_type" class="form-select form-select-sm" id="sponsorableType" required>
                            <option value="event">{{ __('events.event') }}</option>
                            <option value="sangh">{{ __('events.sangh') }}</option>
                        </select>
                    </div>
                    <div class="mb-2" id="eventSelect">
                        <select name="sponsorable_id" class="form-select form-select-sm" required>
                            <option value="">{{ __('events.select_event') }}</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}">{{ $ev->title_en }} ({{ $ev->event_date->format('Y') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 d-none" id="sanghSelect">
                        <select name="sponsorable_id_sangh" class="form-select form-select-sm">
                            <option value="">{{ __('events.select_sangh') }}</option>
                            @foreach($sanghs as $sg)
                                <option value="{{ $sg->id }}">{{ $sg->title() }} ({{ $sg->year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">— {{ __('app.no_user') }} —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm"
                            placeholder="{{ __('app.name') }} *" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="tel" name="mobile" class="form-control form-control-sm"
                                placeholder="{{ __('app.mobile') }}" maxlength="10">
                        </div>
                        <div class="col-6">
                            <input type="text" name="village_city" class="form-control form-control-sm"
                                placeholder="{{ __('app.village') }}">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="number" name="amount" class="form-control form-control-sm"
                                placeholder="{{ __('events.amount') }}" min="0" step="0.01">
                        </div>
                        <div class="col-6">
                            <select name="sponsor_type" class="form-select form-select-sm" required>
                                @foreach(['main','gold','silver','general'] as $t)
                                    <option value="{{ $t }}">{{ __('events.' . $t) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="description_en" class="form-control form-control-sm"
                            placeholder="{{ __('events.note_en') }}">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="description_gu" class="form-control form-control-sm"
                            placeholder="{{ __('events.note_gu') }}">
                    </div>
                    <button type="submit" class="btn btn-sm w-100" style="background:#FF6B00; color:#fff">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('events.add_sponsor') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sponsors List --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><strong>{{ $sponsors->total() }} {{ __('events.sponsors') }}</strong></div>
            <div class="card-body p-0">
                @if($sponsors->isEmpty())
                    <p class="text-muted text-center py-4">{{ __('events.no_sponsors') }}</p>
                @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('events.sponsor_type') }}</th>
                                <th>{{ __('events.amount') }}</th>
                                <th>{{ __('events.for') }}</th>
                                <th>{{ __('app.mobile') }}</th>
                                <th>{{ __('app.user') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sponsors as $sponsor)
                            <tr>
                                <td>
                                    <strong>{{ $sponsor->name }}</strong>
                                    @if($sponsor->village_city)<br><small class="text-muted">{{ $sponsor->village_city }}</small>@endif
                                </td>
                                <td><span class="badge {{ $sponsor->typeBadgeClass() }}">{{ __('events.' . $sponsor->sponsor_type) }}</span></td>
                                <td>{{ $sponsor->amount ? '₹' . number_format($sponsor->amount) : '—' }}</td>
                                <td class="small text-muted">
                                    @if($sponsor->sponsorable)
                                        @if($sponsor->sponsorable instanceof \App\Models\Sangh)
                                            {{ $sponsor->sponsorable->title() }}
                                        @else
                                            {{ $sponsor->sponsorable->title_en ?? '—' }}
                                        @endif
                                    @endif
                                </td>
                                <td class="small">{{ $sponsor->mobile ?? '—' }}</td>
                                <td class="small text-muted">
                                    @if($sponsor->user)
                                        <i class="bi bi-person-check me-1"></i>{{ $sponsor->user->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.sponsors.destroy', $sponsor) }}"
                                        onsubmit="return confirm('{{ __('events.remove_sponsor') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2">{{ $sponsors->links() }}</div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.getElementById('sponsorableType').addEventListener('change', function() {
    const isEvent = this.value === 'event';
    document.getElementById('eventSelect').classList.toggle('d-none', !isEvent);
    document.getElementById('sanghSelect').classList.toggle('d-none', isEvent);
    // sync the hidden field
    document.querySelector('[name="sponsorable_id"]').required = isEvent;
    document.querySelector('[name="sponsorable_id_sangh"]').required = !isEvent;
});
// On submit, copy sangh id to sponsorable_id if sangh is selected
document.querySelector('form').addEventListener('submit', function() {
    if (document.getElementById('sponsorableType').value === 'sangh') {
        const val = document.querySelector('[name="sponsorable_id_sangh"]').value;
        document.querySelector('[name="sponsorable_id"]').value = val;
    }
});
</script>
@endpush
@endsection
