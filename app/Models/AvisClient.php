<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvisClient extends Model
{
    protected $fillable = [
         'client_id',
        'user_id',
        'nom',
        'prenom',
        'telephone',
        'stars',
        'message',
    ];
}
