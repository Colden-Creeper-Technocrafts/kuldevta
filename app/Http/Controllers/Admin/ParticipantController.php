<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sangh;
use App\Models\SanghParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantController extends Controller
{
    public function index(Sangh $sangh, Request $request): View
    {
        $query = $sangh->participants()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%")
                  ->orWhere('token', 'like', "%$search%")
                  ->orWhere('village', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $participants = $query->paginate(25)->withQueryString();

        return view('admin.sangh.participants', compact('sangh', 'participants'));
    }

    public function store(Sangh $sangh, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                     => 'required|string|max:100',
            'mobile'                   => 'required|digits:10',
            'village'                  => 'nullable|string|max:100',
            'city'                     => 'nullable|string|max:100',
            'age'                      => 'nullable|integer|min:5|max:100',
            'gender'                   => 'required|in:male,female,other',
            'emergency_contact_name'   => 'nullable|string|max:100',
            'emergency_contact_mobile' => 'nullable|digits:10',
            'group_name'               => 'nullable|string|max:100',
            'notes'                    => 'nullable|string',
        ]);

        $parent = SanghParticipant::where('sangh_id', $sangh->id)
            ->where('mobile', $data['mobile'])
            ->whereNull('group_leader_id')
            ->first();

        SanghParticipant::create(array_merge($data, [
            'sangh_id'        => $sangh->id,
            'registered_by'   => 'admin',
            'status'          => 'registered',
            'group_leader_id' => $parent?->id,
        ]));

        return back()->with('success', 'Participant added successfully.');
    }

    public function lookup(Sangh $sangh, Request $request): JsonResponse
    {
        $mobile = $request->validate(['mobile' => 'required|digits:10'])['mobile'];

        $primary = SanghParticipant::where('sangh_id', $sangh->id)
            ->where('mobile', $mobile)
            ->whereNull('group_leader_id')
            ->with(['groupMembers' => fn($q) => $q->orderBy('id')])
            ->first();

        if (!$primary) {
            return response()->json(['found' => false, 'message' => __('sangh.not_found')]);
        }

        $all = collect([$primary])->merge($primary->groupMembers);

        return response()->json([
            'found'        => true,
            'participants' => $all->map(fn($p) => [
                'id'           => $p->id,
                'name'         => $p->name,
                'age'          => $p->age,
                'gender'       => $p->gender,
                'token'        => $p->token,
                'status'       => $p->status,
                'is_primary'   => is_null($p->group_leader_id),
                'confirmed_at' => $p->confirmed_at?->format('d M, H:i'),
            ])->values(),
        ]);
    }

    public function confirm(Sangh $sangh, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:sangh_participants,id',
        ]);

        $count = SanghParticipant::where('sangh_id', $sangh->id)
            ->whereIn('id', $data['ids'])
            ->where('status', 'registered')
            ->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
            ]);

        return back()->with('success', "{$count} participant(s) confirmed.");
    }

    public function updateStatus(Sangh $sangh, SanghParticipant $participant, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:registered,confirmed,completed,dropped',
        ]);

        if ($data['status'] === 'confirmed' && $participant->status !== 'confirmed') {
            $data['confirmed_at'] = now();
        }

        $participant->update($data);

        return back()->with('success', 'Status updated.');
    }
}
