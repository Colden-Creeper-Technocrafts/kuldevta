<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sangh;
use App\Models\SanghHawanAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HawanController extends Controller
{
    public function index(Sangh $sangh): View
    {
        $assigned = SanghHawanAssignment::with('user')
            ->where('sangh_id', $sangh->id)
            ->get()
            ->keyBy('role');

        $users = User::orderBy('name')->get();

        return view('admin.sangh.hawan', compact('sangh', 'assigned', 'users'));
    }

    public function assign(Sangh $sangh, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role'    => 'required|in:main,support_1,support_2,support_3,support_4',
            'user_id' => 'required|exists:users,id',
        ]);

        // Upsert — replace whoever currently holds this slot
        SanghHawanAssignment::updateOrCreate(
            ['sangh_id' => $sangh->id, 'role' => $data['role']],
            ['user_id'  => $data['user_id']]
        );

        return back()->with('success', __('sangh.hawan_assigned'));
    }

    public function remove(Sangh $sangh, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => 'required|in:main,support_1,support_2,support_3,support_4',
        ]);

        SanghHawanAssignment::where('sangh_id', $sangh->id)
            ->where('role', $data['role'])
            ->delete();

        return back()->with('success', __('sangh.hawan_removed'));
    }
}
