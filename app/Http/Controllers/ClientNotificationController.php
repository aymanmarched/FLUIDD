<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

;

use Illuminate\Support\Facades\Auth;

class ClientNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('client.notifications.index', compact('notifications'));
    }

    public function read(string $id)
    {
        $user = Auth::user();
        $n = $user->notifications()->where('id', $id)->firstOrFail();

        $n->markAsRead();

        // Redirect to proposal if token exists
        $token = $n->data['token'] ?? null;
        if ($token) {
            return redirect()->route('client.proposals.show', $token);
        }

        // fallback
        return redirect()->route('client.dashboard');
    }

    public function readAll()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications sont marquées comme lues.');
    }
}
