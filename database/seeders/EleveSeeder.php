<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\Etablissement;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EleveSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::where('nom', 'Lycée Almamya')->first();
        $eleveRole     = Role::where('libelle', 'Élève')->first();

        // [prenom, nom, genre, date_naissance, lieu_naissance, registration_number, email, login, groupe_sanguin]
        $eleves = [
            // 6ème A
            ['Mamadou',    'Kouyaté',  'M', '2013-04-10', 'Conakry',  'ELV-2025-001', 'mamadou.kouyate@eleve.gn',    'elv.kouyate1',   'A+'],
            ['Aissatou',   'Traoré',   'F', '2013-08-25', 'Kindia',   'ELV-2025-002', 'aissatou.traore@eleve.gn',    'elv.traore1',    'O+'],
            // 6ème B
            ['Ibrahima',   'Baldé',    'M', '2012-11-03', 'Labé',     'ELV-2025-003', 'ibrahima.balde@eleve.gn',     'elv.balde1',     'B+'],
            ['Fatoumata',  'Sow',      'F', '2013-01-17', 'Conakry',  'ELV-2025-004', 'fatoumata.sow@eleve.gn',      'elv.sow1',       'AB+'],
            // 3ème A
            ['Ousmane',    'Diallo',   'M', '2010-06-20', 'Conakry',  'ELV-2025-005', 'ousmane.diallo@eleve.gn',     'elv.diallo1',    'O-'],
            ['Mariama',    'Camara',   'F', '2010-09-14', 'Boké',     'ELV-2025-006', 'mariama.camara@eleve.gn',     'elv.camara1',    'A-'],
            // Terminale A
            ['Seydou',     'Koné',     'M', '2007-12-01', 'N\'Zérékoré', 'ELV-2025-007', 'seydou.kone@eleve.gn',    'elv.kone1',      'B-'],
            ['Hawa',       'Sylla',    'F', '2007-03-28', 'Conakry',  'ELV-2025-008', 'hawa.sylla@eleve.gn',         'elv.sylla1',     'O+'],
            // Terminale B
            ['Abdoulaye',  'Barry',    'M', '2006-07-15', 'Conakry',  'ELV-2025-009', 'abdoulaye.barry@eleve.gn',    'elv.barry1',     'A+'],
            ['Kadiatou',   'Guilavogui','F','2007-05-09', 'Kissidougou','ELV-2025-010','kadiatou.guilavogui@eleve.gn','elv.guilavogui1','AB-'],
        ];

        foreach ($eleves as [$prenom, $nom, $genre, $dateNaissance, $lieuNaissance, $regNumber, $email, $login, $groupeSanguin]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'nom'               => $nom,
                    'prenom'            => $prenom,
                    'login'             => $login,
                    'password'          => Hash::make('password'),
                    'role_id'           => $eleveRole?->id,
                    'email_verified_at' => now(),
                ]
            );

            Eleve::firstOrCreate(
                ['registration_number' => $regNumber],
                [
                    'nom'              => $nom,
                    'prenom'           => $prenom,
                    'date_naissance'   => $dateNaissance,
                    'genre'            => $genre,
                    'lieu_naissance'   => $lieuNaissance,
                    'nationalite'      => 'Guinéenne',
                    'adresse'          => 'Conakry, Guinée',
                    'email'            => $email,
                    'groupe_sanguin'   => $groupeSanguin,
                    'date_inscription' => '2025-10-01',
                    'statut'           => 'actif',
                    'user_id'          => $user->id,
                    'etablissement_id' => $etablissement->id,
                ]
            );
        }
    }
}
