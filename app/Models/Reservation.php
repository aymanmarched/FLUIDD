<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['client_id',  'reference','date_souhaite','hour'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

