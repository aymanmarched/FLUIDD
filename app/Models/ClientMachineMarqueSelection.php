<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ClientMachineMarqueSelection extends Model
{
    protected $fillable = [
        'client_id',
        'machine_id',
        'marque_id',
        'reference','is_submitted',
'submitted_at',
        
    'annule',
    ];
      protected $casts = [
        'is_submitted' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}


