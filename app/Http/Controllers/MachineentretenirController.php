<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntretenirMonMachine;
use App\Models\Machine;
use App\Models\TypeEquipement;
use Illuminate\Http\Request;

class MachineentretenirController extends Controller
{
    public function store(Request $request)
    {
        // 1️⃣ Validate
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image',
            'type_ids' => 'required|array', // <-- validate types
            'type_ids.*' => 'exists:type_equipements,id',
            'remplacer_machine_id' => 'required|exists:machines,id',
        ]);

        $rm = Machine::findOrFail($data['remplacer_machine_id']);

        // 2️⃣ Store image
        $imagePath = $request->file('image')->store('entretenir', 'public');

        // 3️⃣ Create machine
        $machine = EntretenirMonMachine::create([
            'name' => $data['name'],
            'machine' => $rm->name, // ✅ auto fill (important)
            'image' => $imagePath,
            'remplacer_machine_id' => $rm->id,
        ]);

        // 4️⃣ Attach selected types
        $machine->types()->sync($data['type_ids']);

        return back()->with('success', 'Machine ajoutée avec succès !');
    }


    public function edit($id)
    {
        $entretenir = EntretenirMonMachine::with('types')->findOrFail($id);
        $types = TypeEquipement::all();

        return view('admin.entretenir.editentretenir', compact('entretenir', 'types'));
    }


public function update(Request $request, $id)
{
    $entretenir = EntretenirMonMachine::findOrFail($id);

    $data = $request->validate([
        'name' => 'required|string',
        'image' => 'nullable|image',
        'remplacer_machine_id' => 'required|exists:machines,id',
        'type_ids' => 'required|array',
        'type_ids.*' => 'exists:type_equipements,id',
    ]);

    $rm = Machine::findOrFail($data['remplacer_machine_id']);

    // ✅ update image if new one uploaded
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('entretenir_machines', 'public');
    } else {
        unset($data['image']);
    }

    $entretenir->update([
        'name' => $data['name'],
        'remplacer_machine_id' => $rm->id,
        'machine' => $rm->name, // ✅ auto fill
        ...$data,
    ]);

    $entretenir->types()->sync($data['type_ids']);

    return redirect()->route('admin.entretenir')->with('success', 'Machine mise à jour avec succès');
}


    public function destroy($id)
    {
        $machine = EntretenirMonMachine::findOrFail($id);

        // Optionally, detach types before deleting
        $machine->types()->detach();

        $machine->delete();

        return redirect()->route('admin.entretenir')
            ->with('success', 'Gestion d’Entretien supprimée avec succès !');
    }


}
