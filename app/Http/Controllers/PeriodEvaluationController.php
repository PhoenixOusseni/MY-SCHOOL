<?php

namespace App\Http\Controllers;

use App\Models\PeriodEvaluation;
use Illuminate\Http\Request;

class PeriodEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodesEvaluation = PeriodEvaluation::with('anneeScolaire')
            ->orderBy('order_index')
            ->paginate(15);

        return view('pages.periodes_evaluation.index', compact('periodesEvaluation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = [
            'trimestre' => 'Trimestre',
            'semestre' => 'Semestre',
            'quart' => 'Quart',
        ];

        $anneesScolaires = AnneeScolaire::all();

        return view('pages.periodes_evaluation.create', compact('types', 'anneesScolaires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'libelle' => 'required|string|max:255',
                'type' => 'required|in:trimestre,semestre,quart',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after:date_debut',
                'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
                'order_index' => 'nullable|integer',
            ]);

            PeriodEvaluation::create($request->all());

            return redirect()->route('gestion_periodes_evaluation.index')
                ->with('success', 'Période d\'évaluation créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $periodeEvaluation = PeriodEvaluation::with('anneeScolaire')->findOrFail($id);
            return view('pages.periodes_evaluation.show', compact('periodeEvaluation'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_periodes_evaluation.index')
                ->with('error', 'Période d\'évaluation non trouvée');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        try {
            $types = [
                'trimestre' => 'Trimestre',
                'semestre' => 'Semestre',
                'quart' => 'Quart',
            ];

            $anneesScolaires = AnneeScolaire::all();

            $periodeEvaluation = PeriodEvaluation::findOrFail($id);

            return view('pages.periodes_evaluation.edit', compact('periodeEvaluation', 'types', 'anneesScolaires'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_periodes_evaluation.index')
                ->with('error', 'Période d\'évaluation non trouvée');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validation des données
        try {
            $request->validate([
                'libelle' => 'required|string|max:255',
                'type' => 'required|in:trimestre,semestre,quart',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after:date_debut',
                'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
                'order_index' => 'nullable|integer',
            ]);

            $periodeEvaluation = PeriodEvaluation::findOrFail($id);
            $periodeEvaluation->update($request->all());

            return redirect()->route('gestion_periodes_evaluation.show', $periodeEvaluation->id)
                ->with('success', 'Période d\'évaluation modifiée avec succès');
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
        try {
            $periodeEvaluation = PeriodEvaluation::findOrFail($id);
            $periodeEvaluation->delete();

            return redirect()->route('gestion_periodes_evaluation.index')
                ->with('success', 'Période d\'évaluation supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
