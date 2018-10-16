<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'name', 'last_name', 'email', 'photo', 'class_id'
    ];

    public function classes()
    {
        return $this->belongsTo('App\Classe');
    }

    public function users()
    {
        return $this->belongsTo('App\User');
    }

    public function results()
    {
        return $this->hasMany('App\Result');
    }
}
