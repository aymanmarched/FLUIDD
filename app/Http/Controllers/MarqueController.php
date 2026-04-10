<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use Illuminate\Http\Request;

class MarqueController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prix' => 'required|numeric',
            'caractere' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        // STORE IMAGE
        $imagePath = $request->file('image')->store('marques', 'public');

        // CREATE MARQUE
        Marque::create([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'caractere' => explode(',', $request->caractere),
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Marque ajoutée avec succès');
    }

     public function edit($id)
    {
        $marque = Marque::findOrFail($id);
        return view('admin.machines.editmarque', compact('marque'));
    }

   public function update(Request $request, $id)
{
    $marque = Marque::findOrFail($id);

    $data = $request->validate([
        'nom' => 'required|string',
        'caractere' => 'nullable|string',
        'prix' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp', // optional
    ]);

    // Update image if uploaded
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('marques', 'public');
        $data['image'] = $imagePath;
    }

    // Update marque
    $marque->update([
        'nom' => $data['nom'],
        'caractere' => $data['caractere'] ? explode(',', $data['caractere']) : [],
        'prix' => $data['prix'],
        'image' => $data['image'] ?? $marque->image,
    ]);

    return redirect()->route('admin.machines')
        ->with('success', 'Marque mise à jour avec succès !');
}



    public function destroy($id)
    {
        $marque = Marque::findOrFail($id);

        $marque->delete();

        return redirect()->route('admin.machines')
            ->with('success', 'Marque supprimée avec succès !');
    }

}
