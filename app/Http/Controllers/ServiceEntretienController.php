<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\ClientMachineDetail;
use App\Models\ClientServiceSelection;
use App\Models\EntretenirMonMachine;
use App\Models\Reservation;
use App\Models\Ville;
use App\Notifications\CommandeChangedNotification;
use App\Services\InfobipSms;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
class ServiceEntretienController extends Controller
{

    private function generateEntretienReference(): string
    {
        $date = Carbon::now()->format('Ymd');

        // Get last reference for TODAY
        $lastRef = ClientServiceSelection::where('reference', 'like', "ENT-$date-%")
            ->orderByDesc('id')
            ->value('reference');

        if ($lastRef) {
            $lastNumber = intval(substr($lastRef, -6));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 100000;
        }

        return "ENT-$date-$nextNumber";
    }

    private function generateVerificationCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function verificationSessionKey(int $clientId, string $reference): string
    {
        return "entretien.sms_verified.{$clientId}.{$reference}";
    }

    private function sendStep4VerificationSms(Client $client, string $reference): void
    {
        $code = $this->generateVerificationCode();

        $client->update([
            'sms_verification_code' => Hash::make($code),
            'sms_verification_reference' => $reference,
            'sms_verification_expires_at' => now()->addMinutes(10),
            'sms_verified_at' => null,
        ]);

        $text =
            "Bonjour {$client->prenom},\n" .
            "Votre code de vérification est : {$code}\n" .
            "Référence : {$reference}\n" .
            "Ce code expire dans 10 minutes.";

        app(InfobipSms::class)->send($client->telephone, $text);
    }

   
    public function step1()
    {
        $machines = EntretenirMonMachine::all();
        return view('user.service.entretien.entretenir-ma-maison', compact('machines'));
    }

    public function step1Store(Request $request)
{
    $request->validate([
        'machines' => 'required|array',
        'machines.*' => 'exists:entretenir_mon_machines,id',
    ]);

    $reference = $this->generateEntretienReference();
    $selectionIds = [];

    foreach ($request->machines as $machineId) {
        $selection = ClientServiceSelection::create([
            'client_id' => null,
            'machine_id' => $machineId,
            'reference' => $reference,
            'is_submitted' => false,
            'submitted_at' => null,
        ]);

        $selectionIds[] = $selection->id;
    }

    return redirect()->route('service.entretien.entretenir.step2', [
        'selection_ids' => implode(',', $selectionIds),
        'reference' => $reference
    ]);
}


   
    public function step2(Request $request)
    {
        // $selectionIds comes from Step 1 (comma-separated)
        $selectionIds = explode(',', $request->selection_ids ?? '');
        $selections = ClientServiceSelection::whereIn('id', $selectionIds)->get();
        $reference = $request->reference;
        return view('user.service.entretien.entretenir.step2', compact('selections', 'reference'));
    }

    public function step2Store(Request $request, )
    {
        $request->validate([
            'types' => 'required|array',
            'types.*' => 'required|exists:type_equipements,id'
        ]);

        foreach ($request->types as $selectionId => $typeId) {
            $selection = ClientServiceSelection::findOrFail($selectionId);
            $selection->update(['type_id' => $typeId]);
        }

        // Redirect to Step 3 (client info + photo/video)
        $selectionIds = array_keys($request->types);

        return redirect()->route('service.entretien.entretenir.step3', [
            'selection_ids' => implode(',', $selectionIds),
            'reference' => $request->reference
        ]);

    }
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

  
    public function step3(Request $request)
    {
        $selectionIds = explode(',', $request->selection_ids);
        $selections = ClientServiceSelection::whereIn('id', $selectionIds)->get();
        $villes = Ville::all();
        $reference = $request->reference;

        $client = auth()->check() ? auth()->user()->client : null;

        return view('user.service.entretien.entretenir.step3', compact(
            'selections',
            'villes',
            'reference',
            'client'
        ));
    }




   public function resendSms(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'reference' => 'required|string',
    ]);

    $client = Client::findOrFail($request->client_id);

    $exists = ClientServiceSelection::where('client_id', $client->id)
        ->where('reference', $request->reference)
        ->exists();

    if (!$exists) {
        return back()->withErrors([
            'reference' => 'Référence invalide.'
        ]);
    }

    $rateKey = 'entretien-resend-sms:' . $client->id . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($rateKey, 3)) {
        $seconds = RateLimiter::availableIn($rateKey);

        return back()->withErrors([
            'verification_code' => "Trop de demandes. Réessayez dans {$seconds} secondes."
        ]);
    }

    RateLimiter::increment($rateKey);

    $this->sendStep4VerificationSms($client, $request->reference);

    $request->session()->forget(
        $this->verificationSessionKey($client->id, $request->reference)
    );

    return back()->with('success', 'Un nouveau code a été envoyé par SMS.');
}

