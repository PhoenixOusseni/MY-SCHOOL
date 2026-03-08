<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\EleveParent;
use App\Models\Tuteur;
use Illuminate\Database\Seeder;

class EleveParentSeeder extends Seeder
{
    public function run(): void
    {
        // [registration_number => [[email_tuteur, is_primary, can_pickup, emergency_contact], ...]]
        $liens = [
            'ELV-2025-001' => [
                ['abdoulaye.kouyate@gmail.com', true,  true,  true],
                ['kadiatou.kouyate@gmail.com',  false, true,  false],
            ],
            'ELV-2025-002' => [
                ['abdoulaye.kouyate@gmail.com', true,  true,  true],
                ['kadiatou.kouyate@gmail.com',  false, true,  false],
            ],
            'ELV-2025-003' => [
                ['seydou.traore@gmail.com',  true,  true, true],
                ['aminata.traore@gmail.com', false, true, false],
            ],
            'ELV-2025-004' => [
                ['seydou.traore@gmail.com',  true,  true, true],
                ['aminata.traore@gmail.com', false, true, true],
            ],
            'ELV-2025-005' => [
                ['ibrahima.balde@gmail.com', true, true, true],
            ],
            'ELV-2025-006' => [
                ['hawa.sow@gmail.com', true, true, true],
            ],
            'ELV-2025-007' => [
                ['ibrahima.balde@gmail.com', true,  true, true],
                ['hawa.sow@gmail.com',       false, true, false],
            ],
            'ELV-2025-008' => [
                ['seydou.traore@gmail.com', true, true, true],
            ],
            'ELV-2025-009' => [
                ['abdoulaye.kouyate@gmail.com', true, true, true],
            ],
            'ELV-2025-010' => [
                ['kadiatou.kouyate@gmail.com', true, true, true],
            ],
        ];

        foreach ($liens as $regNumber => $tuteurs) {
            $eleve = Eleve::where('registration_number', $regNumber)->first();

            if (! $eleve) {
                continue;
            }

            foreach ($tuteurs as [$emailTuteur, $isPrimary, $canPickup, $emergencyContact]) {
                $tuteur = Tuteur::where('email', $emailTuteur)->first();

                if (! $tuteur) {
                    continue;
                }

                EleveParent::firstOrCreate(
                    [
                        'eleve_id'  => $eleve->id,
                        'tuteur_id' => $tuteur->id,
                    ],
                    [
                        'is_primary'        => $isPrimary,
                        'can_pickup'        => $canPickup,
                        'emergency_contact' => $emergencyContact,
                    ]
                );
            }
        }
    }
}
