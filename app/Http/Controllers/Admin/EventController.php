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
        $last = Event::where('event_type', 'annual_function')
            ->orderByDesc('event_date')
            ->first();

        $nextVarshikotsavYear = $last ? $last->event_date->year + 1 : now()->year;

        return view('admin.events.create', compact('nextVarshikotsavYear'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title_en'       => 'required|string|max:200',
            'title_gu'       => 'required|string|max:200',
            'description_en' => 'nullable|string',
            'description_gu' => 'nullable|string',
            'event_type'     => 'required|in:havan,monthly_havan,sangh,annual_function,special',
            'event_date'     => 'required|date',
            'event_time'     => 'nullable',
            'venue_en'       => 'nullable|string|max:200',
            'venue_gu'       => 'nullable|string|max:200',
            'status'         => 'required|in:upcoming,ongoing,completed,cancelled',
            'is_featured'    => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        $event = Event::create($data);

        if (in_array($event->event_type, ['sangh', 'annual_function'])) {
            $this->createOrSyncSangh($event);
        }

        $message = in_array($event->event_type, ['sangh', 'annual_function'])
            ? __('events.sangh_auto_created')
            : __('events.event_created');

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
            'event_type'     => 'required|in:havan,monthly_havan,sangh,annual_function,special',
            'event_date'     => 'required|date',
            'event_time'     => 'nullable',
            'venue_en'       => 'nullable|string|max:200',
            'venue_gu'       => 'nullable|string|max:200',
            'status'         => 'required|in:upcoming,ongoing,completed,cancelled',
            'is_featured'    => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        $event->update($data);

        if (in_array($event->event_type, ['sangh', 'annual_function'])) {
            $this->createOrSyncSangh($event);
        }

        return redirect()->route('admin.events.index')->with('success', __('events.event_updated'));
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', __('events.event_deleted'));
    }

    private function createOrSyncSangh(Event $event): Sangh
    {
        $eventDate = $event->event_date; // Carbon instance
        $year      = $eventDate->year;

        // For annual_function: walk starts 2 days before, ends on event day
        // For sangh: walk starts on event day itself
        $isAnnual  = $event->event_type === 'annual_function';
        $startDate = $isAnnual ? $eventDate->copy()->subDays(2) : $eventDate->copy();
        $endDate   = $isAnnual ? $eventDate->copy() : $eventDate->copy()->addDay();

        if ($event->sangh) {
            $event->sangh->update([
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]);
            return $event->sangh;
        }

        $existing = Sangh::where('year', $year)->whereNull('event_id')->first();
        if ($existing) {
            $existing->update([
                'event_id'   => $event->id,
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]);
            return $existing;
        }

        return Sangh::create([
            'event_id'          => $event->id,
            'year'              => $year,
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'total_distance_km' => 35,
            'status'            => 'draft',
        ]);
    }
}
