<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalReport extends Model
{
    protected $table = 'proposal_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'document', 'proposal_id', 'due_date', 'approved', 'user_id'
    ];
    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal');
    }
}
