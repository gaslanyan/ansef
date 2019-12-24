<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','publisher','year', 'person_id', 'user_id'
    ];


    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

}
