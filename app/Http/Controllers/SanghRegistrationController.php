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
            'name'                     => 'required|string|max:100',
            'mobile'                   => 'required|digits:10',
            'village'                  => 'nullable|string|max:100',
            'city'                     => 'nullable|string|max:100',
            'age'                      => 'nullable|integer|min:1|max:100',
            'gender'                   => 'required|in:male,female,other',
            'emergency_contact_name'   => 'nullable|string|max:100',
            'emergency_contact_mobile' => 'nullable|digits:10',
            'group_name'               => 'nullable|string|max:100',
        ]);

        // Find the primary (parent) registration for this mobile in this Sangh
        $parent = SanghParticipant::where('sangh_id', $sangh->id)
            ->where('mobile', $data['mobile'])
            ->whereNull('group_leader_id')
            ->first();

        $registration = SanghParticipant::create(array_merge($data, [
            'sangh_id'        => $sangh->id,
            'registered_by'   => 'self',
            'status'          => 'registered',
            'group_leader_id' => $parent?->id,
        ]));

        // Pass all registrations under this mobile back to status page
        return redirect()->route('sangh.status', ['mobile' => $data['mobile']])
            ->with('success', __('sangh.registration_success', ['token' => $registration->token]));
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
                ->orderBy('id')
                ->get();
        }

        return view('sangh.status', compact('registrations', 'sangh'));
    }
}
