<?php

namespace App\Http\Controllers;

use App\Models\Retard;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $retards = Retard::with('eleve', 'classe', 'matiere', 'reportedBy')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('pages.retards.index', compact('retards'));
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

        return view('pages.retards.create', compact('eleves', 'classes', 'matieres', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'heure_arrivee' => 'nullable|date_format:H:i',
                'duree' => 'nullable|integer|min:0',
                'is_justified' => 'nullable|boolean',
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

            Retard::create($data);

            return redirect()->route('gestion_retards.index')
                ->with('success', 'Retard enregistré avec succès');
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
        $retard = Retard::with('eleve', 'classe', 'matiere', 'reportedBy')->findOrFail($id);

        return view('pages.retards.show', compact('retard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $retard = Retard::findOrFail($id);
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $classes = Classe::orderBy('nom')->get();
        $matieres = Matiere::orderBy('intitule')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();

        return view('pages.retards.edit', compact('retard', 'eleves', 'classes', 'matieres', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'heure_arrivee' => 'nullable|date_format:H:i',
                'duree' => 'nullable|integer|min:0',
                'is_justified' => 'nullable|boolean',
                'raison' => 'nullable|string',
                'reported_at' => 'nullable|date',
                'eleve_id' => 'required|exists:eleves,id',
                'classe_id' => 'required|exists:classes,id',
                'matiere_id' => 'nullable|exists:matieres,id',
                'reported_by' => 'nullable|exists:users,id',
            ]);

            $retard = Retard::findOrFail($id);
            $data = $request->all();
            $data['is_justified'] = $request->has('is_justified');
            $data['reported_by'] = $request->reported_by ?? Auth::id();

            $retard->update($data);

            return redirect()->route('gestion_retards.show', $retard->id)
                ->with('success', 'Retard modifié avec succès');
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
            $retard = Retard::findOrFail($id);
            $retard->delete();

            return redirect()->route('gestion_retards.index')
                ->with('success', 'Retard supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
