<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $table = 'budget_categories';
    protected $fillable = [
        'name', 'min', 'max', 'weight', 'competition_id', 'comments'];

    public function competition()
    {
        return $this->belongsTo('App\Models\Competition', 'competition_id');
    }

    public function items() {
        return $this->hasMany('App\Models\BudgetItem');
    }

}
