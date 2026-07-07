<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanghHawanAssignment extends Model
{
    protected $fillable = ['sangh_id', 'user_id', 'role'];

    public function sangh(): BelongsTo
    {
        return $this->belongsTo(Sangh::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
