<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreType extends Model
{
    protected $table = 'score_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description', 'min', 'max', 'weight', 'competition_id'];

    public function competition()
    {
        return $this->belongsTo('App\Models\Competition');
    }

    function score()
    {
        return $this->hasOne('App\Models\Score', 'score_type_id');
    }

}
