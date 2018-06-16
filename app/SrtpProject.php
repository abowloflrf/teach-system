<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SrtpProject extends Model
{
    protected $guarded = [
        "id"
    ];

    public function leader()
    {
        return $this->belongsTo('App\User','leader_id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id');
    }
}
