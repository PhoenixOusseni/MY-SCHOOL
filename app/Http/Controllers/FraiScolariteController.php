<?php

namespace App\Http\Controllers;

use App\Models\FraiScolarite;
use App\Models\Etablissement;
use Illuminate\Http\Request;

class FraiScolariteController extends Controller
{
    public function index()
    {
        $frais = FraiScolarite::with('etablissement')
            ->orderBy('libelle')
            ->paginate(15);

        return view('pages.frais_scolarite.index', compact('frais'));
    }

    public function create()
    {
        $etablissements = Etablissement::orderBy('nom')->get();

        return view('pages.frais_scolarite.create', compact('etablissements'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'libelle'          => 'required|string|max:100',
                'montant'          => 'required|numeric|min:0',
                'devise'           => 'nullable|string|max:10',
                'frequence'        => 'required|in:unique,mensuelle,trimestrielle,annuelle',
                'est_obligatoire'  => 'nullable|boolean',
                'etablissement_id' => 'required|exists:etablissements,id',
            ]);

            $data = $request->all();
            $data['est_obligatoire'] = $request->has('est_obligatoire');
            $data['devise'] = $request->devise ?: 'XOF';

            FraiScolarite::create($data);

            return redirect()->route('gestion_frais_scolarite.index')
                ->with('success', 'Frais de scolarité ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $frai = FraiScolarite::with('etablissement', 'paiements')->findOrFail($id);

        return view('pages.frais_scolarite.show', compact('frai'));
    }

    public function edit(string $id)
    {
        $frai = FraiScolarite::findOrFail($id);
        $etablissements = Etablissement::orderBy('nom')->get();

        return view('pages.frais_scolarite.edit', compact('frai', 'etablissements'));
    }

    public function update(Request $request, string $id)
    {
        $frai = FraiScolarite::findOrFail($id);

        try {
            $request->validate([
                'libelle'          => 'required|string|max:100',
                'montant'          => 'required|numeric|min:0',
                'devise'           => 'nullable|string|max:10',
                'frequence'        => 'required|in:unique,mensuelle,trimestrielle,annuelle',
                'est_obligatoire'  => 'nullable|boolean',
                'etablissement_id' => 'required|exists:etablissements,id',
            ]);

            $data = $request->all();
            $data['est_obligatoire'] = $request->has('est_obligatoire');
            $data['devise'] = $request->devise ?: 'XOF';

            $frai->update($data);

            return redirect()->route('gestion_frais_scolarite.show', $frai->id)
                ->with('success', 'Frais de scolarité mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $frai = FraiScolarite::findOrFail($id);
            $frai->delete();

            return redirect()->route('gestion_frais_scolarite.index')
                ->with('success', "Frais '{$frai->libelle}' supprimé avec succès.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
