<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $table = 'degrees';

    protected $fillable = [
        'text'
    ];

    function degreePerson() {
        return $this->hasOne(DegreePerson::class);
    }

    function persons() {
        return $this->belongsToMany(Person::class, 'degrees_persons')->withPivot('year');
    }
}
