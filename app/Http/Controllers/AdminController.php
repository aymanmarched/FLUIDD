<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvisClient;
use App\Models\Client;
use App\Models\ClientMachineMarqueSelection;
use App\Models\ClientServiceSelection;
use App\Models\Contact;
use App\Models\Garantie;
use App\Models\Machine;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Technician;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TechnicianCredentialsMail;
use App\Models\Mission;
use App\Models\MissionStep;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class AdminController extends Controller
{


    public function home()
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->count();
        $clients = Client::count();

        $clientsentretien = Client::whereHas('selections', function ($q) {
            $q->where('is_submitted', true);
        })->count();
        $clientsremplacer = Client::whereHas('selectionsremplacer', function ($q) {
            $q->where('is_submitted', true);
        })->count();

        $client_messages = Contact::count();
        $technicians = Technician::count();
        $client_avis = AvisClient::count();

        // ✅ Today commandes (by reservations.date_souhaite)

        $today = Carbon::today();

        $todayRefs = Reservation::whereDate('date_souhaite', $today)
            ->pluck('reference')
            ->filter()
            ->unique()
            ->values();

        $today_entretien = ClientServiceSelection::whereNotNull('reference')
            ->where('is_submitted', true)
            ->whereIn('reference', $todayRefs)
            ->distinct('reference')
            ->count('reference');

        $today_remplacer = ClientMachineMarqueSelection::whereNotNull('reference')
            ->where('is_submitted', true)
            ->whereIn('reference', $todayRefs)
            ->distinct('reference')
            ->count('reference');

        $today_commandes_total = $today_entretien + $today_remplacer;

        return view('admin.home', compact(
            'admins',
            'clients',
            'clientsentretien',
            'clientsremplacer',
            'client_messages',
            'technicians',
            'client_avis',
            'today_entretien',
            'today_remplacer',
            'today_commandes_total'
        ));
    }



    public function clients_Message()
    {
        $clients = Contact::all();   // get all data from contact table

        $activeClients = Contact::where('action', 0)->get();
        $fixedClients = Contact::where('action', 1)->get();

        return view('admin.clients_Message', compact('clients', 'activeClients', 'fixedClients'));
    }


    public function clientsIndex()
    {
        $clients = Client::latest()->paginate(request('perPage', 10));

        return view('admin.clients.index', compact('clients'));
    }

    public function clientsShow(string $id)
    {
        $client = Client::with('ville')->findOrFail($id);

        /** =========================
         * ENTRETIEN COMMANDES
         * ========================= */
        $entretiens = ClientServiceSelection::where('client_id', $client->id)
            ->whereNotNull('reference')
            ->where('is_submitted', true)
            ->select('reference')
            ->distinct()
            ->get()
            ->map(function ($cmd) {
                // date from reservation
                $reservation = Reservation::where('reference', $cmd->reference)->first();

                // total = sum of type->prix
                $total = ClientServiceSelection::where('reference', $cmd->reference)
                    ->with('type')
                    ->get()
                    ->sum(fn($s) => $s->type->prix ?? 0);

                return (object) [
                    'reference' => $cmd->reference,
                    'date' => optional($reservation)->created_at,
                    'total' => $total,
                ];
            });

        /** =========================
         * REMPLACER COMMANDES
         * ========================= */
        $remplacers = ClientMachineMarqueSelection::where('client_id', $client->id)
            ->whereNotNull('reference')
            ->where('is_submitted', true)
            ->select('reference')
            ->distinct()
            ->get()
            ->map(function ($cmd) {
                $selections = ClientMachineMarqueSelection::where('reference', $cmd->reference)
                    ->with('marque')
                    ->get();

                return (object) [
                    'reference' => $cmd->reference,
                    'date' => $selections->first()?->created_at,
                    'total' => $selections->sum(fn($s) => $s->marque->prix ?? 0),
                ];
            });

        return view('admin.clients.show', compact(
            'client',
            'entretiens',
            'remplacers'
        ));
    }



    public function ClientEntretien()
    {
        $commandes = ClientServiceSelection::with([
            'client',
            'machine',
            'type',
            'client.reservation'
        ])->whereNotNull('reference')
            ->where('is_submitted', true)
            ->select('reference', 'client_id')
            ->distinct()
            ->latest()
            ->paginate(request('perPage', 10));

        // Transform the items in the paginator
        $commandes->getCollection()->transform(function ($cmd) {
            $cmd->reservation = Reservation::where('reference', $cmd->reference)->first();
            return $cmd;
        });

        return view('admin.clientsentretien.index', compact('commandes'));
    }



    public function showClientEntretien(string $reference)
    {
        $selections = ClientServiceSelection::with([
            'client.ville',
            'machine',
            'type',
            'client.machineDetails.machine'
        ])->where('reference', $reference)
            ->where('is_submitted', true)
            ->get();

        abort_if($selections->isEmpty(), 404);

        $client = $selections->first()->client;

        $reservation = Reservation::where('reference', $reference)->first();

        return view('admin.clientsentretien.show', compact(
            'reference',
            'client',
            'selections',
            'reservation'
        ));
    }

    public function ClientRemplacer()
    {
        $commandes = ClientMachineMarqueSelection::with('client')
            ->whereNotNull('reference')
            ->where('is_submitted', true)
            ->select('reference', 'client_id')
            ->groupBy('reference', 'client_id')
            ->latest()
            ->paginate(request('perPage', 10));

        return view('admin.clientsremplacer.index', compact('commandes'));
    }


    public function showClientRemplacer(string $reference)
    {
        $selections = ClientMachineMarqueSelection::with([
            'client',
            'machine',
            'marque'
        ])
            ->where('reference', $reference)
            ->where('is_submitted', true)
            ->get();

        abort_if($selections->isEmpty(), 404);

        $client = $selections->first()->client;
        $total = $selections->sum(fn($s) => $s->marque->prix ?? 0);

        return view('admin.clientsremplacer.show', compact(
            'reference',
            'client',
            'selections',
            'total'
        ));
    }


    public function GarantieIndex()
    {
        $garanties = Garantie::latest()->paginate(request('perPage', 10));
        $machines = Machine::with('marques')->get();
        return view('admin.garanties.index', compact('garanties', 'machines'));
    }


    public function GarantieShow(string $id)
    {
        $garantie = Garantie::with([
            'machine',
            'marque',
            'ville',
        ])->findOrFail($id);

        return view('admin.garanties.show', compact('garantie'));
    }


    public function avis_Clients()
    {
        $avis_clients = AvisClient::latest()->paginate(request('perPage', 10));
        return view('admin.AvisClients.index', compact('avis_clients'));
    }

    public function avis_ClientsSow(string $id)
    {
        $avis_clients = AvisClient::findOrFail($id);

        return view('admin.AvisClients.show', compact('avis_clients'));
    }

    public function showClientCmdEntretien(string $reference)
    {
        $selections = ClientServiceSelection::with([
            'machine',
            'type',
            'client.machineDetails.machine'
        ])->where('reference', $reference)
            ->where('is_submitted', true)
            ->get();

        abort_if($selections->isEmpty(), 404);

        $client = $selections->first()->client;

        $reservation = Reservation::where('reference', $reference)->first();

        return view('admin.clients.entretiencmd', compact(
            'reference',
            'client',
            'selections',
            'reservation'
        ));
    }

    public function showClientCmdRemplacer(string $reference)
    {
        $selections = ClientMachineMarqueSelection::with([
            'client',
            'machine',
            'marque'
        ])
            ->where('reference', $reference)
            ->where('is_submitted', true)
            ->get();

        abort_if($selections->isEmpty(), 404);

        $client = $selections->first()->client;
        $total = $selections->sum(fn($s) => $s->marque->prix ?? 0);

        return view('admin.clients.remplacercmd', compact(
            'reference',
            'client',
            'selections',
            'total'
        ));
    }





    public function TechnicianIndex()
    {
        $technicians = Technician::latest()->get();
        return view('admin.technicians.index', compact('technicians'));
    }

    // Show create form
    public function TechnicianCreate()
    {
        return view('admin.technicians.create');


    }

    public function TechnicianStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => "Le nom est obligatoire.",
            'email.required' => "L'email est obligatoire.",
            'email.email' => "Veuillez saisir un email valide.",
            'email.unique' => "Cet email est déjà utilisé.",

            'phone.required' => "Le téléphone est obligatoire.",
            'phone.unique' => "Ce numéro de téléphone est déjà utilisé.",

            'password.required' => "Le mot de passe est obligatoire.",
            'password.min' => "Le mot de passe doit contenir au moins 6 caractères.",
        ]);

        $plainPassword = $request->password;

        $technician = DB::transaction(function () use ($request, $plainPassword) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($plainPassword),
                'role' => 'technicien',
            ]);

            return Technician::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password_visible' => $plainPassword,
            ]);
        });

        $loginUrl = route('login');

        Mail::to($technician->email)->send(
            new TechnicianCredentialsMail(
                $technician->name,
                $technician->email,
                $plainPassword,
                $loginUrl
            )
        );

        return redirect()
            ->route('admin.technicians')
            ->with('success', 'Technicien ajouté avec succès + Email envoyé.');
    }

    public function TechnicianEdit(Technician $technician)
    {

        return view('admin.technicians.edit', compact(
            'technician',
        ));
    }

    public function TechnicianUpdate(Request $request, Technician $technician)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            // 'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $technician->user_id],
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone,' . $technician->user_id],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.max' => "Le nom ne doit pas dépasser 120 caractères.",
            // 'email.unique' => "Cet email est déjà utilisé.",
            'phone.unique' => "Ce numéro de téléphone est déjà utilisé.",
            'password.min' => "Le mot de passe doit contenir au moins 6 caractères.",
        ]);

        // UPDATE TECHNICIAN PROFILE
        $technician->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password_visible' => $request->password
                ? $request->password
                : $technician->password_visible,
        ]);

        // UPDATE USER LOGIN DATA
        $technician->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password
                ? Hash::make($request->password)
                : $technician->user->password,
        ]);

        return redirect()
            ->route('admin.technicians')
            ->with('success', 'Technicien mis à jour avec succès');
    }


    public function TechnicianShow(Technician $technician)
    {
        return view('admin.technicians.show', compact('technician'));
    }


    public function TechnicianDestroy(Technician $technician)
    {


        $technician->user()->delete(); // deletes user
        $technician->delete();

        return redirect()
            ->route('admin.technicians')
            ->with('success', 'Technicien supprimé avec succès.');
    }



    public function commandesPlanning(Request $request)
    {
        $selectedDate = $request->get('date'); // nullable => show all
        $typeFilter = strtolower($request->get('type', 'all'));   // all|entretien|remplacer
        $statutFilter = strtolower($request->get('statut', 'all')); // all|not_started|completed
        $paymentFilter = strtolower($request->get('payment', 'all')); // all|paid|unpaid

        $q = Reservation::query();

        if (!empty($selectedDate)) {
            $q->whereDate('date_souhaite', $selectedDate);
        }

        // ✅ Base references autorisées
        $entretienRefsQuery = ClientServiceSelection::whereNotNull('reference')
            ->where('is_submitted', true)
            ->select('reference')
            ->distinct();

        $remplacerRefsQuery = ClientMachineMarqueSelection::whereNotNull('reference')
            ->where('is_submitted', true)
            ->select('reference')
            ->distinct();

        // ✅ Type filter
        if ($typeFilter === 'entretien') {
            $q->whereIn('reference', $entretienRefsQuery);
        } elseif ($typeFilter === 'remplacer') {
            $q->whereIn('reference', $remplacerRefsQuery);
        } else {
            $allowedRefs = ClientServiceSelection::whereNotNull('reference')
                ->where('is_submitted', true)
                ->pluck('reference')
                ->merge(
                    ClientMachineMarqueSelection::whereNotNull('reference')
                        ->where('is_submitted', true)
                        ->pluck('reference')
                )
                ->unique()
                ->values();

            $q->whereIn('reference', $allowedRefs);
        }

        // ✅ Mission filter
        if ($statutFilter !== 'all') {
            $refs = collect();

            if ($typeFilter === 'all' || $typeFilter === 'entretien') {
                $entBase = ClientServiceSelection::whereNotNull('reference')
                    ->where('is_submitted', true)
                    ->select('reference')
                    ->distinct();

                if ($statutFilter === 'completed') {
                    $entBase->whereIn(
                        'reference',
                        Mission::where('kind', 'entretien')
                            ->where('status', 'completed')
                            ->select('reference')
                    );
                } elseif ($statutFilter === 'not_started') {
                    $entBase->whereNotIn(
                        'reference',
                        Mission::where('kind', 'entretien')->select('reference')
                    );
                }

                $refs = $refs->merge($entBase->pluck('reference'));
            }

            if ($typeFilter === 'all' || $typeFilter === 'remplacer') {
                $remBase = ClientMachineMarqueSelection::whereNotNull('reference')
                    ->where('is_submitted', true)
                    ->select('reference')
                    ->distinct();

                if ($statutFilter === 'completed') {
                    $remBase->whereIn(
                        'reference',
                        Mission::where('kind', 'remplacer')
                            ->where('status', 'completed')
                            ->select('reference')
                    );
                } elseif ($statutFilter === 'not_started') {
                    $remBase->whereNotIn(
                        'reference',
                        Mission::where('kind', 'remplacer')->select('reference')
                    );
                }

                $refs = $refs->merge($remBase->pluck('reference'));
            }

            $refs = $refs->unique()->values();
            $q->whereIn('reference', $refs);
        }

        // ✅ Payment filter
        if ($paymentFilter !== 'all') {
            $refs = collect();

            if ($typeFilter === 'all' || $typeFilter === 'entretien') {
                $entBase = ClientServiceSelection::whereNotNull('reference')
                    ->where('is_submitted', true)
                    ->select('reference')
                    ->distinct();

                if ($paymentFilter === 'paid') {
                    $entBase->whereIn(
                        'reference',
                        Payment::where('kind', 'entretien')
                            ->select('reference')
                            ->distinct()
                    );
                } elseif ($paymentFilter === 'unpaid') {
                    $entBase->whereNotIn(
                        'reference',
                        Payment::where('kind', 'entretien')
                            ->select('reference')
                            ->distinct()
                    );
                }

                $refs = $refs->merge($entBase->pluck('reference'));
            }

            if ($typeFilter === 'all' || $typeFilter === 'remplacer') {
                $remBase = ClientMachineMarqueSelection::whereNotNull('reference')
    ->where('is_submitted', true)
    ->select('reference')
    ->distinct();

                if ($paymentFilter === 'paid') {
                    $remBase->whereIn(
                        'reference',
                        Payment::where('kind', 'remplacer')
                            ->select('reference')
                            ->distinct()
                    );
                } elseif ($paymentFilter === 'unpaid') {
                    $remBase->whereNotIn(
                        'reference',
                        Payment::where('kind', 'remplacer')
                            ->select('reference')
                            ->distinct()
                    );
                }

                $refs = $refs->merge($remBase->pluck('reference'));
            }

            $refs = $refs->unique()->values();
            $q->whereIn('reference', $refs);
        }

        // ✅ paginate reservations (driver)
        $reservations = $q->orderBy('date_souhaite', 'desc')
            ->orderBy('hour')
            ->paginate(10)
            ->withQueryString();

        $references = $reservations->getCollection()
            ->pluck('reference')
            ->filter()
            ->unique()
            ->values();

        $entretiens = ClientServiceSelection::with(['client.ville', 'machine', 'type'])
            ->where('is_submitted', true)
            ->whereIn('reference', $references)
            ->get()
            ->groupBy('reference');

        $remplacers = ClientMachineMarqueSelection::with(['client.ville', 'machine', 'marque'])
    ->where('is_submitted', true)
    ->whereIn('reference', $references)
    ->get()
    ->groupBy('reference');

        $missions = Mission::whereIn('reference', $references)
            ->whereIn('kind', ['entretien', 'remplacer'])
            ->get()
            ->keyBy(fn($m) => strtolower($m->kind) . '|' . $m->reference);

        $payments = Payment::whereIn('reference', $references)
            ->whereIn('kind', ['entretien', 'remplacer'])
            ->get()
            ->keyBy(fn($p) => strtolower($p->kind) . '|' . $p->reference);

        $commandesCollection = $reservations->getCollection()
            ->map(function ($res) use ($entretiens, $remplacers, $missions, $payments) {

                if ($entretiens->has($res->reference)) {
                    $items = $entretiens[$res->reference];
                    $client = $items->first()->client;
                    $annule = (bool) ($items->first()->annule ?? false);

                    $mission = $missions->get('entretien|' . $res->reference);
                    $payment = $payments->get('entretien|' . $res->reference);

                    return (object) [
                        'reference' => $res->reference,
                        'date' => $res->date_souhaite,
                        'hour' => $res->hour,
                        'type' => 'ENTRETIEN',
                        'client' => $client,
                        'machines' => $items->pluck('machine.name')->unique()->values(),
                        'total' => $items->sum(fn($x) => $x->type->prix ?? 0),
                        'annule' => $annule,
                        'statut' => $annule ? 'ANNULÉE' : 'ACTIVE',
                        'mission' => $mission,
                        'mission_status' => $mission ? $mission->status : 'not_started',
                        'is_paid' => (bool) $payment,
                    ];
                }

                if ($remplacers->has($res->reference)) {
                    $items = $remplacers[$res->reference];
                    $client = $items->first()->client;
                    $annule = (bool) ($items->first()->annule ?? false);

                    $mission = $missions->get('remplacer|' . $res->reference);
                    $payment = $payments->get('remplacer|' . $res->reference);

                    return (object) [
                        'reference' => $res->reference,
                        'date' => $res->date_souhaite,
                        'hour' => $res->hour,
                        'type' => 'REMPLACER',
                        'client' => $client,
                        'machines' => $items->pluck('machine.name')->unique()->values(),
                        'total' => $items->sum(fn($x) => $x->marque->prix ?? 0),
                        'annule' => $annule,
                        'statut' => $annule ? 'ANNULÉE' : 'ACTIVE',
                        'mission' => $mission,
                        'mission_status' => $mission ? $mission->status : 'not_started',
                        'is_paid' => (bool) $payment,
                    ];
                }

                return null; // ✅ no UNKNOWN
            })
            ->filter()
            ->values();

        $commandes = new LengthAwarePaginator(
            $commandesCollection,
            $reservations->total(),
            $reservations->perPage(),
            $reservations->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('admin.commandes.planning', compact(
            'selectedDate',
            'typeFilter',
            'statutFilter',
            'paymentFilter',
            'commandes'
        ));
    }

    public function showMission(Mission $mission)
    {
        // admin only (already middleware)
        $mission->load(['steps' => fn($q) => $q->orderBy('step_no')]);

        $reference = $mission->reference;
        $reservation = Reservation::where('reference', $reference)->first();

     if ($mission->kind === 'entretien') {
    $selections = ClientServiceSelection::with(['client.ville', 'machine', 'type'])
        ->where('reference', $reference)
        ->where('is_submitted', true)
        ->get();
} else {
    $selections = ClientMachineMarqueSelection::with(['client.ville', 'machine', 'marque'])
        ->where('reference', $reference)
        ->where('is_submitted', true)
        ->get();
}

        $client = $selections->first()?->client;

        return view('admin.missions.show', compact(
            'mission',
            'reservation',
            'selections',
            'client'
        ));
    }

}
