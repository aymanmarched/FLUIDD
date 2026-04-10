<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissionStep extends Model
{
    protected $fillable = [
        'mission_id',
        'step_no',
        'comment',
        'media_path',
        'media_type',
    ];

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}
