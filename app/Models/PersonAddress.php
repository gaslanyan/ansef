<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonAddress extends Model
{
    protected $table = 'person_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id', 'address_id'
    ];
}
