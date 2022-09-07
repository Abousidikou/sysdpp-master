<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'id_structure',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ]; 

    #Begin relationship
    public function data()
    {
        return $this->hasMany('App\Models\Data','id_user');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structures','id_structure');
    }
    #End relationship

    #Begin accessors
    public function getName($value)
    {
        return ucfirst($value);
    }

    public function getEmail($value)
    {
        return $value;
    }

    public function getIdStructure($value)
    {
        return $value;
    }
    #End accessors

    #Begin mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value;
    }

    public function setIdStructureAttribute($value)
    {
        $this->attributes['id_structure'] = $value;
    }
    #End mutators

    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }
}
