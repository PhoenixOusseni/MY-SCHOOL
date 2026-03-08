<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Etablissement;
use App\Models\Inscription;
use Illuminate\Database\Seeder;

class InscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();
        $annee         = AnneeScolaire::where('libelle', '2025-2026')->first();

        // [registration_number_eleve => nom_classe]
        $inscriptions = [
            'ELV-2025-001' => '6ème A',
            'ELV-2025-002' => '6ème A',
            'ELV-2025-003' => '6ème B',
            'ELV-2025-004' => '6ème B',
            'ELV-2025-005' => '3ème A',
            'ELV-2025-006' => '3ème A',
            'ELV-2025-007' => 'Terminale A',
            'ELV-2025-008' => 'Terminale A',
            'ELV-2025-009' => 'Terminale B',
            'ELV-2025-010' => 'Terminale B',
        ];

        foreach ($inscriptions as $regNumber => $nomClasse) {
            $eleve = Eleve::where('registration_number', $regNumber)->first();
            $classe = Classe::where('nom', $nomClasse)
                ->where('annee_scolaire_id', $annee->id)
                ->where('etablissement_id', $etablissement->id)
                ->first();

            if (! $eleve || ! $classe) {
                continue;
            }

            Inscription::firstOrCreate(
                [
                    'eleve_id'          => $eleve->id,
                    'classe_id'         => $classe->id,
                    'annee_scolaire_id' => $annee->id,
                ],
                [
                    'date_inscription' => '2025-10-01',
                    'statut'           => 'inscrit',
                ]
            );
        }
    }
}
