<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'supervisors';
    protected $guarded = [];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class, 
            'supervisor_teachers', 
            'supervisor_id', 
            'teacher_id' 
        );
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
