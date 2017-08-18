<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $fillable = [
        'class_id', 'form_id', 'name', 'test_weight', 'titles', 'answers', 'answers_weight'
    ];

    public function classes()
    {
        return $this->belongsTo('App\Class');
    }

    public function results()
    {
        return $this->hasMany('App\Result');
    }

}
