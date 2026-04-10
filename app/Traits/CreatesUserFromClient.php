<?php

namespace App\Traits;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
trait CreatesUserFromClient
{


    public  function  getOrCreateUserFromClient(array $clientData)
    {
        $user = User::where('email', $clientData['email'])
            ->orWhere('phone', $clientData['telephone'])
            ->first();

        if (!$user) {
            $password = Str::random(8);

            $user = User::create([
                'name' => $clientData['prenom'] . ' ' . $clientData['nom'],
                'email' => $clientData['email'] ?? 'client' . $clientData['telephone'] . '@auto.local',
                'phone' => $clientData['telephone'],
                'address' => $clientData['adresse'] ?? null,
                'password' => Hash::make($password),
            ]);

            // OPTIONAL: send WhatsApp / Email with password
        }

        return $user;
    }

}
