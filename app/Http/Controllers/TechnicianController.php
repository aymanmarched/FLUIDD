<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use DB;
use Illuminate\Http\Request;
use App\Models\Technician;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ClientServiceSelection;
use App\Models\ClientMachineMarqueSelection;

use App\Models\Payment;







use Carbon\Carbon;

class TechnicianController extends Controller
{
    // Show all technicians


    public function index()
    {
        $user = auth()->user();
        $technician = $user->technician;

        // ENTRETIEN COMMANDES
        $entretiens = ClientServiceSelection::whereNotNull('reference')
            ->where('is_submitted', true)
            ->latest()
            ->get()
            ->groupBy('reference');

        // REMPLACER COMMANDES
      $remplacers = ClientMachineMarqueSelection::whereNotNull('reference')
    ->where('is_submitted', true)
    ->latest()
    ->get()
    ->groupBy('reference');

        return view('technicien.dashboard', compact(
            'technician',
            'entretiens',
            'remplacers'
        ));
    }

    public function profile()
    {
        return view('technicien.profile', [
            'technician' => auth()->user()->technician
        ]);
    }

    public function updateProfile(Request $request)
    {
        $technician = auth()->user()->technician;
        $user = auth()->user();

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $technician->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'password_visible' => $request->password
                ? $request->password
                : $technician->password_visible,
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password
                ? bcrypt($request->password)
                : $user->password,
        ]);

        return back()->with('success', 'Profil mis à jour');
    }





 public function planning(Request $request)
{
    $technician = auth()->user()->technician;
    $selectedDate = $request->get('date', Carbon::today()->toDateString());

    $q = Reservation::query()
        ->whereDate('date_souhaite', $selectedDate);

    // ✅ references autorisées pour éviter UNKNOWN
    $entretienRefs = ClientServiceSelection::whereNotNull('reference')
        ->where('is_submitted', true)
        ->pluck('reference');

   $remplacerRefs = ClientMachineMarqueSelection::whereNotNull('reference')
    ->where('is_submitted', true)
    ->pluck('reference');

    $allowedRefs = $entretienRefs
        ->merge($remplacerRefs)
        ->unique()
        ->values();

    $reservations = $q->whereIn('reference', $allowedRefs)
        ->orderBy('hour')
        ->get();

    $references = $reservations->pluck('reference')->filter()->unique()->values();

    $payments = Payment::whereIn('reference', $references)
        ->whereIn('kind', ['entretien', 'remplacer'])
        ->get()
        ->keyBy(fn($p) => strtolower($p->kind) . '|' . $p->reference);

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

    $commandes = $reservations
        ->map(function ($res) use ($entretiens, $remplacers, $payments) {

            if ($entretiens->has($res->reference)) {
                $items = $entretiens[$res->reference];
                $client = $items->first()->client;

                $payment = $payments->get('entretien|' . $res->reference);
                $isPaid = (bool) $payment;

                return (object) [
                    'reference' => $res->reference,
                    'date' => $res->date_souhaite,
                    'hour' => $res->hour,
                    'type' => 'ENTRETIEN',
                    'client' => $client,
                    'machines' => $items->pluck('machine.name')->unique()->values(),
                    'total' => $items->sum(fn($x) => $x->type->prix ?? 0),
                    'annule' => (bool) ($items->first()->annule ?? false),
                    'is_paid' => $isPaid,
                ];
            }

            if ($remplacers->has($res->reference)) {
                $items = $remplacers[$res->reference];
                $client = $items->first()->client;

                $payment = $payments->get('remplacer|' . $res->reference);
                $isPaid = (bool) $payment;

                return (object) [
                    'reference' => $res->reference,
                    'date' => $res->date_souhaite,
                    'hour' => $res->hour,
                    'type' => 'REMPLACER',
                    'client' => $client,
                    'machines' => $items->pluck('machine.name')->unique()->values(),
                    'total' => $items->sum(fn($x) => $x->marque->prix ?? 0),
                    'annule' => (bool) ($items->first()->annule ?? false),
                    'is_paid' => $isPaid,
                ];
            }

            return null; // ✅ no UNKNOWN
        })
        ->filter()
        ->values();

    return view('technicien.planning.index', compact('technician', 'selectedDate', 'commandes'));
}


    public function showCommande(string $type, string $reference)
    {
        $type = strtolower($type); // ✅ converts ENTRETIEN -> entretien

        $reservation = Reservation::where('reference', $reference)->first();

        if ($type === 'entretien') {
            $selections = ClientServiceSelection::with([
                'client.ville',
                'machine',
                'type',
                'client.machineDetails.machine'
            ])
                ->where('reference', $reference)
                ->where('is_submitted', true)
                ->get();

            abort_if($selections->isEmpty(), 404);

            $client = $selections->first()->client;
            $total = $selections->sum(fn($s) => $s->type->prix ?? 0);

            return view('technicien.commandes.show', compact(
                'type',
                'reference',
                'client',
                'selections',
                'reservation',
                'total'
            ));
        }

        if ($type === 'remplacer') {
            $selections = ClientMachineMarqueSelection::with([
                'client.ville',
                'machine',
                'marque'
            ])
                ->where('reference', $reference)
                ->where('is_submitted', true)
                ->get();

            abort_if($selections->isEmpty(), 404);

            $client = $selections->first()->client;
            $total = $selections->sum(fn($s) => $s->marque->prix ?? 0);

            return view('technicien.commandes.show', compact(
                'type',
                'reference',
                'client',
                'selections',
                'reservation',
                'total'
            ));
        }

        abort(404);
    }


}

