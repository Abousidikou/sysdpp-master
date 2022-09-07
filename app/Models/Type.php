<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $table = 'type';

    protected $guarded = [];

    public function level()
    {
        return $this->hasMany('App\Models\Level','id_type');
    }

    public function getWording($value)
    {
        return ucfirst($value);
    }
}
