<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'telephone',
        'email',
        'ville_id',
        'adresse',
        'location',
        'sms_verification_code',
        'sms_verification_reference',
        'sms_verification_expires_at',
        'sms_verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function selections()
    {
        return $this->hasMany(ClientServiceSelection::class);
    }

    public function selectionsremplacer()
    {
        return $this->hasMany(ClientMachineMarqueSelection::class);
    }


    public function machineDetails()
    {
        return $this->hasMany(ClientMachineDetail::class);
    }

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }

}
