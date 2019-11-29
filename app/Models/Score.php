<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'score_type_id', 'value', 'report_id'
    ];

    public function scoreType()
    {
        return $this->belongsTo(ScoreType::class );
    }
    public function report()
    {
        return $this->belongsTo(RefereeReport::class);
    }
}
