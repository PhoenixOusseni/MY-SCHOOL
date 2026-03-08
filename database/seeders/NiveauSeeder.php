<?php

namespace Database\Seeders;

use App\Models\Etablissement;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();

        $niveaux = [
            ['code' => '6EME',  'nom' => '6ème',      'order_index' => 1],
            ['code' => '5EME',  'nom' => '5ème',      'order_index' => 2],
            ['code' => '4EME',  'nom' => '4ème',      'order_index' => 3],
            ['code' => '3EME',  'nom' => '3ème',      'order_index' => 4],
            ['code' => '2NDE',  'nom' => '2nde',      'order_index' => 5],
            ['code' => '1ERE',  'nom' => '1ère',      'order_index' => 6],
            ['code' => 'TERM',  'nom' => 'Terminale', 'order_index' => 7],
        ];

        foreach ($niveaux as $niveau) {
            Niveau::firstOrCreate(
                [
                    'code'             => $niveau['code'],
                    'etablissement_id' => $etablissement->id,
                ],
                [
                    'nom'         => $niveau['nom'],
                    'order_index' => $niveau['order_index'],
                ]
            );
        }
    }
}
