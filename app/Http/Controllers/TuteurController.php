<?php

namespace App\Http\Controllers;

use App\Models\Tuteur;
use Illuminate\Http\Request;

class TuteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tuteurs = Tuteur::with('user', 'eleveParents.eleve')->paginate(15);
        return view('pages.tuteurs.index', compact('tuteurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.tuteurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $tuteur = Tuteur::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'relationship' => $request->relationship,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'profession' => $request->profession,
                'lieu_travail' => $request->lieu_travail,
            ]);

            return redirect()->route('gestion_tuteurs.show', $tuteur->id)
                ->with('success', 'Tuteur créé avec succès');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création du tuteur');
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
        try {
            $tuteur = Tuteur::with('user', 'eleveParents.eleve')->findOrFail($id);
            return view('pages.tuteurs.show', compact('tuteur'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_tuteurs.index')
                ->with('error', 'Tuteur non trouvé');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        try {
            $tuteur = Tuteur::findOrFail($id);
            return view('pages.tuteurs.edit', compact('tuteur'));
        } catch (\Exception $e) {
            return redirect()->route('gestion_tuteurs.index')
                ->with('error', 'Tuteur non trouvé');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            $tuteur = Tuteur::findOrFail($id);
            $tuteur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'relationship' => $request->relationship,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'profession' => $request->profession,
                'lieu_travail' => $request->lieu_travail,
            ]);

            return redirect()->route('gestion_tuteurs.show', $tuteur->id)
                ->with('success', 'Tuteur modifié avec succès');
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
            $tuteur = Tuteur::findOrFail($id);
            $tuteur->delete();

            return redirect()->route('gestion_tuteurs.index')
                ->with('success', 'Tuteur supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
