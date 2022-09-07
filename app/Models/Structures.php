<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Structures extends Model
{
    //
    protected $table = 'structures';

    protected $guarded = [];

    #Begin relationship

    public function user()
    {
        return $this->hasMany('App\User','id_structure');
    }

    public function subdomain()
    {
        return $this->belongsToMany('App\Models\SubDomain','structures_subdomains', 'id_structure', 'id_subdomain');
    }
    #End relationship

    #Begin accessors
    public function getWording($value)
    {
        return ucfirst($value);
    }

    public function getAbr($value)
    {
        return $value;
    }

    #End accessors

    #Begin mutators
    public function setWordingAttribute($value)
    {
        $this->attributes['wording'] = $value;
    }

    public function setAbrAttribute($value)
    {
        $this->attributes['abr'] = $value;
    }

    #End mutators
}
