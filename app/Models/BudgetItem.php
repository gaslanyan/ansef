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
        'budget_cat_id', 'description', 'amount', 'proposal_id'];
}
