<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientServiceSelection extends Model
{
    protected $fillable = ['client_id','machine_id','type_id','reference','is_submitted','submitted_at'];
      protected $casts = [
        'is_submitted' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function machine()
    {
        return $this->belongsTo(EntretenirMonMachine::class, 'machine_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeEquipement::class, 'type_id');
    }
}
