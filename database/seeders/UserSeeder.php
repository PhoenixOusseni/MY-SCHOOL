<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les rôles
        $superAdminRole = Role::where('libelle', 'Super Admin')->first();
        $directeurRole = Role::where('libelle', 'Directeur')->first();
        $adminRole = Role::where('libelle', 'Administrateur')->first();
        $enseignantRole = Role::where('libelle', 'Enseignant')->first();

        // Créer un Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@school.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'telephone' => '0123456789',
                'login' => 'super.admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Directeur
        User::firstOrCreate(
            ['email' => 'directeur@school.com'],
            [
                'nom' => 'Diallo',
                'prenom' => 'Amadou',
                'telephone' => '0123456790',
                'login' => 'directeur',
                'password' => Hash::make('password'),
                'role_id' => $directeurRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Administrateur
        User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'nom' => 'Traoré',
                'prenom' => 'Fatou',
                'telephone' => '0123456791',
                'login' => 'admin',
                'password' => Hash::make('password'),
                'role_id' => $adminRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Enseignant
        User::firstOrCreate(
            ['email' => 'enseignant@school.com'],
            [
                'nom' => 'Koné',
                'prenom' => 'Ibrahim',
                'telephone' => '0123456792',
                'login' => 'enseignant',
                'password' => Hash::make('password'),
                'role_id' => $enseignantRole?->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
