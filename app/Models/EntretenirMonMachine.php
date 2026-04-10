<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntretenirMonMachine extends Model
{
   protected $fillable = [
    'name', 'image', 'machine', 'remplacer_machine_id'
];


   
    public function types()
    {
       return $this->belongsToMany(
            TypeEquipement::class,
            'entretenir_machine_type_equipement',
            'machine_id',
            'type_id'
        );
    }
    public function remplacerMachine()
{
    return $this->belongsTo(\App\Models\Machine::class, 'remplacer_machine_id');
}


public function technicians()
{
    return $this->belongsToMany(Technician::class, 'entretenir_machine_technician', 'machine_id', 'technician_id');
}


    protected static function booted()
    {
        static::created(function ($machine) {
            // attach ALL types automatically
            // $allTypeIds = TypeEquipement::pluck('id')->toArray();
            // $machine->types()->sync($allTypeIds);
        });
    }
}
