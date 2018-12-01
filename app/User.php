<?php

namespace App;

use App\Events\UserEvent;
use App\Http\Helpers\StatusResponse;
use App\Providers\EventServiceProvider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;

class User extends Authenticatable
{
    use Notifiable,
        HasApiTokens,
        SoftDeletes;

    public const GAME_TOKEN = "GameToken";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    public $timestamps = [
        'created_at',
        'updated_at'
    ];

    /**
     * softDelete attribute.
     * @var array
     */
    public $dates = ['deleted_at'];

    /**
     * Relation to character model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Relation to role model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function($user){
            $userEvent = new UserEvent();
            event($userEvent->userCreated($user));
        });
    }

    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(StatusResponse::STATUS_UNAUTHORIZED,'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(StatusResponse::STATUS_UNAUTHORIZED, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
        return !is_null($this->roles()->whereIn('name',$roles)->first());
    }

    public function hasRole($role)
    {
        return !is_null($this->roles()->where('name','=',$role)->first());
    }

}