public function verifyStep4Sms(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'reference' => 'required|string',
        'verification_code' => 'required|digits:6',
    ]);

    $client = Client::findOrFail($request->client_id);

    $exists = ClientServiceSelection::where('client_id', $client->id)
        ->where('reference', $request->reference)
        ->exists();

    if (!$exists) {
        return back()->withErrors([
            'reference' => 'Référence invalide.'
        ])->withInput();
    }

    $rateKey = 'entretien-check-sms:' . $client->id . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($rateKey, 5)) {
        $seconds = RateLimiter::availableIn($rateKey);

        return back()->withErrors([
            'verification_code' => "Trop d'essais. Réessayez dans {$seconds} secondes."
        ])->withInput();
    }

    if (
        !$client->sms_verification_code ||
        !$client->sms_verification_expires_at ||
        !$client->sms_verification_reference ||
        $client->sms_verification_reference !== $request->reference ||
        now()->gt($client->sms_verification_expires_at)
    ) {
        return back()->withErrors([
            'verification_code' => 'Code expiré ou invalide. Veuillez demander un nouveau code.'
        ])->withInput();
    }

    if (!Hash::check($request->verification_code, $client->sms_verification_code)) {
        RateLimiter::increment($rateKey);

        return back()->withErrors([
            'verification_code' => 'Code incorrect.'
        ])->withInput();
    }

    RateLimiter::clear($rateKey);

    $client->update([
        'sms_verified_at' => now(),
        'sms_verification_code' => null,
        'sms_verification_reference' => null,
        'sms_verification_expires_at' => null,
    ]);

    $request->session()->put(
        $this->verificationSessionKey($client->id, $request->reference),
        true
    );

    return redirect()->route('service.entretien.entretenir.step5', [
        'client_id' => $client->id,
        'reference' => $request->reference,
    ]);
}

    public function step3Store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => [
                'required',
                'regex:/^(\+212|0)?[6-7]\d{8}$/'
            ],
            'email' => 'nullable|email',
            'ville_id' => 'nullable|exists:villes,id',
            'adresse' => 'nullable|string',
            'location' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048', // 2MB
            'video.*' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:30720', // 30MB
        ]);

        // Format phone
        $rawPhone = preg_replace('/\D/', '', $request->telephone);
        if (str_starts_with($rawPhone, '0')) {
            $rawPhone = substr($rawPhone, 1);
        }
        $formattedPhone = '+212' . $rawPhone;

        // ✅ STOP if phone/email already used (for guests/new clients)
        if (!auth()->check()) {

            // phone duplicate (users.phone is UNIQUE in your migration)
            $phoneExists = User::where('phone', $formattedPhone)->exists();

            // email duplicate (only if email is filled)
            $email = $request->filled('email') ? strtolower(trim($request->email)) : null;
            $emailExists = $email ? User::whereRaw('LOWER(email) = ?', [$email])->exists() : false;

            $errors = [];

            if ($phoneExists) {
                $errors['telephone'] = "Ce numéro de téléphone est déjà utilisé. Veuillez utiliser un autre numéro.";
            }

            if ($emailExists) {
                $errors['email'] = "Cette adresse email est déjà utilisée. Veuillez utiliser une autre adresse.";
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages($errors);
                // أو:
                // return back()->withErrors($errors)->withInput();
            }
        }

        $client = $this->getOrCreateClient([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $formattedPhone,
            'ville_id' => $request->ville_id,
            'adresse' => $request->adresse,
            'location' => $request->location,
        ]);

        if ($request->filled('location') && !$client->location) {
            $client->update([
                'location' => $request->location
            ]);
        }


        $selectionIds = explode(',', $request->selection_ids);

        ClientServiceSelection::whereIn('id', $selectionIds)
            ->update(['client_id' => $client->id]);

        // 📸🎥 Files PER MACHINE
        foreach ($selectionIds as $selectionId) {

            $photoPath = isset($request->photo[$selectionId])
                ? $request->photo[$selectionId]->store('machine_photos', 'public')
                : null;

            $videoPath = isset($request->video[$selectionId])
                ? $request->video[$selectionId]->store('machine_videos', 'public')
                : null;

            $selection = ClientServiceSelection::find($selectionId);

            ClientMachineDetail::create([
                'client_id' => $client->id,
                'machine_id' => $selection->machine_id,
                'reference' => $request->reference, // 🔥 IMPORTANT,
                'photo' => $photoPath,
                'video' => $videoPath,
            ]);
        }


        $this->sendStep4VerificationSms($client, $request->reference);

