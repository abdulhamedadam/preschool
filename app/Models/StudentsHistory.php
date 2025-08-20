<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsHistory extends Model
{
    protected $table = 'students_histories';

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
