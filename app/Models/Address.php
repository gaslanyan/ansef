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
        'country_id', 'city_id', 'provence', 'street'
    ];

    public function institutions()
    {
        return $this->hasMany('App\Models\Institution');
    }

    public function country()
    {
        $this->belongsTo('\App\Models\Country', 'COUNTRY')->withPivot('country_name');
    }
//    public function city()
//    {
//        $this->belongsToMany(City::class);
//    }
    public function city()
    {
        $this->belongsTo('\App\Models\City', 'city_id', 'id');
    }
    public function person()
    {
        return $this->belongsToMany('App\Models\Person','person_address');
    }
    public function persons()
    {
        return $this->belongsTo('App\Models\Person','person_address');
    }
}
