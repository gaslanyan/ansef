<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'city', 'province', 'street', 'addressable_id', 'addressable_type', 'user_id'
    ];


    public function country()
    {
        return $this->belongsTo('\App\Models\Country');
    }

    public function addressable()
    {
        return $this->morphTo();
    }

}
