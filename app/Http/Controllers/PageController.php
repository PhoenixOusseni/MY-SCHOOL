<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\AnneeScolaire;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\IncidentDisciplinaire;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\PeriodEvaluation;
use App\Models\Retard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    // Afficher la page de connexion
    public function home()
    {
        return view('pages.auth.login');
    }

    // Afficher la page d'inscription des utilisateurs
    public function add_users()
    {
        return view('pages.auth.register');
    }

    // Afficher le tableau de bord
    public function dashboard()
    {
        // Année scolaire courante
        $annee = AnneeScolaire::where('is_current', true)->first()
            ?? AnneeScolaire::latest('id')->first();

        // ── KPI principaux ──────────────────────────────────────────
        $totalEleves     = Eleve::count();
        $totalEnseignants = Enseignant::count();
        $totalClasses    = $annee ? Classe::where('annee_scolaire_id', $annee->id)->count() : 0;
        $totalInscrits   = $annee
            ? Inscription::where('annee_scolaire_id', $annee->id)->count()
            : 0;

        // ── Finances (année courante) ────────────────────────────────
        $totalCollecte = $annee
            ? Paiement::where('annee_scolaire_id', $annee->id)->sum('montant')
            : 0;
        $totalReste = $annee
            ? Paiement::where('annee_scolaire_id', $annee->id)->sum('reste_a_payer')
            : 0;

        // ── Assiduité (mois courant) ─────────────────────────────────
        $absencesMois = Absence::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->count();
        $retardsMois  = Retard::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->count();

        // ── Discipline (mois courant) ────────────────────────────────
        $incidentsMois = IncidentDisciplinaire::whereMonth('date_incident', Carbon::now()->month)
            ->whereYear('date_incident', Carbon::now()->year)
            ->count();

        // ── Répartition par genre ────────────────────────────────────
        $parGenre = $annee
            ? Inscription::where('inscriptions.annee_scolaire_id', $annee->id)
                ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
                ->select('eleves.genre', DB::raw('COUNT(*) as total'))
                ->groupBy('eleves.genre')
                ->get()
            : collect();

        // ── Évolution des effectifs (6 dernières années) ─────────────
        $evolutionEffectifs = Inscription::join('annee_scolaires', 'inscriptions.annee_scolaire_id', '=', 'annee_scolaires.id')
            ->select('annee_scolaires.libelle as annee', DB::raw('COUNT(*) as total'))
            ->groupBy('annee_scolaires.id', 'annee_scolaires.libelle')
            ->orderByDesc('annee_scolaires.id')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // ── Taux de réussite (dernière période de l'année courante) ──
        $tauxReussite = 0;
        $dernierePeriode = null;
        if ($annee) {
            $dernierePeriode = PeriodEvaluation::where('annee_scolaire_id', $annee->id)
                ->latest('id')->first();
            if ($dernierePeriode) {
                $total = Bulletin::where('period_evaluation_id', $dernierePeriode->id)->count();
                $admis = Bulletin::where('period_evaluation_id', $dernierePeriode->id)
                    ->where('moyenne_globale', '>=', 10)->count();
                $tauxReussite = $total > 0 ? round(($admis / $total) * 100, 1) : 0;
            }
        }

        // ── Top 5 classes par effectif ────────────────────────────────
        $topClasses = $annee
            ? Inscription::where('inscriptions.annee_scolaire_id', $annee->id)
                ->join('classes', 'inscriptions.classe_id', '=', 'classes.id')
                ->select('classes.nom as classe', DB::raw('COUNT(*) as total'))
                ->groupBy('inscriptions.classe_id', 'classes.nom')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
            : collect();

        // ── Paiements mensuels (6 derniers mois) ─────────────────────
        $paiementsMensuels = Paiement::select(
                DB::raw("DATE_FORMAT(date_paiement, '%Y-%m') as mois"),
                DB::raw("DATE_FORMAT(date_paiement, '%b %Y') as mois_label"),
                DB::raw('SUM(montant) as total')
            )
            ->groupBy(DB::raw("DATE_FORMAT(date_paiement, '%Y-%m')"), DB::raw("DATE_FORMAT(date_paiement, '%b %Y')"))
            ->orderByDesc('mois')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        return view('pages.dashboard.index', compact(
            'annee',
            'totalEleves', 'totalEnseignants', 'totalClasses', 'totalInscrits',
            'totalCollecte', 'totalReste',
            'absencesMois', 'retardsMois', 'incidentsMois',
            'parGenre', 'evolutionEffectifs',
            'tauxReussite', 'dernierePeriode',
            'topClasses', 'paiementsMensuels'
        ));
    }

    // Afficher le profil utilisateur
    public function profile($id)
    {
        $user = Auth::user();
        return view('pages.users.profil', compact('user'));
    }
}
