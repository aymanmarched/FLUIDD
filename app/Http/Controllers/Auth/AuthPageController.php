<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthPageController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::first();
        $siteSettings = $settings;
        $villes = Ville::orderBy('name')->get();

        return view('auth.auth', compact('settings', 'siteSettings', 'villes'));
    }

    public function submit(Request $request)
    {
        $mode = $request->input('mode');

        if ($mode === 'login') {
            return $this->handleLogin($request);
        }

        if ($mode === 'register') {
            return $this->handleRegister($request);
        }

        return back()
            ->withInput()
            ->withErrors(['mode' => 'Action invalide.'], 'login');
    }

    protected function handleLogin(Request $request)
    {
        $data = $request->validateWithBag('login', [
            'mode' => ['required', 'in:login,register'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'L’email est obligatoire.',
            'email.email' => 'Veuillez entrer un email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email ou mot de passe incorrect.'], 'login')
                ->withInput($request->only('email', 'remember', 'mode'));
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectByRole(Auth::user()));
    }

    protected function handleRegister(Request $request)
    {
        $data = $request->validateWithBag('register', [
            'mode' => ['required', 'in:login,register'],
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'telephone' => ['required', 'regex:/^(0)?[6-7][0-9]{8}$/', 'unique:users,email'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'ville_id' => ['nullable', 'exists:villes,id'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'telephone.regex' => 'Le téléphone doit être au format 6XXXXXXXX, 7XXXXXXXX, 06XXXXXXXX ou 07XXXXXXXX.',
            'telephone.unique' => 'Cet telephone est déjà utilisé.',
            'email.required' => 'L’email est obligatoire.',
            'email.email' => 'Veuillez entrer un email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'ville_id.exists' => 'La ville sélectionnée est invalide.',
            'adresse.max' => 'L’adresse est trop longue.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $formattedPhone = $this->normalizeMoroccanPhone($data['telephone']);

        if (User::where('phone', $formattedPhone)->exists()) {
            return back()
                ->withErrors(['telephone' => 'Ce numéro de téléphone est déjà utilisé.'], 'register')
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        if (Client::where('telephone', $formattedPhone)->exists()) {
            return back()
                ->withErrors(['telephone' => 'Ce numéro de téléphone est déjà utilisé.'], 'register')
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $user = DB::transaction(function () use ($data, $formattedPhone) {
            $user = User::create([
                'name' => trim($data['prenom'] . ' ' . $data['nom']),
                'email' => $data['email'],
                'phone' => $formattedPhone,
                'address' => $data['adresse'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => 'client',
            ]);

            Client::create([
                'user_id' => $user->id,
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => $formattedPhone,
                'email' => $data['email'],
                'ville_id' => $data['ville_id'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'location' => null,
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect($this->redirectByRole($user));
    }

    protected function normalizeMoroccanPhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($phone, '212')) {
            $phone = substr($phone, 3);
        }

        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        return '+212' . $phone;
    }

    protected function redirectByRole($user): string
    {
        return match ($user->role ?? 'client') {
            'admin', 'superadmin' => route('admin.home'),
            'technicien' => route('technicien.profile'),
            default => route('client.dashboard'),
        };
    }
}