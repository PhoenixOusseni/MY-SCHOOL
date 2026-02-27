<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Etablissement;

use Illuminate\Http\Request;

class MatiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matieres = Matiere::with('etablissement')
            ->paginate(15);
        return view('pages.matieres.index', compact('matieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $etablissements = Etablissement::orderBy('nom')->get();
        return view('pages.matieres.create', compact('etablissements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Matiere::create([
                'intitule' => $request->intitule,
                'code' => $request->code,
                'description' => $request->description,
                'color' => $request->color ?? '#007bff',
                'is_active' => $request->has('is_active') ? true : false,
                'etablissement_id' => $request->etablissement_id,
            ]);

            return redirect()->route('gestion_matieres.index')
                ->with('success', 'Matière créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $matiere = Matiere::with('etablissement', 'enseignementMatiereClasses.classe', 'enseignementMatiereClasses.enseignant')->findOrFail($id);
        return view('pages.matieres.show', compact('matiere'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $matiere = Matiere::findOrFail($id);
        $etablissements = Etablissement::orderBy('nom')->get();
        return view('pages.matieres.edit', compact('matiere', 'etablissements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $matiere = Matiere::findOrFail($id);
        try {
            $matiere->update([
                'intitule' => $request->intitule,
                'code' => $request->code,
                'description' => $request->description,
                'color' => $request->color ?? $matiere->color,
                'is_active' => $request->has('is_active') ? true : false,
                'etablissement_id' => $request->etablissement_id,
            ]);

            return redirect()->route('gestion_matieres.show', $matiere->id)
                ->with('success', 'Matière modifiée avec succès');
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
        $matiere = Matiere::findOrFail($id);
        try {
            $matiere->delete();

            return redirect()->route('gestion_matieres.index')
                ->with('success', 'Matière supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
