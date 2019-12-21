<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PersonType;

class Person extends Model
{
    protected $table = 'persons';
    protected $appends = ['assigned'];
    protected $fillable = [
        'id','birthdate', 'birthplace','sex', 'state', 'first_name', 'last_name', 'nationality','type','specialization','user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function phone()
    {
        return $this->hasOne('App\Models\Phone');
    }

    function report()
    {
        return $this->hasOne('App\Models\RefereeReport');
    }

    public function administeredproposals()
    {
        return $this->hasMany('App\Models\Proposal');
    }


    public function emails()
    {
        return $this->hasMany('App\Models\Email');
    }
    public function phones()
    {
        return $this->hasMany('App\Models\Phone');
    }
    public function addresses()
    {
        return $this->morphMany('App\Models\Address', 'addressable');
    }
    public function institutions()
    {
        return $this->belongsToMany('App\Models\Institution', 'institutions_persons')->withPivot('title', 'type', 'start', 'end');
    }
    public function degrees() {
        return $this->belongsToMany('App\Models\Degree', 'degrees_persons')->withPivot('year');
    }
    public function honors()
    {
        return $this->hasMany('App\Models\Honor');
    }
    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }
    public function meetings()
    {
        return $this->hasMany('App\Models\Meeting');
    }
    public function publications()
    {
        return $this->hasMany('App\Models\Publication');
    }

    public function getAssignedAttribute()
    {
        return (PersonType::where('person_id', '=', $this->id)->count() > 0);
    }

}
