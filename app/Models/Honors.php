<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Honors extends Model
{
    protected $table = 'honors';

    protected $fillable = [
        'description', 'year','person_id'
    ];
    
    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

}
