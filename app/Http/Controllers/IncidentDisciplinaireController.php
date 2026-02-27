<?php

namespace App\Http\Controllers;

use App\Models\IncidentDisciplinaire;
use App\Models\Eleve;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class IncidentDisciplinaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidents = IncidentDisciplinaire::with('eleve', 'reportedBy')
            ->orderBy('date_incident', 'desc')
            ->paginate(15);

        return view('pages.incidents.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

        $types = [
            'inconduite' => 'Inconduite',
            'violence' => 'Violence',
            'insubordination' => 'Insubordination',
            'tricherie' => 'Tricherie',
            'autre' => 'Autre',
        ];

        $gravites = [
            'mineur' => 'Mineur',
            'modéré' => 'Modéré',
            'sérieux' => 'Sérieux',
            'critique' => 'Critique',
        ];

        $statuts = [
            'ouvert' => 'Ouvert',
            'en_cours_examen' => 'En cours d\'examen',
            'résolu' => 'Résolu',
            'fermé' => 'Fermé',
        ];

        return view('pages.incidents.create', compact('eleves', 'enseignants', 'types', 'gravites', 'statuts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'date_incident' => 'required|date',
                'heure_incident' => 'nullable|date_format:H:i',
                'type' => 'required|in:inconduite,violence,insubordination,tricherie,autre',
                'gravite' => 'required|in:mineur,modéré,sérieux,critique',
                'description' => 'required|string',
                'emplacement' => 'nullable|string|max:255',
                'temoins' => 'nullable|string',
                'action_pris' => 'nullable|string',
                'parent_notifie' => 'nullable|boolean',
                'date_notification' => 'nullable|date',
                'statut' => 'required|in:ouvert,en_cours_examen,résolu,fermé',
                'eleve_id' => 'required|exists:eleves,id',
                'reported_by' => 'nullable|exists:enseignants,id',
            ]);

            IncidentDisciplinaire::create([
                'date_incident' => $request->date_incident,
                'heure_incident' => $request->heure_incident,
                'type' => $request->type,
                'gravité' => $request->gravite,
                'description' => $request->description,
                'emplacement' => $request->emplacement,
                'temoins' => $request->temoins,
                'action_pris' => $request->action_pris,
                'parent_notifie' => $request->has('parent_notifie'),
                'date_notification' => $request->date_notification,
                'statut' => $request->statut,
                'eleve_id' => $request->eleve_id,
                'reported_by' => $request->reported_by,
            ]);

            return redirect()->route('gestion_incidents.index')
                ->with('success', 'Incident disciplinaire enregistré avec succès');
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
        $incident = IncidentDisciplinaire::with('eleve', 'reportedBy', 'sanctions')->findOrFail($id);

        return view('pages.incidents.show', compact('incident'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $incident = IncidentDisciplinaire::findOrFail($id);
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

        $types = [
            'inconduite' => 'Inconduite',
            'violence' => 'Violence',
            'insubordination' => 'Insubordination',
            'tricherie' => 'Tricherie',
            'autre' => 'Autre',
        ];

        $gravites = [
            'mineur' => 'Mineur',
            'modéré' => 'Modéré',
            'sérieux' => 'Sérieux',
            'critique' => 'Critique',
        ];

        $statuts = [
            'ouvert' => 'Ouvert',
            'en_cours_examen' => 'En cours d\'examen',
            'résolu' => 'Résolu',
            'fermé' => 'Fermé',
        ];

        return view('pages.incidents.edit', compact('incident', 'eleves', 'enseignants', 'types', 'gravites', 'statuts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'date_incident' => 'required|date',
                'heure_incident' => 'nullable|date_format:H:i',
                'type' => 'required|in:inconduite,violence,insubordination,tricherie,autre',
                'gravite' => 'required|in:mineur,modéré,sérieux,critique',
                'description' => 'required|string',
                'emplacement' => 'nullable|string|max:255',
                'temoins' => 'nullable|string',
                'action_pris' => 'nullable|string',
                'parent_notifie' => 'nullable|boolean',
                'date_notification' => 'nullable|date',
                'statut' => 'required|in:ouvert,en_cours_examen,résolu,fermé',
                'eleve_id' => 'required|exists:eleves,id',
                'reported_by' => 'nullable|exists:enseignants,id',
            ]);

            $incident = IncidentDisciplinaire::findOrFail($id);
            $incident->update([
                'date_incident' => $request->date_incident,
                'heure_incident' => $request->heure_incident,
                'type' => $request->type,
                'gravité' => $request->gravite,
                'description' => $request->description,
                'emplacement' => $request->emplacement,
                'temoins' => $request->temoins,
                'action_pris' => $request->action_pris,
                'parent_notifie' => $request->has('parent_notifie'),
                'date_notification' => $request->date_notification,
                'statut' => $request->statut,
                'eleve_id' => $request->eleve_id,
                'reported_by' => $request->reported_by,
            ]);

            return redirect()->route('gestion_incidents.show', $incident->id)
                ->with('success', 'Incident disciplinaire modifié avec succès');
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
            $incident = IncidentDisciplinaire::findOrFail($id);
            $incident->delete();

            return redirect()->route('gestion_incidents.index')
                ->with('success', 'Incident disciplinaire supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
