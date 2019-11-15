<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;

    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function country(){
        return $this->belongsToMany(Country::class, 'address', 'city_id', 'country_id');
    }
}
