<?php
namespace App\Http\Controllers;

use App\Models\ClientMachineDetail;
use App\Models\ClientMachineMarqueSelection;
use App\Models\ClientServiceSelection;
use App\Models\ConversionProposal;
use App\Models\Mission;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;





class ClientProposalController extends Controller
{
    private function generateRemplacerReference(): string
    {
        $date = Carbon::now()->format('Ymd');

        $lastRef = ClientMachineMarqueSelection::where('reference', 'like', "REM-$date-%")
            ->orderByDesc('reference')
            ->value('reference');

        $next = $lastRef ? intval(substr($lastRef, -6)) + 1 : 100000;

        return "REM-$date-" . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    public function show(string $token)
    {
        $proposal = ConversionProposal::where('token', $token)->firstOrFail();

        $client = auth()->user()->client;
        abort_if(!$client || $client->id !== $proposal->client_id, 403);

        $mission = Mission::with([
            'recommendations.machine.marques', // ✅ all marques for each machine
            'recommendations.marque',
        ])
            ->findOrFail($proposal->mission_id);

        return view('client.proposals.entretien_to_remplacer', compact('proposal', 'mission'));
    }

    public function accept(string $token)
    {
        $proposal = ConversionProposal::where('token', $token)->firstOrFail();

        $client = auth()->user()->client;
        abort_if(!$client || $client->id !== $proposal->client_id, 403);
        abort_if($proposal->status !== 'pending', 403);

        $mission = Mission::with(['recommendations'])
            ->findOrFail($proposal->mission_id);

        abort_if($mission->kind !== 'entretien', 403);

        $newReference = $this->generateRemplacerReference();

        DB::transaction(function () use ($proposal, $mission, $client, $newReference) {

            // must have recommendations
            abort_if($mission->recommendations->isEmpty(), 422, 'Aucune recommandation trouvée.');

            // create remplacer selections from recommendations
            foreach ($mission->recommendations as $rec) {
                ClientMachineMarqueSelection::create([
                    'client_id' => $client->id,
                    'machine_id' => $rec->machine_id,
                    'marque_id' => $rec->marque_id,
                    'reference' => $newReference,
                    'is_submitted' => true,
                    'submitted_at' => now(),
                ]);
            }

            // move reservation to new ref
            Reservation::where('client_id', $client->id)
                ->where('reference', $proposal->old_reference)
                ->update(['reference' => $newReference]);

            // delete old entretien selections + media
            ClientServiceSelection::where('client_id', $client->id)
                ->where('reference', $proposal->old_reference)
                ->delete();

            ClientMachineDetail::where('client_id', $client->id)
                ->where('reference', $proposal->old_reference)
                ->delete();

            // update proposal + mission
            $proposal->update([
                'status' => 'accepted',
                'new_reference' => $newReference,
            ]);

            $mission->update([
                'proposal_status' => 'accepted',
                'status' => 'completed', // ✅ finish entretien mission
            ]);
        });

        return redirect()->route('client.remplacers.show', $newReference)
            ->with('success', "Conversion acceptée. Nouvelle commande: $newReference");
    }

    public function reject(string $token)
    {
        $proposal = ConversionProposal::where('token', $token)->firstOrFail();

        $client = auth()->user()->client;
        abort_if(!$client || $client->id !== $proposal->client_id, 403);
        abort_if($proposal->status !== 'pending', 403);

        DB::transaction(function () use ($proposal) {
            $proposal->update(['status' => 'rejected']);

            $mission = Mission::find($proposal->mission_id);
            if ($mission) {
                $mission->update([
                    'proposal_status' => 'rejected',
                    'status' => 'completed', // ✅ finish mission when rejected
                ]);
            }
        });

        return redirect()->route('client.entretiens.show', $proposal->old_reference)
            ->with('success', 'Proposition refusée. Mission terminée.');
    }
}


