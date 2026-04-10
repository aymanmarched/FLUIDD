<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    public function index()
    {
        $minutes = 5;
        $threshold = now()->subMinutes($minutes)->timestamp;

        $sessionSub = DB::table('sessions')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity'))
            ->whereNotNull('user_id')
            ->groupBy('user_id');

        $admins = User::query()
            ->whereIn('role', ['admin', 'superadmin'])
            ->leftJoinSub($sessionSub, 'sess', fn($j) => $j->on('users.id', '=', 'sess.user_id'))
            ->select('users.*', 'sess.last_activity')
            ->orderByRaw("CASE WHEN users.role = 'superadmin' THEN 0 ELSE 1 END")
            ->orderBy('users.name')
            ->paginate(10);

        return view('admin.admins.index', compact('admins', 'threshold'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);

        $data = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|min:6',
        'role' => 'required|in:admin,superadmin', 
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        'password' => Hash::make($data['password']),
        'role' => $data['role'],
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin ajouté.');
    }

    public function edit(User $user)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        abort_if($user->role !== 'admin', 403);

        return view('admin.admins.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        abort_if($user->role !== 'admin', 403);

        $data = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'password' => 'nullable|min:6',
        'role' => 'required|in:admin,superadmin',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        'role' => $data['role'],
            'password' => !empty($data['password']) ? Hash::make($data['password']) : $user->password,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin modifié.');
    }

    public function destroy(User $user)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        abort_if($user->role !== 'admin', 403);

        $user->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin supprimé.');
    }
}
