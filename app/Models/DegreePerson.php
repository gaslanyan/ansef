<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DegreePerson extends Model
{
    protected $table = 'degrees_persons';

    protected $fillable = [
        'degree_id', 'year','person_id'
    ];
    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }
}
