<?php

namespace App\Http\Controllers;

use App\Models\AvisClient;
use Illuminate\Http\Request;

class ClientAvisController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;

        abort_if(!$client, 403);

        // One avis per client (if you want multiple, change ->first() to ->latest()->get()
        // $avis = AvisClient::where('client_id', $client->id)->latest()->first();
        $avisList = AvisClient::where('client_id', $client->id)->latest()->get();

return view('client.avis.index', compact('client','avisList'));    }

   public function store(Request $request)
{
    $user = auth()->user();
    $client = $user->client;

    abort_if(!$client, 403);

    $data = $request->validate([
        'stars'   => ['required','integer','min:1','max:5'],
        'message' => ['nullable','string','max:2000'],
    ]);

    AvisClient::create([
        'client_id' => $client->id,
        'user_id' => $user->id,
        'nom' => $client->nom ?? ($user->name ?? ''),
        'prenom' => $client->prenom ?? '',
        'telephone' => $client->telephone ?? ($user->phone ?? ''),
        'stars' => $data['stars'],
        'message' => $data['message'] ?? null,
    ]);

    return redirect()
        ->route('client.avis.index')
        ->with('success','Merci ! Votre avis a été ajouté.');
}
    public function update(Request $request, AvisClient $avis)
    {
        $user = auth()->user();
        $client = $user->client;

        abort_if(!$client, 403);
        abort_if((int)$avis->client_id !== (int)$client->id, 403);

        $data = $request->validate([
            'stars'   => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $avis->update([
            'stars' => $data['stars'],
            'message' => $data['message'] ?? null,
        ]);

        return redirect()->route('client.avis.index')->with('success', 'Votre avis a été mis à jour.');
    }

    public function destroy(AvisClient $avis)
    {
        $user = auth()->user();
        $client = $user->client;

        abort_if(!$client, 403);
        abort_if((int)$avis->client_id !== (int)$client->id, 403);

        $avis->delete();

        return redirect()->route('client.avis.index')->with('success', 'Votre avis a été supprimé.');
    }
}