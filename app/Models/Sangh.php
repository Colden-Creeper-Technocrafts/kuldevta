<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int|null    $event_id
 * @property int         $year
 * @property Carbon      $end_date
 * @property Carbon|null $registration_open_from
 * @property Carbon|null $registration_open_until
 * @property int         $total_distance_km
 * @property string      $status
 */
class Sangh extends Model
{
    protected $fillable = [
        'event_id', 'source_en', 'source_gu', 'year', 'start_date', 'end_date',
        'registration_open_from', 'registration_open_until',
        'total_distance_km', 'status',
    ];

    protected $casts = [
        'start_date'              => 'date',
        'end_date'                => 'date',
        'registration_open_from'  => 'date',
        'registration_open_until' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function stoppages(): HasMany
    {
        return $this->hasMany(Stoppage::class)->orderBy('sort_order');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SanghParticipant::class);
    }

    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class);
    }

    public function sponsors()
    {
        return $this->morphMany(Sponsor::class, 'sponsorable');
    }

    public function hawanAssignments(): HasMany
    {
        return $this->hasMany(SanghHawanAssignment::class)
                    ->with('user')
                    ->orderByRaw("FIELD(role, 'main','support_1','support_2','support_3','support_4')");
    }

    // ── Delegated getters (read from Event) ────────────────────

    public function title(): string
    {
        return $this->event?->title() ?? "Sangh {$this->year}";
    }

    public function description(): ?string
    {
        return $this->event?->description();
    }

    public function startDate()
    {
        return $this->start_date ?? $this->event?->event_date;
    }

    public function startTime(): string
    {
        return $this->event?->event_time ?? '05:00:00';
    }

    // ── Business logic ─────────────────────────────────────────

    public function isRegistrationOpen(): bool
    {
        if ($this->status !== 'registration_open') return false;
        $today = now()->toDateString();
        if ($this->registration_open_from && $today < $this->registration_open_from->toDateString()) return false;
        if ($this->registration_open_until && $today > $this->registration_open_until->toDateString()) return false;
        return true;
    }

    public function confirmedCount(): int
    {
        return $this->participants()->where('status', 'confirmed')->count();
    }

    public function registeredCount(): int
    {
        return $this->participants()->count();
    }
}
