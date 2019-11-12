<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    function role() {
        return $this->hasOne(Role::class);
    }
    public function persons()
    {
        return $this->hasManyThrough(
            Person::class,
            User::class,
            'role_id', // Foreign key on users table...
            'user_id', // Foreign key on persons table...
            'id', // Local key on role table...
            'id' // Local key on users table...
        );
    }
}
