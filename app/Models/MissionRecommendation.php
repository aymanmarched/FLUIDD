<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissionRecommendation extends Model
{
    protected $fillable = [
        'mission_id',
        'machine_id',
        'marque_id',
    ];
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

}
