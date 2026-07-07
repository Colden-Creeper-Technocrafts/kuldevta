@extends('layouts.admin')
@section('title', __('app.edit') . ' Event')
@section('page-title', __('app.edit') . ' — ' . $event->title_en)

@section('content')

@if($event->event_type === 'annual_function' && $event->sangh)
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.sangh.show', $event->sangh) }}" class="btn btn-sm btn-success">
        <i class="bi bi-people-fill me-1"></i>{{ __('events.manage_sangh') }}
    </a>
    <a href="{{ route('admin.sangh.hawan', $event->sangh) }}" class="btn btn-sm btn-warning">
        <i class="bi bi-fire me-1"></i>{{ __('sangh.hawan') }}
    </a>
</div>
@endif

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header"><strong>{{ __('app.edit') }} {{ __('events.event') }}</strong></div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.events.update', $event) }}">
                    @csrf @method('PUT')
                    @include('admin.events._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn" style="background:#FF6B00; color:#fff">{{ __('app.save') }}</button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const sel = document.getElementById('eventType');
    const hint = document.getElementById('annualFunctionHint');
    function toggle() { hint.classList.toggle('d-none', sel.value !== 'annual_function'); }
    sel.addEventListener('change', toggle);
    toggle();
})();
</script>
@endpush
