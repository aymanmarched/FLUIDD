<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TypeEquipement;
use Illuminate\Http\Request;

class TypeEquipementController extends Controller
{
    public function store(Request $request)
    {
        TypeEquipement::create([
            'name' => $request->name,
            'caracteres' => explode(',', $request->caracteres),
            'prix' => $request->prix,
        ]);

        return back()->with('success', 'Type ajouté');
    }
    public function edit($id)
    {
        $type = TypeEquipement::findOrFail($id);
        return view('admin.entretenir.edittype', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = TypeEquipement::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'caracteres' => 'nullable|string',
            'prix' => 'required|numeric',
        ]);

        $type->update([
            'name' => $data['name'],
            'caracteres' => $data['caracteres'] ? explode(',', $data['caracteres']) : [],
            'prix' => $data['prix'],
        ]);

        return redirect()->route('admin.entretenir')->with('success', 'Type d’équipement mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $type = TypeEquipement::findOrFail($id);

        $type->delete();

        return redirect()->route('admin.entretenir')
            ->with('success', 'Type du Gestion d’Entretien supprimée avec succès !');
    }

}
