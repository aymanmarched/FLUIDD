<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
     protected $fillable = [
        'nom',
        'image',
        'caractere',
        'prix',
    ];
protected $casts = [
        'caractere' => 'array'
    ];
    public function machines()
    {
        return $this->belongsToMany(Machine::class);
    }
}
