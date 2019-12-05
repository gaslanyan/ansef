<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'competitions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'submission_start_date', 'submission_end_date', 'announcement_date',
        'project_start_date', 'duration', 'min_budget', 'max_budget', 'min_level_deg_id', 'max_level_deg_id',
        'min_age', 'max_age', 'allow_foreign', 'comments', 'first_report', 'second_report', 'state',
        'recommendations', 'categories', 'additional', 'instructions'
    ];
    public function min_degree()
    {
        return $this->belongsTo('App\Models\Degree', 'min_level_deg_id');
    }
    public function max_degree()
    {
        return $this->belongsTo('App\Models\Degree', 'max_level_deg_id');
    }
    function rank()
    {
        return $this->hasOne('App\Models\Score');
    }
    function score()
    {
        return $this->hasMany('App\Models\ScoreType','competition_id');
    }
    function proposal()
    {
        return $this->hasOne(Proposal::class);
    }
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
    public function refereereports()
    {
        return $this->hasMany(RefereeReport::class);
    }

    public function budgetCategories() {
        return $this->hasMany(BudgetCategory::class);
    }

    public function proposalsCount()
    {
        return $this->proposals()
            ->selectRaw('competition_id, count(competition_id) as p_count')
            ->groupBy('competition_id');
    }



}
