<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\DetailBulletin;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\MatiereNiveau;
use App\Models\MoyenneMatiere;
use App\Models\PeriodEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $matieres = Matiere::orderBy('intitule')->get();
            $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

            return view('pages.bulletins.show', compact('bulletin', 'matieres', 'enseignants'));
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

    /**
     * Show the bulk generation form.
     */
    public function generateForm()
    {
        try {
            $classes = Classe::with('niveau')->orderBy('nom')->get();
            $periodEvaluations = PeriodEvaluation::orderBy('date_debut', 'desc')->get();

            return view('pages.bulletins.generate', compact('classes', 'periodEvaluations'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement du formulaire de génération');
        }
    }

    /**
     * Automatically generate bulletins for all students in a class/period
     * based on their evaluation grades (notes).
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'classe_id'           => 'required|exists:classes,id',
            'period_evaluation_id' => 'required|exists:period_evaluations,id',
            'status'              => 'required|in:brouillon,publie,envoye',
            'overwrite'           => 'nullable|boolean',
        ]);

        $overwrite = $request->boolean('overwrite');
        $status    = $validated['status'];

        try {
            $classe = Classe::with('niveau')->findOrFail($validated['classe_id']);
            $period = PeriodEvaluation::findOrFail($validated['period_evaluation_id']);

            // Students in the class (via inscriptions)
            $eleves = $classe->eleves()->get();

            if ($eleves->isEmpty()) {
                return redirect()->back()->with('error', 'Aucun élève inscrit dans cette classe.');
            }

            // EnseignementMatiereClasse for this class, with evaluations filtered by period
            $emcs = $classe->enseignementMatiereClasses()
                ->with([
                    'matiere',
                    'enseignant',
                    'evaluations' => fn($q) => $q->where('period_evaluation_id', $period->id),
                    'evaluations.notes',
                ])
                ->get();

            // Subject coefficients from MatiereNiveau (subject coefficient per niveau)
            $niveauId    = $classe->niveau_id;
            $matiereIds  = $emcs->pluck('matiere_id')->unique();
            $coefficients = MatiereNiveau::where('niveau_id', $niveauId)
                ->whereIn('matiere_id', $matiereIds)
                ->pluck('coefficient', 'matiere_id');

            // ── 1. Calculate average per student per subject ─────────────────
            // [eleve_id][matiere_id] = ['moyenne', 'coefficient', 'moyenne_ponderee', 'emc']
            $studentSubjectData = [];

            foreach ($eleves as $eleve) {
                foreach ($emcs as $emc) {
                    if ($emc->evaluations->isEmpty()) continue;

                    $totalWeightedScore = 0;
                    $totalWeight        = 0;

                    foreach ($emc->evaluations as $evaluation) {
                        $note = $evaluation->notes->firstWhere('eleve_id', $eleve->id);
                        if (!$note || $note->is_absent || is_null($note->score) || $evaluation->note_max <= 0) {
                            continue;
                        }
                        $scoreOn20           = ($note->score / $evaluation->note_max) * 20;
                        $totalWeightedScore += $scoreOn20 * $evaluation->coefficient;
                        $totalWeight        += $evaluation->coefficient;
                    }

                    if ($totalWeight > 0) {
                        $moyenne      = round($totalWeightedScore / $totalWeight, 2);
                        $subjectCoeff = $coefficients[$emc->matiere_id] ?? 1;

                        $studentSubjectData[$eleve->id][$emc->matiere_id] = [
                            'moyenne'          => $moyenne,
                            'coefficient'      => $subjectCoeff,
                            'moyenne_ponderee' => round($moyenne * $subjectCoeff, 2),
                            'emc'              => $emc,
                        ];
                    }
                }
            }

            // ── 2. Class-level stats per subject (for DetailBulletin) ────────
            // [matiere_id] = ['moyenne_classe', 'min', 'max']
            $subjectClassStats = [];

            foreach ($emcs as $emc) {
                $allAverages = [];
                foreach ($eleves as $eleve) {
                    if (isset($studentSubjectData[$eleve->id][$emc->matiere_id])) {
                        $allAverages[] = $studentSubjectData[$eleve->id][$emc->matiere_id]['moyenne'];
                    }
                }
                if (!empty($allAverages)) {
                    $subjectClassStats[$emc->matiere_id] = [
                        'moyenne_classe' => round(array_sum($allAverages) / count($allAverages), 2),
                        'min'            => min($allAverages),
                        'max'            => max($allAverages),
                    ];
                }
            }

            // ── 3. Subject ranks per student ─────────────────────────────────
            // [matiere_id][eleve_id] = rank
            $subjectRanks = [];

            foreach ($emcs as $emc) {
                $matiereAverages = [];
                foreach ($eleves as $eleve) {
                    if (isset($studentSubjectData[$eleve->id][$emc->matiere_id])) {
                        $matiereAverages[$eleve->id] = $studentSubjectData[$eleve->id][$emc->matiere_id]['moyenne'];
                    }
                }
                arsort($matiereAverages);
                $rank = 1;
                foreach ($matiereAverages as $eleveId => $avg) {
                    $subjectRanks[$emc->matiere_id][$eleveId] = $rank++;
                }
            }

            // ── 4. Global average per student ────────────────────────────────
            // [eleve_id] = ['moyenne_globale', 'total_points', 'total_coefficient']
            $globalAverages = [];

            foreach ($eleves as $eleve) {
                if (empty($studentSubjectData[$eleve->id])) continue;

                $totalPoints = 0;
                $totalCoeff  = 0;
                foreach ($studentSubjectData[$eleve->id] as $data) {
                    $totalPoints += $data['moyenne_ponderee'];
                    $totalCoeff  += $data['coefficient'];
                }

                if ($totalCoeff > 0) {
                    $globalAverages[$eleve->id] = [
                        'moyenne_globale'   => round($totalPoints / $totalCoeff, 2),
                        'total_points'      => round($totalPoints, 2),
                        'total_coefficient' => round($totalCoeff, 2),
                    ];
                }
            }

            if (empty($globalAverages)) {
                return redirect()->back()->with('error', 'Aucune note trouvée pour cette classe et cette période. Vérifiez que des évaluations et des notes ont été saisies.');
            }

            // ── 5. Overall rank (by global average) ──────────────────────────
            $sortedEleveIds = collect($globalAverages)
                ->sortByDesc('moyenne_globale')
                ->keys()
                ->values();
            $totalEleves = $eleves->count();

            // ── 6. Create / update bulletins & detail_bulletins ──────────────
            $created = 0;
            $updated = 0;
            $skipped = 0;

            DB::beginTransaction();

            foreach ($eleves as $eleve) {
                if (!isset($globalAverages[$eleve->id])) continue;

                $existing = Bulletin::where('eleve_id', $eleve->id)
                    ->where('period_evaluation_id', $period->id)
                    ->first();

                if ($existing && !$overwrite) {
                    $skipped++;
                    continue;
                }

                $rang        = $sortedEleveIds->search($eleve->id) + 1;
                $globalData  = $globalAverages[$eleve->id];
                $publishedAt = ($existing ? $existing->published_at : null);

                if (in_array($status, ['publie', 'envoye']) && !$publishedAt) {
                    $publishedAt = now();
                }

                $bulletinData = [
                    'eleve_id'             => $eleve->id,
                    'classe_id'            => $classe->id,
                    'period_evaluation_id' => $period->id,
                    'moyenne_globale'      => $globalData['moyenne_globale'],
                    'total_points'         => $globalData['total_points'],
                    'total_coefficient'    => $globalData['total_coefficient'],
                    'rang'                 => $rang,
                    'total_eleves'         => $totalEleves,
                    'status'               => $status,
                    'generated_at'         => now(),
                    'published_at'         => $publishedAt,
                ];

                if ($existing) {
                    $existing->update($bulletinData);
                    $bulletin = $existing;
                    $updated++;
                } else {
                    $bulletin = Bulletin::create($bulletinData);
                    $created++;
                }

                // Detail bulletins (one per subject)
                foreach ($studentSubjectData[$eleve->id] as $matiereId => $data) {
                    $emc        = $data['emc'];
                    $classStats = $subjectClassStats[$matiereId] ?? null;
                    $subjectRang = $subjectRanks[$matiereId][$eleve->id] ?? null;
                    $appreciation = $this->getAppreciation($data['moyenne']);

                    DetailBulletin::updateOrCreate(
                        ['bulletin_id' => $bulletin->id, 'matiere_id' => $matiereId],
                        [
                            'enseignant_id'    => $emc->enseignant_id,
                            'moyenne'          => $data['moyenne'],
                            'coefficient'      => $data['coefficient'],
                            'moyenne_ponderee' => $data['moyenne_ponderee'],
                            'moyenne_classe'   => $classStats ? $classStats['moyenne_classe'] : null,
                            'point_min'        => $classStats ? $classStats['min'] : null,
                            'point_max'        => $classStats ? $classStats['max'] : null,
                            'rang'             => $subjectRang,
                            'appreciation'     => $appreciation,
                        ]
                    );

                    // Also persist in moyenne_matieres for statistics
                    MoyenneMatiere::updateOrCreate(
                        [
                            'eleve_id'             => $eleve->id,
                            'matiere_id'           => $matiereId,
                            'period_evaluation_id' => $period->id,
                        ],
                        [
                            'classe_id'        => $classe->id,
                            'moyenne'          => $data['moyenne'],
                            'coefficient'      => $data['coefficient'],
                            'moyenne_ponderee' => $data['moyenne_ponderee'],
                            'rang'             => $subjectRang,
                            'total_eleve'      => count($subjectRanks[$matiereId] ?? []),
                            'appreciation'     => $appreciation,
                            'calculated_at'    => now(),
                        ]
                    );
                }
            }

            DB::commit();

            $message = "Génération terminée : {$created} bulletin(s) créé(s), {$updated} mis à jour";
            if ($skipped > 0) {
                $message .= ", {$skipped} ignoré(s) (déjà existants).";
            }

            return redirect()->route('gestion_bulletins.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la génération : ' . $e->getMessage());
        }
    }

    /**
     * Return a French appreciation label for a grade out of 20.
     */
    private function getAppreciation(float $moyenne): string
    {
        if ($moyenne >= 18) return 'Excellent';
        if ($moyenne >= 16) return 'Très Bien';
        if ($moyenne >= 14) return 'Bien';
        if ($moyenne >= 12) return 'Assez Bien';
        if ($moyenne >= 10) return 'Passable';
        return 'Insuffisant';
    }
}
