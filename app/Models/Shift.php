<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'code',
        'description',
        'day_of_week',
        'clock_in',
        'clock_out',
        'break_in',
        'break_out',
        'allow_break',
        'is_general_shift'
    ];
}
