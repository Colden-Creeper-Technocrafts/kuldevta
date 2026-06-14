<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sangh;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $activeSangh = Sangh::whereIn('status', ['registration_open', 'registration_closed', 'in_progress'])
            ->orderByDesc('year')
            ->first();

        $stats = [
            'total_sanghs'     => Sangh::count(),
            'total_events'     => Event::count(),
            'upcoming_events'  => Event::where('status', 'upcoming')->count(),
        ];

        if ($activeSangh) {
            $stats['registered']  = $activeSangh->participants()->count();
            $stats['confirmed']   = $activeSangh->participants()->where('status', 'confirmed')->count();
            $stats['completed']   = $activeSangh->participants()->where('status', 'completed')->count();
        }

        $recentRegistrations = $activeSangh
            ? $activeSangh->participants()->latest()->limit(10)->get()
            : collect();

        return view('admin.dashboard.index', compact('activeSangh', 'stats', 'recentRegistrations'));
    }
}
