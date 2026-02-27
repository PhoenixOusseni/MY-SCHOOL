<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Etablissement;
use Illuminate\Http\Request;

class AnneeScolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annees = AnneeScolaire::with('etablissement')->get();
        $etablissements = Etablissement::all();
        return view('pages.annees.index', compact('annees', 'etablissements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'etablissement_id' => 'required|exists:etablissements,id',
            'libelle' => 'required|string|max:255|unique:annee_scolaires,libelle',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'is_current' => 'sometimes|boolean',
        ]);

        $anneeScolaire = new AnneeScolaire();
        $anneeScolaire->etablissement_id = $request->etablissement_id;
        $anneeScolaire->libelle = $request->libelle;
        $anneeScolaire->date_debut = $request->date_debut;
        $anneeScolaire->date_fin = $request->date_fin;
        $anneeScolaire->is_current = $request->has('is_current') ? $request->is_current : false;
        $anneeScolaire->save();

        return redirect()->route('gestion_annees_scolaires.index')->with('success', 'Année scolaire créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $annee = AnneeScolaire::with([
            'etablissement',
            'classes.niveau',
            'classes.eleves',
            'inscriptions.eleve.user',
            'inscriptions.classe',
            'enseignementMatiereClasses.enseignant.user',
            'enseignementMatiereClasses.matiere',
            'enseignementMatiereClasses.classe',
            'periodEvaluations',
            'paiements.inscription.eleve.user',
            'professeurPrincipals.enseignant.user'
        ])->findOrFail($id);
        
        return view('pages.annees.show', compact('annee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $anneeScolaire = AnneeScolaire::findOrFail($id);
        $etablissements = Etablissement::all();
        return view('pages.annees.edit', compact('anneeScolaire', 'etablissements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $anneeScolaire = AnneeScolaire::findOrFail($id);

        $request->validate([
            'etablissement_id' => 'required|exists:etablissements,id',
            'libelle' => 'required|string|max:255|unique:annee_scolaires,libelle,' . $anneeScolaire->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'is_current' => 'sometimes|boolean',
        ]);

        $anneeScolaire->etablissement_id = $request->etablissement_id;
        $anneeScolaire->libelle = $request->libelle;
        $anneeScolaire->date_debut = $request->date_debut;
        $anneeScolaire->date_fin = $request->date_fin;
        $anneeScolaire->is_current = $request->has('is_current') ? $request->is_current : false;
        $anneeScolaire->save();

        return redirect()->route('gestion_annees_scolaires.index')->with('success', 'Année scolaire mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $anneeScolaire = AnneeScolaire::findOrFail($id);
        $anneeScolaire->delete();

        return redirect()->route('gestion_annees_scolaires.index')->with('success', 'Année scolaire supprimée avec succès.');
    }
}
