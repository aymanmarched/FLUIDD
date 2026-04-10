<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Client;
use App\Models\Technicien;
use App\Models\Admin;
class UserObserver
{
    /**
     * Handle the User "created" event.
     */
   public function created(User $user): void
{
    \Log::info("Observer fired. User ID: {$user->id}, Code: {$user->code}");

    if ($user->code == 1) {
        \Log::info("Creating CLIENT for user {$user->id}");
        Client::create([
            'user_id' => $user->id,
        ]);
    }

    if ($user->code == 2) {
        \Log::info("Creating ADMIN for user {$user->id}");
        Admin::create([
            'user_id' => $user->id,
        ]);
    }

    if ($user->code == 3) {
        \Log::info("Creating TECHNICIEN for user {$user->id}");
        Technicien::create([
            'user_id' => $user->id,
        ]);
    }
}


    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
