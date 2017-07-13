<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formcoord extends Model
{
    protected $fillable = [
        'form_id', 'field_name', 'x', 'y', 'w', 'h', 'r', 'shape', 'fill', 'multiMark', 'q_id', 'q_option', 'idField' 
    ];

    public function forms()
    {
        return $this->belongsTo('App\Form');
    }
}
