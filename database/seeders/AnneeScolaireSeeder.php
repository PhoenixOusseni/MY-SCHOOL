<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Etablissement;
use Illuminate\Database\Seeder;

class AnneeScolaireSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();

        $annees = [
            [
                'libelle'    => '2024-2025',
                'date_debut' => '2024-10-01',
                'date_fin'   => '2025-07-15',
                'is_current' => false,
            ],
            [
                'libelle'    => '2025-2026',
                'date_debut' => '2025-10-01',
                'date_fin'   => '2026-07-15',
                'is_current' => true,
            ],
        ];

        foreach ($annees as $annee) {
            AnneeScolaire::firstOrCreate(
                [
                    'libelle'          => $annee['libelle'],
                    'etablissement_id' => $etablissement->id,
                ],
                [
                    'date_debut' => $annee['date_debut'],
                    'date_fin'   => $annee['date_fin'],
                    'is_current' => $annee['is_current'],
                ]
            );
        }
    }
}
