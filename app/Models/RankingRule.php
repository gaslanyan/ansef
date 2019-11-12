<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingRule extends Model
{
    protected $table = 'ranking_rules';
    protected $fillable = [
        'id','sql', 'value','user_id', 'competition_id', 'table_name'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
