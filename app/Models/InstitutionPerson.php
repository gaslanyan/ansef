<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionPerson extends Model
{
    protected $table = 'institutions_persons';
    protected $fillable = [
        'person_id', 'institution_id', 'title', 'start', 'end', 'type'
    ];
    public function iperson()
    {
        return $this->belongsTo(Institution::class ,'institution_id');
    }

}
