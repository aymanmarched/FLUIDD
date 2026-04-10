<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;





class NotificationCenterController extends Controller
{

public function __construct()
{
    $this->middleware(function ($request, $next) {
        abort_unless(auth()->check(), 403);
        abort_if(auth()->user()->role === 'client', 403);
        return $next($request);
    });
}
    public function index()
    {
        $user = auth()->user();

      $notifications = auth()->user()
        ->notifications()
        ->latest()
        ->paginate(20);

    return view('notifications.index', compact('notifications'));     return view('notifications.index', compact('notifications'));
    }

    

    public function show(string $id)
    {
        $n = auth()->user()->notifications()->findOrFail($id);
        $n->markAsRead();
        return view('notifications.show', ['n' => $n]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

public function unreadCount()
{
    abort_unless(auth()->check(), 403);
    abort_if(auth()->user()->role === 'client', 403);

    

    return response()->json([
        'count' => auth()->user()->unreadNotifications()->count()
    ]);
}


}
