<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        // Add other fields as necessary
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
