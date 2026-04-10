<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientMachineDetail;
use App\Models\ClientMachineMarqueSelection;
use App\Models\ClientServiceSelection;
use App\Models\Garantie;
use App\Models\Machine;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Ville;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\CommandeChangedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Mission;
class ClientDashboardController extends Controller
{
    // ✅ Generate REM reference (REM-YYYYMMDD-100000)
    private function generateRemplacerReference(): string
    {
        $date = Carbon::now()->format('Ymd');

        // Get last reference for TODAY
        $lastRef = ClientMachineMarqueSelection::where('reference', 'like', "REM-$date-%")
            ->orderByDesc('reference')
            ->value('reference');

        if ($lastRef) {
            $lastNumber = intval(substr($lastRef, -6));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 100000;
        }

        // Always 6 digits at the end
        $nextNumber = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return "REM-$date-$nextNumber";
    }

  public function index()
{
    $user = auth()->user();

    if (!$user->client) {
        return redirect()->route('client.profile.edit')
            ->with('info', 'Complétez votre profil pour gérer vos commandes.');
    }

    $client = $user->client;

    $entretienCount = ClientServiceSelection::where('client_id', $client->id)
        ->whereNotNull('reference')
        ->where('is_submitted', true)
        ->distinct('reference')
        ->count('reference');

    $remplacerCount = ClientMachineMarqueSelection::where('client_id', $client->id)
    ->whereNotNull('reference')
    ->where('is_submitted', true)
    ->distinct('reference')
    ->count('reference');

    $garantieCount = Garantie::where(function ($q) use ($client) {
        $q->where('email', $client->email)
            ->orWhere('telephone', $client->telephone);
    })->count();

    return view('client.home', [
        'entretien' => $entretienCount,
        'remplacer' => $remplacerCount,
        'garantie' => $garantieCount,
    ]);
}

    public function modifier()
    {
        $client = auth()->user()->client;
        $villes = Ville::all();

        return view('client.profile.edit', compact('client', 'villes'));
    }

