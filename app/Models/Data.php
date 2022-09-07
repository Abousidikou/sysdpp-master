<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    //
    protected $table = 'data';

    protected $guarded = [];

    #Begin relationship 
    public function indicator() 
    {
        return $this->belongsTo('App\Models\Indicators','id_indicator');
    }

    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Models\Level','data_levels','id_data','id_level');
    }
    #End relationship

    #Begin accessors
    public function getObservation($value)
    {
        return ucfirst($value);
    }

    public function getValue($value)
    {
        return $value;
    }

    public function getPeriodicity($value)
    {
        return $value;
    }

    public function getIdLevel($value)
    {
        return $value;
    }

    public function getIdIndicator($value)
    {
        return $value;
    }

    public function getDateStart($value)
    {
        return $value;
    }

    public function getDateEnd($value)
    {
        return $value;
    }

    public function getYear($value)
    {
        return $value;
    }

    public function getIdUser($value)
    {
        return $value;
    }
    #End accessors

    #Begin mutators
    public function setObservationAttribute($value)
    {
        $this->attributes['observation'] = $value;
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $value;
    }

    public function setPeriodicityAttribute($value)
    {
        $this->attributes['periodicity'] = $value;
    }

    public function setIdLevelAttribute($value)
    {
        $this->attributes['id_level'] = $value;
    }

    public function setIdIndicatorAttribute($value)
    {
        $this->attributes['id_indicator'] = $value;
    }

    public function setYearAttribute($value)
    {
        $this->attributes['year'] = $value;
    }

    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] = $value;
    }

    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = $value;
    }

    public function setIdUserAttribute($value)
    {
        $this->attributes['id_user'] = $value;
    }
    #End mutators
}
