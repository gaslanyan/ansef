<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $table = 'institutions';
    protected $fillable = [
        'address_id', 'content'
    ];

    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }

    function institution()
    {
        return $this->hasMany(InstitutionPerson::class,'id');
    }
    function institutions()
    {
        return $this->belongsToMany(Person::class, 'institutions_persons');
    }
    function proposals()
    {
        return $this->belongsToMany(Proposal::class, 'proposal_institutions', 'institution_id', 'proposal_id')->withPivot('institutionname');
    }

}
