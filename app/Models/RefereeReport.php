<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefereeReport extends Model
{
    protected $table = 'referee_reports';
    protected $fillable = [
        'id','private_comment', 'public_comment','state', 'proposal_id',
        'due_date', 'scores', 'overall_score'
    ];
    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal');
    }
    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'referee_id', 'id');
    }
    public function score()
    {
        return $this->hasOne(Score::class);
    }
}
