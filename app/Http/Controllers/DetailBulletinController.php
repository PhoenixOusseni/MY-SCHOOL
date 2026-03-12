<?php

namespace App\Http\Controllers;

use App\Models\DetailBulletin;
use App\Models\Bulletin;
use App\Models\Enseignant;
use App\Models\Matiere;
use Illuminate\Http\Request;

class DetailBulletinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $details = DetailBulletin::with('bulletin.eleve', 'matiere', 'enseignant')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('pages.detail_bulletins.index', compact('details'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement des détails de bulletin');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $bulletins = Bulletin::with('eleve', 'periodEvaluation')->orderBy('created_at', 'desc')->get();
            $matieres = Matiere::orderBy('intitule')->get();
            $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

            return view('pages.detail_bulletins.create', compact('bulletins', 'matieres', 'enseignants'));
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
                'moyenne' => 'nullable|numeric|min:0',
                'coefficient' => 'nullable|numeric|min:0',
                'moyenne_ponderee' => 'nullable|numeric|min:0',
                'moyenne_classe' => 'nullable|numeric|min:0',
                'point_min' => 'nullable|numeric|min:0',
                'point_max' => 'nullable|numeric|min:0',
                'rang' => 'nullable|integer|min:1',
                'appreciation' => 'nullable|string',
                'commentaire_enseignant' => 'nullable|string',
                'bulletin_id' => 'required|exists:bulletins,id',
                'matiere_id' => 'required|exists:matieres,id',
                'enseignant_id' => 'nullable|exists:enseignants,id',
            ]);

            DetailBulletin::create($validated);

            $redirectUrl = $request->input('redirect_url');
            if ($redirectUrl) {
                return redirect($redirectUrl)->with('success', 'Détail du bulletin ajouté avec succès');
            }

            return redirect()->route('gestion_detail_bulletins.index')
                ->with('success', 'Détail du bulletin créé avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du détail du bulletin');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $detailBulletin = DetailBulletin::with('bulletin.eleve', 'bulletin.periodEvaluation', 'matiere', 'enseignant')
                ->findOrFail($id);

            return view('pages.detail_bulletins.show', compact('detailBulletin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Détail du bulletin non trouvé');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $detailBulletin = DetailBulletin::findOrFail($id);
            $bulletins = Bulletin::with('eleve', 'periodEvaluation')->orderBy('created_at', 'desc')->get();
            $matieres = Matiere::orderBy('intitule')->get();
            $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();

            return view('pages.detail_bulletins.edit', compact('detailBulletin', 'bulletins', 'matieres', 'enseignants'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Détail du bulletin non trouvé');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $detailBulletin = DetailBulletin::findOrFail($id);

            $validated = $request->validate([
                'moyenne' => 'nullable|numeric|min:0',
                'coefficient' => 'nullable|numeric|min:0',
                'moyenne_ponderee' => 'nullable|numeric|min:0',
                'moyenne_classe' => 'nullable|numeric|min:0',
                'point_min' => 'nullable|numeric|min:0',
                'point_max' => 'nullable|numeric|min:0',
                'rang' => 'nullable|integer|min:1',
                'appreciation' => 'nullable|string',
                'commentaire_enseignant' => 'nullable|string',
                'bulletin_id' => 'required|exists:bulletins,id',
                'matiere_id' => 'required|exists:matieres,id',
                'enseignant_id' => 'nullable|exists:enseignants,id',
            ]);

            $detailBulletin->update($validated);

            return redirect()->route('gestion_detail_bulletins.index')
                ->with('success', 'Détail du bulletin mis à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du détail du bulletin');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $detailBulletin = DetailBulletin::findOrFail($id);
            $detailBulletin->delete();

            return redirect()->route('gestion_detail_bulletins.index')
                ->with('success', 'Détail du bulletin supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression du détail du bulletin');
        }
    }
}
