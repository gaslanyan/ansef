<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meetings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id','description','year', 'ansef_supported', 'domestic', 'user_id'
    ];


    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

}
