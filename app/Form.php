<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'user_id', 'form_name'
    ];

    public function formcoords()
    {
        return $this->hasMany('App\Formcoord')->orderBy('shape')->orderBy('field_name')->orderBy('q_id')->orderBy('q_option');
    }

    public function shareforms()
    {
        return $this->hasMany('App\Shareform');
    }

    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
