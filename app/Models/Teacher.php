<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $guarded = [];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function supervisors()
    {
        return $this->belongsToMany(
            Supervisor::class,
            'supervisor_teachers',
            'teacher_id',
            'supervisor_id'
        );
    }

    //------------------------------------------------
    public function students()
    {
        return $this->hasMany(TeacherStudents::class,'teacher_id');
    }

}
