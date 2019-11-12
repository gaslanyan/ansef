<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonType extends Model
{


    protected $table = 'person_type';
    protected $fillable = [
        'id','person_id', 'proposal_id','subtype'
    ];


}
