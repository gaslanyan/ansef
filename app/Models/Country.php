<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;


    public function address()
    {
        return $this->hasMany(Address::class);
    }

//    public function city(){
//        return $this->belongsToMany(City::class, 'address', 'country_id', 'city_id');
//    }
}
