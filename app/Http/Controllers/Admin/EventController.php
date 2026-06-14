<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sangh;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::withCount('sponsors')
            ->with('sangh')
            ->orderByDesc('event_date')
            ->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title_en'       => 'required|string|max:200',
            'title_gu'       => 'required|string|max:200',
            'description_en' => 'nullable|string',
            'description_gu' => 'nullable|string',
            'event_type'     => 'required|in:havan,monthly_havan,sangh,special',
            'event_date'     => 'required|date',
            'event_time'     => 'nullable',
            'venue_en'       => 'nullable|string|max:200',
            'venue_gu'       => 'nullable|string|max:200',
            'status'         => 'required|in:upcoming,ongoing,completed,cancelled',
            'is_featured'    => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        $event = Event::create($data);

        if ($event->event_type === 'sangh') {
            $this->createOrSyncSangh($event);
        }

        $message = $event->event_type === 'sangh'
            ? 'Sangh event created and Sangh record auto-generated.'
            : 'Event created.';

        return redirect()->route('admin.events.index')->with('success', $message);
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'title_en'       => 'required|string|max:200',
            'title_gu'       => 'required|string|max:200',
            'description_en' => 'nullable|string',
            'description_gu' => 'nullable|string',
            'event_type'     => 'required|in:havan,monthly_havan,sangh,special',
            'event_date'     => 'required|date',
            'event_time'     => 'nullable',
            'venue_en'       => 'nullable|string|max:200',
            'venue_gu'       => 'nullable|string|max:200',
            'status'         => 'required|in:upcoming,ongoing,completed,cancelled',
            'is_featured'    => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        $event->update($data);

        if ($event->event_type === 'sangh') {
            // Create Sangh if not exists, otherwise sync title/dates from event
            $this->createOrSyncSangh($event);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        // event_id on sangh is nullOnDelete, so the Sangh record stays but loses its event link
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    private function createOrSyncSangh(Event $event): Sangh
    {
        $year = $event->event_date->year;

        // If this event already has a linked Sangh, just sync it
        if ($event->sangh) {
            $event->sangh->update([
                'title_en'    => $event->title_en,
                'title_gu'    => $event->title_gu,
                'description_en' => $event->description_en,
                'description_gu' => $event->description_gu,
                'start_date'  => $event->event_date,
                'start_time'  => $event->event_time ?? '05:00:00',
            ]);
            return $event->sangh;
        }

        // Check if a Sangh already exists for this year (created manually)
        $existing = Sangh::where('year', $year)->whereNull('event_id')->first();

        if ($existing) {
            // Link the existing Sangh to this event
            $existing->update(['event_id' => $event->id]);
            return $existing;
        }

        // Create a fresh Sangh record from the event data
        return Sangh::create([
            'event_id'        => $event->id,
            'year'            => $year,
            'title_en'        => $event->title_en,
            'title_gu'        => $event->title_gu,
            'description_en'  => $event->description_en,
            'description_gu'  => $event->description_gu,
            'start_date'      => $event->event_date,
            'end_date'        => $event->event_date->addDay(),
            'start_time'      => $event->event_time ?? '05:00:00',
            'total_distance_km' => 35,
            'status'          => 'draft',
        ]);
    }
}
