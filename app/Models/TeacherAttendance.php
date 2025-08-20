<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $guarded = [];
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function students()
    {
        return $this->hasMany(TeacherAttendanceDetails::class, 'teacher_attendance_id');
    }


    public function details()
    {
        return $this->hasMany(TeacherAttendanceDetails::class, 'teacher_attendance_id');
    }
}
