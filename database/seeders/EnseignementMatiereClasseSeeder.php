<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\EnseignementMatiereClasse;
use App\Models\Etablissement;
use App\Models\Matiere;
use Illuminate\Database\Seeder;

class EnseignementMatiereClasseSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();
        $annee         = AnneeScolaire::where('libelle', '2025-2026')->first();

        // [numero_employe_enseignant, code_matiere, nom_classe, heures/semaine]
        $enseignements = [
            // Ibrahim Koné — Mathématiques
            ['ENS-001', 'MATH', '6ème A',     5],
            ['ENS-001', 'MATH', '6ème B',     5],
            ['ENS-001', 'MATH', '3ème A',     5],
            ['ENS-001', 'MATH', 'Terminale A', 6],
            ['ENS-001', 'MATH', 'Terminale B', 6],

            // Mariama Bah — Français
            ['ENS-002', 'FR', '6ème A',     5],
            ['ENS-002', 'FR', '6ème B',     5],
            ['ENS-002', 'FR', '3ème A',     5],
            ['ENS-002', 'FR', 'Terminale A', 5],
            ['ENS-002', 'FR', 'Terminale B', 5],

            // Ousmane Camara — Sciences Physiques
            ['ENS-003', 'SP', '3ème A',     4],
            ['ENS-003', 'SP', 'Terminale A', 6],
            ['ENS-003', 'SP', 'Terminale B', 6],

            // Fatoumata Diallo — SVT
            ['ENS-004', 'SVT', '6ème A',     3],
            ['ENS-004', 'SVT', '6ème B',     3],
            ['ENS-004', 'SVT', '3ème A',     4],
            ['ENS-004', 'SVT', 'Terminale A', 6],
            ['ENS-004', 'SVT', 'Terminale B', 6],

            // Mamadou Sylla — Histoire-Géographie
            ['ENS-005', 'HG', '6ème A',     3],
            ['ENS-005', 'HG', '6ème B',     3],
            ['ENS-005', 'HG', '3ème A',     3],
            ['ENS-005', 'HG', 'Terminale A', 4],
            ['ENS-005', 'HG', 'Terminale B', 4],
        ];

        foreach ($enseignements as [$numeroEmploye, $codeMatiere, $nomClasse, $heures]) {
            $enseignant = Enseignant::where('numero_employe', $numeroEmploye)->first();
            $matiere    = Matiere::where('code', $codeMatiere)
                ->where('etablissement_id', $etablissement->id)
                ->first();
            $classe     = Classe::where('nom', $nomClasse)
                ->where('annee_scolaire_id', $annee->id)
                ->where('etablissement_id', $etablissement->id)
                ->first();

            if (! $enseignant || ! $matiere || ! $classe) {
                continue;
            }

            EnseignementMatiereClasse::firstOrCreate(
                [
                    'enseignant_id'     => $enseignant->id,
                    'matiere_id'        => $matiere->id,
                    'classe_id'         => $classe->id,
                    'annee_scolaire_id' => $annee->id,
                ],
                [
                    'heure_par_semaine' => $heures,
                ]
            );
        }
    }
}
