<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sangh;
use App\Models\Stoppage;
use App\Models\StoppageServiceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoppageController extends Controller
{
    public function index(Sangh $sangh): View
    {
        $stoppages = $sangh->stoppages()->with('volunteers')->get();
        return view('admin.sangh.stoppages', compact('sangh', 'stoppages'));
    }

    public function store(Sangh $sangh, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_en'     => 'required|string|max:100',
            'name_gu'     => 'required|string|max:100',
            'address_en'  => 'nullable|string|max:200',
            'address_gu'  => 'nullable|string|max:200',
            'km_marker'   => 'required|integer|min:0',
            'sort_order'  => 'required|integer|min:0',
            'has_water'   => 'boolean',
            'has_food'    => 'boolean',
            'has_tea'     => 'boolean',
            'has_medical' => 'boolean',
            'has_rest'    => 'boolean',
        ]);

        $data['has_water']   = $request->boolean('has_water');
        $data['has_food']    = $request->boolean('has_food');
        $data['has_tea']     = $request->boolean('has_tea');
        $data['has_medical'] = $request->boolean('has_medical');
        $data['has_rest']    = $request->boolean('has_rest');

        $sangh->stoppages()->create($data);

        return back()->with('success', 'Stoppage added.');
    }

    public function update(Sangh $sangh, Stoppage $stoppage, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_en'     => 'required|string|max:100',
            'name_gu'     => 'required|string|max:100',
            'address_en'  => 'nullable|string|max:200',
            'address_gu'  => 'nullable|string|max:200',
            'km_marker'   => 'required|integer|min:0',
            'sort_order'  => 'required|integer|min:0',
            'has_water'   => 'boolean',
            'has_food'    => 'boolean',
            'has_tea'     => 'boolean',
            'has_medical' => 'boolean',
            'has_rest'    => 'boolean',
        ]);

        $data['has_water']   = $request->boolean('has_water');
        $data['has_food']    = $request->boolean('has_food');
        $data['has_tea']     = $request->boolean('has_tea');
        $data['has_medical'] = $request->boolean('has_medical');
        $data['has_rest']    = $request->boolean('has_rest');

        $stoppage->update($data);

        return back()->with('success', 'Stoppage updated.');
    }

    public function destroy(Sangh $sangh, Stoppage $stoppage): RedirectResponse
    {
        $stoppage->delete();
        return back()->with('success', 'Stoppage deleted.');
    }

    public function logService(Sangh $sangh, Stoppage $stoppage, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_type' => 'required|in:water,food,tea,medical,rest',
            'count'        => 'required|integer|min:1',
            'notes'        => 'nullable|string|max:200',
        ]);

        StoppageServiceLog::create([
            'stoppage_id'  => $stoppage->id,
            'service_type' => $data['service_type'],
            'count'        => $data['count'],
            'logged_by'    => auth()->id(),
            'notes'        => $data['notes'] ?? null,
            'logged_at'    => now(),
        ]);

        return back()->with('success', 'Service logged.');
    }
}
