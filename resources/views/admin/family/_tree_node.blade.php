@foreach($members->reject(fn($m) => in_array($m->id, $spouseIds) && $m->parent_id === null) as $m)
<li>
    {{-- Husband / primary member --}}
    <div class="tree-node {{ $m->gender === 'female' ? 'tree-node--female' : '' }}">
        <div class="tree-node__icon">
            @if($m->gender === 'female')
                <i class="bi bi-person-heart"></i>
            @else
                <i class="bi bi-person-fill"></i>
            @endif
        </div>
        <div class="tree-node__body">
            <div class="tree-node__name">{{ $m->fullName() }}</div>
            <div class="tree-node__meta">
                @if($m->dob)<span>{{ $m->dob->format('d M Y') }}</span><span class="sep">&bull;</span>@endif
                {{ __("app.{$m->gender}") }}
                @if($m->is_daughter)<span class="sep">&bull;</span>{{ __('family.is_daughter') }}@endif
                @if($m->is_married)<span class="sep">&bull;</span>{{ __('family.is_married') }}@endif
            </div>
        </div>
        <div class="tree-node__actions">
            <a href="{{ route('admin.family.create', ['parent_id' => $m->id]) }}"
               class="btn btn-xs" title="{{ __('family.add_child') }}"><i class="bi bi-plus-lg"></i></a>
            <a href="{{ route('admin.family.edit', $m) }}"
               class="btn btn-xs" title="{{ __('app.edit') }}"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('admin.family.destroy', $m) }}" style="display:inline"
                  onsubmit="return confirm('{{ __('family.delete_confirm') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-xs btn-xs--danger" title="{{ __('app.delete') }}">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Wife / spouse — same indent, directly below --}}
    @if($m->spouse)
    <div class="tree-node tree-node--spouse {{ $m->spouse->gender === 'female' ? 'tree-node--female' : '' }} mt-1">
        <div class="tree-node__icon">
            @if($m->spouse->gender === 'female')
                <i class="bi bi-person-heart"></i>
            @else
                <i class="bi bi-person-fill"></i>
            @endif
        </div>
        <div class="tree-node__body">
            <div class="tree-node__name">{{ $m->spouse->fullName() }}</div>
            <div class="tree-node__meta">
                @if($m->spouse->dob)<span>{{ $m->spouse->dob->format('d M Y') }}</span><span class="sep">&bull;</span>@endif
                {{ __("app.{$m->spouse->gender}") }}
            </div>
        </div>
        <div class="tree-node__actions">
            <a href="{{ route('admin.family.edit', $m->spouse) }}"
               class="btn btn-xs" title="{{ __('app.edit') }}"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('admin.family.destroy', $m->spouse) }}" style="display:inline"
                  onsubmit="return confirm('{{ __('family.delete_confirm') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-xs btn-xs--danger" title="{{ __('app.delete') }}">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Children under the couple --}}
    @php
        $coupleChildren = $m->children;
        if ($m->spouse) {
            $coupleChildren = $coupleChildren->merge($m->spouse->children)->sortBy('first_name')->values();
        }
    @endphp
    @if($coupleChildren->isNotEmpty())
        <ul>
            @include('admin.family._tree_node', ['members' => $coupleChildren, 'depth' => $depth + 1])
        </ul>
    @endif
</li>
@endforeach
