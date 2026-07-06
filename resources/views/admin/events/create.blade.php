@extends('layouts.admin')
@section('title', __('app.add_new') . ' Event')
@section('page-title', __('app.add_new') . ' Event')

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header"><strong>{{ __('app.add_new') }} {{ __('events.event') }}</strong></div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.events.store') }}">
                    @csrf
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
