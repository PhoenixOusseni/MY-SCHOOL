<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Etablissement;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();
        $annee         = AnneeScolaire::where('libelle', '2025-2026')->first();

        // [niveau_code, nom_classe, capacite, salle]
        $classes = [
            ['6EME', '6ème A', 45, 'Salle 01'],
            ['6EME', '6ème B', 45, 'Salle 02'],
            ['5EME', '5ème A', 42, 'Salle 03'],
            ['5EME', '5ème B', 42, 'Salle 04'],
            ['4EME', '4ème A', 40, 'Salle 05'],
            ['3EME', '3ème A', 40, 'Salle 06'],
            ['2NDE', '2nde A', 38, 'Salle 07'],
            ['1ERE', '1ère A', 38, 'Salle 08'],
            ['TERM', 'Terminale A', 35, 'Salle 09'],
            ['TERM', 'Terminale B', 35, 'Salle 10'],
        ];

        foreach ($classes as [$niveauCode, $nom, $capacite, $salle]) {
            $niveau = Niveau::where('code', $niveauCode)
                ->where('etablissement_id', $etablissement->id)
                ->first();

            Classe::firstOrCreate(
                [
                    'nom'              => $nom,
                    'annee_scolaire_id' => $annee->id,
                    'etablissement_id' => $etablissement->id,
                ],
                [
                    'capacite'  => $capacite,
                    'salle'     => $salle,
                    'niveau_id' => $niveau?->id,
                ]
            );
        }
    }
}
