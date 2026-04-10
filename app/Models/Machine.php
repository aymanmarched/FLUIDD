<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'name',
        'image',
        'garantie_period_days'
    ];

   public function marques()
{
    return $this->belongsToMany(Marque::class, 'machine_marque', 'machine_id', 'marque_id');
}

}
