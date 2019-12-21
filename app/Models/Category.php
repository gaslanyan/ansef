<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','abbreviation','parent_id'
    ];
    public function children(){
        return $this->hasMany( Category::class, 'parent_id', 'id' );
    }

    public function parent(){
        return $this->belongsTo( Category::class, 'id', 'parent_id' );
    }

}
