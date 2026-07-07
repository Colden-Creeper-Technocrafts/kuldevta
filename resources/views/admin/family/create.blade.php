@extends('layouts.admin')
@section('title', __('family.add_member'))
@section('page-title', __('family.parivar') . ' — ' . __('family.add_member'))

@section('content')
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.family.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <strong><i class="bi bi-person-plus me-1"></i>{{ __('family.add_member') }}</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.family.store') }}">
                    @csrf
                    @include('admin.family._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn text-white" style="background:#8B0000">
                            <i class="bi bi-check2 me-1"></i>{{ __('app.save') }}
                        </button>
                        <a href="{{ route('admin.family.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
