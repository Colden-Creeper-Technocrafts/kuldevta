<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sangh;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('status', 'upcoming')
            ->orderBy('event_date')
            ->limit(4)
            ->get();

        $activeSangh = Sangh::whereIn('status', ['registration_open', 'in_progress'])
            ->orderByDesc('year')
            ->first();

        $featuredEvents = Event::where('is_featured', true)
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('event_date')
            ->limit(3)
            ->get();

        return view('home.index', compact('upcomingEvents', 'activeSangh', 'featuredEvents'));
    }
}
