<?php

namespace App\Http\Controllers;

use App\Models\EleveParent;
use App\Models\Eleve;
use App\Models\Tuteur;
use Illuminate\Http\Request;

class EleveParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tuteurId = $request->query('tuteur_id');

        if ($tuteurId) {
            // Afficher les associations pour un tuteur spécifique
            $query = EleveParent::with('eleve', 'tuteur')
                ->where('tuteur_id', $tuteurId);
        } else {
            // Afficher toutes les associations
            $query = EleveParent::with('eleve', 'tuteur');
        }

        $eleveParents = $query->paginate(15);
        $tuteur = $tuteurId ? Tuteur::findOrFail($tuteurId) : null;

        return view('pages.associations.index', compact('eleveParents', 'tuteur'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tuteurId = $request->query('tuteur_id');
        $tuteur = $tuteurId ? Tuteur::findOrFail($tuteurId) : null;

        // Charger tous les élèves avec leurs inscriptions et classes
        $eleves = Eleve::with('inscriptions.classe')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        // Charger tous les tuteurs
        $tuteurs = Tuteur::orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('pages.associations.create', compact('eleves', 'tuteurs', 'tuteur', 'tuteurId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Récupérer les élèves (array ou single value)
            $eleveIds = is_array($request->eleve_ids) ? $request->eleve_ids : [$request->eleve_ids];

            if (empty($eleveIds) || empty($eleveIds[0])) {
                return redirect()->back()
                    ->with('error', 'Veuillez sélectionner au moins un élève');
            }

            $successCount = 0;
            $errorCount = 0;

            // Créer une association pour chaque élève
            foreach ($eleveIds as $eleveId) {
                // Vérifier si l'association existe déjà
                $exists = EleveParent::where('eleve_id', $eleveId)
                    ->where('tuteur_id', $request->tuteur_id)
                    ->exists();

                if (!$exists) {
                    EleveParent::create([
                        'eleve_id' => $eleveId,
                        'tuteur_id' => $request->tuteur_id,
                        'is_primary' => $request->has('is_primary') ? true : false,
                        'can_pickup' => $request->has('can_pickup') ? true : false,
                        'emergency_contact' => $request->has('emergency_contact') ? true : false,
                    ]);
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            $message = "$successCount association(s) créée(s) avec succès";
            if ($errorCount > 0) {
                $message .= " ($errorCount doublon(s) ignoré(s))";
            }

            $redirectUrl = $request->has('tuteur_id')
                ? route('gestion_associations.index', ['tuteur_id' => $request->tuteur_id])
                : route('gestion_associations.index');

            return redirect()->to($redirectUrl)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'association: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $eleveParent = EleveParent::with('eleve', 'tuteur')->findOrFail($id);
        // Charger tous les élèves associés au tuteur
        $elevesAssocies = $eleveParent->tuteur->eleves()->with('inscriptions.classe')->get();
        return view('pages.associations.show', compact('eleveParent', 'elevesAssocies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $eleveParent = EleveParent::with('eleve', 'tuteur')->findOrFail($id);

        $eleves = Eleve::with('inscriptions.classe')
            ->orderBy('nom')->orderBy('prenom')->get();

        $tuteurs = Tuteur::orderBy('nom')->orderBy('prenom')->get();

        // Charger tous les élèves associés au tuteur
        $elevesAssocies = $eleveParent->tuteur->eleves()->with('inscriptions.classe')->get();

        return view('pages.associations.edit', compact('eleveParent', 'eleves', 'tuteurs', 'elevesAssocies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            // Vérifier s'il y a déjà une autre association avec le même élève/tuteur
            $exists = EleveParent::where('eleve_id', $request->eleve_id)
                ->where('tuteur_id', $request->tuteur_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Cette association existe déjà');
            }

            $eleveParent->update([
                'eleve_id' => $request->eleve_id,
                'tuteur_id' => $request->tuteur_id,
                'is_primary' => $request->has('is_primary') ? true : false,
                'can_pickup' => $request->has('can_pickup') ? true : false,
                'emergency_contact' => $request->has('emergency_contact') ? true : false,
            ]);

            return redirect()->route('gestion_associations.show', $eleveParent->id)
                ->with('success', 'Association modifiée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $eleveParent = EleveParent::findOrFail($id);
            $eleveParent->delete();

            return redirect()->route('gestion_associations.index')
                ->with('success', 'Association supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
