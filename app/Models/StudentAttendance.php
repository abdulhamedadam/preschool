<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $guarded = [];

    public function students()
    {
        return $this->belongsToMany(
            Students::class,
            'student_attendance_details',
            'student_attendance_id',
            'student_id'
        );
    }

    public function details()
    {
        return $this->hasMany(StudentAttendanceDetails::class, 'student_attendance_id');
    }


    
}
