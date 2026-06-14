<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stoppage extends Model
{
    protected $fillable = [
        'sangh_id', 'name_en', 'name_gu', 'address_en', 'address_gu',
        'km_marker', 'sort_order', 'has_water', 'has_food',
        'has_tea', 'has_medical', 'has_rest',
    ];

    protected $casts = [
        'has_water' => 'boolean',
        'has_food' => 'boolean',
        'has_tea' => 'boolean',
        'has_medical' => 'boolean',
        'has_rest' => 'boolean',
    ];

    public function sangh(): BelongsTo
    {
        return $this->belongsTo(Sangh::class);
    }

    public function serviceLogs(): HasMany
    {
        return $this->hasMany(StoppageServiceLog::class);
    }

    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class, 'assigned_stoppage_id');
    }

    public function name(): string
    {
        return app()->getLocale() === 'gu' ? $this->name_gu : $this->name_en;
    }

    public function address(): ?string
    {
        return app()->getLocale() === 'gu' ? $this->address_gu : $this->address_en;
    }

    public function facilitiesList(): array
    {
        $list = [];
        if ($this->has_water) $list[] = 'water';
        if ($this->has_food) $list[] = 'food';
        if ($this->has_tea) $list[] = 'tea';
        if ($this->has_medical) $list[] = 'medical';
        if ($this->has_rest) $list[] = 'rest';
        return $list;
    }

    public function totalServicesCount(string $type): int
    {
        return $this->serviceLogs()->where('service_type', $type)->sum('count');
    }
}
