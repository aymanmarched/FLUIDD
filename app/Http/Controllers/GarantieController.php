<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Garantie;
use App\Models\Machine;
use App\Models\Ville;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GarantieController extends Controller
{
    public function create()
    {
        $client = null;

        if (Auth::check()) {
            $client = Auth::user()->client;
        }

        return view('user.service.entretien.activer-ma-garantie', [
            'villes' => Ville::all(),
            'machines' => Machine::with('marques')->get(),
            'client' => $client
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'telephone' => 'required',
            'email' => 'required',
            'ville_id' => 'required|exists:villes,id',
            'adresse' => 'required',
            'machine_id' => 'required|exists:machines,id',
            'marque_id' => 'required|exists:marques,id',
            'machine_series' => 'required',
        ]);

        // FORMAT PHONE
        $data['telephone'] = '+212' . ltrim(preg_replace('/\D/', '', $data['telephone']), '0');

        // 🔍 CHECK IF GARANTIE ALREADY EXISTS (SERIE + MACHINE + MARQUE)
        $existingGarantie = Garantie::where('machine_series', $data['machine_series'])
            ->where('machine_id', $data['machine_id'])
            ->where('marque_id', $data['marque_id'])
            ->first();

        if ($existingGarantie) {

            $remainingDays = now()->diffInDays(
                $existingGarantie->date_garante,
                false
            );

            return view('user.service.entretien.activergarantie', [
                'status' => 'already',
                'garantie' => $existingGarantie,
                'remainingDays' => max(0, (int) $remainingDays),
            ]);
        }

        $machine = Machine::findOrFail($data['machine_id']);

        $dateFinGarantie = now()->addDays($machine->garantie_period_days);
        // ✅ CREATE NEW GARANTIE
        $garantie = Garantie::create([
            ...$data,
            'date_garante' => $dateFinGarantie,
        ]);
        $remainingDays = now()->diffInDays($dateFinGarantie);

        return view('user.service.entretien.activergarantie', [
            'status' => 'success',
            'garantie' => $garantie,
            'remainingDays' => $remainingDays,
        ]);
    }


    public function clientIndex()
    {
        $client = Auth::user()->client;

        abort_if(!$client, 403);

        $garanties = Garantie::with(['machine', 'marque'])
            ->where(function ($q) use ($client) {
                $q->where('email', $client->email)
                    ->orWhere('telephone', $client->telephone);
            })
            ->latest()
            ->get();

        return view('client.garanties.index', compact('garanties'));
    }

    public function clientShow(Garantie $garantie)
    {
        $client = Auth::user()->client;

        abort_if(!$client, 403);

        // 🔐 SECURITY CHECK
        if (
            $garantie->email !== $client->email &&
            $garantie->telephone !== $client->telephone
        ) {
            abort(403);
        }

        return view('client.garanties.show', compact('garantie'));
    }

}

