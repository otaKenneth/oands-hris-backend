<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'email',
        'phone'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
