<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendanceDetails extends Model
{
    protected $guarded = [];
    public function teacherAttendance()
    {
        return $this->belongsTo(TeacherAttendance::class, 'teacher_attendance_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }
}
