<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiseEnStage extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo('App\Models\AgentFormation','id_agent');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country','pays_stage_id');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State','region_stage_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City','ville_stage_id');
    }
}
