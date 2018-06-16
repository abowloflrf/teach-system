<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\SrtpProject;
use App\TeachProject;
use App\GraProject;

class User extends Authenticatable implements JWTSubject
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    //学生用户获取其单条SRTP
    public function getSrtp()
    {
        if ($this->role == 1)
            return SrtpProject::where('leader_id', $this->id)->first();
        else
            return null;
    }

    //学生获取其单条毕业项目
    public function getGraduation()
    {
        if ($this->role == 1)
            return GraProject::where('student_id', $this->id)->first();
        else
            return null;
    }

    //教师用户获取其所有教改项目
    public function getTeachProjects()
    {
        if ($this->role == 2)
            return TeachProject::where('teacher_id', $this->id)->get();
        else
            return [];
    }

    //教师用户获取其所有负责毕业项目
    public function getGraduations()
    {
        if ($this->role == 2)
            return GraProject::where('teacher_id', $this->id)->get();
        else
            return [];
    }
}
