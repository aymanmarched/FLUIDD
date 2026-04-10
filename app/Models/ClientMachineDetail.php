<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientMachineDetail extends Model
{
 protected $fillable = [
        'client_id',
        'machine_id',
        'reference',
        'photo',
        'video'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function machine()
    {
        return $this->belongsTo(EntretenirMonMachine::class, 'machine_id');
    }
}

