<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public const LIMIT_IN_MINUTES = 60;

    protected $fillable = [
        'token', 'user_id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
