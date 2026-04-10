<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Garantie extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'ville_id',
        'adresse',
        'machine_id',
        'marque_id',
        'machine_series',
        'date_garante',
    ];

    // Automatically cast date_garante to Carbon instance
    protected $casts = [
    'date_garante' => 'datetime',
];


    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

      public function machine() {
        return $this->belongsTo(Machine::class);
    }

    public function marque() {
        return $this->belongsTo(Marque::class);
    }


}
