<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\User
 *
 */
class User extends Authenticatable implements JWTSubject
{

    use Notifiable, HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
        'email_verified_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'mobile',
        'email',
        'role_id',
        'password',
        'title'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function setRole($role)
    {
        return $this->role()->associate($role);
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
