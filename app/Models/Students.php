<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    protected $table = 'students';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function level()
    {
        return $this->hasOne(Level::class, 'id', 'level_id');
    }
    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_students', 'student_id', 'teacher_id');
    }
}
