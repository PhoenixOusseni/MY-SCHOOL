<?php

namespace Database\Seeders;

use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Database\Seeder;

class EtablissementSeeder extends Seeder
{
    public function run(): void
    {
        $directeur = User::where('email', 'directeur@school.com')->first();

        Etablissement::firstOrCreate(
            ['nom' => 'Ecole Municipal de dagnoen'],
            [
                'code'          => 'LYC-EMD-001',
                'adresse'       => 'Quartier dagnoen, Commune de kadiogo, Ouagadougou, Burkina Faso',
                'telephone'     => '+226 62 00 11 22',
                'email'         => 'contact@ecole-dagnoen.gn',
                'nom_directeur' => 'M. OUEDRAOGO Ousseni',
                'user_id'       => $directeur?->id,
            ]
        );
    }
}
