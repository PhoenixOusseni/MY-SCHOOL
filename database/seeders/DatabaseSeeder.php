<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles & utilisateurs de base (admin, directeur...)
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);

        // 2. Structure de l'établissement
        $this->call(EtablissementSeeder::class);
        $this->call(NiveauSeeder::class);
        $this->call(AnneeScolaireSeeder::class);
        $this->call(ClasseSeeder::class);

        // 3. Matières et leur configuration par niveau
        $this->call(MatiereSeeder::class);
        $this->call(MatiereNiveauSeeder::class);

        // 4. Personnel enseignant et parents tuteurs
        $this->call(EnseignantSeeder::class);
        $this->call(TuteurSeeder::class);

        // 5. Élèves
        $this->call(EleveSeeder::class);

        // 6. Relations : inscriptions, liens élève-parent,
        //    affectations pédagogiques et professeurs principaux
        $this->call(InscriptionSeeder::class);
        $this->call(EleveParentSeeder::class);
        $this->call(EnseignementMatiereClasseSeeder::class);
        $this->call(ProfesseurPrincipalSeeder::class);
    }
}
