<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Builder, Model, SoftDeletes
};

class Character extends Model
{
    use SoftDeletes;

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
}

