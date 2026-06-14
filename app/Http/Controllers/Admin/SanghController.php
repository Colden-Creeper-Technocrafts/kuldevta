<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sangh;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SanghController extends Controller
{
    public function index(): View
    {
        $sanghs = Sangh::with('event')->orderByDesc('year')->get();
        return view('admin.sangh.index', compact('sanghs'));
    }

    public function create(): View
    {
        $sangh = new Sangh();
        return view('admin.sangh.create', compact('sangh'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Event fields
            'title_en'                => 'required|string|max:200',
            'title_gu'                => 'required|string|max:200',
            'description_en'          => 'nullable|string',
            'description_gu'          => 'nullable|string',
            'event_date'              => 'required|date',
            'event_time'              => 'nullable',
            'venue_en'                => 'nullable|string|max:200',
            'venue_gu'                => 'nullable|string|max:200',
            // Sangh-only fields
            'source_en'               => 'nullable|string|max:200',
            'source_gu'               => 'nullable|string|max:200',
            'end_date'                => 'required|date|after_or_equal:event_date',
            'registration_open_from'  => 'nullable|date',
            'registration_open_until' => 'nullable|date',
            'total_distance_km'       => 'required|integer|min:1',
            'status'                  => 'required|in:draft,registration_open,registration_closed,in_progress,completed',
        ]);

        $year = date('Y', strtotime($data['event_date']));

        if (Sangh::where('year', $year)->exists()) {
            return back()->withInput()->withErrors(['event_date' => "A Sangh for year {$year} already exists."]);
        }

        // Create Event first
        $event = Event::create([
            'title_en'       => $data['title_en'],
            'title_gu'       => $data['title_gu'],
            'description_en' => $data['description_en'] ?? null,
            'description_gu' => $data['description_gu'] ?? null,
            'event_type'     => 'sangh',
            'event_date'     => $data['event_date'],
            'event_time'     => $data['event_time'] ?? null,
            'venue_en'       => $data['venue_en'] ?? null,
            'venue_gu'       => $data['venue_gu'] ?? null,
            'status'         => $this->eventStatusFrom($data['status']),
            'is_featured'    => true,
        ]);

        // Create Sangh with only its own fields
        $sangh = Sangh::create([
            'event_id'                => $event->id,
            'source_en'               => $data['source_en'] ?? null,
            'source_gu'               => $data['source_gu'] ?? null,
            'year'                    => $year,
            'end_date'                => $data['end_date'],
            'registration_open_from'  => $data['registration_open_from'] ?? null,
            'registration_open_until' => $data['registration_open_until'] ?? null,
            'total_distance_km'       => $data['total_distance_km'],
            'status'                  => $data['status'],
        ]);

        return redirect()->route('admin.sangh.show', $sangh)
            ->with('success', 'Event and Sangh created successfully.');
    }

    public function show(Sangh $sangh): View
    {
        $sangh->load(['stoppages', 'participants', 'event']);
        $stats = [
            'registered' => $sangh->participants()->count(),
            'confirmed'  => $sangh->participants()->where('status', 'confirmed')->count(),
            'completed'  => $sangh->participants()->where('status', 'completed')->count(),
            'dropped'    => $sangh->participants()->where('status', 'dropped')->count(),
        ];
        return view('admin.sangh.show', compact('sangh', 'stats'));
    }

    public function edit(Sangh $sangh): View
    {
        $sangh->load('event');
        return view('admin.sangh.edit', compact('sangh'));
    }

    public function update(Request $request, Sangh $sangh): RedirectResponse
    {
        $data = $request->validate([
            'source_en'               => 'nullable|string|max:200',
            'source_gu'               => 'nullable|string|max:200',
            'end_date'                => 'required|date',
            'registration_open_from'  => 'nullable|date',
            'registration_open_until' => 'nullable|date',
            'total_distance_km'       => 'required|integer|min:1',
            'status'                  => 'required|in:draft,registration_open,registration_closed,in_progress,completed',
        ]);

        $sangh->update($data);

        // Keep Event status in sync
        $sangh->event?->update(['status' => $this->eventStatusFrom($data['status'])]);

        return redirect()->route('admin.sangh.show', $sangh)
            ->with('success', 'Sangh updated successfully.');
    }

    public function destroy(Sangh $sangh): RedirectResponse
    {
        if ($sangh->event) {
            $sangh->event->delete();
        }
        $sangh->delete();

        return redirect()->route('admin.sangh.index')
            ->with('success', 'Sangh and its event deleted.');
    }

    private function eventStatusFrom(string $sanghStatus): string
    {
        return match($sanghStatus) {
            'in_progress' => 'ongoing',
            'completed'   => 'completed',
            default       => 'upcoming',
        };
    }
}
