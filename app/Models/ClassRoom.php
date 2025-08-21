<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'class_rooms';
    protected $guarded = [];


    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }
}
