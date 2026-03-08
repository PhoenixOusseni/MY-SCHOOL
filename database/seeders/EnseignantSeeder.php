<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use App\Models\Etablissement;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnseignantSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement  = Etablissement::where('nom', 'Lycée Almamya')->first();
        $enseignantRole = Role::where('libelle', 'Enseignant')->first();

        // [prenom, nom, sexe, telephone, email, login, qualification, specialisation, date_naissance, date_embauche]
        $enseignants = [
            [
                'prenom'         => 'Ibrahim',
                'nom'            => 'Koné',
                'sexe'           => 'M',
                'telephone'      => '+224 621 11 22 33',
                'email'          => 'enseignant@school.com', // user existant du UserSeeder
                'login'          => 'enseignant',
                'qualification'  => 'Master en Mathématiques',
                'specialisation' => 'Mathématiques',
                'date_naissance' => '1985-03-15',
                'date_embauche'  => '2015-09-01',
                'numero_employe' => 'ENS-001',
            ],
            [
                'prenom'         => 'Mariama',
                'nom'            => 'Bah',
                'sexe'           => 'F',
                'telephone'      => '+224 622 33 44 55',
                'email'          => 'mariama.bah@lycee-almamya.gn',
                'login'          => 'm.bah',
                'qualification'  => 'Licence en Lettres Modernes',
                'specialisation' => 'Français',
                'date_naissance' => '1988-07-22',
                'date_embauche'  => '2016-10-01',
                'numero_employe' => 'ENS-002',
            ],
            [
                'prenom'         => 'Ousmane',
                'nom'            => 'Camara',
                'sexe'           => 'M',
                'telephone'      => '+224 623 55 66 77',
                'email'          => 'ousmane.camara@lycee-almamya.gn',
                'login'          => 'o.camara',
                'qualification'  => 'Master en Sciences Physiques',
                'specialisation' => 'Sciences Physiques',
                'date_naissance' => '1982-11-05',
                'date_embauche'  => '2012-09-01',
                'numero_employe' => 'ENS-003',
            ],
            [
                'prenom'         => 'Fatoumata',
                'nom'            => 'Diallo',
                'sexe'           => 'F',
                'telephone'      => '+224 624 77 88 99',
                'email'          => 'fatoumata.diallo@lycee-almamya.gn',
                'login'          => 'f.diallo',
                'qualification'  => 'Master en SVT',
                'specialisation' => 'Sciences de la Vie et de la Terre',
                'date_naissance' => '1990-02-18',
                'date_embauche'  => '2018-10-01',
                'numero_employe' => 'ENS-004',
            ],
            [
                'prenom'         => 'Mamadou',
                'nom'            => 'Sylla',
                'sexe'           => 'M',
                'telephone'      => '+224 625 99 00 11',
                'email'          => 'mamadou.sylla@lycee-almamya.gn',
                'login'          => 'm.sylla',
                'qualification'  => 'Master en Histoire',
                'specialisation' => 'Histoire-Géographie',
                'date_naissance' => '1979-09-30',
                'date_embauche'  => '2010-09-01',
                'numero_employe' => 'ENS-005',
            ],
        ];

        foreach ($enseignants as $data) {
            // Récupérer ou créer le compte utilisateur
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nom'                => $data['nom'],
                    'prenom'             => $data['prenom'],
                    'telephone'          => $data['telephone'],
                    'login'              => $data['login'],
                    'password'           => Hash::make('password'),
                    'role_id'            => $enseignantRole?->id,
                    'email_verified_at'  => now(),
                ]
            );

            Enseignant::firstOrCreate(
                ['numero_employe' => $data['numero_employe']],
                [
                    'prenom'          => $data['prenom'],
                    'nom'             => $data['nom'],
                    'sexe'            => $data['sexe'],
                    'telephone'       => $data['telephone'],
                    'email'           => $data['email'],
                    'adresse'         => 'Conakry, Guinée',
                    'qualification'   => $data['qualification'],
                    'specialisation'  => $data['specialisation'],
                    'date_naissance'  => $data['date_naissance'],
                    'date_embauche'   => $data['date_embauche'],
                    'statut'          => 'actif',
                    'user_id'         => $user->id,
                    'etablissement_id' => $etablissement->id,
                ]
            );
        }
    }
}
