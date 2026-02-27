<?php

namespace App\Http\Controllers;

use App\Models\EnseignementMatiereClasse;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class EnseignementMatiereClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enseignementMatiereClasses = EnseignementMatiereClasse::with('enseignant', 'matiere', 'classe', 'anneeScolaire')
            ->paginate(15);
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();
        $classes = Classe::orderBy('nom')->get();
        return view('pages.enseignement_matiere_classes.index', compact('enseignementMatiereClasses', 'anneesScolaires', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $enseignants = Enseignant::where('statut', 'actif')->orderBy('nom')->orderBy('prenom')->get();
        $matieres = Matiere::where('is_active', true)->orderBy('intitule')->get();
        $classes = Classe::orderBy('nom')->get();
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();
        return view('pages.enseignement_matiere_classes.create', compact('enseignants', 'matieres', 'classes', 'anneesScolaires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            EnseignementMatiereClasse::create([
                'enseignant_id' => $request->enseignant_id,
                'matiere_id' => $request->matiere_id,
                'classe_id' => $request->classe_id,
                'annee_scolaire_id' => $request->annee_scolaire_id,
                'heure_par_semaine' => $request->heure_par_semaine ?? 1,
            ]);

            return redirect()->route('gestion_enseignement_matieres.index')
                ->with('success', 'Enseignement assigné avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . ($e->getCode() == 23000 ? 'Cette combinaison enseignant/matière/classe/année existe déjà' : $e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $enseignementMatiereClasse = EnseignementMatiereClasse::with('enseignant', 'matiere', 'classe', 'anneeScolaire', 'devoirs', 'evaluations')->findOrFail($id);
        return view('pages.enseignement_matiere_classes.show', compact('enseignementMatiereClasse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $enseignement = EnseignementMatiereClasse::findOrFail($id);
        $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();
        $matieres = Matiere::orderBy('intitule')->get();
        $classes = Classe::orderBy('nom')->get();
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();
        return view('pages.enseignement_matiere_classes.edit', compact('enseignement', 'enseignants', 'matieres', 'classes', 'anneesScolaires'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $enseignementMatiereClasse = EnseignementMatiereClasse::findOrFail($id);
        try {
            $enseignementMatiereClasse->update([
                'heure_par_semaine' => $request->heure_par_semaine ?? 1,
            ]);

            return redirect()->route('gestion_enseignement_matieres.show', $enseignementMatiereClasse->id)
                ->with('success', 'Enseignement modifié avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $enseignementMatiereClasse = EnseignementMatiereClasse::findOrFail($id);
        try {
            $enseignementMatiereClasse->delete();

            return redirect()->route('gestion_enseignement_matieres.index')
                ->with('success', 'Enseignement supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
