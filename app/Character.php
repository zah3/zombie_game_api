<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class Character extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Relation to fraction model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fraction()
    {
        return $this->belongsTo(Fraction::class);
    }

    /**
     * Relation to user model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

