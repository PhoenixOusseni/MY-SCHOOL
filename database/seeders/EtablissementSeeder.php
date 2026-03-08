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
            ['nom' => 'Lycée Almamya'],
            [
                'code'          => 'LYC-ALM-001',
                'adresse'       => 'Quartier Almamya, Commune de Kaloum, Conakry, Guinée',
                'telephone'     => '+224 622 00 11 22',
                'email'         => 'contact@lycee-almamya.gn',
                'nom_directeur' => 'M. Amadou Diallo',
                'user_id'       => $directeur?->id,
            ]
        );
    }
}