$request->session()->forget(
    $this->verificationSessionKey($client->id, $request->reference)
);

        return redirect()->route('service.entretien.entretenir.step4', [
    'client_id' => $client->id,
    'reference' => $request->reference,
])->with('success', 'Un code de vérification a été envoyé par SMS.');
    }



   public function step4(Request $request)
{
    $client = Client::findOrFail($request->client_id);
    $reference = $request->reference;

    if ($request->session()->get($this->verificationSessionKey($client->id, $reference))) {
        return redirect()->route('service.entretien.entretenir.step5', [
            'client_id' => $client->id,
            'reference' => $reference,
        ]);
    }

    return view('user.service.entretien.entretenir.step4', compact('client', 'reference'));
}

    public function step5(Request $request)
{
    $client = Client::findOrFail($request->client_id);
    $reference = $request->reference;

    return view('user.service.entretien.entretenir.step5', compact('client', 'reference'));
}

   public function step5Store(Request $request, $clientId)
{
    $request->validate([
        'reference' => [
            'required',
            'string',
            function ($attr, $value, $fail) use ($clientId) {
                $exists = ClientServiceSelection::where('reference', $value)
                    ->where('client_id', $clientId)
                    ->exists();

                if (!$exists) {
                    $fail('Référence invalide.');
                }
            }
        ],
        'date_souhaite' => ['required', 'date', 'after_or_equal:today'],
        'hour' => 'required|date_format:H:i'
    ]);

    $exists = Reservation::where('reference', $request->reference)
        ->where('date_souhaite', $request->date_souhaite)
        ->where('hour', $request->hour)
        ->exists();

    if ($exists) {
        return back()->withErrors([
            'hour' => 'Cette heure est déjà réservée. Veuillez en choisir une autre.'
        ])->withInput();
    }

    $alreadyExists = Reservation::where('reference', $request->reference)->exists();

    DB::transaction(function () use ($request, $clientId) {
        Reservation::create([
            'client_id' => $clientId,
            'reference' => $request->reference,
            'date_souhaite' => $request->date_souhaite,
            'hour' => $request->hour
        ]);

        ClientServiceSelection::where('reference', $request->reference)
            ->where('client_id', $clientId)
            ->update([
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);
    });

    if (!$alreadyExists) {
        $client = Client::findOrFail($clientId);

        $payload = [
            'type' => 'commande_created',
            'title' => 'Nouvelle commande',
            'message' => 'Une nouvelle commande a été créée.',
            'client_id' => $client->id,
            'client_name' => $client->prenom . ' ' . $client->nom,
            'reference' => $request->reference,
            'kind' => 'entretien',
            'changed_at' => now()->toDateTimeString(),
            'changes' => [
                [
                    'field' => 'planning',
                    'from' => '-',
                    'to' => $request->date_souhaite . ' ' . $request->hour,
                ],
            ],
            'panel_links' => [
                'admin' => route('admin.clients.entretien', ['reference' => $request->reference]),
                'technicien' => route('technicien.commandes.show', [
                    'type' => 'entretien',
                    'reference' => $request->reference,
                ]),
            ],
        ];

        $recipients = User::whereIn('role', ['admin', 'technicien'])->get();
        Notification::sendNow($recipients, new CommandeChangedNotification($payload));
    }

    $request->session()->forget(
        $this->verificationSessionKey($clientId, $request->reference)
    );

    return redirect()->route('service.entretien.entretenir.complete', [
        'client_id' => $clientId,
        'reference' => $request->reference
    ]);
}



    public function getAvailableHours(Request $request)
    {
        $date = $request->date;
        $today = Carbon::today()->toDateString();
        $nowHour = Carbon::now()->format('H:i');

        // Working hours: 08:00 → 18:00
        $hours = collect(range(8, 18))->map(fn($h) => sprintf('%02d:00', $h));


        // Already taken hours (HH:MM)
        $taken = Reservation::where('date_souhaite', $date)

            ->pluck('hour')
            ->map(fn($h) => substr($h, 0, 5));

        // Build availability
        $hoursWithAvailability = $hours->map(function ($hour) use ($taken, $date, $today, $nowHour) {

            $available = true;

            // ❌ Hour already reserved
            if ($taken->contains($hour)) {
                $available = false;
            }

            // ❌ Past hours today
            if ($date === $today && $hour <= $nowHour) {
                $available = false;
            }

            return [
                'hour' => $hour,
                'available' => $available
            ];
        });

        // Check if all hours are unavailable
        $allTaken = $hoursWithAvailability->every(fn($h) => !$h['available']);

        return response()->json([
            'allTaken' => $allTaken,
            'hours' => $hoursWithAvailability
        ]);
    }




    // ============================
    // COMPLETE
    // ============================
    public function complete(Request $request)
    {
        $client = Client::findOrFail($request->client_id);

        $reference = $request->reference;
        return view('user.service.entretien.entretenir.complete', compact('client', 'reference'));
    }
}
