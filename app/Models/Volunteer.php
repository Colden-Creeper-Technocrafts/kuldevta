<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volunteer extends Model
{
    protected $fillable = [
        'sangh_id', 'user_id', 'name', 'mobile', 'village_city',
        'role', 'assigned_stoppage_id',
    ];

    public function sangh(): BelongsTo
    {
        return $this->belongsTo(Sangh::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStoppage(): BelongsTo
    {
        return $this->belongsTo(Stoppage::class, 'assigned_stoppage_id');
    }
}
