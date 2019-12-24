<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DegreePerson extends Model
{
    protected $table = 'degree_persons';

    protected $fillable = [
        'degree_id', 'year', 'institution', 'institution_id', 'person_id'
    ];

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }
}
