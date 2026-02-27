<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EnseignementMatiereClasse;
use App\Models\PeriodEvaluation;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $evaluations = Evaluation::with('enseignementMatiereClasse', 'periodEvaluation')
                ->orderBy('date_examen', 'desc')
                ->paginate(15);

            return view('pages.evaluations.index', compact('evaluations'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement des évaluations');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $enseignementMatiereClasses = EnseignementMatiereClasse::with('classe', 'matiere', 'enseignant')
                ->get();
            $periodEvaluations = PeriodEvaluation::orderBy('date_debut', 'desc')->get();
            $types = [
                'examen' => 'Examen',
                'controle' => 'Contrôle',
                'interrogation' => 'Interrogation',
                'devoir_sur_table' => 'Devoir sur table',
                'autre' => 'Autre'
            ];

            return view('pages.evaluations.create', compact(
                'enseignementMatiereClasses',
                'periodEvaluations',
                'types'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_examen' => 'required|date',
                'heure_debut' => 'nullable|date_format:H:i',
                'duree' => 'nullable|integer|min:1',
                'salle' => 'nullable|string|max:100',
                'note_max' => 'nullable|decimal:0,2|min:0',
                'coefficient' => 'nullable|decimal:0,2|min:0',
                'instructions' => 'nullable|string',
                'type' => 'required|in:examen,controle,interrogation,devoir_sur_table,autre',
                'est_publie' => 'nullable|boolean',
                'enseignement_matiere_classe_id' => 'required|exists:enseignement_matiere_classes,id',
                'period_evaluation_id' => 'required|exists:period_evaluations,id'
            ]);

            $validated['est_publie'] = $request->has('est_publie');

            Evaluation::create($validated);

            return redirect()->route('gestion_evaluations.index')
                ->with('success', 'Évaluation créée avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'évaluation');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $evaluation = Evaluation::with('enseignementMatiereClasse.classe.eleves', 'enseignementMatiereClasse.matiere', 'enseignementMatiereClasse.enseignant', 'periodEvaluation')->findOrFail($id);
            $notes = $evaluation->notes()->with('eleve')->get();
            $eleves = $evaluation->enseignementMatiereClasse?->classe?->eleves ?? collect();
            $enseignants = Enseignant::orderBy('nom')->get();

            return view('pages.evaluations.show', compact('evaluation', 'notes', 'eleves', 'enseignants'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement de l\'évaluation');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        try {
            $evaluation = Evaluation::findOrFail($id);
            $enseignementMatiereClasses = EnseignementMatiereClasse::with('classe', 'matiere', 'enseignant')
                ->get();
            $periodEvaluations = PeriodEvaluation::orderBy('date_debut', 'desc')->get();
            $types = [
                'examen' => 'Examen',
                'controle' => 'Contrôle',
                'interrogation' => 'Interrogation',
                'devoir_sur_table' => 'Devoir sur table',
                'autre' => 'Autre'
            ];
            return view('pages.evaluations.edit', compact(
                'evaluation',
                'enseignementMatiereClasses',
                'periodEvaluations',
                'types'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_examen' => 'required|date',
                'heure_debut' => 'nullable|date_format:H:i',
                'duree' => 'nullable|integer|min:1',
                'salle' => 'nullable|string|max:100',
                'note_max' => 'nullable|decimal:0,2|min:0',
                'coefficient' => 'nullable|decimal:0,2|min:0',
                'instructions' => 'nullable|string',
                'type' => 'required|in:examen,controle,interrogation,devoir_sur_table,autre',
                'est_publie' => 'nullable|boolean',
                'enseignement_matiere_classe_id' => 'required|exists:enseignement_matiere_classes,id',
                'period_evaluation_id' => 'required|exists:period_evaluations,id'
            ]);

            $validated['est_publie'] = $request->has('est_publie');

            $evaluation->update($validated);

            return redirect()->route('gestion_evaluations.index')
                ->with('success', 'Évaluation mise à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de l\'évaluation');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        try {
            $evaluation->delete();

            return redirect()->route('gestion_evaluations.index')
                ->with('success', 'Évaluation supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'évaluation');
        }
    }
}
