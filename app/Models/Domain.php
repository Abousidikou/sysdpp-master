<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    //
    protected $table = 'domains';

    protected $guarded = [];

    #Begin relationship
    public function subdomain()
    {
        return $this->hasMany('App\Models\SubDomain','id_domain');
    }

    #End relationship

    #Begin accessors
    public function getWording($value)
    {
        return ucfirst($value);
    }

    #End accessors

    #Begin mutators

    public function setWordingAttribute($value)
    {
        $this->attributes['wording'] = $value;
    }

    #End mutators

}
