<?php

namespace Database\Seeders;

use App\Models\Etablissement;
use App\Models\Matiere;
use App\Models\MatiereNiveau;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class MatiereNiveauSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();

        // Configuration par niveau : [code_matiere => [coefficient, heures/semaine]]
        $config = [
            // Collège (6ème - 3ème)
            '6EME' => [
                'MATH' => [3, 5], 'FR'   => [3, 5], 'SP'   => [2, 3],
                'SVT'  => [2, 3], 'HG'   => [2, 3], 'ANG'  => [2, 4],
                'EPS'  => [1, 2], 'ARTS' => [1, 2],
            ],
            '5EME' => [
                'MATH' => [3, 5], 'FR'   => [3, 5], 'SP'   => [2, 3],
                'SVT'  => [2, 3], 'HG'   => [2, 3], 'ANG'  => [2, 4],
                'EPS'  => [1, 2], 'ARTS' => [1, 2],
            ],
            '4EME' => [
                'MATH' => [3, 5], 'FR'   => [3, 5], 'SP'   => [2, 4],
                'SVT'  => [2, 4], 'HG'   => [2, 3], 'ANG'  => [2, 4],
                'EPS'  => [1, 2], 'INFO' => [1, 2],
            ],
            '3EME' => [
                'MATH' => [4, 5], 'FR'   => [4, 5], 'SP'   => [3, 4],
                'SVT'  => [3, 4], 'HG'   => [2, 3], 'ANG'  => [2, 4],
                'EPS'  => [1, 2], 'INFO' => [1, 2],
            ],
            // Lycée (2nde - Terminale)
            '2NDE' => [
                'MATH'  => [4, 5], 'FR'  => [3, 5], 'SP'   => [3, 4],
                'SVT'   => [3, 4], 'HG'  => [3, 4], 'ANG'  => [2, 4],
                'PHILO' => [2, 3], 'EPS' => [1, 2], 'INFO' => [2, 2],
            ],
            '1ERE' => [
                'MATH'  => [5, 6], 'FR'  => [4, 5], 'SP'   => [4, 5],
                'SVT'   => [4, 5], 'HG'  => [3, 4], 'ANG'  => [2, 4],
                'PHILO' => [3, 4], 'EPS' => [1, 2], 'INFO' => [2, 2],
            ],
            'TERM' => [
                'MATH'  => [6, 6], 'FR'  => [4, 5], 'SP'   => [5, 6],
                'SVT'   => [5, 6], 'HG'  => [3, 4], 'ANG'  => [2, 4],
                'PHILO' => [4, 4], 'EPS' => [1, 2], 'INFO' => [2, 2],
            ],
        ];

        foreach ($config as $niveauCode => $matieres) {
            $niveau = Niveau::where('code', $niveauCode)
                ->where('etablissement_id', $etablissement->id)
                ->first();

            if (! $niveau) {
                continue;
            }

            foreach ($matieres as $matiereCode => [$coefficient, $heures]) {
                $matiere = Matiere::where('code', $matiereCode)
                    ->where('etablissement_id', $etablissement->id)
                    ->first();

                if (! $matiere) {
                    continue;
                }

                MatiereNiveau::firstOrCreate(
                    [
                        'matiere_id' => $matiere->id,
                        'niveau_id'  => $niveau->id,
                    ],
                    [
                        'coefficient'       => $coefficient,
                        'heure_par_semaine' => $heures,
                        'is_optional'       => false,
                    ]
                );
            }
        }
    }
}
