<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalReports extends Model
{
    protected $table = 'proposal_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'document', 'proposal_id', 'due_date', 'approved'
    ];
    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal');
    }
}
