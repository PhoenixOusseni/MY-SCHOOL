<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Etablissement;
use App\Models\ProfesseurPrincipal;
use Illuminate\Database\Seeder;

class ProfesseurPrincipalSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();
        $annee         = AnneeScolaire::where('libelle', '2025-2026')->first();

        // [numero_employe, nom_classe, is_main]
        $assignments = [
            ['ENS-001', '6ème A',      true],
            ['ENS-002', '6ème B',      true],
            ['ENS-004', '3ème A',      true],
            ['ENS-003', 'Terminale A', true],
            ['ENS-005', 'Terminale B', true],
        ];

        foreach ($assignments as [$numeroEmploye, $nomClasse, $isMain]) {
            $enseignant = Enseignant::where('numero_employe', $numeroEmploye)->first();
            $classe     = Classe::where('nom', $nomClasse)
                ->where('annee_scolaire_id', $annee->id)
                ->where('etablissement_id', $etablissement->id)
                ->first();

            if (! $enseignant || ! $classe) {
                continue;
            }

            ProfesseurPrincipal::firstOrCreate(
                [
                    'enseignant_id'     => $enseignant->id,
                    'classe_id'         => $classe->id,
                    'annee_scolaire_id' => $annee->id,
                ],
                [
                    'is_main' => $isMain,
                ]
            );
        }
    }
}
