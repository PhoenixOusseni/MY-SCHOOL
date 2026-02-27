<?php

namespace App\Http\Controllers;

use App\Models\Devoir;
use App\Models\EnseignementMatiereClasse;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class DevoirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devoirs = Devoir::with('enseignementMatiereClasse.enseignant', 'enseignementMatiereClasse.matiere', 'enseignementMatiereClasse.classe')
            ->orderBy('date_echeance', 'desc')
            ->paginate(15);

        return view('pages.devoirs.index', compact('devoirs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $enseignements = EnseignementMatiereClasse::with('enseignant', 'matiere', 'classe')
            ->orderBy('classe_id')
            ->get();

        $types = [
            'travail de maison' => 'Travail de maison',
            'projet' => 'Projet',
            'recherche' => 'Recherche',
            'lecture' => 'Lecture',
            'autre' => 'Autre',
        ];

        return view('pages.devoirs.create', compact('enseignements', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:travail de maison,projet,recherche,lecture,autre',
                'date_assignation' => 'nullable|date',
                'date_echeance' => 'nullable|date',
                'note_max' => 'nullable|numeric|min:0',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png',
                'est_note' => 'nullable|boolean',
                'enseignement_matiere_classe_id' => 'required|exists:enseignement_matiere_classes,id',
            ]);

            $data = $request->all();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('devoirs', $filename, 'public');
                $data['attachment'] = 'devoirs/' . $filename;
            }

            Devoir::create($data);

            return redirect()->route('gestion_devoirs.index')
                ->with('success', 'Devoir créé avec succès');
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
        $devoir = Devoir::with('enseignementMatiereClasse.enseignant', 'enseignementMatiereClasse.matiere', 'enseignementMatiereClasse.classe.eleves', 'soumissionsDevoirs.eleve')->findOrFail($id);

        $eleves = $devoir->enseignementMatiereClasse?->classe?->eleves ?? collect();
        $enseignants = Enseignant::orderBy('nom')->get();
        $statuses = [
            'en cours' => 'En cours',
            'soumis'   => 'Soumis',
            'en retard' => 'En retard',
            'noté'     => 'Noté',
        ];

        return view('pages.devoirs.show', compact('devoir', 'eleves', 'enseignants', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $devoir = Devoir::findOrFail($id);
        $enseignements = EnseignementMatiereClasse::with('enseignant', 'matiere', 'classe')
            ->orderBy('classe_id')
            ->get();

        $types = [
            'travail de maison' => 'Travail de maison',
            'projet' => 'Projet',
            'recherche' => 'Recherche',
            'lecture' => 'Lecture',
            'autre' => 'Autre',
        ];

        return view('pages.devoirs.edit', compact('devoir', 'enseignements', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:travail de maison,projet,recherche,lecture,autre',
                'date_assignation' => 'nullable|date',
                'date_echeance' => 'nullable|date',
                'note_max' => 'nullable|numeric|min:0',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png',
                'est_note' => 'nullable|boolean',
                'enseignement_matiere_classe_id' => 'required|exists:enseignement_matiere_classes,id',
            ]);

            $devoir = Devoir::findOrFail($id);
            $data = $request->all();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                // Delete old file if exists
                if ($devoir->attachment && file_exists(storage_path('app/public/' . $devoir->attachment))) {
                    unlink(storage_path('app/public/' . $devoir->attachment));
                }

                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('devoirs', $filename, 'public');
                $data['attachment'] = 'devoirs/' . $filename;
            }

            $devoir->update($data);

            return redirect()->route('gestion_devoirs.index')
                ->with('success', 'Devoir modifié avec succès');
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
            $devoir = Devoir::findOrFail($id);

            // Delete attachment if exists
            if ($devoir->attachment && file_exists(storage_path('app/public/' . $devoir->attachment))) {
                unlink(storage_path('app/public/' . $devoir->attachment));
            }

            $devoir->delete();

            return redirect()->route('gestion_devoirs.index')
                ->with('success', 'Devoir supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
