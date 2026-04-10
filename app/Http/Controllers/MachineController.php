<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Marque;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::with('marques')->get();
        $marques = Marque::all();

        return view('admin.machines.index', compact('machines', 'marques'));
    }


    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
            'marques' => 'nullable|array',
            'marques.*' => 'exists:marques,id',
        ]);

        // STORE IMAGE
        $imagePath = $request->file('image')->store('machines', 'public');

        $years = $request->garantie_years ?? 0;
        $months = $request->garantie_months ?? 0;
        $days = $request->garantie_days ?? 0;

        $totalDays = ($years * 365) + ($months * 30) + $days;

        if ($totalDays <= 0) {
            return back()->withErrors([
                'garantie_period_days' => 'La période de garantie doit être supérieure à 0'
            ]);
        }


        // CREATE MACHINE
        $machine = Machine::create([
            'name' => $request->name,
            'image' => $imagePath,
            'garantie_period_days' => $totalDays,

        ]);

        // ATTACH MARQUES
        if ($request->filled('marques')) {
            $machine->marques()->attach($request->marques);
        }

        return back()->with('success', 'Machine ajoutée avec succès');
    }


    public function edit($id)
    {
        $machine = Machine::with('marques')->findOrFail($id);
        $marques = Marque::all();

        return view('admin.machines.editmachine', compact('machine', 'marques'));
    }

    public function update(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp', // make image optional
            'marques' => 'nullable|array',
            'marques.*' => 'exists:marques,id',
            'garantie_years' => 'nullable|integer|min:0',
            'garantie_months' => 'nullable|integer|min:0',
            'garantie_days' => 'nullable|integer|min:0',
        ]);

        // Update image if new one uploaded
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('machines', 'public');
            $data['image'] = $imagePath;
        }
        // GARANTIE LOGIC
        $years = $request->garantie_years ?? 0;
        $months = $request->garantie_months ?? 0;
        $days = $request->garantie_days ?? 0;

        $totalDays = ($years * 365) + ($months * 30) + $days;

        if ($totalDays <= 0) {
            return back()->withErrors([
                'garantie_period_days' => 'La période de garantie doit être supérieure à 0'
            ]);
        }

        // Update machine
        $machine->update([
            'name' => $data['name'],
            'image' => $data['image'] ?? $machine->image,
            'garantie_period_days' => $totalDays,
        ]);

        // Sync associated marques (many-to-many)
        $machine->marques()->sync($data['marques'] ?? []);

        return redirect()->route('admin.machines')->with('success', 'Machine mise à jour avec succès !');
    }


    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);

        $machine->delete();

        return redirect()->route('admin.machines')
            ->with('success', 'Machine supprimée avec succès !');
    }

}
