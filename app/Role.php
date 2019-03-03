<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use MongoDB\Driver\Query;

class Role extends Model
{

    public const ADMIN = 'administrator';
    public const USER = 'user';

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

    /// Scopes

    /**
     * Scope return role with name.
     *
     * @param $query
     * @param string $name
     * @return Query
     */
    public function scopeWithName($query, string $name)
    {
        return $query->where($this->table . '.name', '=', $name);
    }
}
