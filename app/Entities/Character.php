<?php

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Builder, Model, Relations\BelongsTo, Relations\BelongsToMany, Relations\HasOne, SoftDeletes
};

/**
 * Class Character
 *
 * @property int user_id
 * @property int fraction_id
 * @property string name
 * @property int experience
 * @property int agility
 * @property int strength
 * @property Carbon created_at
 * @property Carbon deleted_at
 * @property Carbon updated_at
 * @package App
 */
class Character extends Model
{
    use SoftDeletes;

    public const LIMIT_PER_USER = 5;
    public const DEFAULT_AGILITY = 5;
    public const DEFAULT_STRENGTH = 5;

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Relation to fraction model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fraction() : BelongsTo
    {
        return $this->belongsTo(Fraction::class);
    }

    /**
     * Relation to user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to Coordinate model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coordinate() : HasOne
    {
        return $this->hasOne(Coordinate::class);
    }

    /**
     * Relation to Abilities model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities() : BelongsToMany
    {
        return $this->belongsToMany(Ability::class, 'character_abilities')
            ->withPivot(['is_active']);
    }

    /**
     * Find models with provided user.
     *
     * @param Builder $query
     * @param User $user
     *
     * @return Builder
     */
    public function scopeWithUser(Builder $query, User $user) : Builder
    {
        return $query->where($this->getTable() . '.user_id', '=', $user->id);
    }

    /**
     * Scope query to find deleted models
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWithDeleted(Builder $query) : Builder
    {
        return $query->withTrashed()->where($this->getTable() . '.deleted_at', '!=', null);
    }
}

