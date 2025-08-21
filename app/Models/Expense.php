<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    //------------------------------------------------
    public function band()
    {
        return $this->belongsTo(BnodSarf::class);
    }
}
