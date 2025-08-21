<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SandQabd extends Model
{
  
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
           
            $maxNumber = self::max('code');
            $model->code = $maxNumber ? $maxNumber + 1 : 1;
        });
    }

}
