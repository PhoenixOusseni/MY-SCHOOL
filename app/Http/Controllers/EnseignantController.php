<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Etablissement;
use App\Models\Role;
use App\Models\User;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use App\Models\ProfesseurPrincipal;

use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enseignants = Enseignant::with('etablissement', 'user')->paginate(15);
        return view('pages.enseignants.index', compact('enseignants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $etablissements = Etablissement::orderBy('nom')->get();
        $roles = Role::orderBy('libelle')->get();
        return view('pages.enseignants.create', compact('etablissements', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Générer automatiquement le numéro d'emploi
            $year = date('Y');
            $count = Enseignant::where('numero_employe', 'like', 'EMP' . $year . '%')->count();
            $numeroEmploye = 'EMP' . $year . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            // Créer le compte utilisateur si email et password sont fournis
            $user_id = null;
            if ($request->create_account && $request->account_email && $request->account_password) {
                $user = User::create([
                    'login' => $request->account_email,
                    'email' => $request->account_email,
                    'password' => $request->account_password,
                    'role_id' => $request->role_id,
                    'is_active' => true,
                ]);
                $user_id = $user->id;
            }

            Enseignant::create([
                'numero_employe' => $numeroEmploye,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'sexe' => $request->sexe,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'qualification' => $request->qualification,
                'specialisation' => $request->specialisation,
                'date_embauche' => $request->date_embauche,
                'statut' => $request->statut ?? 'actif',
                'etablissement_id' => $request->etablissement_id,
                'user_id' => $user_id,
            ]);

            return redirect()->route('gestion_enseignants.index')->with('success', 'Enseignant créé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $enseignant = Enseignant::with('etablissement', 'user', 'enseignementMatiereClasses.matiere', 'enseignementMatiereClasses.classe')->findOrFail($id);
        return view('pages.enseignants.show', compact('enseignant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $enseignant = Enseignant::findOrFail($id);
        $etablissements = Etablissement::orderBy('nom')->get();
        $roles = Role::orderBy('libelle')->get();
        return view('pages.enseignants.edit', compact('enseignant', 'etablissements', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // N'update pas le numero_employe car il est auto-généré
            $enseignant = Enseignant::findOrFail($id);
            $enseignant->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'sexe' => $request->sexe,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'qualification' => $request->qualification,
                'specialisation' => $request->specialisation,
                'date_embauche' => $request->date_embauche,
                'statut' => $request->statut,
                'etablissement_id' => $request->etablissement_id,
            ]);

            // Gérer le compte utilisateur
            if ($request->create_account && !$enseignant->user_id) {
                // Créer un nouveau compte
                $user = User::create([
                    'email' => $request->account_email,
                    'password' => $request->account_password,
                    'role_id' => $request->role_id,
                    'is_active' => true,
                ]);
                $enseignant->update(['user_id' => $user->id]);
            } elseif ($enseignant->user_id && $request->account_email) {
                // Mettre à jour le compte existant
                $enseignant->user->update([
                    'email' => $request->account_email,
                    'role_id' => $request->role_id,
                ]);
                if ($request->account_password) {
                    $enseignant->user->update(['password' => $request->account_password]);
                }
            }

            return redirect()->route('gestion_enseignants.show', $enseignant->id)->with('success', 'Enseignant modifié avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $enseignant = Enseignant::findOrFail($id);
            $enseignant->delete();

            return redirect()->route('gestion_enseignants.index')->with('success', 'Enseignant supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Méthode pour afficher le formulaire de gestion des professeurs principaux
    public function form_professeurPrincipal()
    {
        $enseignants = Enseignant::orderBy('nom')->orderBy('prenom')->get();
        $classes = Classe::orderBy('nom')->get();
        $anneesScolaires = AnneeScolaire::orderBy('libelle')->get();
        $professeursRetour = ProfesseurPrincipal::with('enseignant', 'classe', 'anneeScolaire')->get();
        return view('pages.enseignants.prof_principale', compact('enseignants', 'classes', 'anneesScolaires', 'professeursRetour'));
    }

    // Méthode pour créer ou modifier une attribution de professeur principal
    public function professeurPrincipal(Request $request)
    {
        try {
            if ($request->has('prof_principal_id') && $request->prof_principal_id) {
                // Modification d'une attribution existante
                $profPrincipal = ProfesseurPrincipal::findOrFail($request->prof_principal_id);
                $profPrincipal->update([
                    'is_main' => $request->has('is_main') ? true : false,
                ]);
                $message = 'Attribution mise à jour avec succès';
            } else {
                // Création d'une nouvelle attribution
                ProfesseurPrincipal::create([
                    'enseignant_id' => $request->enseignant_id,
                    'classe_id' => $request->classe_id,
                    'annee_scolaire_id' => $request->annee_scolaire_id,
                    'is_main' => $request->has('is_main') ? true : false,
                ]);
                $message = 'Professeur principal assigné avec succès';
            }

            return redirect()->route('form.professeur_principal')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Méthode pour supprimer une attribution de professeur principal
    public function professeurPrincipalDelete($id)
    {
        try {
            $profPrincipal = ProfesseurPrincipal::findOrFail($id);
            $profPrincipal->delete();

            return redirect()->route('form.professeur_principal')
                ->with('success', 'Attribution supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
