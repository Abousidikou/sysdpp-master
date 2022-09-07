<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    //
    protected $table = 'levelofdisintegration';

    protected $guarded = [];

    #Begin relationship
    public function type()
    {
        return $this->belongsTo('App\Models\Type','id_type');
    }

    public function indicators()
    {
        return $this->belongsToMany('App\Models\Indicators','indicators_level', 'id_level', 'id_indicator');
    }

    public function data()
    {
        return $this->belongsToMany('App\Models\Data','data_levels','id_level','id_data');
    }
    #End relationship

    #Begin accessors
    public function getWording($value)
    {
        return ucfirst($value);
    }

    public function getIdType($value)
    {
        return $value;
    }
    #End  accessors

    #Begin mutators

    public function setWordingAttribute($value)
    {
        $this->attributes['wording'] = $value;
    }

    public function setIdTypeAttribute($value)
    {
        $this->attributes['id_type'] = $value;
    }

    // public function setIdIndicatorAttribute($value)
    // {
    //     $this->attributes['id_indicator'] = $value;
    // }

    #End mutators
}
