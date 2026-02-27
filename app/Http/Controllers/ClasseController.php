<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::all();
        $niveaux = Niveau::all();
        $etablissements = Etablissement::all();
        $anneesScolaires = AnneeScolaire::all();
        return view('pages.classes.index', compact('classes', 'niveaux', 'etablissements', 'anneesScolaires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'capacite' => 'nullable|integer|min:1|max:500',
            'salle' => 'nullable|string|max:50',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'etablissement_id' => 'nullable|exists:etablissements,id',
            'annee_scolaire_id' => 'nullable|exists:annee_scolaires,id',
        ]);

        $classe = new Classe();
        $classe->nom = $request->nom;
        $classe->capacite = $request->capacite ?? 50;
        $classe->salle = $request->salle;
        $classe->niveau_id = $request->niveau_id;
        $classe->etablissement_id = $request->etablissement_id;
        $classe->annee_scolaire_id = $request->annee_scolaire_id;
        $classe->save();

        return redirect()->route('gestion_classes.index')->with('success', 'Classe créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $classe = Classe::findOrFail($id);
        return view('pages.classes.show', compact('classe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $niveaux = Niveau::all();
        $etablissements = Etablissement::all();
        $anneesScolaires = AnneeScolaire::all();
        return view('pages.classes.edit', compact('classe', 'niveaux', 'etablissements', 'anneesScolaires'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'capacite' => 'nullable|integer|min:1|max:500',
            'salle' => 'nullable|string|max:50',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'etablissement_id' => 'nullable|exists:etablissements,id',
            'annee_scolaire_id' => 'nullable|exists:annee_scolaires,id',
        ]);

        $classe->nom = $request->nom;
        $classe->capacite = $request->capacite ?? 50;
        $classe->salle = $request->salle;
        $classe->niveau_id = $request->niveau_id;
        $classe->etablissement_id = $request->etablissement_id;
        $classe->annee_scolaire_id = $request->annee_scolaire_id;
        $classe->save();

        return redirect()->route('gestion_classes.index')->with('success', 'Classe mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('gestion_classes.index')->with('success', 'Classe supprimée avec succès.');
    }
}
