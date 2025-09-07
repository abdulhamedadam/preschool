<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $table = 'curricula';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(CurriculumDetails::class, 'curriculum_id');
    }
}
