<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
  protected $fillable = [
      'class_id', 'student_id', 'student_name',
  ];

  public function classes()
  {
      return $this->belongsTo('App\Class');
  }

  public function students()
  {
      return $this->belongsTo('App\Student', 'student_id');
  }

}
