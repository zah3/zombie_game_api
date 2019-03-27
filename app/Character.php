<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Builder, Model, SoftDeletes
};

class Character extends Model
{
    use SoftDeletes;

    public const LIMIT_PER_USER = 5;

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Relation to fraction model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fraction()
    {
        return $this->belongsTo(Fraction::class);
    }

    /**
     * Relation to user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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

