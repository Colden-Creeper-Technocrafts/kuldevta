<?php

namespace App\Http\Controllers;

use App\Models\SanghParticipant;
use App\Models\Sangh;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SanghRegistrationController extends Controller
{
    public function create(): View
    {
        $sangh = Sangh::where('status', 'registration_open')
            ->orderByDesc('year')
            ->first();

        return view('sangh.register', compact('sangh'));
    }

    public function store(Request $request): RedirectResponse
    {
        $sangh = Sangh::where('status', 'registration_open')
            ->orderByDesc('year')
            ->firstOrFail();

        if (!$sangh->isRegistrationOpen()) {
            return back()->withErrors(['mobile' => __('sangh.registration_closed')]);
        }

        $data = $request->validate([
            // Primary member fields
            'name'                       => 'required|string|max:100',
            'age'                        => 'nullable|integer|min:1|max:100',
            'gender'                     => 'required|in:male,female,other',
            'mobile'                     => 'required|digits:10',
            'village'                    => 'nullable|string|max:100',
            'emergency_contact_name'     => 'nullable|string|max:100',
            'emergency_contact_mobile'   => 'nullable|digits:10',
            // Additional members
            'members'                    => 'nullable|array|max:19',
            'members.*.name'             => 'required|string|max:100',
            'members.*.age'              => 'nullable|integer|min:1|max:100',
            'members.*.gender'           => 'required|in:male,female,other',
            'members.*.mobile'           => 'nullable|digits:10',
        ]);

        // Create the primary member
        $primary = SanghParticipant::create([
            'sangh_id'                 => $sangh->id,
            'name'                     => $data['name'],
            'mobile'                   => $data['mobile'],
            'age'                      => $data['age'] ?? null,
            'gender'                   => $data['gender'],
            'village'                  => $data['village'] ?? null,
            'emergency_contact_name'   => $data['emergency_contact_name'] ?? null,
            'emergency_contact_mobile' => $data['emergency_contact_mobile'] ?? null,
            'registered_by'            => 'self',
            'status'                   => 'registered',
            'group_leader_id'          => null,
        ]);

        $tokens = [$primary->token];

        // Create additional members
        foreach ($data['members'] ?? [] as $member) {
            $participant = SanghParticipant::create([
                'sangh_id'                 => $sangh->id,
                'name'                     => $member['name'],
                'mobile'                   => !empty($member['mobile']) ? $member['mobile'] : $data['mobile'],
                'age'                      => $member['age'] ?? null,
                'gender'                   => $member['gender'],
                'emergency_contact_name'   => $data['emergency_contact_name'] ?? null,
                'emergency_contact_mobile' => $data['emergency_contact_mobile'] ?? null,
                'registered_by'            => 'self',
                'status'                   => 'registered',
                'group_leader_id'          => $primary->id,
            ]);
            $tokens[] = $participant->token;
        }

        $count   = count($tokens);
        $message = $count === 1
            ? __('sangh.registration_success', ['token' => $tokens[0]])
            : __('sangh.registration_success_multi', ['count' => $count, 'tokens' => implode(', ', $tokens)]);

        return redirect()->route('sangh.status', ['mobile' => $data['mobile']])
            ->with('success', $message);
    }

    public function status(Request $request): View
    {
        $registrations = collect();
        $sangh = Sangh::whereIn('status', ['registration_open', 'registration_closed', 'in_progress', 'completed'])
            ->orderByDesc('year')
            ->first();

        if ($request->filled('mobile') && $sangh) {
            $registrations = SanghParticipant::where('sangh_id', $sangh->id)
                ->where('mobile', $request->mobile)
                ->whereNull('group_leader_id')
                ->with(['groupMembers' => fn($q) => $q->orderBy('id')])
                ->orderBy('id')
                ->get();
        }

        $totalCount = $registrations->sum(fn($p) => 1 + $p->groupMembers->count());

        return view('sangh.status', compact('registrations', 'totalCount', 'sangh'));
    }
}
