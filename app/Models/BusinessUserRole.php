<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUserRole extends Model
{
    protected $fillable = [
        'user_id',
        'role'
    ];
}
