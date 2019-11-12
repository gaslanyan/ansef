<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publications extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'publications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id','journal','title', 'year','ansef_supported','domestic'
    ];
    
    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

}
