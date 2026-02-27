<?php

namespace App\Http\Controllers;

use App\Models\Sanction;
use App\Models\Eleve;
use App\Models\IncidentDisciplinaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanctions = Sanction::with('eleve', 'imposedBy', 'incidentDisciplinaire')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.sanctions.index', compact('sanctions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $incidents = IncidentDisciplinaire::with('eleve')->orderBy('date_incident', 'desc')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();

        $types = [
            'avertissement' => 'Avertissement',
            'detention' => 'Détention',
            'suspension' => 'Suspension',
            'expulsion' => 'Expulsion',
            'autre' => 'Autre',
        ];

        $statuses = [
            'pending' => 'En attente',
            'active' => 'Active',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
        ];

        return view('pages.sanctions.create', compact('eleves', 'incidents', 'users', 'types', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:avertissement,detention,suspension,expulsion,autre',
                'description' => 'required|string',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'duree' => 'nullable|integer|min:0',
                'imposed_by' => 'nullable|exists:users,id',
                'incident_disciplinaire_id' => 'required|exists:incident_disciplinaires,id',
                'eleve_id' => 'required|exists:eleves,id',
                'status' => 'required|in:pending,active,completed,cancelled',
            ]);

            Sanction::create([
                'type' => $request->type,
                'description' => $request->description,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'duree' => $request->duree,
                'imposed_by' => $request->imposed_by ?? Auth::id(),
                'incident_disciplinaire_id' => $request->incident_disciplinaire_id,
                'eleve_id' => $request->eleve_id,
                'status' => $request->status,
            ]);

            return redirect()->route('gestion_sanctions.index')
                ->with('success', 'Sanction enregistrée avec succès');
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
        $sanction = Sanction::with('eleve', 'imposedBy', 'incidentDisciplinaire')->findOrFail($id);

        return view('pages.sanctions.show', compact('sanction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sanction = Sanction::findOrFail($id);
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $incidents = IncidentDisciplinaire::with('eleve')->orderBy('date_incident', 'desc')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();

        $types = [
            'avertissement' => 'Avertissement',
            'detention' => 'Détention',
            'suspension' => 'Suspension',
            'expulsion' => 'Expulsion',
            'autre' => 'Autre',
        ];

        $statuses = [
            'pending' => 'En attente',
            'active' => 'Active',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
        ];

        return view('pages.sanctions.edit', compact('sanction', 'eleves', 'incidents', 'users', 'types', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|in:avertissement,detention,suspension,expulsion,autre',
                'description' => 'required|string',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'duree' => 'nullable|integer|min:0',
                'imposed_by' => 'nullable|exists:users,id',
                'incident_disciplinaire_id' => 'required|exists:incident_disciplinaires,id',
                'eleve_id' => 'required|exists:eleves,id',
                'status' => 'required|in:pending,active,completed,cancelled',
            ]);

            $sanction = Sanction::findOrFail($id);
            $sanction->update([
                'type' => $request->type,
                'description' => $request->description,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'duree' => $request->duree,
                'imposed_by' => $request->imposed_by ?? Auth::id(),
                'incident_disciplinaire_id' => $request->incident_disciplinaire_id,
                'eleve_id' => $request->eleve_id,
                'status' => $request->status,
            ]);

            return redirect()->route('gestion_sanctions.show', $sanction->id)
                ->with('success', 'Sanction modifiée avec succès');
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
            $sanction = Sanction::findOrFail($id);
            $sanction->delete();

            return redirect()->route('gestion_sanctions.index')
                ->with('success', 'Sanction supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
