<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absences = Absence::with('eleve', 'classe', 'matiere', 'reportedBy')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('pages.absences.index', compact('absences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $classes = Classe::orderBy('nom')->get();
        $matieres = Matiere::orderBy('intitule')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();

        // Map eleve_id => classe_id (dernière inscription)
        $eleveClasseMap = Inscription::orderBy('created_at', 'desc')
            ->get()
            ->unique('eleve_id')
            ->pluck('classe_id', 'eleve_id');

        return view('pages.absences.create', compact('eleves', 'classes', 'matieres', 'users', 'eleveClasseMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'periode' => 'nullable|string|max:255',
                'is_justified' => 'nullable|boolean',
                'justification_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
                'raison' => 'nullable|string',
                'reported_at' => 'nullable|date',
                'eleve_id' => 'required|exists:eleves,id',
                'classe_id' => 'required|exists:classes,id',
                'matiere_id' => 'nullable|exists:matieres,id',
                'reported_by' => 'nullable|exists:users,id',
            ]);

            $data = $request->all();
            $data['is_justified'] = $request->has('is_justified');
            $data['reported_by'] = $request->reported_by ?? Auth::id();

            if ($request->hasFile('justification_document')) {
                $file = $request->file('justification_document');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('absences', $filename, 'public');
                $data['justification_document'] = 'absences/' . $filename;
            }

            Absence::create($data);

            return redirect()->route('gestion_absences.index')
                ->with('success', 'Absence enregistrée avec succès');
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
        $absence = Absence::with('eleve', 'classe', 'matiere', 'reportedBy')->findOrFail($id);

        return view('pages.absences.show', compact('absence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absence = Absence::findOrFail($id);
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $classes = Classe::orderBy('nom')->get();
        $matieres = Matiere::orderBy('intitule')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();

        return view('pages.absences.edit', compact('absence', 'eleves', 'classes', 'matieres', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'periode' => 'nullable|string|max:255',
                'is_justified' => 'nullable|boolean',
                'justification_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
                'raison' => 'nullable|string',
                'reported_at' => 'nullable|date',
                'eleve_id' => 'required|exists:eleves,id',
                'classe_id' => 'required|exists:classes,id',
                'matiere_id' => 'nullable|exists:matieres,id',
                'reported_by' => 'nullable|exists:users,id',
            ]);

            $absence = Absence::findOrFail($id);
            $data = $request->all();
            $data['is_justified'] = $request->has('is_justified');
            $data['reported_by'] = $request->reported_by ?? Auth::id();

            if ($request->hasFile('justification_document')) {
                if ($absence->justification_document && file_exists(storage_path('app/public/' . $absence->justification_document))) {
                    unlink(storage_path('app/public/' . $absence->justification_document));
                }

                $file = $request->file('justification_document');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('absences', $filename, 'public');
                $data['justification_document'] = 'absences/' . $filename;
            }

            $absence->update($data);

            return redirect()->route('gestion_absences.show', $absence->id)
                ->with('success', 'Absence modifiée avec succès');
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
            $absence = Absence::findOrFail($id);

            if ($absence->justification_document && file_exists(storage_path('app/public/' . $absence->justification_document))) {
                unlink(storage_path('app/public/' . $absence->justification_document));
            }

            $absence->delete();

            return redirect()->route('gestion_absences.index')
                ->with('success', 'Absence supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
