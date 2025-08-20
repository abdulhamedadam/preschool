<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendanceDetails extends Model
{
    protected $guarded = [];
    public function studentAttendance()
    {
        return $this->belongsTo(StudentAttendance::class, 'student_attendance_id');
    }
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
