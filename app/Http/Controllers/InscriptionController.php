<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inscriptions = Inscription::with(['eleve', 'classe', 'anneeScolaire'])->get();
        return view('pages.inscriptions.index', compact('inscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eleves = Eleve::all();
        $classes = Classe::all();
        $anneesScolaires = AnneeScolaire::all();
        return view('pages.inscriptions.create', compact('eleves', 'classes', 'anneesScolaires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Créer une nouvelle inscription
            $inscription = Inscription::create([
                'eleve_id' => $request->input('eleve_id'),
                'classe_id' => $request->input('classe_id'),
                'annee_scolaire_id' => $request->input('annee_scolaire_id'),
                'date_inscription' => $request->input('date_inscription'),
                'statut' => $request->input('statut', 'inscrit'),
            ]);

            // Récupérer les informations complètes
            $inscription->load(['eleve', 'classe', 'anneeScolaire']);

            return redirect()->route('gestion_inscriptions.index')
                ->with('success', "Inscription de {$inscription->eleve->prenom} {$inscription->eleve->nom} à la classe {$inscription->classe->libelle} créée avec succès.");

        } catch (\Illuminate\Database\QueryException $e) {
            // Erreur de contrainte unique ou base de données
            if (str_contains($e->getMessage(), 'unique_inscription')) {
                return redirect()->back()
                    ->with('error', 'Cet élève est déjà inscrit à cette classe pour cette année scolaire.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('error', 'Erreur de base de données: ' . $e->getMessage())
                ->withInput();

        } catch (\Exception $e) {
            // Autres erreurs
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'inscription: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $inscription = Inscription::with(['eleve', 'classe', 'anneeScolaire'])->findOrFail($id);
            return view('pages.inscriptions.show', compact('inscription'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_inscriptions.index')
                ->with('error', 'Inscription non trouvée: ' . $e->getMessage());
        }
    }

    /**
     * Show the print preview of the inscription
     */
    public function print(String $id)
    {
        try {
            $inscription = Inscription::with(['eleve', 'classe', 'anneeScolaire'])->findOrFail($id);
            return view('pages.inscriptions.print', compact('inscription'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_inscriptions.index')
                ->with('error', 'Inscription non trouvée: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        try {
            $inscription = Inscription::findOrFail($id);
            $eleves = Eleve::all();
            $classes = Classe::all();
            $anneesScolaires = AnneeScolaire::all();
            return view('pages.inscriptions.edit', compact('inscription', 'eleves', 'classes', 'anneesScolaires'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_inscriptions.index')
                ->with('error', 'Inscription non trouvée: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            $inscription = Inscription::findOrFail($id);

            $inscription->update([
                'eleve_id' => $request->input('eleve_id'),
                'classe_id' => $request->input('classe_id'),
                'annee_scolaire_id' => $request->input('annee_scolaire_id'),
                'date_inscription' => $request->input('date_inscription'),
                'statut' => $request->input('statut', 'inscrit'),
            ]);

            return redirect()->route('gestion_inscriptions.show', $inscription->id)
                ->with('success', "Inscription mise à jour avec succès.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'inscription: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $inscription = Inscription::findOrFail($id);
            $eleveNom = $inscription->eleve->prenom . ' ' . $inscription->eleve->nom;
            $inscription->delete();

            return redirect()->route('gestion_inscriptions.index')
                ->with('success', "Inscription de {$eleveNom} supprimée avec succès.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }
}
