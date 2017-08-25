<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
  protected $fillable = [
      'user_id', 'name', 'syllabus'
  ];

  public function classrooms()
  {
      return $this->hasMany('App\Classroom', 'class_id');
  }

  public function tests()
  {
      return $this->hasMany('App\Test', 'class_id');
  }

  public function users()
  {
      return $this->belongsTo('App\User');
  }
}
