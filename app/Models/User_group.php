<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User_group extends Model
{
    use Notifiable;

    protected $table = 'user_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_user_id', 'group_user_id'
    ];
}
