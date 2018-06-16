<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeachProject extends Model
{
    protected $guarded = [
        "id"
    ];
    
    public function owner()
    {
        return $this->belongsTo('App\User', 'teacher_id');
    }
}
