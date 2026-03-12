<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\AnneeScolaire;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\FraiScolarite;
use App\Models\IncidentDisciplinaire;
use App\Models\Inscription;
use App\Models\Niveau;
use App\Models\Paiement;
use App\Models\PeriodEvaluation;
use App\Models\Retard;
use App\Models\Sanction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    /* ═══════════════════════════════════════════════════════════
     |  HELPER : année scolaire (filtre ou courante)
     ═══════════════════════════════════════════════════════════ */

    private function resolveAnnee(?int $id): ?AnneeScolaire
    {
        if ($id) return AnneeScolaire::find($id);
        return AnneeScolaire::where('is_current', true)->first()
            ?? AnneeScolaire::latest('id')->first();
    }

    /* ═══════════════════════════════════════════════════════════
     |  1. EFFECTIFS
     ═══════════════════════════════════════════════════════════ */

    public function effectifs(Request $request)
    {
        $annees  = AnneeScolaire::orderByDesc('id')->get();
        $annee   = $this->resolveAnnee($request->integer('annee_id') ?: null);

        $parClasse = $parNiveau = $parGenre = $evolution = [];
        $totaux = ['total' => 0, 'filles' => 0, 'garcons' => 0, 'classes' => 0];

        if ($annee) {
            // Par classe
            $parClasse = Inscription::where('annee_scolaire_id', $annee->id)
                ->select('classe_id', DB::raw('COUNT(*) as total'))
                ->with('classe.niveau')
                ->groupBy('classe_id')
                ->get()
                ->map(function ($row) {
                    $filles   = Inscription::where('classe_id', $row->classe_id)
                        ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
                        ->where('genre', 'féminin')
                        ->count();
                    return [
                        'classe'   => $row->classe->nom ?? '—',
                        'niveau'   => $row->classe->niveau->nom ?? '—',
                        'total'    => $row->total,
                        'garcons'  => $row->total - $filles,
                        'filles'   => $filles,
                    ];
                })->sortBy('classe')->values();

            // Par niveau
            $parNiveau = Inscription::where('inscriptions.annee_scolaire_id', $annee->id)
                ->join('classes', 'inscriptions.classe_id', '=', 'classes.id')
                ->join('niveaux', 'classes.niveau_id', '=', 'niveaux.id')
                ->select('niveaux.nom as niveau', DB::raw('COUNT(*) as total'))
                ->groupBy('niveaux.id', 'niveaux.nom')
                ->orderBy('niveaux.nom')
                ->get();

            // Par genre global
            $parGenre = Inscription::where('inscriptions.annee_scolaire_id', $annee->id)
                ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
                ->select('eleves.genre', DB::raw('COUNT(*) as total'))
                ->groupBy('eleves.genre')
                ->get();

            // Évolution sur les 5 dernières années
            $evolution = Inscription::join('annee_scolaires', 'inscriptions.annee_scolaire_id', '=', 'annee_scolaires.id')
                ->select('annee_scolaires.libelle as annee', DB::raw('COUNT(*) as total'))
                ->groupBy('annee_scolaires.id', 'annee_scolaires.libelle')
                ->orderByDesc('annee_scolaires.id')
                ->limit(6)
                ->get()
                ->reverse()
                ->values();

            $totaux['total']   = $parClasse->sum('total');
            $totaux['filles']  = $parClasse->sum('filles');
            $totaux['garcons'] = $parClasse->sum('garcons');
            $totaux['classes'] = $parClasse->count();
        }

        return view('pages.statistiques.effectifs', compact(
            'annees', 'annee', 'parClasse', 'parNiveau', 'parGenre', 'evolution', 'totaux'
        ));
    }

    /* ═══════════════════════════════════════════════════════════
     |  2. RÉSULTATS SCOLAIRES
     ═══════════════════════════════════════════════════════════ */

    public function resultats(Request $request)
    {
        $annees  = AnneeScolaire::orderByDesc('id')->get();
        $annee   = $this->resolveAnnee($request->integer('annee_id') ?: null);
        $periodes = $annee ? PeriodEvaluation::where('annee_scolaire_id', $annee->id)->get() : collect();
        $periodeId = $request->integer('periode_id') ?: ($periodes->first()?->id);

        $parClasse = $distributionMentions = $topEleves = [];
        $totaux = ['bulletins' => 0, 'moyenne_generale' => 0];

        if ($annee && $periodeId) {
            // Moyenne par classe
            $parClasse = Bulletin::where('period_evaluation_id', $periodeId)
                ->join('classes', 'bulletins.classe_id', '=', 'classes.id')
                ->select(
                    'classes.nom as classe',
                    DB::raw('ROUND(AVG(moyenne_globale), 2) as moyenne'),
                    DB::raw('ROUND(MIN(moyenne_globale), 2) as min_moy'),
                    DB::raw('ROUND(MAX(moyenne_globale), 2) as max_moy'),
                    DB::raw('COUNT(*) as nb_eleves'),
                    DB::raw('SUM(CASE WHEN moyenne_globale >= 10 THEN 1 ELSE 0 END) as admis')
                )
                ->groupBy('bulletins.classe_id', 'classes.nom')
                ->orderBy('classes.nom')
                ->get()
                ->map(function ($r) {
                    $r->taux_reussite = $r->nb_eleves > 0
                        ? round(($r->admis / $r->nb_eleves) * 100, 1)
                        : 0;
                    return $r;
                });

            // Distribution des moyennes en plages
            $allMoyennes = Bulletin::where('period_evaluation_id', $periodeId)
                ->whereHas('classe', fn($q) => $q->where('annee_scolaire_id', $annee->id))
                ->pluck('moyenne_globale')
                ->map(fn($m) => (float) $m);

            $distributionMentions = [
                'Excellent (≥16)'   => $allMoyennes->filter(fn($m) => $m >= 16)->count(),
                'Bien (14-15.99)'   => $allMoyennes->filter(fn($m) => $m >= 14 && $m < 16)->count(),
                'Assez bien (12-13.99)' => $allMoyennes->filter(fn($m) => $m >= 12 && $m < 14)->count(),
                'Passable (10-11.99)'   => $allMoyennes->filter(fn($m) => $m >= 10 && $m < 12)->count(),
                'Insuffisant (<10)'     => $allMoyennes->filter(fn($m) => $m < 10)->count(),
            ];

            // Top 10 élèves
            $topEleves = Bulletin::where('period_evaluation_id', $periodeId)
                ->whereHas('classe', fn($q) => $q->where('annee_scolaire_id', $annee->id))
                ->with(['eleve', 'classe'])
                ->orderByDesc('moyenne_globale')
                ->limit(10)
                ->get();

            $totaux['bulletins']        = $allMoyennes->count();
            $totaux['moyenne_generale'] = $allMoyennes->count() > 0 ? round($allMoyennes->avg(), 2) : 0;
        }

        return view('pages.statistiques.resultats', compact(
            'annees', 'annee', 'periodes', 'periodeId',
            'parClasse', 'distributionMentions', 'topEleves', 'totaux'
        ));
    }

    /* ═══════════════════════════════════════════════════════════
     |  3. TAUX DE RÉUSSITE
     ═══════════════════════════════════════════════════════════ */

    public function tauxReussite(Request $request)
    {
        $annees  = AnneeScolaire::orderByDesc('id')->get();
        $annee   = $this->resolveAnnee($request->integer('annee_id') ?: null);
        $periodes = $annee ? PeriodEvaluation::where('annee_scolaire_id', $annee->id)->get() : collect();
        $periodeId = $request->integer('periode_id') ?: ($periodes->first()?->id);

        $parClasse = $evolution = [];
        $totaux = ['total' => 0, 'admis' => 0, 'taux_global' => 0];

        if ($annee && $periodeId) {
            $parClasse = Bulletin::where('period_evaluation_id', $periodeId)
                ->join('classes', 'bulletins.classe_id', '=', 'classes.id')
                ->join('niveaux', 'classes.niveau_id', '=', 'niveaux.id')
                ->select(
                    'classes.nom as classe',
                    'niveaux.nom as niveau',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN moyenne_globale >= 10 THEN 1 ELSE 0 END) as admis'),
                    DB::raw('SUM(CASE WHEN moyenne_globale < 10 THEN 1 ELSE 0 END) as echec')
                )
                ->groupBy('bulletins.classe_id', 'classes.nom', 'niveaux.nom')
                ->orderBy('niveaux.nom')
                ->orderBy('classes.nom')
                ->get()
                ->map(function ($r) {
                    $r->taux = $r->total > 0 ? round(($r->admis / $r->total) * 100, 1) : 0;
                    return $r;
                });

            // Évolution taux de réussite par période
            $evolution = PeriodEvaluation::where('annee_scolaire_id', $annee->id)
                ->orderBy('id')
                ->get()
                ->map(function ($p) use ($annee) {
                    $total = Bulletin::where('period_evaluation_id', $p->id)->count();
                    $admis = Bulletin::where('period_evaluation_id', $p->id)
                        ->where('moyenne_globale', '>=', 10)->count();
                    return [
                        'periode' => $p->libelle,
                        'total'   => $total,
                        'admis'   => $admis,
                        'taux'    => $total > 0 ? round(($admis / $total) * 100, 1) : 0,
                    ];
                });

            $totaux['total'] = $parClasse->sum('total');
            $totaux['admis'] = $parClasse->sum('admis');
            $totaux['taux_global'] = $totaux['total'] > 0
                ? round(($totaux['admis'] / $totaux['total']) * 100, 1) : 0;
        }

        return view('pages.statistiques.taux_reussite', compact(
            'annees', 'annee', 'periodes', 'periodeId', 'parClasse', 'evolution', 'totaux'
        ));
    }

    /* ═══════════════════════════════════════════════════════════
     |  4. ÉTATS FINANCIERS
     ═══════════════════════════════════════════════════════════ */

    public function finances(Request $request)
    {
        $annees = AnneeScolaire::orderByDesc('id')->get();
        $annee  = $this->resolveAnnee($request->integer('annee_id') ?: null);

        $parFrais = $evolutionMensuelle = $parMethode = [];
        $totaux = ['collecte' => 0, 'reste' => 0, 'paiements' => 0];

        if ($annee) {
            // Total collecté par type de frais
            $parFrais = Paiement::where('annee_scolaire_id', $annee->id)
                ->join('frai_scolarites', 'paiements.frai_scolarite_id', '=', 'frai_scolarites.id')
                ->select(
                    'frai_scolarites.libelle as frais',
                    DB::raw('SUM(paiements.montant) as total_perçu'),
                    DB::raw('SUM(paiements.reste_a_payer) as total_reste'),
                    DB::raw('COUNT(*) as nb_paiements')
                )
                ->groupBy('frai_scolarites.id', 'frai_scolarites.libelle')
                ->orderByDesc('total_perçu')
                ->get();

            // Évolution mensuelle
            $evolutionMensuelle = Paiement::where('annee_scolaire_id', $annee->id)
                ->select(
                    DB::raw("DATE_FORMAT(date_paiement, '%Y-%m') as mois"),
                    DB::raw("DATE_FORMAT(date_paiement, '%b %Y') as mois_label"),
                    DB::raw('SUM(montant) as total'),
                    DB::raw('COUNT(*) as nb')
                )
                ->groupBy(DB::raw("DATE_FORMAT(date_paiement, '%Y-%m')"), DB::raw("DATE_FORMAT(date_paiement, '%b %Y')"))
                ->orderBy('mois')
                ->get();

            // Répartition par méthode de paiement
            $parMethode = Paiement::where('annee_scolaire_id', $annee->id)
                ->select('methode_paiement', DB::raw('SUM(montant) as total'), DB::raw('COUNT(*) as nb'))
                ->groupBy('methode_paiement')
                ->orderByDesc('total')
                ->get();

            $totaux['collecte']   = Paiement::where('annee_scolaire_id', $annee->id)->sum('montant');
            $totaux['reste']      = Paiement::where('annee_scolaire_id', $annee->id)->sum('reste_a_payer');
            $totaux['paiements']  = Paiement::where('annee_scolaire_id', $annee->id)->count();
        }

        return view('pages.statistiques.finances', compact(
            'annees', 'annee', 'parFrais', 'evolutionMensuelle', 'parMethode', 'totaux'
        ));
    }

    /* ═══════════════════════════════════════════════════════════
     |  5. RAPPORT ASSIDUITÉ
     ═══════════════════════════════════════════════════════════ */

    public function assiduite(Request $request)
    {
        $annees  = AnneeScolaire::orderByDesc('id')->get();
        $annee   = $this->resolveAnnee($request->integer('annee_id') ?: null);
        $classes = $annee ? Classe::where('annee_scolaire_id', $annee->id)->get() : collect();
        $classeId = $request->integer('classe_id') ?: null;

        $parClasse = $parMois = $topAbsents = $parJustification = [];
        $totaux = ['absences' => 0, 'retards' => 0, 'justifiees' => 0, 'non_justifiees' => 0];

        if ($annee) {
            // Absences par classe
            $queryAbs = Absence::join('classes', 'absences.classe_id', '=', 'classes.id')
                ->where('classes.annee_scolaire_id', $annee->id);
            if ($classeId) $queryAbs->where('absences.classe_id', $classeId);

            $parClasse = (clone $queryAbs)
                ->select(
                    'classes.nom as classe',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN is_justified = 1 THEN 1 ELSE 0 END) as justifiees'),
                    DB::raw('SUM(CASE WHEN is_justified = 0 THEN 1 ELSE 0 END) as non_justifiees')
                )
                ->groupBy('absences.classe_id', 'classes.nom')
                ->orderBy('total', 'desc')
                ->get();

            // Évolution mensuelle absences
            $queryMois = Absence::join('classes', 'absences.classe_id', '=', 'classes.id')
                ->where('classes.annee_scolaire_id', $annee->id);
            if ($classeId) $queryMois->where('absences.classe_id', $classeId);

            $parMois = (clone $queryMois)
                ->select(
                    DB::raw("DATE_FORMAT(absences.date, '%Y-%m') as mois"),
                    DB::raw("DATE_FORMAT(absences.date, '%b %Y') as mois_label"),
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy(DB::raw("DATE_FORMAT(absences.date, '%Y-%m')"), DB::raw("DATE_FORMAT(absences.date, '%b %Y')"))
                ->orderBy('mois')
                ->get();

            // Top 10 élèves les plus absents
            $queryTop = Absence::join('classes', 'absences.classe_id', '=', 'classes.id')
                ->where('classes.annee_scolaire_id', $annee->id);
            if ($classeId) $queryTop->where('absences.classe_id', $classeId);

            $topAbsents = (clone $queryTop)
                ->join('eleves', 'absences.eleve_id', '=', 'eleves.id')
                ->select(
                    'eleves.nom', 'eleves.prenom',
                    'classes.nom as classe',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN is_justified = 0 THEN 1 ELSE 0 END) as non_justifiees')
                )
                ->groupBy('absences.eleve_id', 'eleves.nom', 'eleves.prenom', 'classes.nom')
                ->orderByDesc('total')
                ->limit(10)
                ->get();

            // Retards par classe
            $queryRet = Retard::join('classes', 'retards.classe_id', '=', 'classes.id')
                ->where('classes.annee_scolaire_id', $annee->id);
            if ($classeId) $queryRet->where('retards.classe_id', $classeId);

            $retardsParClasse = (clone $queryRet)
                ->select('classes.nom as classe', DB::raw('COUNT(*) as total'))
                ->groupBy('retards.classe_id', 'classes.nom')
                ->orderByDesc('total')
                ->get()
                ->keyBy('classe');

            $totaux['absences']       = $parClasse->sum('total');
            $totaux['justifiees']     = $parClasse->sum('justifiees');
            $totaux['non_justifiees'] = $parClasse->sum('non_justifiees');
            $totaux['retards']        = $retardsParClasse->sum('total');
        }

        return view('pages.statistiques.assiduite', compact(
            'annees', 'annee', 'classes', 'classeId',
            'parClasse', 'parMois', 'topAbsents', 'totaux'
        ));
    }

    /* ═══════════════════════════════════════════════════════════
     |  6. RAPPORT DISCIPLINE
     ═══════════════════════════════════════════════════════════ */

    public function discipline(Request $request)
    {
        $annees  = AnneeScolaire::orderByDesc('id')->get();
        $annee   = $this->resolveAnnee($request->integer('annee_id') ?: null);
        $classes = $annee ? Classe::where('annee_scolaire_id', $annee->id)->get() : collect();
        $classeId = $request->integer('classe_id') ?: null;

        $parType = $parClasse = $parMois = $topEleves = $sanctionsParType = [];
        $totaux = ['incidents' => 0, 'sanctions' => 0, 'graves' => 0, 'parents_notifies' => 0];

        if ($annee) {
            // Base query incidents de l'année via classe
            $baseQuery = IncidentDisciplinaire::join('eleves', 'incident_disciplinaires.eleve_id', '=', 'eleves.id')
                ->join('inscriptions', function ($j) use ($annee) {
                    $j->on('inscriptions.eleve_id', '=', 'eleves.id')
                      ->where('inscriptions.annee_scolaire_id', $annee->id);
                });
            if ($classeId) {
                $baseQuery->where('inscriptions.classe_id', $classeId);
            }

            // Par type d'incident
            $parType = (clone $baseQuery)
                ->select('incident_disciplinaires.type', DB::raw('COUNT(DISTINCT incident_disciplinaires.id) as total'))
                ->groupBy('incident_disciplinaires.type')
                ->orderByDesc('total')
                ->get();

            // Par classe
            $parClasse = (clone $baseQuery)
                ->join('classes', 'inscriptions.classe_id', '=', 'classes.id')
                ->select(
                    'classes.nom as classe',
                    DB::raw('COUNT(DISTINCT incident_disciplinaires.id) as total'),
                    DB::raw('SUM(CASE WHEN incident_disciplinaires.parent_notifie = 1 THEN 1 ELSE 0 END) as notifies')
                )
                ->groupBy('inscriptions.classe_id', 'classes.nom')
                ->orderByDesc('total')
                ->get();

            // Évolution mensuelle
            $parMois = (clone $baseQuery)
                ->select(
                    DB::raw("DATE_FORMAT(incident_disciplinaires.date_incident, '%Y-%m') as mois"),
                    DB::raw("DATE_FORMAT(incident_disciplinaires.date_incident, '%b %Y') as mois_label"),
                    DB::raw('COUNT(DISTINCT incident_disciplinaires.id) as total')
                )
                ->groupBy(
                    DB::raw("DATE_FORMAT(incident_disciplinaires.date_incident, '%Y-%m')"),
                    DB::raw("DATE_FORMAT(incident_disciplinaires.date_incident, '%b %Y')")
                )
                ->orderBy('mois')
                ->get();

            // Top 10 élèves avec le plus d'incidents
            $topEleves = (clone $baseQuery)
                ->join('classes', 'inscriptions.classe_id', '=', 'classes.id')
                ->select(
                    'eleves.nom', 'eleves.prenom',
                    'classes.nom as classe',
                    DB::raw('COUNT(DISTINCT incident_disciplinaires.id) as total')
                )
                ->groupBy('incident_disciplinaires.eleve_id', 'eleves.nom', 'eleves.prenom', 'classes.nom')
                ->orderByDesc('total')
                ->limit(10)
                ->get();

            // Sanctions par type
            $sanctionsParType = Sanction::join('eleves', 'sanctions.eleve_id', '=', 'eleves.id')
                ->join('inscriptions', function ($j) use ($annee) {
                    $j->on('inscriptions.eleve_id', '=', 'eleves.id')
                      ->where('inscriptions.annee_scolaire_id', $annee->id);
                })
                ->when($classeId, fn($q) => $q->where('inscriptions.classe_id', $classeId))
                ->select('sanctions.type', DB::raw('COUNT(DISTINCT sanctions.id) as total'))
                ->groupBy('sanctions.type')
                ->orderByDesc('total')
                ->get();

            $totaux['incidents']        = $parClasse->sum('total');
            $totaux['sanctions']        = $sanctionsParType->sum('total');
            $totaux['parents_notifies'] = $parClasse->sum('notifies');
            $totaux['graves']           = $parType->where('type', 'grave')->first()?->total ?? 0;
        }

        return view('pages.statistiques.discipline', compact(
            'annees', 'annee', 'classes', 'classeId',
            'parType', 'parClasse', 'parMois', 'topEleves', 'sanctionsParType', 'totaux'
        ));
    }
}
