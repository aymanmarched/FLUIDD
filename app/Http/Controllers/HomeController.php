<?php

namespace App\Http\Controllers;


use App\Models\AvisClient;
use App\Models\Client;

use App\Models\HomeSection;
use App\Models\Marque;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private function getOrCreateClient(array $data)
    {
        // 1️⃣ Find user by email or phone
        $user = User::where('email', $data['email'])
            ->orWhere('phone', $data['telephone'])
            ->first();

        $isNewUser = false;

        // 2️⃣ Create user ONLY if not exists
        if (!$user) {
            $user = User::create([
                'name' => $data['prenom'] . ' ' . $data['nom'],
                'email' => $data['email'] ?? 'client' . $data['telephone'] . '@auto.local',
                'phone' => $data['telephone'],
                'address' => $data['adresse'] ?? null,
                'password' => Hash::make(Str::random(12)),
                'role' => 'client',
            ]);

            $isNewUser = true;
        }

        // 3️⃣ Find or create client (CRITICAL FIX)
        $client = Client::firstOrCreate(
            ['user_id' => $user->id],   // 🔥 UNIQUE CONDITION
            [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => $data['telephone'],
                'email' => $data['email'],
                'ville_id' => $data['ville_id'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'location' => $data['location'] ?? null,
            ]
        );

        // 4️⃣ Only new users need password setup
        if ($isNewUser) {
            $client->password_token = Str::random(60);
            $client->save();
        }

        return $client;
    }



    public function showRegisterForm()
    {
        $villes = Ville::all(); // fetch all cities
        return view('auth.register', compact('villes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => ['required', 'regex:/^(0)?[6-7]\d{8}$/'],
            'email' => 'nullable|email',
            'ville_id' => 'nullable|exists:villes,id',
            'adresse' => 'nullable|string',
            'location' => 'nullable|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Format phone
        $rawPhone = preg_replace('/\D/', '', $request->telephone);
        if (str_starts_with($rawPhone, '0')) {
            $rawPhone = substr($rawPhone, 1);
        }
        $formattedPhone = '+212' . $rawPhone;

        // Create user
        $user = User::create([
            'name' => $request->prenom . ' ' . $request->nom,
            'email' => $request->email ?? 'client' . $formattedPhone . '@auto.local',
            'phone' => $formattedPhone,
            'address' => $request->adresse ?? null,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        // Create client linked to user
        $client = Client::create([
            'user_id' => $user->id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $formattedPhone,
            'email' => $request->email,
            'ville_id' => $request->ville_id,
            'adresse' => $request->adresse,
            'location' => $request->location,
        ]);

        // Login user automatically
        Auth::login($user);

        // Redirect to client dashboard
        return redirect()->route('client.dashboard')->with('success', 'Bienvenue, ' . $user->name);
    }


    public function home()
    {




        // dynamic: avis
        $avis = AvisClient::latest()->take(20)->get();

        // dynamic: marques (IMPORTANT: keep light query)
        $marques = Marque::query()
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'not like', 'data:%') // avoid base64
            ->inRandomOrder()
            ->take(80) // adjust 40-120 max
            ->get(['id', 'nom', 'image']);
        $siteSettings = SiteSetting::first();

        return view('user.home', compact('siteSettings', 'avis', 'marques'));
    }

    public function create()
{
    $settings = SiteSetting::first();
    $siteSettings = $settings;
    $villes = Ville::all();

    return view('auth.auth', compact('settings', 'siteSettings', 'villes'));
}

public function loginView()
{
    $settings = SiteSetting::first();
    $siteSettings = $settings;
    $villes = Ville::all(); // not required for login, but harmless for same view

    return view('auth.auth', compact('settings', 'siteSettings', 'villes'));
}
}
