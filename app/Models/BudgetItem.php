<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $table = 'budget_item';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'budget_cat_id', 'description', 'amount', 'proposal_id', 'user_id'];

    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal');
    }

    public function category() {
        return $this->belongsTo('App\Models\BudgetCategory', 'budget_cat_id');
    }

}
