<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sangh;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SponsorController extends Controller
{
    public function index(): View
    {
        $sponsors = Sponsor::with(['sponsorable', 'user'])->latest()->paginate(25);
        $sanghs   = Sangh::orderByDesc('year')->get();
        $events   = Event::orderByDesc('event_date')->limit(30)->get();
        $users    = User::orderBy('name')->get();
        return view('admin.sponsors.index', compact('sponsors', 'sanghs', 'events', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sponsorable_type' => 'required|in:sangh,event',
            'sponsorable_id'   => 'required|integer',
            'user_id'          => 'nullable|exists:users,id',
            'name'             => 'required|string|max:150',
            'mobile'           => 'nullable|digits:10',
            'village_city'     => 'nullable|string|max:100',
            'amount'           => 'nullable|numeric|min:0',
            'sponsor_type'     => 'required|in:main,gold,silver,general',
            'description_en'   => 'nullable|string|max:200',
            'description_gu'   => 'nullable|string|max:200',
        ]);

        $morphClass = $data['sponsorable_type'] === 'sangh' ? Sangh::class : Event::class;

        Sponsor::create([
            'user_id'          => $data['user_id'] ?? null,
            'sponsorable_type' => $morphClass,
            'sponsorable_id'   => $data['sponsorable_id'],
            'name'             => $data['name'],
            'mobile'           => $data['mobile'],
            'village_city'     => $data['village_city'],
            'amount'           => $data['amount'],
            'sponsor_type'     => $data['sponsor_type'],
            'description_en'   => $data['description_en'],
            'description_gu'   => $data['description_gu'],
        ]);

        return back()->with('success', 'Sponsor added.');
    }

    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        $sponsor->delete();
        return back()->with('success', 'Sponsor removed.');
    }
}
