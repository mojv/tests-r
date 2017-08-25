<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'test_id', 'student_id', 'omr_responses', 'img_responses', 'img_grade', 'omr_grade', 'grade'
    ];

    public function tests()
    {
        return $this->belongsTo('App\Test', 'test_id');
    }

    public function students()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }

}
