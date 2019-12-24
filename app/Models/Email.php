<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Email extends Model
{
    use Notifiable;
    protected $table = 'emails';

    protected $fillable = [
        'email', 'person_id', 'user_id'
    ];

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

}
