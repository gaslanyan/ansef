<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proposals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'abstract', 'state', 'document', 'overall_score',
        'comment', 'rank', 'competition_id', 'categories', 'proposal_members','proposal_refeeres','proposal_admins'
         ];

    public function competition()
    {
        return $this->belongsTo(Competition::class , 'competition_id', 'id');
    }

    function report()
    {
        return $this->hasOne('App\Models\RefereeReport');
    }

    function proposalReport()
    {
        return $this->hasOne(ProposalReports::class);
    }

    function proposalReports()
    {
        return $this->hasMany(ProposalReports::class);
    }

    function reports()
    {
        return $this->hasMany('App\Models\RefereeReport');
    }

    function persons()
    {
        return $this->belongsToMany(Person::class , 'person_types', 'proposal_id', 'person_id')->withPivot('subtype');
    }

    function institutions()
    {
        return $this->belongsToMany(Institution::class, 'proposal_institutions', 'proposal_id', 'institution_id')->withPivot('institutionname');
    }
}
