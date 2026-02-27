<?php

namespace App\Http\Controllers;

use App\Models\SoumissionDevoir;
use App\Models\Devoir;
use App\Models\Eleve;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class SoumissionDevoirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soumissions = SoumissionDevoir::with('devoir', 'eleve', 'gradedBy')
            ->orderBy('date_submission', 'desc')
            ->paginate(15);

        return view('pages.soumissions_devoirs.index', compact('soumissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devoirs = Devoir::with('enseignementMatiereClasse')->orderBy('created_at', 'desc')->get();
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $statuses = [
            'en cours' => 'En cours',
            'soumis' => 'Soumis',
            'en retard' => 'En retard',
            'noté' => 'Noté',
        ];

        return view('pages.soumissions_devoirs.create', compact('devoirs', 'eleves', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'devoir_id' => 'required|exists:devoirs,id',
                'eleve_id' => 'required|exists:eleves,id',
                'date_submission' => 'nullable|date',
                'content' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,zip,rar',
                'status' => 'required|in:en cours,soumis,en retard,noté',
                'score' => 'nullable|numeric|min:0',
                'feedback' => 'nullable|string',
                'graded_by' => 'nullable|exists:enseignants,id',
            ]);

            $data = $request->all();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('soumissions', $filename, 'public');
                $data['attachment'] = 'soumissions/' . $filename;
            }

            if ($request->filled('score')) {
                $data['status'] = 'noté';
                $data['graded_at'] = now();
            }

            SoumissionDevoir::create($data);

            return redirect()->back()
                ->with('success', 'Soumission créée avec succès');
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
        $soumission = SoumissionDevoir::with('devoir', 'eleve', 'gradedBy')->findOrFail($id);

        return view('pages.soumissions_devoirs.show', compact('soumission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $soumission = SoumissionDevoir::findOrFail($id);
        $devoirs = Devoir::with('enseignementMatiereClasse')->orderBy('created_at', 'desc')->get();
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $enseignants = Enseignant::where('statut', 'actif')->orderBy('nom')->get();
        $statuses = [
            'en cours' => 'En cours',
            'soumis' => 'Soumis',
            'en retard' => 'En retard',
            'noté' => 'Noté',
        ];

        return view('pages.soumissions_devoirs.edit', compact('soumission', 'devoirs', 'eleves', 'enseignants', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'devoir_id' => 'required|exists:devoirs,id',
                'eleve_id' => 'required|exists:eleves,id',
                'date_submission' => 'nullable|date',
                'content' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,zip,rar',
                'status' => 'required|in:en cours,soumis,en retard,noté',
                'score' => 'nullable|numeric|min:0',
                'feedback' => 'nullable|string',
                'graded_by' => 'nullable|exists:enseignants,id',
            ]);

            $soumission = SoumissionDevoir::findOrFail($id);
            $data = $request->all();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                // Delete old file if exists
                if ($soumission->attachment && file_exists(storage_path('app/public/' . $soumission->attachment))) {
                    unlink(storage_path('app/public/' . $soumission->attachment));
                }

                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('soumissions', $filename, 'public');
                $data['attachment'] = 'soumissions/' . $filename;
            }

            if ($request->filled('score')) {
                $data['status'] = 'noté';
                $data['graded_at'] = now();
            }

            $soumission->update($data);

            return redirect()->back()
                ->with('success', 'Soumission mise à jour avec succès');
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
            $soumission = SoumissionDevoir::findOrFail($id);

            // Delete attachment if exists
            if ($soumission->attachment && file_exists(storage_path('app/public/' . $soumission->attachment))) {
                unlink(storage_path('app/public/' . $soumission->attachment));
            }

            $soumission->delete();

            return redirect()->route('gestion_soumissions.index')
                ->with('success', 'Soumission supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
