<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinePerson extends Model
{
    protected $table = 'disciplines_persons';

    protected $fillable = [
        'discipline_id', 'person_id'
    ];
    public function discipline()
    {
        return $this->belongsTo('App\Models\Discipline');
    }
}
