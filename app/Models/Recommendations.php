<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendations extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recommendations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'proposal_id','person_id'
    ];

}
