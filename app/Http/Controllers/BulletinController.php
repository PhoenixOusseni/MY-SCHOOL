<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\PeriodEvaluation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BulletinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $bulletins = Bulletin::with('eleve', 'classe', 'periodEvaluation')->orderBy('created_at', 'desc')->paginate(15);

            return view('pages.bulletins.index', compact('bulletins'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement des bulletins');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
            $classes = Classe::orderBy('nom')->get();
            $periodEvaluations = PeriodEvaluation::orderBy('date_debut', 'desc')->get();

            return view('pages.bulletins.create', compact('eleves', 'classes', 'periodEvaluations'));
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
                'moyenne_globale' => 'nullable|numeric|min:0',
                'rang' => 'nullable|integer|min:1',
                'total_eleves' => 'nullable|integer|min:1',
                'total_points' => 'nullable|numeric|min:0',
                'total_coefficient' => 'nullable|numeric|min:0',
                'mention_conduite' => 'nullable|string|max:255',
                'absences' => 'nullable|integer|min:0',
                'justification_absences' => 'nullable|integer|min:0',
                'retards' => 'nullable|integer|min:0',
                'commentaire_principal' => 'nullable|string',
                'commentaire_directeur' => 'nullable|string',
                'status' => 'required|in:brouillon,publie,envoye',
                'published_at' => 'nullable|date',
                'generated_at' => 'nullable|date',
                'eleve_id' => 'required|exists:eleves,id',
                'classe_id' => 'required|exists:classes,id',
                'period_evaluation_id' => [
                    'required',
                    'exists:period_evaluations,id',
                    Rule::unique('bulletins')->where(function ($query) use ($request) {
                        return $query->where('eleve_id', $request->eleve_id);
                    }),
                ],
            ]);

            if (in_array($validated['status'], ['publie', 'envoye']) && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            Bulletin::create($validated);

            return redirect()->route('gestion_bulletins.index')->with('success', 'Bulletin créé avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du bulletin');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $bulletin = Bulletin::with('eleve', 'classe', 'periodEvaluation', 'detailBulletins.matiere', 'detailBulletins.enseignant')->findOrFail($id);

            return view('pages.bulletins.show', compact('bulletin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulletin non trouvé');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $bulletin = Bulletin::findOrFail($id);
            $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
            $classes = Classe::orderBy('nom')->get();
            $periodEvaluations = PeriodEvaluation::orderBy('date_debut', 'desc')->get();

            return view('pages.bulletins.edit', compact('bulletin', 'eleves', 'classes', 'periodEvaluations'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulletin non trouvé');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $bulletin = Bulletin::findOrFail($id);

            $validated = $request->validate([
                'moyenne_globale' => 'nullable|numeric|min:0',
                'rang' => 'nullable|integer|min:1',
                'total_eleves' => 'nullable|integer|min:1',
                'total_points' => 'nullable|numeric|min:0',
                'total_coefficient' => 'nullable|numeric|min:0',
                'mention_conduite' => 'nullable|string|max:255',
                'absences' => 'nullable|integer|min:0',
                'justification_absences' => 'nullable|integer|min:0',
                'retards' => 'nullable|integer|min:0',
                'commentaire_principal' => 'nullable|string',
                'commentaire_directeur' => 'nullable|string',
                'status' => 'required|in:brouillon,publie,envoye',
                'published_at' => 'nullable|date',
                'generated_at' => 'nullable|date',
                'eleve_id' => 'required|exists:eleves,id',
                'classe_id' => 'required|exists:classes,id',
                'period_evaluation_id' => [
                    'required',
                    'exists:period_evaluations,id',
                    Rule::unique('bulletins')
                        ->where(function ($query) use ($request) {
                            return $query->where('eleve_id', $request->eleve_id);
                        })
                        ->ignore($bulletin->id),
                ],
            ]);

            if (in_array($validated['status'], ['publie', 'envoye']) && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            $bulletin->update($validated);

            return redirect()->route('gestion_bulletins.index')->with('success', 'Bulletin mis à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du bulletin');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bulletin = Bulletin::findOrFail($id);
            $bulletin->delete();

            return redirect()->route('gestion_bulletins.index')->with('success', 'Bulletin supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression du bulletin');
        }
    }

    /**
     * Print the specified resource.
     */
    public function print(string $id)
    {
        try {
            $bulletin = Bulletin::with('eleve', 'classe', 'periodEvaluation.anneeScolaire', 'detailBulletins.matiere', 'detailBulletins.enseignant')->findOrFail($id);

            return view('pages.bulletins.print', compact('bulletin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulletin non trouvé');
        }
    }
}
