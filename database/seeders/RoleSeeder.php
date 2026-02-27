<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['libelle' => 'Super Admin'],
            ['libelle' => 'Directeur'],
            ['libelle' => 'Administrateur'],
            ['libelle' => 'Enseignant'],
            ['libelle' => 'Élève'],
            ['libelle' => 'Parent'],
            ['libelle' => 'Comptable'],
            ['libelle' => 'Surveillant'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
