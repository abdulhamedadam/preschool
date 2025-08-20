<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorTeachers extends Model
{
    protected $table = 'supervisor_teachers';

    protected $guarded = [];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'supervisor_teachers', 'supervisor_id', 'teacher_id');
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'supervisor_teachers', 'teacher_id', 'supervisor_id');
    }
}
