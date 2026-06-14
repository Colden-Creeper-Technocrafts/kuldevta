@extends('layouts.admin')
@section('title', __('sangh.create'))
@section('page-title', __('sangh.create'))

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><strong>{{ __('sangh.create') }}</strong></div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.sangh.store') }}">
                    @csrf
                    @include('admin.sangh._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn" style="background:#FF6B00; color:#fff">{{ __('app.save') }}</button>
                        <a href="{{ route('admin.sangh.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
