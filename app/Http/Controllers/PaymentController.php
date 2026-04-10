<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\ClientMachineMarqueSelection;
use App\Models\ClientServiceSelection;
use App\Models\Mission;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\CommandeChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function create(string $type, string $reference)
    {
        $type = strtolower($type);
        abort_unless(in_array($type, ['entretien', 'remplacer']), 404);

        $client = auth()->user()->client;
        abort_if(!$client, 403);

        // must belong to this client
        $exists = $type === 'entretien'
            ? ClientServiceSelection::where('client_id', $client->id)->where('reference', $reference)->exists()
            : ClientMachineMarqueSelection::where('client_id', $client->id)->where('reference', $reference)->exists();

        abort_if(!$exists, 404);

        // block if already paid
        $alreadyPaid = Payment::where('reference', $reference)->where('kind', $type)->exists();
        if ($alreadyPaid) {
            return redirect()->back()->with('info', 'Commande déjà payée.');
        }

        // if cancelled (entretien only in your code)
        if ($type === 'entretien') {
            $first = ClientServiceSelection::where('client_id', $client->id)->where('reference', $reference)->first();
            if ((bool) ($first->annule ?? false)) {
                return redirect()->back()->with('error', 'Commande annulée: paiement impossible.');
            }
        }

        $total = $this->computeTotal($type, $reference, $client->id);

        // ✅ discount only if mission NOT started (mission record does not exist)
        $mission = Mission::where('kind', $type)->where('reference', $reference)->first();
        $discountPercent = $mission ? 0 : 10;

        $amountToPay = round($total * (1 - ($discountPercent / 100)), 2);

        return view('client.payments.create', [
            'type' => $type,
            'reference' => $reference,
            'total' => $total,
            'discountPercent' => $discountPercent,
            'amountToPay' => $amountToPay,
            'mission' => $mission,
        ]);
    }

    public function store(Request $request, string $type, string $reference)
    {
        $type = strtolower($type);
        abort_unless(in_array($type, ['entretien', 'remplacer']), 404);

        $client = auth()->user()->client;
        abort_if(!$client, 403);

        // recompute server-side
        $total = $this->computeTotal($type, $reference, $client->id);

        $mission = Mission::where('kind', $type)->where('reference', $reference)->first();
        $discountPercent = $mission ? 0 : 10;
        $amountToPay = round($total * (1 - ($discountPercent / 100)), 2);
        $created = false;

        try {
            DB::transaction(function () use ($client, $type, $reference, $total, $discountPercent, $amountToPay ,
        &$created) {

                // prevent duplicates
                if (Payment::where('reference', $reference)->where('kind', $type)->lockForUpdate()->exists()) {
                    return;
                }

                Payment::create([
                    'client_id' => $client->id,
                    'reference' => $reference,
                    'kind' => $type,
                    'amount_original' => $total,
                    'discount_percent' => $discountPercent,
                    'amount_paid' => $amountToPay,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
                $created = true;
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur paiement: ' . $e->getMessage());
        }

        if (!$created) {
            return redirect()->route(
                $type === 'entretien' ? 'client.entretiens.show' : 'client.remplacers.show',
                $reference
            )->with('info', 'Commande déjà payée.');
        }
        $paidAfterMissionStart = (bool) $mission;
        $payload = [
            'title' => 'Client a payé une commande',
            'client_id' => $client->id,
            'client_name' => $client->prenom . ' ' . $client->nom,
            'reference' => $reference,
            'kind' => $type,
            'changes' => [
                ['field' => 'payment', 'from' => 'Non payée', 'to' => 'Payée'],
                [
                    'field' => 'price_rule',
                    'from' => $paidAfterMissionStart ? 'Mission démarrée' : 'Mission non démarrée',
                    'to' => $paidAfterMissionStart
                        ? 'Prix original appliqué (0% réduction)'
                        : 'Réduction 10% appliquée',
                ],
            ],
            'changed_at' => now()->toDateTimeString(),
            'panel_links' => [
                'admin' => route($type === 'entretien' ? 'admin.clients.entretien' : 'admin.clients.remplacer', ['reference' => $reference]),
                'superadmin' => route($type === 'entretien' ? 'admin.clients.entretien' : 'admin.clients.remplacer', ['reference' => $reference]),
            ],
        ];
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::sendNow($admins, new CommandeChangedNotification($payload));


        return redirect()->route(
            $type === 'entretien' ? 'client.entretiens.show' : 'client.remplacers.show',
            $reference
        )->with('success', 'Paiement enregistré.');
    }

    private function computeTotal(string $type, string $reference, int $clientId): float
    {
        if ($type === 'entretien') {
            return (float) ClientServiceSelection::where('client_id', $clientId)
                ->where('reference', $reference)
                ->with('type')
                ->get()
                ->sum(fn($s) => $s->type->prix ?? 0);
        }

        return (float) ClientMachineMarqueSelection::where('client_id', $clientId)
            ->where('reference', $reference)
            ->with('marque')
            ->get()
            ->sum(fn($s) => $s->marque->prix ?? 0);
    }
}
