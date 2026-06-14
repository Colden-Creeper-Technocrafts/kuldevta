<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sangh;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function index(Sangh $sangh): View
    {
        $volunteers = $sangh->volunteers()->with('assignedStoppage')->get();
        $stoppages  = $sangh->stoppages;
        return view('admin.sangh.volunteers', compact('sangh', 'volunteers', 'stoppages'));
    }

    public function store(Sangh $sangh, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:100',
            'mobile'               => 'required|digits:10',
            'village_city'         => 'nullable|string|max:100',
            'role'                 => 'required|in:coordinator,registration_desk,stoppage_service,medical,security,general',
            'assigned_stoppage_id' => 'nullable|exists:stoppages,id',
        ]);

        $sangh->volunteers()->create($data);

        return back()->with('success', 'Volunteer added.');
    }

    public function destroy(Sangh $sangh, Volunteer $volunteer): RedirectResponse
    {
        $volunteer->delete();
        return back()->with('success', 'Volunteer removed.');
    }
}