    public function updateprofile(Request $request)
    {
        $client = auth()->user()->client;

        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => ['required', 'regex:/^(6|7)[0-9]{8}$/'],
            'email' => 'nullable|email',
            'ville_id' => 'nullable|exists:villes,id',
            'adresse' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $client->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => '+212' . ltrim(preg_replace('/\D/', '', $request->telephone), '0'),
            'email' => $request->email,
            'ville_id' => $request->ville_id,
            'adresse' => $request->adresse,
            'location' => $request->location,
        ]);

        return redirect()->route('client.dashboard')
            ->with('success', 'Profil mis à jour avec succès !');
    }

   public function entretiens()
{
    $client = auth()->user()->client;

    if (!$client) {
        return view('client.entretiens.index', ['commandes' => collect()]);
    }

    $refs = ClientServiceSelection::where('client_id', $client->id)
        ->whereNotNull('reference')
        ->where('is_submitted', true)
        ->distinct()
        ->pluck('reference');

    $missionsByRef = Mission::where('kind', 'entretien')
        ->whereIn('reference', $refs)
        ->get()
        ->keyBy('reference');

    $paymentsByRef = Payment::where('kind', 'entretien')
        ->whereIn('reference', $refs)
        ->get()
        ->keyBy('reference');

    $commandes = $refs->map(function ($ref) use ($missionsByRef, $paymentsByRef, $client) {

        $selections = ClientServiceSelection::where('client_id', $client->id)
            ->where('reference', $ref)
            ->where('is_submitted', true)
            ->with('type')
            ->get();

        if ($selections->isEmpty()) {
            return null;
        }

        $first = $selections->first();
        $annule = (bool) ($first->annule ?? false);

        $mission = $missionsByRef->get($ref);

        $payment = $paymentsByRef->get($ref);
        $isPaid = (bool) $payment;

        if ($annule) {
            $statusCode = 'cancelled';
            $statusLabel = 'Annulée';
        } elseif ($mission?->status === 'completed') {
            $statusCode = 'completed';
            $statusLabel = 'Terminée';
        } elseif ($mission?->status === 'in_progress') {
            $statusCode = 'in_progress';
            $statusLabel = 'En cours';
        } else {
            $statusCode = 'pending';
            $statusLabel = 'En attente';
        }

        $canEdit = ($statusCode === 'pending' || $statusCode === 'in_progress');
        $discountPercent = $mission ? 0 : 10;

        return (object) [
            'reference' => $ref,
            'date' => $first->created_at ?? null,
            'total' => $selections->sum(fn($s) => $s->type->prix ?? 0),
            'annule' => $annule,
            'status_code' => $statusCode,
            'status_label' => $statusLabel,
            'can_edit' => $canEdit,
            'is_paid' => $isPaid,
            'payment_label' => $isPaid ? 'Payée' : 'Non payée',
            'pay_discount_percent' => $isPaid ? 0 : $discountPercent,
        ];
    })->filter()->values();

    return view('client.entretiens.index', compact('commandes'));
}


   public function toggleEntretien(string $reference)
{
    $client = auth()->user()->client;

    $current = ClientServiceSelection::where('client_id', $client->id)
        ->where('reference', $reference)
        ->where('is_submitted', true)
        ->firstOrFail();

    $newStatus = !$current->annule;

    ClientServiceSelection::where('client_id', $client->id)
        ->where('reference', $reference)
        ->where('is_submitted', true)
        ->update(['annule' => $newStatus]);

    $message = $newStatus ? 'Commande annulée avec succès.' : 'Commande réactivée avec succès.';

    return back()->with('success', $message);
}

    public function showEntretienCommande(string $reference)
{
    $client = Auth::user()->client;

    $selections = ClientServiceSelection::with(['machine', 'type'])
        ->where('client_id', $client->id)
        ->where('reference', $reference)
        ->where('is_submitted', true)
        ->get();

    abort_if($selections->isEmpty(), 404);

    $reservation = Reservation::where('reference', $reference)
        ->where('client_id', $client->id)
        ->first();

    $total = $selections->sum(fn($sel) => $sel->type->prix ?? 0);

    return view('client.entretiens.show', compact(
        'reference',
        'client',
        'selections',
        'reservation',
        'total'
    ));
}

  public function editentretien(string $reference)
{
    $client = auth()->user()->client;

    $selections = ClientServiceSelection::with(['machine.types', 'type'])
        ->where('reference', $reference)
        ->where('client_id', $client->id)
        ->where('is_submitted', true)
        ->get();

    abort_if($selections->isEmpty(), 404);

    $reservation = Reservation::where('reference', $reference)
        ->where('client_id', $client->id)
        ->first();

    return view('client.entretiens.edit', compact(
        'reference',
        'client',
        'selections',
        'reservation'
    ));
}

    public function updateeditentretien(Request $request, string $reference)
{
    $client = auth()->user()->client;
    $before = $this->snapshotCommande($reference, $client->id);

    $selections = ClientServiceSelection::with('machine')->where([
        'reference' => $reference,
        'client_id' => $client->id
    ])->where('is_submitted', true)->get();

    abort_if($selections->isEmpty(), 404);

    if ($request->input('action') === 'convert_to_remplacer') {

        $newReference = $this->generateRemplacerReference();

        $missing = [];
        foreach ($selections as $sel) {
            if (!$sel->machine?->remplacer_machine_id) {
                $missing[] = $sel->machine?->name ?? 'Machine inconnue';
            }
        }

        if (!empty($missing)) {
            return back()->withErrors([
                'convert' => "Conversion impossible. Ces machines ne sont pas liées à Remplacer : " . implode(', ', $missing)
            ]);
        }

        DB::transaction(function () use ($selections, $client, $reference, $newReference) {

            foreach ($selections as $sel) {
               ClientMachineMarqueSelection::create([
    'client_id' => $client->id,
    'machine_id' => $sel->machine->remplacer_machine_id,
    'marque_id' => null,
    'reference' => $newReference,
    'is_submitted' => true,
    'submitted_at' => now(),
]);
            }

            Reservation::where('client_id', $client->id)
                ->where('reference', $reference)
                ->update(['reference' => $newReference]);

            ClientServiceSelection::where('client_id', $client->id)
                ->where('reference', $reference)
                ->where('is_submitted', true)
                ->delete();

            ClientMachineDetail::where('client_id', $client->id)
                ->where('reference', $reference)
                ->delete();
        });

        $after = $this->snapshotCommande($newReference, $client->id);

        $payload = [
            'title' => 'Commande convertie Entretien -> Remplacer',
            'client_id' => $client->id,
            'client_name' => $client->prenom . ' ' . $client->nom,
            'reference_old' => $reference,
            'reference_new' => $newReference,
            'kind' => 'remplacer',
            'changes' => [
                ['field' => 'convert', 'from' => 'entretien', 'to' => 'remplacer'],
                ['field' => 'reference', 'from' => $reference, 'to' => $newReference],
            ],
            'changed_at' => now()->toDateTimeString(),
            'panel_links' => [
                'admin' => route('admin.clients.remplacer', ['reference' => $newReference]),
                'technicien' => route('technicien.commandes.show', ['type' => 'remplacer', 'reference' => $newReference]),
            ],
        ];

        $this->notifyAdminsAndTechnicians($client, $payload);

        return redirect()
            ->route('client.remplacers.edit', $newReference)
            ->with('success', "Commande convertie en Remplacement : $newReference. Choisissez maintenant vos marques.");
    }

    foreach ($selections as $selection) {

        if ($request->input("remove.{$selection->id}") == 1) {
            $selection->delete();
            continue;
        }

        if ($request->has("type_id.{$selection->id}")) {
            $selection->update([
                'type_id' => $request->input("type_id.{$selection->id}")
            ]);
        }

        $detail = ClientMachineDetail::updateOrCreate(
            [
                'client_id' => $client->id,
                'machine_id' => $selection->machine_id,
                'reference' => $reference,
            ],
            []
        );

        if ($request->hasFile("photo.{$selection->id}")) {
            $detail->photo = $request->file("photo.{$selection->id}")
                ->store('machine_photos', 'public');
        }

        if ($request->hasFile("video.{$selection->id}")) {
            $detail->video = $request->file("video.{$selection->id}")
                ->store('machine_videos', 'public');
        }

        $detail->save();
    }

    if ($request->filled(['date_souhaite', 'hour'])) {

        $request->validate([
            'date_souhaite' => ['required', 'date', 'after_or_equal:today'],
            'hour' => ['required', 'date_format:H:i'],
        ]);

        $currentReservation = Reservation::where('reference', $reference)
            ->where('client_id', $client->id)
            ->first();

        $exists = Reservation::where('date_souhaite', $request->date_souhaite)
            ->where('hour', $request->hour)
            ->when($currentReservation, fn($q) => $q->where('id', '!=', $currentReservation->id))
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'hour' => 'Cette heure est déjà réservée. Veuillez choisir une autre.'
            ])->withInput();
        }

        Reservation::updateOrCreate(
            [
                'client_id' => $client->id,
                'reference' => $reference,
            ],
            [
                'date_souhaite' => $request->date_souhaite,
                'hour' => $request->hour,
            ]
        );
    }

    $after = $this->snapshotCommande($reference, $client->id);
    $changes = $this->diffSnapshots($before, $after);

    if (!empty($changes)) {
        $payload = [
            'title' => 'Client a modifié une commande',
            'client_id' => $client->id,
            'client_name' => $client->prenom . ' ' . $client->nom,
            'reference' => $reference,
            'kind' => $after['kind'] ?? 'entretien',
            'changes' => $changes,
            'changed_at' => now()->toDateTimeString(),
            'panel_links' => [
                'admin' => route('admin.clients.entretien', ['reference' => $reference]),
                'technicien' => route('technicien.commandes.show', ['type' => $after['kind'], 'reference' => $reference]),
            ],
        ];

        $this->notifyAdminsAndTechnicians($client, $payload);
    }

    return redirect()
        ->route('client.entretiens.show', $reference)
        ->with('success', 'Commande mise à jour avec succès.');
}

   public function remplacers()
{
    $client = auth()->user()->client;

    if (!$client) {
        return view('client.remplacers.index', ['commandes' => collect()]);
    }

    $refs = ClientMachineMarqueSelection::where('client_id', $client->id)
        ->whereNotNull('reference')
        ->where('is_submitted', true)
        ->distinct()
        ->pluck('reference');

    $missionsByRef = Mission::where('kind', 'remplacer')
        ->whereIn('reference', $refs)
        ->get()
        ->keyBy('reference');

    $paymentsByRef = Payment::where('kind', 'remplacer')
        ->whereIn('reference', $refs)
        ->get()
        ->keyBy('reference');

    $commandes = $refs->map(function ($ref) use ($missionsByRef, $paymentsByRef, $client) {

        $items = ClientMachineMarqueSelection::where('client_id', $client->id)
            ->where('reference', $ref)
            ->where('is_submitted', true)
            ->with('marque')
            ->get();

        if ($items->isEmpty()) {
            return null;
        }

        $first = $items->first();
        $mission = $missionsByRef->get($ref);

        $payment = $paymentsByRef->get($ref);
        $isPaid = (bool) $payment;

        if ($mission?->status === 'completed') {
            $statusCode = 'completed';
            $statusLabel = 'Terminée';
        } elseif ($mission?->status === 'in_progress') {
            $statusCode = 'in_progress';
            $statusLabel = 'En cours';
        } else {
            $statusCode = 'pending';
            $statusLabel = 'En attente';
        }

        $canEdit = ($statusCode === 'pending');
        $discountPercent = $mission ? 0 : 10;

        return (object) [
            'reference' => $ref,
            'date' => $first->created_at ?? null,
            'total' => $items->sum(fn($s) => $s->marque->prix ?? 0),
            'status_code' => $statusCode,
            'status_label' => $statusLabel,
            'can_edit' => $canEdit,
            'is_paid' => $isPaid,
            'payment_label' => $isPaid ? 'Payée' : 'Non payée',
            'pay_discount_percent' => $isPaid ? 0 : $discountPercent,
        ];
    })->filter()->values();

    return view('client.remplacers.index', compact('commandes'));
}


    public function showRemplacerCommande(string $reference)
    {
        $client = auth()->user()->client;

        $selections = ClientMachineMarqueSelection::with(['machine', 'marque'])
    ->where('client_id', $client->id)
    ->where('reference', $reference)
    ->where('is_submitted', true)
    ->get();

        abort_if($selections->isEmpty(), 404);

        $reservation = Reservation::where('reference', $reference)
            ->where('client_id', $client->id)
            ->first();

        $total = $selections->sum(fn($s) => $s->marque->prix ?? 0);

        return view('client.remplacers.show', compact(
            'reference',
            'client',
            'reservation',
            'selections',
            'total'
        ));
    }

    public function editRemplacer(string $reference)
    {
        $client = auth()->user()->client;

        $selections = ClientMachineMarqueSelection::with(['machine.marques', 'marque'])
    ->where('client_id', $client->id)
    ->where('reference', $reference)
    ->where('is_submitted', true)
    ->get();

        abort_if($selections->isEmpty(), 404);

        $total = $selections->sum(fn($s) => $s->marque->prix ?? 0);

        $reservation = Reservation::where('reference', $reference)
            ->where('client_id', $client->id)
            ->first();

        return view('client.remplacers.edit', compact(
            'reference',
            'client',
            'selections',
            'total',
            'reservation'
        ));
    }

    public function updateRemplacer(Request $request, string $reference)
    {
        $client = auth()->user()->client;
        $before = $this->snapshotCommande($reference, $client->id);

        $selections = ClientMachineMarqueSelection::where([
            'client_id' => $client->id,
            'reference' => $reference
        ])->where('is_submitted', true)->get();

        abort_if($selections->isEmpty(), 404);

        foreach ($selections as $selection) {

            if ($request->input("remove.{$selection->id}") == 1) {
                $selection->delete();
                continue;
            }

            if ($request->has("marque_id.{$selection->id}")) {
                $selection->update([
                    'marque_id' => $request->input("marque_id.{$selection->id}")
                ]);
            }
        }

if (ClientMachineMarqueSelection::where('reference', $reference)
    ->where('is_submitted', true)
    ->count() === 0) {
                    return redirect()->route('client.remplacers')
                ->with('success', 'Commande supprimée.');
        }

        // ✅ RESERVATION UPDATE (for this reference)
        if ($request->filled(['date_souhaite', 'hour'])) {

            $request->validate([
                'date_souhaite' => ['required', 'date', 'after_or_equal:today'],
                'hour' => ['required', 'date_format:H:i'],
            ]);

            $currentReservation = Reservation::where('reference', $reference)
                ->where('client_id', $client->id)
                ->first();

            $exists = Reservation::where('date_souhaite', $request->date_souhaite)
                ->where('hour', $request->hour)
                ->when($currentReservation, fn($q) => $q->where('id', '!=', $currentReservation->id))
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'hour' => 'Cette heure est déjà réservée. Veuillez choisir une autre.'
                ])->withInput();
            }

            Reservation::updateOrCreate(
                [
                    'reference' => $reference,
                    'client_id' => $client->id,
                ],
                [
                    'date_souhaite' => $request->date_souhaite,
                    'hour' => $request->hour,
                ]
            );
        }
        $after = $this->snapshotCommande($reference, $client->id);
        $changes = $this->diffSnapshots($before, $after);

        if (!empty($changes)) {
            $payload = [
                'title' => 'Client a modifié une commande',
                'client_id' => $client->id,
                'client_name' => $client->prenom . ' ' . $client->nom,
                'reference' => $reference,
                'kind' => 'remplacer',
                'changes' => $changes,
                'changed_at' => now()->toDateTimeString(),
                'panel_links' => [
                    'admin' => route('admin.clients.remplacer', ['reference' => $reference]),
                    'technicien' => route('technicien.commandes.show', ['type' => 'remplacer', 'reference' => $reference]),
                ],
            ];

            $this->notifyAdminsAndTechnicians($client, $payload);
        }

        return redirect()
            ->route('client.remplacers.show', $reference)
            ->with('success', 'Commande mise à jour avec succès.');
    }



    public function showSetPasswordForm(Client $client)
    {
        if (!$client->password_token) {
            abort(403, 'Token invalide ou déjà utilisé.');
        }

        return view('client.password.set-password', compact('client'));
    }

    public function setPassword(Request $request, Client $client)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        if (!$client->user) {
            abort(404, "Utilisateur non trouvé pour ce client. Veuillez contacter le support.");
        }

        $client->user->update([
            'password' => Hash::make($request->password),
        ]);

        $client->password_token = null;
        $client->save();

        Auth::login($client->user);

        return redirect()->route('client.dashboard')
            ->with('success', 'Votre mot de passe a été défini. Vous pouvez maintenant vous connecter.');
    }





   private function snapshotCommande(string $reference, int $clientId): array
{
    $isEntretien = ClientServiceSelection::where('reference', $reference)
        ->where('client_id', $clientId)
        ->where('is_submitted', true)
        ->exists();

   $isRemplacer = ClientMachineMarqueSelection::where('reference', $reference)
    ->where('client_id', $clientId)
    ->where('is_submitted', true)
    ->exists();

    $reservation = Reservation::where('reference', $reference)
        ->where('client_id', $clientId)
        ->first();

    if ($isEntretien) {
        $items = ClientServiceSelection::with(['machine', 'type'])
            ->where('reference', $reference)
            ->where('client_id', $clientId)
            ->where('is_submitted', true)
            ->get()
            ->map(fn($s) => [
                'key' => (string) $s->machine_id,
                'machine_id' => $s->machine_id,
                'machine' => $s->machine?->name,
                'service_id' => $s->type_id,
                'service' => $s->type?->name,
                'price' => $s->type?->prix ?? 0,
            ])->values()->all();

        return [
            'kind' => 'entretien',
            'reference' => $reference,
            'reservation' => [
                'date' => $reservation?->date_souhaite,
                'hour' => $reservation?->hour ? substr($reservation->hour, 0, 5) : null,
            ],
            'items' => $items,
        ];
    }

    if ($isRemplacer) {
     $items = ClientMachineMarqueSelection::with(['machine', 'marque'])
    ->where('reference', $reference)
    ->where('client_id', $clientId)
    ->where('is_submitted', true)
    ->get()
            ->map(fn($s) => [
                'key' => (string) $s->machine_id,
                'machine_id' => $s->machine_id,
                'machine' => $s->machine?->name,
                'service_id' => $s->marque_id,
                'service' => $s->marque?->nom,
                'price' => $s->marque?->prix ?? 0,
            ])->values()->all();

        return [
            'kind' => 'remplacer',
            'reference' => $reference,
            'reservation' => [
                'date' => $reservation?->date_souhaite,
                'hour' => $reservation?->hour ? substr($reservation->hour, 0, 5) : null,
            ],
            'items' => $items,
        ];
    }

    return [
        'kind' => 'unknown',
        'reference' => $reference,
        'reservation' => [
            'date' => $reservation?->date_souhaite,
            'hour' => $reservation?->hour ? substr($reservation->hour, 0, 5) : null,
        ],
        'items' => [],
    ];
}
    private function diffSnapshots(array $before, array $after): array
    {
        $changes = [];

        // Reservation changes
        if (
            ($before['reservation']['date'] ?? null) !== ($after['reservation']['date'] ?? null)
            || ($before['reservation']['hour'] ?? null) !== ($after['reservation']['hour'] ?? null)
        ) {
            $changes[] = [
                'field' => 'reservation',
                'from' => trim(($before['reservation']['date'] ?? '-') . ' ' . ($before['reservation']['hour'] ?? '')),
                'to' => trim(($after['reservation']['date'] ?? '-') . ' ' . ($after['reservation']['hour'] ?? '')),
            ];
        }

        // Kind change (entretien <-> remplacer)
        if (($before['kind'] ?? null) !== ($after['kind'] ?? null)) {
            $changes[] = [
                'field' => 'kind',
                'from' => $before['kind'] ?? '-',
                'to' => $after['kind'] ?? '-',
            ];
        }

        // Item changes
        $b = collect($before['items'] ?? [])->keyBy('key');
        $a = collect($after['items'] ?? [])->keyBy('key');

        $removedKeys = $b->keys()->diff($a->keys());
        foreach ($removedKeys as $k) {
            $changes[] = [
                'field' => 'removed_item',
                'machine' => $b[$k]['machine'] ?? ('machine_id:' . $b[$k]['machine_id']),
                'from' => $b[$k]['service'] ?? '-',
                'to' => null,
            ];
        }

        $addedKeys = $a->keys()->diff($b->keys());
        foreach ($addedKeys as $k) {
            $changes[] = [
                'field' => 'added_item',
                'machine' => $a[$k]['machine'] ?? ('machine_id:' . $a[$k]['machine_id']),
                'from' => null,
                'to' => $a[$k]['service'] ?? '-',
            ];
        }

        $commonKeys = $a->keys()->intersect($b->keys());
        foreach ($commonKeys as $k) {
            if (($b[$k]['service_id'] ?? null) !== ($a[$k]['service_id'] ?? null)) {
                $changes[] = [
                    'field' => 'service_change', // type (entretien) OR marque (remplacer)
                    'machine' => $a[$k]['machine'] ?? $b[$k]['machine'] ?? '-',
                    'from' => $b[$k]['service'] ?? '-',
                    'to' => $a[$k]['service'] ?? '-',
                ];
            }
        }

        return $changes;
    }

    private function notifyAdminsAndTechnicians(Client $client, array $payload): void
    {
        $recipients = User::query()
            ->whereIn('role', ['admin', 'technicien', 'superadmin'])
            ->whereNotNull('email')
            ->get();

        // Immediate send (no queue)
        Notification::sendNow($recipients, new CommandeChangedNotification($payload));
    }

}
