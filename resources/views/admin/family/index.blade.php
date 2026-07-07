@extends('layouts.admin')
@section('title', __('family.parivar'))
@section('page-title', __('family.parivar') . ' — ' . __('family.tree'))

@push('styles')
<style>
/* ── Tree layout ─────────────────────────────────────────── */
.family-tree {
    --line:   #c8bfb0;
    --root:   #8B0000;
    --female: #c2185b;
    --male:   #37474f;
    list-style: none;
    margin: 0;
    padding: 0;
}

.family-tree ul {
    list-style: none;
    margin: 0;
    padding: 0 0 0 2.5rem;
    position: relative;
}

/* Vertical spine on the left of each sub-list */
.family-tree ul::before {
    content: '';
    position: absolute;
    left: .8rem;
    top: 0;
    bottom: 1.4rem;
    border-left: 2px solid var(--line);
}

/* Each list item is a row */
.family-tree li {
    position: relative;
    padding: .25rem 0;
}

/* Horizontal branch from spine to node */
.family-tree ul > li::before {
    content: '';
    position: absolute;
    left: -1.7rem;
    top: 1.6rem;
    width: 1.7rem;
    border-top: 2px solid var(--line);
}

/* ── Node card ───────────────────────────────────────────── */
.tree-node {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: #fff;
    border: 1px solid #e0d8d0;
    border-left: 4px solid var(--male);
    border-radius: .375rem;
    padding: .35rem .6rem;
    min-width: 200px;
    max-width: 340px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    transition: box-shadow .15s;
}
.tree-node:hover { box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.tree-node--female { border-left-color: var(--female); }

/* Root-level nodes get a bolder left border */
.family-tree > li > .tree-node {
    border-left-width: 5px;
    border-left-color: var(--root);
}
.family-tree > li > .tree-node.tree-node--female {
    border-left-color: var(--female);
}

.tree-node__icon {
    font-size: 1.2rem;
    color: var(--male);
    flex-shrink: 0;
}
.tree-node--female .tree-node__icon { color: var(--female); }

.tree-node__body { flex: 1; min-width: 0; }
.tree-node__name {
    font-weight: 600;
    font-size: .88rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tree-node__meta {
    font-size: .72rem;
    color: #888;
    margin-top: 1px;
}
.tree-node__meta .sep { margin: 0 .2rem; }

.tree-node__actions {
    display: flex;
    gap: .2rem;
    flex-shrink: 0;
}
.tree-node__actions .btn {
    font-size: .7rem;
    padding: 2px 6px;
    border: 1px solid #ddd;
    background: #f8f9fa;
    border-radius: .25rem;
    color: #555;
    line-height: 1.4;
    text-decoration: none;
}
.tree-node__actions .btn:hover { background: #e9ecef; color: #000; }
.tree-node__actions .btn-xs--danger { border-color: #f5c6cb; color: #842029; }
.tree-node__actions .btn-xs--danger:hover { background: #f8d7da; border-color: #f1aeb5; }

/* Spouse card */
.tree-node--spouse {
    opacity: .92;
}

/* Heart connector between couple cards */
.couple-link {
    font-size: 1rem;
    line-height: 1;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small"><i class="bi bi-diagram-3 me-1"></i>{{ __('family.tree') }}</span>
    <a href="{{ route('admin.family.create') }}" class="btn btn-sm text-white" style="background:#8B0000">
        <i class="bi bi-plus-lg me-1"></i>{{ __('family.add_member') }}
    </a>
</div>

@if($roots->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-diagram-3 fs-1 d-block mb-2"></i>
        {{ __('family.no_members') }}
        <div class="mt-2">
            <a href="{{ route('admin.family.create') }}" class="btn btn-sm btn-outline-secondary">
                {{ __('family.add_member') }}
            </a>
        </div>
    </div>
@else
    <div class="card p-4" style="overflow-x: auto;">
        <ul class="family-tree">
            @include('admin.family._tree_node', ['members' => $roots, 'depth' => 0])
        </ul>
    </div>
@endif

@endsection
