<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $table = 'budget_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'min', 'max', 'weight', 'competition_id'];
    public function competition()
    {
        return $this->belongsTo('App\Models\Competition');
    }
}