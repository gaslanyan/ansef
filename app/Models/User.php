<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'password_salt', 'role', 'state', 'confirmation', 'requested_role_id', 'remember_token'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function person()
    {
        return $this->hasOne('App\Models\Person');
    }

    function rank()
    {
        return $this->hasOne('App\Models\Score');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    function proposals()
    {
        return $this->hasMany(Proposal::class);
    }


}
