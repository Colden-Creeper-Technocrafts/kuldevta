<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoppageServiceLog extends Model
{
    protected $fillable = [
        'stoppage_id', 'service_type', 'count', 'logged_by', 'logged_at', 'notes',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function stoppage(): BelongsTo
    {
        return $this->belongsTo(Stoppage::class);
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
