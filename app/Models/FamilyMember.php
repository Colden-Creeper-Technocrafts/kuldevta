<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int         $id
 * @property int|null    $parent_id
 * @property int|null    $spouse_id
 * @property string      $first_name
 * @property string|null $middle_name
 * @property string      $last_name
 * @property string|null $suffix
 * @property string      $gender
 * @property \Illuminate\Support\Carbon|null $dob
 * @property bool        $is_daughter
 * @property bool        $is_married
 */
class FamilyMember extends Model
{
    protected $fillable = [
        'parent_id', 'spouse_id', 'first_name', 'middle_name', 'last_name',
        'suffix', 'gender', 'dob', 'is_daughter', 'is_married',
    ];

    protected $casts = [
        'dob'         => 'date',
        'is_daughter' => 'boolean',
        'is_married'  => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(FamilyMember::class, 'parent_id')->orderBy('first_name');
    }

    /** The married-in spouse (unidirectional: set on the born-family member). */
    public function spouse(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'spouse_id');
    }

    public function fullName(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])));
    }
}
