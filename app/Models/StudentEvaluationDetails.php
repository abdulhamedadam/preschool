<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluationDetails extends Model
{
    protected $table = 'student_evaluations_details';
    protected $guarded = [];


    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }

    //------------------------------------
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

}
