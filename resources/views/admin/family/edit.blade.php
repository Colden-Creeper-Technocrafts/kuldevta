@extends('layouts.admin')
@section('title', __('family.edit_member'))
@section('page-title', __('family.parivar') . ' — ' . $family->fullName())

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
                <strong><i class="bi bi-pencil me-1"></i>{{ __('family.edit_member') }} — {{ $family->fullName() }}</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.family.update', $family) }}" id="editForm">
                    @csrf @method('PUT')
                    @php $member = $family; $selectedParent = null; @endphp
                    @include('admin.family._form')
                    <div class="d-flex gap-2 mt-4 align-items-center">
                        <a href="{{ route('admin.family.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>{{ __('app.cancel') }}
                        </a>
                        <button type="submit" class="btn text-white" style="background:#8B0000">
                            <i class="bi bi-check2 me-1"></i>{{ __('app.save') }}
                        </button>
                    </div>
                </form>

                {{-- Delete form is separate — nested forms are invalid HTML --}}
                <form method="POST" action="{{ route('admin.family.destroy', $family) }}" class="mt-3"
                      onsubmit="return confirm('{{ __('family.delete_confirm') }}')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>{{ __('app.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
