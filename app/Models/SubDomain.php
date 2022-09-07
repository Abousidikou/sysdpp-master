<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDomain extends Model
{
    //
    protected $table = 'sub_domains';

    protected $guarded = [];

    #Begin relationship
    public function domain()
    {
        return $this->belongsTo('App\Models\Domain','id_domain');
    }

    public function structure()
    {
        return $this->belongsToMany('App\Models\Structures', 'structures_subdomains', 'id_subdomain', 'id_structure');
    }

    public function indicators()
    {
        return $this->hasMany('App\Models\Indicators','id_subdomain');
    }
    
    #End relationship

    #Begin accessors
    public function getWording($value)
    {
        return ucfirst($value);
    }

    public function getIdDomain($value)
    {
        return $value;
    }

    #End accessors

    #Begin mutators

    public function setWordingAttribute($value)
    {
        $this->attributes['wording'] = $value;
    }

    public function setIdDomainAttribute($value)
    {
        $this->attributes['id_domain'] = $value;
    }

    #End mutators

}
