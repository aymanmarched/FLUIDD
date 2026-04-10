<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Technician extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'password_visible',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

