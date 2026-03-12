<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\User;
use App\Models\Role;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;
use App\Models\Niveau;
use App\Models\Classe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eleves = Eleve::all();
        return view('pages.eleves.index', compact('eleves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $etablissements = Etablissement::all();
        $anneesScolaires = AnneeScolaire::all();
        $niveaux = Niveau::all();
        $classes = Classe::all();
        return view('pages.eleves.create', compact('etablissements', 'anneesScolaires', 'niveaux', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            // Informations personnelles
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'genre' => 'nullable|in:M,F,Autres',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:100',
            'nationalite' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:20',
            'groupe_sanguin' => 'nullable|string|max:10',
            'notes_medicales' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pieces_jointes' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            // Compte utilisateur
            'email' => 'required|email|unique:users,email',
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string|min:8|confirmed',

            // Informations scolaires
            'date_inscription' => 'nullable|date',
            'statut' => 'nullable|in:actif,suspendu,diplome,abandonne',
            'etablissement_id' => 'nullable|exists:etablissements,id',
        ]);

        try {
            DB::beginTransaction();

            // Générer le numéro d'immatriculation automatiquement
            $prefix = 'EL';
            $year = date('Y');
            $lastEleve = Eleve::where('registration_number', 'like', $prefix . $year . '%')
                ->latest('id')->first();

            $number = $lastEleve ? (int)substr($lastEleve->registration_number, -5) + 1 : 1;
            $registrationNumber = $prefix . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);

            // Récupérer le rôle étudiant
            $roleEleve = Role::where('libelle', 'eleve')->orWhere('libelle', 'Élève')->first();
            $roleId = $roleEleve?->id ?? 3; // Fallback à 3 si le rôle n'existe pas

            // Créer le compte utilisateur
            $user = User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'login' => $validated['login'],
                'telephone' => $validated['telephone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role_id' => $roleId,
            ]);

            // Upload photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('eleves/photos', 'public');
            }

            // Upload pièces jointes
            $piecesJointesPath = null;
            if ($request->hasFile('pieces_jointes')) {
                $piecesJointesPath = $request->file('pieces_jointes')->store('eleves/pieces_jointes', 'public');
            }

            // Créer l'élève
            $eleve = Eleve::create([
                'registration_number' => $registrationNumber,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'genre' => $validated['genre'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'lieu_naissance' => $validated['lieu_naissance'] ?? null,
                'nationalite' => $validated['nationalite'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'groupe_sanguin' => $validated['groupe_sanguin'] ?? null,
                'notes_medicales' => $validated['notes_medicales'] ?? null,
                'date_inscription' => $validated['date_inscription'] ?? today(),
                'statut' => $validated['statut'] ?? 'actif',
                'photo' => $photoPath,
                'pieces_jointes' => $piecesJointesPath,
                'user_id' => $user->id,
                'etablissement_id' => $validated['etablissement_id'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('gestion_eleves.index')
                ->with('success', "Élève '{$eleve->prenom} {$eleve->nom}' créé avec succès. Numéro d'immatriculation: {$registrationNumber}");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de l\'élève: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $eleve = Eleve::findOrFail($id);
        return view('pages.eleves.show', compact('eleve'));
    }

    public function carteScolaire(String $id)
    {
        $eleve = Eleve::with([
            'etablissement',
            'inscriptions.anneeScolaire',
            'inscriptions.classe.niveau',
            'eleveParents.tuteur',
        ])->findOrFail($id);

        $inscriptionActive = $eleve->inscriptions->sortByDesc('created_at')->first();

        return view('pages.eleves.carte_scolaire', compact('eleve', 'inscriptionActive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $eleve = Eleve::findOrFail($id);
        $etablissements = Etablissement::all();
        $anneesScolaires = AnneeScolaire::all();
        $niveaux = Niveau::all();
        $classes = Classe::all();
        return view('pages.eleves.edit', compact('eleve', 'etablissements', 'anneesScolaires', 'niveaux', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $eleve = Eleve::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            // Informations personnelles
            'nom'              => 'required|string|max:100',
            'prenom'           => 'required|string|max:100',
            'genre'            => 'nullable|in:M,F,Autres',
            'date_naissance'   => 'nullable|date',
            'lieu_naissance'   => 'nullable|string|max:100',
            'nationalite'      => 'nullable|string|max:100',
            'adresse'          => 'nullable|string|max:500',
            'telephone'        => 'nullable|string|max:20',
            'groupe_sanguin'   => 'nullable|string|max:10',
            'notes_medicales'  => 'nullable|string',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pieces_jointes'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            // Informations scolaires
            'date_inscription' => 'nullable|date',
            'statut'           => 'nullable|in:actif,suspendu,diplome,abandonne',
            'etablissement_id' => 'nullable|exists:etablissements,id',
        ]);

        try {
            DB::beginTransaction();

            // Upload photo (remplace l'ancienne si présente)
            if ($request->hasFile('photo')) {
                if ($eleve->photo) {
                    Storage::disk('public')->delete($eleve->photo);
                }
                $validated['photo'] = $request->file('photo')->store('eleves/photos', 'public');
            }

            // Upload pièces jointes (remplace l'ancienne si présente)
            if ($request->hasFile('pieces_jointes')) {
                if ($eleve->pieces_jointes) {
                    Storage::disk('public')->delete($eleve->pieces_jointes);
                }
                $validated['pieces_jointes'] = $request->file('pieces_jointes')->store('eleves/pieces_jointes', 'public');
            }

            // Mise à jour de l'élève
            $eleve->update($validated);

            // Mise à jour du compte utilisateur lié (nom, prenom, telephone)
            if ($eleve->user) {
                $eleve->user->update([
                    'nom'       => $validated['nom'],
                    'prenom'    => $validated['prenom'],
                    'telephone' => $validated['telephone'] ?? $eleve->user->telephone,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('gestion_eleves.show', $eleve->id)
                ->with('success', "Élève '{$eleve->prenom} {$eleve->nom}' mis à jour avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $eleve = Eleve::findOrFail($id);
        $eleve->delete();

        return redirect()->route('gestion_eleves.index')
            ->with('success', "Élève '{$eleve->prenom} {$eleve->nom}' supprimé avec succès.");
    }
}
