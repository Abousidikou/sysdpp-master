<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicators extends Model
{
    //
    protected $table = 'indicators';

    protected $guarded = [];

    #Begin relationship
    public function subdomain()
    {
        return $this->belongsTo('App\Models\SubDomain','id_subdomain');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Models\Level','indicators_level', 'id_indicator', 'id_level'); 
    }

    public function data()
    {
        return $this->hasMany('App\Models\Data','id_indicator');
    }
    #End relationship

    #Begin accessors
    public function getWording($value)
    {
        return ucfirst($value);
    }

    public function getIdSubDomain($value)
    {
        return $value;
    }

    #End accessors

    #Begin mutators

    public function setWordingAttribute($value)
    {
        $this->attributes['wording'] = $value;
    }

    public function setIdSubDomainAttribute($value)
    {
        $this->attributes['id_subdomain'] = $value;
    }

    #End mutators

}
