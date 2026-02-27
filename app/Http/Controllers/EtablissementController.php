<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $etablissements = Etablissement::all();
        return view('pages.etablissements.index', compact('etablissements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.etablissements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255|unique:etablissements',
            'adresse' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'nom_directeur' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $etablissement = new Etablissement();
        $etablissement->code = $request->code;
        $etablissement->nom = $request->nom;
        $etablissement->adresse = $request->adresse;
        $etablissement->telephone = $request->telephone;
        $etablissement->email = $request->email;
        $etablissement->nom_directeur = $request->nom_directeur;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $etablissement->logo = $logoPath;
        }

        $etablissement->save();

        return redirect()->route('gestion_etablissements.index')->with('success', 'Établissement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $etablissement = Etablissement::with([
            'user',
            'classes.niveau',
            'classes.anneeScolaire',
            'enseignants',
            'eleves',
            'matieres',
            'anneesScolaires'
        ])->findOrFail($id);

        return view('pages.etablissements.show', compact('etablissement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $etablissement = Etablissement::findOrFail($id);
        return view('pages.etablissements.edit', compact('etablissement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $request->validate([
            'code' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255|unique:etablissements,nom,' . $id,
            'adresse' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'nom_directeur' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $etablissement = Etablissement::findOrFail($id);
        $etablissement->code = $request->code;
        $etablissement->nom = $request->nom;
        $etablissement->adresse = $request->adresse;
        $etablissement->telephone = $request->telephone;
        $etablissement->email = $request->email;
        $etablissement->nom_directeur = $request->nom_directeur;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $etablissement->logo = $logoPath;
        }

        $etablissement->save();

        return redirect()->route('gestion_etablissements.index')->with('success', 'Établissement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $etablissement = Etablissement::findOrFail($id);
        $etablissement->delete();

        return redirect()->route('gestion_etablissements.index')->with('success', 'Établissement supprimé avec succès.');
    }
}
