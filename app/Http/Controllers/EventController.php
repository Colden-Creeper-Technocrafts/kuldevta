<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $upcoming = Event::where('status', 'upcoming')
            ->orderBy('event_date')
            ->get();

        $past = Event::where('status', 'completed')
            ->orderByDesc('event_date')
            ->limit(20)
            ->get();

        return view('events.index', compact('upcoming', 'past'));
    }
}
