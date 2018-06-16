<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GraProject extends Model
{
    protected $guarded = [
        "id"
    ];

    public function student()
    {
        return $this->belongsTo('App\User', 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id');
    }
}
