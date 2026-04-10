<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvisClient;
use Illuminate\Http\Request;

class AvisClientController extends Controller
{
    
//     public function index()
// {
//     $avis = AvisClient::latest()->take(20)->get(); // you can change limit
//     return view('user.home', compact('avis'));
// }
public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string',
        ]);

       $avis = AvisClient::create($data);

    // Return with modal data
    return back()->with('avis', $avis);
    }

    public function update(Request $request, AvisClient $avis)
{
    $data = $request->validate([
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'telephone' => 'required|string',
        'stars' => 'required|integer|min:1|max:5',
        'message' => 'nullable|string',
    ]);

    $avis->update($data);

    return back()->with('avis', $avis);
}

public function destroy($id)
{
    $avis_client = AvisClient::findOrFail($id);
    $avis_client->delete();

    return redirect()->route('admin.AvisClients')
        ->with('success', 'Avis du client supprimé avec succès !');
}

}
