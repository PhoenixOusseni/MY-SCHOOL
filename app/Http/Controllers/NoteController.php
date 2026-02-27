<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Eleve;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $notes = Note::with('evaluation', 'eleve', 'enteredBy')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('pages.notes.index', compact('notes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement des notes');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $evaluations = Evaluation::with('enseignementMatiereClasse.classe', 'enseignementMatiereClasse.matiere')
                ->orderBy('date_examen', 'desc')
                ->get();
            $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
            $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

            return view('pages.notes.create', compact('evaluations', 'eleves', 'enseignants'));
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
                'score' => 'nullable|numeric|min:0',
                'max_score' => 'required|numeric|min:0',
                'is_absent' => 'nullable|boolean',
                'absence_justified' => 'nullable|boolean',
                'comment' => 'nullable|string',
                'entered_by' => 'nullable|exists:enseignants,id',
                'evaluation_id' => 'required|exists:evaluations,id',
                'eleve_id' => 'required|exists:eleves,id',
            ]);

            // Gestion des booléens
            $validated['is_absent'] = $request->has('is_absent');
            $validated['absence_justified'] = $request->has('absence_justified');

            // Calcul du pourcentage
            if (!$validated['is_absent'] && isset($validated['score']) && $validated['max_score'] > 0) {
                $validated['percentage'] = ($validated['score'] / $validated['max_score']) * 100;
            } else {
                $validated['percentage'] = null;
            }

            $validated['entered_at'] = now();

            Note::create($validated);

            return redirect()->back()
                ->with('success', 'Note créée avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la note: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $note = Note::with('evaluation.enseignementMatiereClasse.classe', 'evaluation.enseignementMatiereClasse.matiere', 'eleve', 'enteredBy')
                ->findOrFail($id);

            return view('pages.notes.show', compact('note'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Note non trouvée');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $note = Note::findOrFail($id);
            $evaluations = Evaluation::with('enseignementMatiereClasse.classe', 'enseignementMatiereClasse.matiere')
                ->orderBy('date_examen', 'desc')
                ->get();
            $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
            $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

            return view('pages.notes.edit', compact('note', 'evaluations', 'eleves', 'enseignants'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Note non trouvée');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $note = Note::findOrFail($id);

            $validated = $request->validate([
                'score' => 'nullable|numeric|min:0',
                'max_score' => 'required|numeric|min:0',
                'is_absent' => 'nullable|boolean',
                'absence_justified' => 'nullable|boolean',
                'comment' => 'nullable|string',
                'entered_by' => 'nullable|exists:enseignants,id',
                'evaluation_id' => 'required|exists:evaluations,id',
                'eleve_id' => 'required|exists:eleves,id',
            ]);

            // Gestion des booléens
            $validated['is_absent'] = $request->has('is_absent');
            $validated['absence_justified'] = $request->has('absence_justified');

            // Calcul du pourcentage
            if (!$validated['is_absent'] && isset($validated['score']) && $validated['max_score'] > 0) {
                $validated['percentage'] = ($validated['score'] / $validated['max_score']) * 100;
            } else {
                $validated['percentage'] = null;
            }

            $note->update($validated);

            return redirect()->back()
                ->with('success', 'Note mise à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la note');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $note = Note::findOrFail($id);
            $note->delete();

            return redirect()->route('gestion_notes.index')
                ->with('success', 'Note supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la note');
        }
    }
}
