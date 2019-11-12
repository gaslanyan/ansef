<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Person_groups extends Model
{


    protected $table = 'person_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_person_id', 'group_person_id'
    ];
}
