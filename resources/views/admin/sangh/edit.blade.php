@extends('layouts.admin')
@section('title', __('app.edit') . ' Sangh')
@section('page-title', __('app.edit') . ' — ' . $sangh->title())

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><strong>{{ __('app.edit') }} Sangh</strong></div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.sangh.update', $sangh) }}">
                    @csrf @method('PUT')
                    @include('admin.sangh._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn" style="background:#FF6B00; color:#fff">{{ __('app.save') }}</button>
                        <a href="{{ route('admin.sangh.show', $sangh) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
