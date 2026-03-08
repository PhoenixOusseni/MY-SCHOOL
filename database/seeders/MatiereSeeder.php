<?php

namespace Database\Seeders;

use App\Models\Etablissement;
use App\Models\Matiere;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();

        // [intitule, code, description, color]
        $matieres = [
            ['Mathématiques',                   'MATH', 'Algèbre, géométrie et analyse',                   '#3B82F6'],
            ['Français',                         'FR',   'Langue française, littérature et expression',     '#EF4444'],
            ['Sciences Physiques',               'SP',   'Physique et chimie',                              '#8B5CF6'],
            ['Sciences de la Vie et de la Terre','SVT',  'Biologie, géologie et environnement',             '#10B981'],
            ['Histoire-Géographie',              'HG',   'Histoire, géographie et éducation civique',       '#F59E0B'],
            ['Anglais',                          'ANG',  'Langue anglaise et civilisation',                 '#06B6D4'],
            ['Philosophie',                      'PHILO','Philosophie et pensée critique',                  '#6366F1'],
            ['Éducation Physique et Sportive',   'EPS',  'Sport et éducation physique',                    '#F97316'],
            ['Informatique',                     'INFO', 'Informatique et numérique',                       '#64748B'],
            ['Arts Plastiques',                  'ARTS', 'Arts visuels et expression plastique',            '#EC4899'],
        ];

        foreach ($matieres as [$intitule, $code, $description, $color]) {
            Matiere::firstOrCreate(
                [
                    'code'             => $code,
                    'etablissement_id' => $etablissement->id,
                ],
                [
                    'intitule'    => $intitule,
                    'description' => $description,
                    'color'       => $color,
                    'is_active'   => true,
                ]
            );
        }
    }
}
