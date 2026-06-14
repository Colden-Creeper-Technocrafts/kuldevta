<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Sponsor extends Model
{
    protected $fillable = [
        'user_id', 'sponsorable_type', 'sponsorable_id', 'name', 'mobile',
        'village_city', 'amount', 'sponsor_type', 'description_en', 'description_gu',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function sponsorable(): MorphTo
    {
        return $this->morphTo();
    }

    public function description(): ?string
    {
        return app()->getLocale() === 'gu' ? $this->description_gu : $this->description_en;
    }

    public function typeBadgeClass(): string
    {
        return match($this->sponsor_type) {
            'main'    => 'bg-danger',
            'gold'    => 'bg-warning text-dark',
            'silver'  => 'bg-secondary',
            default   => 'bg-light text-dark border',
        };
    }
}
