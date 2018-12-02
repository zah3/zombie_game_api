<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public const ROLE_USER = 'user';

    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Relation to user model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
