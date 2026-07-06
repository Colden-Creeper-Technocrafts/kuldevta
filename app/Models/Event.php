<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    protected $fillable = [
        'title_en', 'title_gu', 'description_en', 'description_gu',
        'event_type', 'event_date', 'event_time', 'venue_en', 'venue_gu',
        'status', 'is_featured',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function sangh(): HasOne
    {
        return $this->hasOne(Sangh::class);
    }

    public function sponsors(): MorphMany
    {
        return $this->morphMany(Sponsor::class, 'sponsorable');
    }

    public function title(): string
    {
        return app()->getLocale() === 'gu' ? $this->title_gu : $this->title_en;
    }

    public function description(): ?string
    {
        return app()->getLocale() === 'gu' ? $this->description_gu : $this->description_en;
    }

    public function venue(): ?string
    {
        return app()->getLocale() === 'gu' ? $this->venue_gu : $this->venue_en;
    }

    public function typeBadgeClass(): string
    {
        return match($this->event_type) {
            'havan', 'monthly_havan' => 'bg-warning text-dark',
            'sangh'                  => 'bg-success',
            'annual_function'        => 'bg-danger',
            default                  => 'bg-info text-dark',
        };
    }
}
