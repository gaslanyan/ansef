<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalPerson extends Model
{


    protected $table = 'proposal_persons';
    protected $fillable = [
        'id','person_id', 'proposal_id', 'subtype', 'competition_id'
    ];


}
