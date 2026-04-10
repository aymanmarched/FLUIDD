<?php

namespace App\Actions;

use Laravel\Fortify\Contracts\LoginResponse;

class RedirectAfterLogin implements LoginResponse
{
    public function toResponse($request)
    {
        $user = auth()->user();

       
        // Use the 'role' or 'code' field to redirect
        if ($user->role === 'admin' || $user->role === 'superadmin') {
            return redirect('/admin');
        }

        if ($user->role === 'client' ) {
            return redirect('/client');
        }

        if ($user->role === 'technicien' ) {
            return redirect('/technicien');
        }

        // fallback
        return redirect('/');

       
    }
}
