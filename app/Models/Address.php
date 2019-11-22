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
        'country_id', 'city', 'province', 'street', 'addressable_id', 'addressable_type'
    ];


    public function country()
    {
        $this->belongsTo('\App\Models\Country', 'COUNTRY');
    }

    public function addressable()
    {
        return $this->morphTo();
    }

}
