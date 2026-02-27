<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Models\Etablissement;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $niveaux = Niveau::with('etablissement')->get();
        $etablissements = Etablissement::all();
        return view('pages.niveaux.index', compact('niveaux', 'etablissements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:niveaux,code',
            'nom' => 'required|string|max:100',
            'order_index' => 'required|integer|min:1',
            'etablissement_id' => 'nullable|exists:etablissements,id',
        ]);

        $niveau = new Niveau();
        $niveau->code = $request->code;
        $niveau->nom = $request->nom;
        $niveau->order_index = $request->order_index;
        $niveau->etablissement_id = $request->etablissement_id;
        $niveau->save();

        return redirect()->route('gestion_niveaux.index')->with('success', 'Niveau créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $niveau = Niveau::with([
            'etablissement',
            'classes.eleves',
            'classes.enseignementMatiereClasses.enseignant.user',
            'matiereNiveaux.matiere'
        ])->findOrFail($id);
        
        return view('pages.niveaux.show', compact('niveau'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $niveau = Niveau::with('etablissement')->findOrFail($id);
        $etablissements = Etablissement::all();
        return view('pages.niveaux.edit', compact('niveau', 'etablissements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $niveau = Niveau::with('etablissement')->findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:20|unique:niveaux,code,' . $niveau->id,
            'nom' => 'required|string|max:100',
            'order_index' => 'required|integer|min:1',
            'etablissement_id' => 'nullable|exists:etablissements,id',
        ]);

        $niveau->code = $request->code;
        $niveau->nom = $request->nom;
        $niveau->order_index = $request->order_index;
        $niveau->etablissement_id = $request->etablissement_id;
        $niveau->save();

        return redirect()->route('gestion_niveaux.index')->with('success', 'Niveau mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $niveau = Niveau::with('etablissement')->findOrFail($id);
        $niveau->delete();

        return redirect()->route('gestion_niveaux.index')->with('success', 'Niveau supprimé avec succès.');
    }
}
