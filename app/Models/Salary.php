<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $guarded = [];
    protected $table = 'salaries';


        protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
