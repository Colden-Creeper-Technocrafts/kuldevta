<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FamilyController extends Controller
{
    public function index(): View
    {
        // Fix any wrong-direction spouse links before rendering.
        // Rule: the born-family member (has parent_id) must hold spouse_id,
        // not the married-in member (no parent_id).
        FamilyMember::whereNotNull('spouse_id')
            ->whereNull('parent_id')
            ->with('spouse')
            ->each(function (FamilyMember $m) {
                if ($m->spouse && !is_null($m->spouse->parent_id)) {
                    $m->spouse->update(['spouse_id' => $m->id]);
                    $m->update(['spouse_id' => null]);
                }
            });

        // Only married-in members (no parent_id) are suppressed as standalone nodes.
        $spouseIds = FamilyMember::whereNotNull('spouse_id')->pluck('spouse_id')
            ->intersect(FamilyMember::whereNull('parent_id')->pluck('id'))
            ->all();

        $roots = FamilyMember::whereNull('parent_id')
            ->whereNotIn('id', $spouseIds)
            ->with($this->deepWith())
            ->orderBy('first_name')
            ->get();

        return view('admin.family.index', compact('roots', 'spouseIds'));
    }

    public function create(Request $request): View
    {
        $parents = FamilyMember::orderBy('first_name')->get();
        $selectedParent = $request->filled('parent_id')
            ? FamilyMember::find($request->parent_id)
            : null;

        $spouseOptions = FamilyMember::orderBy('first_name')->get();

        return view('admin.family.create', compact('parents', 'selectedParent', 'spouseOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'parent_id'   => 'nullable|exists:family_members,id',
            'spouse_id'   => 'nullable|exists:family_members,id',
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'suffix'      => 'nullable|string|max:50',
            'gender'      => 'required|in:male,female,other',
            'dob'         => 'nullable|date|before_or_equal:today',
            'is_daughter' => 'boolean',
            'is_married'  => 'boolean',
        ]);

        $data['is_daughter'] = $request->boolean('is_daughter');
        $data['is_married']  = $request->boolean('is_married');

        if (!($data['gender'] === 'female' && $data['is_daughter'] && $data['is_married'])) {
            $data['last_name'] = 'Kotak';
        }

        $member = FamilyMember::create($data);
        $this->normalizeSpouseDirection($member);

        return redirect()->route('admin.family.index')->with('success', __('family.member_added'));
    }

    public function edit(FamilyMember $family): View
    {
        $parents = FamilyMember::where('id', '!=', $family->id)->orderBy('first_name')->get();

        $takenSpouseIds = FamilyMember::whereNotNull('spouse_id')
            ->where('id', '!=', $family->id)
            ->pluck('spouse_id')
            ->all();

        $spouseOptions = FamilyMember::where('id', '!=', $family->id)
            ->whereNotIn('id', $takenSpouseIds)
            ->orderBy('first_name')
            ->get();

        return view('admin.family.edit', compact('family', 'parents', 'spouseOptions'));
    }

    public function update(Request $request, FamilyMember $family): RedirectResponse
    {
        $data = $request->validate([
            'parent_id'   => 'nullable|exists:family_members,id',
            'spouse_id'   => 'nullable|exists:family_members,id',
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'suffix'      => 'nullable|string|max:50',
            'gender'      => 'required|in:male,female,other',
            'dob'         => 'nullable|date|before_or_equal:today',
            'is_daughter' => 'boolean',
            'is_married'  => 'boolean',
        ]);

        $data['is_daughter'] = $request->boolean('is_daughter');
        $data['is_married']  = $request->boolean('is_married');

        if (!($data['gender'] === 'female' && $data['is_daughter'] && $data['is_married'])) {
            $data['last_name'] = 'Kotak';
        }

        $oldSpouseId = $family->spouse_id;
        $newSpouseId = $data['spouse_id'] ?? null;

        if ($oldSpouseId && $oldSpouseId !== $newSpouseId) {
            FamilyMember::where('id', $oldSpouseId)->update(['spouse_id' => null]);
        }

        $family->update($data);
        $family->refresh();
        $this->normalizeSpouseDirection($family);

        return redirect()->route('admin.family.index')->with('success', __('family.member_updated'));
    }

    public function destroy(FamilyMember $family): RedirectResponse
    {
        FamilyMember::where('spouse_id', $family->id)->update(['spouse_id' => null]);
        $family->delete();
        return redirect()->route('admin.family.index')->with('success', __('family.member_deleted'));
    }

    public function middleNameSuggest(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        $names = FamilyMember::where('first_name', 'like', "{$q}%")
            ->distinct()
            ->orderBy('first_name')
            ->limit(10)
            ->pluck('first_name');

        return response()->json($names);
    }

    /**
     * The born-family member (has parent_id) must hold spouse_id.
     * If the married-in member (no parent_id) holds it, swap.
     */
    private function normalizeSpouseDirection(FamilyMember $member): void
    {
        $member->refresh();
        if (!$member->spouse_id) return;

        $spouse = FamilyMember::find($member->spouse_id);
        if (!$spouse) return;

        if (is_null($member->parent_id) && !is_null($spouse->parent_id)) {
            $spouse->update(['spouse_id' => $member->id]);
            $member->update(['spouse_id' => null]);
        }
    }

    private function deepWith(): array
    {
        $relations = ['spouse', 'spouse.children'];
        $prefix = 'children';
        for ($i = 0; $i < 5; $i++) {
            $relations[] = $prefix;
            $relations[] = $prefix . '.spouse';
            $relations[] = $prefix . '.spouse.children';
            $prefix .= '.children';
        }
        return $relations;
    }
}
