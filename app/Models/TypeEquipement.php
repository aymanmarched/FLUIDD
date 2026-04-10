<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    protected $fillable = [
        'name', 'caracteres', 'prix'
    ];

    protected $casts = [
        'caracteres' => 'array'
    ];

    public function machines()
    {  return $this->belongsToMany(
            EntretenirMonMachine::class,
            'entretenir_machine_type_equipement', // pivot table
            'type_id',  // foreign key on pivot for this model
            'machine_id' // foreign key on pivot for EntretenirMonMachine
        );
    }
 

}
