<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalInstitution extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proposal_institutions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution_id', 'proposal_id'
    ];
}
