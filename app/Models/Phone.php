<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phones';
    protected $fillable = [
        'country_code', 'number', 'person_id', 'user_id'
    ];

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }
}
