<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversionProposal extends Model
{
     protected $fillable = [
        'mission_id',
        'client_id',
        'old_reference',
    'new_reference',
        'status',
        'token',
    ];

    public function mission()
{
    return $this->belongsTo(\App\Models\Mission::class);
}
}
