<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'name', 'last_name', 'email', 'photo'
    ];

    public function classrooms()
    {
        return $this->hasMany('App\Classroom');
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
