<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('nom')->paginate(20);

        $stats = [
            'total'    => User::count(),
            'actifs'   => User::where('actif', true)->count(),
            'inactifs' => User::where('actif', false)->count(),
        ];

        return view('pages.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $roles = Role::orderBy('libelle')->get();
        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'role_id'   => 'nullable|exists:roles,id',
            'password'  => 'required|string|min:8|confirmed',
            'notes'     => 'nullable|string|max:1000',
        ]);

        // Générer le login : 1ère lettre prénom + . + nom (minuscule)
        $base  = strtolower(substr($request->prenom, 0, 1) . '.' . $request->nom);
        $login = $base;
        $i     = 1;
        while (User::where('login', $login)->exists()) {
            $login = $base . $i++;
        }

        $user = User::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role_id'   => $request->role_id,
            'login'     => $login,
            'password'  => Hash::make($request->password),
            'actif'     => $request->boolean('actif', true),
            'notes'     => $request->notes,
        ]);

        LogHelper::log('création', 'Utilisateurs', "Création de l'utilisateur : {$user->prenom} {$user->nom} (login: {$user->login})");

        return redirect()->route('gestion_utilisateurs.show', $user->id)
            ->with('success', "Utilisateur « {$user->prenom} {$user->nom} » créé. Login généré : {$user->login}");
    }

    public function show(string $id)
    {
        $user = User::with('role')->findOrFail($id);
        $logs = \App\Models\SystemLog::where('user_id', $id)->latest()->take(20)->get();
        return view('pages.users.show', compact('user', 'logs'));
    }

    public function edit(string $id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::orderBy('libelle')->get();
        return view('pages.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'role_id'   => 'nullable|exists:roles,id',
            'password'  => 'nullable|string|min:8|confirmed',
            'notes'     => 'nullable|string|max:1000',
        ]);

        $data = [
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role_id'   => $request->role_id,
            'actif'     => $request->boolean('actif'),
            'notes'     => $request->notes,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        LogHelper::log('modification', 'Utilisateurs', "Modification de l'utilisateur : {$user->prenom} {$user->nom}");

        return redirect()->route('gestion_utilisateurs.show', $user->id)
            ->with('success', "Utilisateur « {$user->prenom} {$user->nom} » mis à jour.");
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        $nom = "{$user->prenom} {$user->nom}";
        $user->delete();

        LogHelper::log('suppression', 'Utilisateurs', "Suppression de l'utilisateur : {$nom}");

        return redirect()->route('gestion_utilisateurs.index')
            ->with('success', "Utilisateur « {$nom} » supprimé.");
    }

    /** Basculer l'état actif/inactif */
    public function toggleActif(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous désactiver.');
        }

        $user->update(['actif' => !$user->actif]);
        $etat = $user->actif ? 'activé' : 'désactivé';

        LogHelper::log('modification', 'Utilisateurs', "Compte {$etat} : {$user->prenom} {$user->nom}");

        return redirect()->back()->with('success', "Compte de {$user->prenom} {$user->nom} {$etat}.");
    }
}
