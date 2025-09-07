<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluation extends Model
{
    protected $table = 'student_evaluations';

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function studentEvaluationDetails()
    {
        return $this->hasMany(StudentEvaluationDetails::class);
    }

    public function evaluationDetails()
    {
        return $this->hasMany(StudentEvaluationDetails::class, 'student_evaluation_id');
    }
}
