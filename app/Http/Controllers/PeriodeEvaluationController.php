<?php

namespace App\Http\Controllers;

use App\Models\PeriodEvaluation;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class PeriodeEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodes = PeriodEvaluation::with('anneeScolaire')
            ->orderBy('date_debut')
            ->paginate(15);
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();

        return view('pages.periodes_evaluation.index', compact('periodes', 'anneesScolaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();
        $types = [
            'trimester' => 'Trimestre',
            'semester' => 'Semestre',
            'quarter' => 'Quart',
        ];

        return view('pages.periodes_evaluation.create', compact('anneesScolaires', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'libelle' => 'required|string|max:255',
                'type' => 'required|in:trimester,semester,quarter',
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
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $periode = PeriodEvaluation::with('anneeScolaire', 'evaluations')->findOrFail($id);

        return view('pages.periodes_evaluation.show', compact('periode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $periode = PeriodEvaluation::findOrFail($id);
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();
        $types = [
            'trimester' => 'Trimestre',
            'semester' => 'Semestre',
            'quarter' => 'Quart',
        ];

        return view('pages.periodes_evaluation.edit', compact('periode', 'anneesScolaires', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'libelle' => 'required|string|max:255',
                'type' => 'required|in:trimester,semester,quarter',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after:date_debut',
                'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
                'order_index' => 'nullable|integer',
            ]);

            $periode = PeriodEvaluation::findOrFail($id);
            $periode->update($request->all());

            return redirect()->route('gestion_periodes_evaluation.index')
                ->with('success', 'Période d\'évaluation mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $periode = PeriodEvaluation::findOrFail($id);
            $periode->delete();

            return redirect()->route('gestion_periodes_evaluation.index')
                ->with('success', 'Période d\'évaluation supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
