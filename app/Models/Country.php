<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;


    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

}
