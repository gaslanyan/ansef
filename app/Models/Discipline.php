<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $table = 'disciplines';

    protected $fillable = [
        'text'
    ];
    
    public function persons() {
        return $this->belongsToMany('App\Models\Person', 'disciplines_persons');
    }

}
