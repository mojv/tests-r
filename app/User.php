<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastName', 'country', 'company', 'email', 'password', 'gender', 'dateOfBirth'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function forms()
    {
        return $this->hasMany('App\Form')->orderBy('form_name');
    }

    public function shareforms()
    {
        return $this->hasMany('App\Shareform');
    }

    public function shareforms_owner()
    {
        return $this->hasMany('App\Shareform', 'user_owner');
    }
}
