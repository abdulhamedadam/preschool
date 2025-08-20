<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherStudents extends Model
{
    protected $table = 'teacher_students';
    
    protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
