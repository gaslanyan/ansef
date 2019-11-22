<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{


    protected $table = 'persons';
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
    public function address()
    {
        return $this->belongsToMany('App\Models\Address', 'person_address');
    }
    function report()
    {
        return $this->hasOne('App\Models\RefereeReport');
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
    public function disciplines() {
        return $this->belongsToMany('App\Models\Discipline', 'disciplines_persons');
    }
    public function honors()
    {
        return $this->hasMany('App\Models\Honors');
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
        return $this->hasMany('App\Models\Publications');
    }
    function proposals()
    {
        return $this->belongsToMany(Proposal::class , 'person_types', 'person_id', 'proposal_id')->withPivot('subtype');
    }

}
