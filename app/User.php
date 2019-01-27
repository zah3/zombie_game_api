<?php

namespace App;

use App\Events\UserEvent;
use App\Http\Helpers\StatusResponse;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    public const GAME_TOKEN = "GameToken";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function($user){
            $userEvent = new UserEvent();
            event($userEvent->userCreated($user));
        });
    }

    /**
     * @param string || array $roles
     * @return bool
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(StatusResponse::STATUS_UNAUTHORIZED,'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(StatusResponse::STATUS_UNAUTHORIZED, 'This action is unauthorized.');
    }

    /**
     * Check if user has any of roles.
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        return !is_null($this->roles()->whereIn('name',$roles)->first());
    }

    /**
     * Check if user has 1 role.
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role)
    {
        return !is_null($this->roles()->where('name','=',$role)->first());
    }

    /**
     * Activate User.
     * @return bool || void
     */
    public function activate()
    {
        if ($this->is_active === false) {
            return $this->is_active = true;
        }
    }

    /**
     * Activate User.
     * @return bool
     */
    public function activateAndSave()
    {
        if ($this->is_active === false) {
            $this->is_active = true;
            $this->update();
            return true;
        } else {
            return false;
        }
    }


    /**
     * Scope for active users.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where($this->table . '.is_active','=',true);
    }

    /**
     * Scope with username
     *
     * @param $query
     * @param $username
     * @return mixed
     */
    public function scopeWithUsername($query, $username)
    {
        return $query->where($this->table . '.username','=',$username);
    }
}

