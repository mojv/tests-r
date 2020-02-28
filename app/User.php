<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastName', 'country', 'company', 'email', 'password', 'gender', 'dateOfBirth', 'usage', 'pro', 'pro_sheets'
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

    public function students()
    {
        return $this->hasMany('App\Student');
    }

    public function classes()
    {
        return $this->hasMany('App\Classe');
    }
}
