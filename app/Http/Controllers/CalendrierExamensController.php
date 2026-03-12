<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Classe;
use App\Models\PeriodEvaluation;
use Carbon\Carbon;

class CalendrierExamensController extends Controller
{
    public function index(Request $request)
    {
        try {
            $classes  = Classe::with('niveau')->orderBy('nom')->get();
            $periodes = PeriodEvaluation::orderBy('date_debut', 'desc')->get();
            $types    = ['examen', 'controle', 'interrogation', 'devoir_sur_table', 'autre'];

            $query = Evaluation::with([
                'enseignementMatiereClasse.classe',
                'enseignementMatiereClasse.matiere',
                'enseignementMatiereClasse.enseignant',
                'periodEvaluation',
            ])->whereNotNull('date_examen');

            if ($request->filled('classe_id')) {
                $query->whereHas('enseignementMatiereClasse', function ($q) use ($request) {
                    $q->where('classe_id', $request->classe_id);
                });
            }

            if ($request->filled('period_evaluation_id')) {
                $query->where('period_evaluation_id', $request->period_evaluation_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('mois')) {
                try {
                    $date = Carbon::createFromFormat('Y-m', $request->mois);
                    $query->whereYear('date_examen', $date->year)
                          ->whereMonth('date_examen', $date->month);
                } catch (\Exception $e) {
                    // ignore invalid date format
                }
            }

            $evaluations = $query->orderBy('date_examen')->get();

            // Group by date for list view
            $evaluationsParJour = $evaluations->groupBy(function ($e) {
                return Carbon::parse($e->date_examen)->format('Y-m-d');
            });

            // Format for FullCalendar
            $events = $evaluations->map(function ($e) {
                $color   = $e->enseignementMatiereClasse->matiere->color ?? '#c41e3a';
                $start   = $e->date_examen;
                if ($e->heure_debut) {
                    $start .= 'T' . $e->heure_debut;
                }
                return [
                    'id'    => $e->id,
                    'title' => $e->titre . ' · ' . ($e->enseignementMatiereClasse->classe->nom ?? ''),
                    'start' => $start,
                    'color' => $color,
                    'url'   => route('gestion_evaluations.show', $e->id),
                    'extendedProps' => [
                        'type'       => ucfirst(str_replace('_', ' ', $e->type)),
                        'classe'     => $e->enseignementMatiereClasse->classe->nom ?? 'N/A',
                        'matiere'    => $e->enseignementMatiereClasse->matiere->intitule ?? 'N/A',
                        'enseignant' => ($e->enseignementMatiereClasse->enseignant->prenom ?? '') . ' ' . ($e->enseignementMatiereClasse->enseignant->nom ?? ''),
                        'salle'      => $e->salle ?? '-',
                        'heure'      => $e->heure_debut ?? '-',
                        'duree'      => $e->duree ? $e->duree . ' min' : '-',
                        'note_max'   => $e->note_max,
                        'est_publie' => $e->est_publie,
                        'periode'    => $e->periodEvaluation->libelle ?? '-',
                    ],
                ];
            })->values();

            return view('pages.calendrier_examens.index', compact(
                'evaluations', 'evaluationsParJour', 'events',
                'classes', 'periodes', 'types'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement du calendrier : ' . $e->getMessage());
        }
    }
}
