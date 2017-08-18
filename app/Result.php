<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'test_id', 'student_id', 'answers', 'grade'
    ];

    public function tests()
    {
        return $this->belongsTo('App\Test');
    }

    public function students()
    {
        return $this->belongsTo('App\Student');
    }

}
