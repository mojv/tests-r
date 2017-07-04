<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shareform extends Model
{
    public function forms()
    {
        return $this->belongsTo('App\Form', 'form_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function user_owners()
    {
        return $this->belongsTo('App\User', 'user_owner');
    }
}
