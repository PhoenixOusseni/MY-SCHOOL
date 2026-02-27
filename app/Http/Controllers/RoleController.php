<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->orderBy('libelle')->get();
        return view('pages.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('label')->get()->groupBy('module');
        return view('pages.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle'     => 'required|string|max:100|unique:roles,libelle',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'libelle'     => $request->libelle,
            'description' => $request->description,
        ]);

        if ($request->filled('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        LogHelper::log('création', 'Rôles', "Création du rôle : {$role->libelle}");

        return redirect()->route('gestion_roles.show', $role->id)
            ->with('success', "Rôle « {$role->libelle} » créé avec succès.");
    }

    public function show(string $id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);
        $permissions = Permission::orderBy('module')->orderBy('label')->get()->groupBy('module');
        return view('pages.roles.show', compact('role', 'permissions'));
    }

    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('module')->orderBy('label')->get()->groupBy('module');
        return view('pages.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'libelle'       => 'required|string|max:100|unique:roles,libelle,' . $role->id,
            'description'   => 'nullable|string|max:500',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'libelle'     => $request->libelle,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        LogHelper::log('modification', 'Rôles', "Modification du rôle : {$role->libelle}");

        return redirect()->route('gestion_roles.show', $role->id)
            ->with('success', "Rôle « {$role->libelle} » mis à jour.");
    }

    public function destroy(string $id)
    {
        $role = Role::withCount('users')->findOrFail($id);

        if ($role->users_count > 0) {
            return redirect()->back()
                ->with('error', "Impossible de supprimer : {$role->users_count} utilisateur(s) ont ce rôle.");
        }

        $libelle = $role->libelle;
        $role->delete();

        LogHelper::log('suppression', 'Rôles', "Suppression du rôle : {$libelle}");

        return redirect()->route('gestion_roles.index')
            ->with('success', "Rôle « {$libelle} » supprimé.");
    }
}
