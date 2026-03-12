<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class DossierEleveController extends Controller
{
    /**
     * Liste de tous les élèves avec filtres pour accéder à leur dossier.
     */
    public function index(Request $request)
    {
        $query = Eleve::with(['etablissement', 'inscriptions.classe', 'inscriptions.anneeScolaire']);

        // Filtre par nom/prénom/matricule
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Filtre par classe (via inscriptions)
        if ($request->filled('classe_id')) {
            $query->whereHas('inscriptions', function ($q) use ($request) {
                $q->where('classe_id', $request->classe_id);
            });
        }

        // Filtre par année scolaire
        if ($request->filled('annee_scolaire_id')) {
            $query->whereHas('inscriptions', function ($q) use ($request) {
                $q->where('annee_scolaire_id', $request->annee_scolaire_id);
            });
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $eleves = $query->orderBy('nom')->orderBy('prenom')->get();
        $classes = Classe::orderBy('nom')->get();
        $anneesScolaires = AnneeScolaire::orderBy('libelle', 'desc')->get();

        return view('pages.dossiers_eleves.index', compact('eleves', 'classes', 'anneesScolaires'));
    }

    /**
     * Vue d'impression du dossier complet d'un élève.
     */
    public function print(string $id)
    {
        $eleve = Eleve::with([
            'etablissement',
            'user',
            'inscriptions.classe.niveau',
            'inscriptions.anneeScolaire',
            'eleveParents.tuteur',
            'absences',
            'retards',
            'incidentsDisciplinaires',
            'sanctions',
            'paiements.fraiScolarite',
            'paiements.anneeScolaire',
            'bulletins.classe',
            'bulletins.periodEvaluation',
            'bulletins.detailBulletins',
            'notes',
        ])->findOrFail($id);

        $totalAbsences  = $eleve->absences->count();
        $totalRetards   = $eleve->retards->count();
        $totalIncidents = $eleve->incidentsDisciplinaires->count();
        $totalPaye      = $eleve->paiements->sum('montant');
        $totalReste     = $eleve->paiements->sum('reste_a_payer');
        $derniereInscription = $eleve->inscriptions->sortByDesc('created_at')->first();

        return view('pages.dossiers_eleves.print', compact(
            'eleve',
            'totalAbsences',
            'totalRetards',
            'totalIncidents',
            'totalPaye',
            'totalReste',
            'derniereInscription'
        ));
    }

    /**
     * Affiche le dossier complet d'un élève.
     */
    public function show(string $id)
    {
        $eleve = Eleve::with([
            'etablissement',
            'user',
            'inscriptions.classe.niveau',
            'inscriptions.anneeScolaire',
            'eleveParents.tuteur',
            'absences',
            'retards',
            'incidentsDisciplinaires',
            'sanctions',
            'paiements.fraiScolarite',
            'paiements.anneeScolaire',
            'bulletins.classe',
            'bulletins.periodEvaluation',
            'bulletins.detailBulletins',
            'notes',
        ])->findOrFail($id);

        // Stats rapides
        $totalAbsences   = $eleve->absences->count();
        $totalRetards    = $eleve->retards->count();
        $totalIncidents  = $eleve->incidentsDisciplinaires->count();
        $totalPaye       = $eleve->paiements->sum('montant');
        $totalReste      = $eleve->paiements->sum('reste_a_payer');

        // Dernière inscription (année courante ou la plus récente)
        $derniereInscription = $eleve->inscriptions->sortByDesc('created_at')->first();

        return view('pages.dossiers_eleves.show', compact(
            'eleve',
            'totalAbsences',
            'totalRetards',
            'totalIncidents',
            'totalPaye',
            'totalReste',
            'derniereInscription'
        ));
    }
}
