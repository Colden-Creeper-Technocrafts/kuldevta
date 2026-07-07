<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int         $id
 * @property int         $sangh_id
 * @property string      $token
 * @property string      $name
 * @property string      $mobile
 * @property string|null $village
 * @property string|null $city
 * @property int|null    $age
 * @property string      $gender
 * @property int|null    $group_leader_id
 * @property string      $status
 * @property string      $registered_by
 */
class SanghParticipant extends Model
{
    protected $table = 'sangh_participants';

    public const HAWAN_ROLES = ['main', 'support_1', 'support_2', 'support_3', 'support_4'];

    protected $fillable = [
        'sangh_id', 'token', 'name', 'mobile', 'village', 'city', 'age', 'gender',
        'emergency_contact_name', 'emergency_contact_mobile', 'group_name',
        'is_group_leader', 'group_leader_id', 'status', 'registered_by',
        'confirmed_at', 'notes', 'hawan_role',
    ];

    protected $casts = [
        'is_group_leader' => 'boolean',
        'confirmed_at'    => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SanghParticipant $p) {
            if (empty($p->token)) {
                $p->token = strtoupper(Str::random(8));
            }
        });
    }

    public function sangh(): BelongsTo
    {
        return $this->belongsTo(Sangh::class);
    }

    public function groupLeader(): BelongsTo
    {
        return $this->belongsTo(SanghParticipant::class, 'group_leader_id');
    }

    public function groupMembers(): HasMany
    {
        return $this->hasMany(SanghParticipant::class, 'group_leader_id');
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'confirmed' => 'bg-success',
            'completed' => 'bg-primary',
            'dropped'   => 'bg-danger',
            default     => 'bg-warning text-dark',
        };
    }
}
