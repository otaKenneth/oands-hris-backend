<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
