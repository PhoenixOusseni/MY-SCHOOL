<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tuteur;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TuteurSeeder extends Seeder
{
    public function run(): void
    {
        $parentRole = Role::where('libelle', 'Parent')->first();

        // [prenom, nom, relationship, telephone, email, login, profession, lieu_travail]
        $tuteurs = [
            [
                'prenom'      => 'Abdoulaye',
                'nom'         => 'Kouyaté',
                'relationship'=> 'pere',
                'telephone'   => '+224 628 11 22 33',
                'email'       => 'abdoulaye.kouyate@gmail.com',
                'login'       => 'a.kouyate',
                'profession'  => 'Commerçant',
                'lieu_travail'=> 'Marché Madina, Conakry',
            ],
            [
                'prenom'      => 'Kadiatou',
                'nom'         => 'Kouyaté',
                'relationship'=> 'mere',
                'telephone'   => '+224 628 11 22 34',
                'email'       => 'kadiatou.kouyate@gmail.com',
                'login'       => 'k.kouyate',
                'profession'  => 'Infirmière',
                'lieu_travail'=> 'CHU Donka, Conakry',
            ],
            [
                'prenom'      => 'Seydou',
                'nom'         => 'Traoré',
                'relationship'=> 'pere',
                'telephone'   => '+224 629 33 44 55',
                'email'       => 'seydou.traore@gmail.com',
                'login'       => 's.traore',
                'profession'  => 'Fonctionnaire',
                'lieu_travail'=> 'Ministère de l\'Éducation, Conakry',
            ],
            [
                'prenom'      => 'Aminata',
                'nom'         => 'Traoré',
                'relationship'=> 'mere',
                'telephone'   => '+224 629 33 44 56',
                'email'       => 'aminata.traore@gmail.com',
                'login'       => 'am.traore',
                'profession'  => 'Enseignante',
                'lieu_travail'=> 'École primaire de Ratoma',
            ],
            [
                'prenom'      => 'Ibrahima',
                'nom'         => 'Baldé',
                'relationship'=> 'pere',
                'telephone'   => '+224 621 55 66 77',
                'email'       => 'ibrahima.balde@gmail.com',
                'login'       => 'i.balde',
                'profession'  => 'Médecin',
                'lieu_travail'=> 'Clinique Ambroise Paré, Conakry',
            ],
            [
                'prenom'      => 'Hawa',
                'nom'         => 'Sow',
                'relationship'=> 'mere',
                'telephone'   => '+224 622 77 88 99',
                'email'       => 'hawa.sow@gmail.com',
                'login'       => 'h.sow',
                'profession'  => 'Commerçante',
                'lieu_travail'=> 'Marché Niger, Conakry',
            ],
        ];

        foreach ($tuteurs as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nom'               => $data['nom'],
                    'prenom'            => $data['prenom'],
                    'telephone'         => $data['telephone'],
                    'login'             => $data['login'],
                    'password'          => Hash::make('password'),
                    'role_id'           => $parentRole?->id,
                    'email_verified_at' => now(),
                ]
            );

            Tuteur::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nom'          => $data['nom'],
                    'prenom'       => $data['prenom'],
                    'relationship' => $data['relationship'],
                    'telephone'    => $data['telephone'],
                    'adresse'      => 'Conakry, Guinée',
                    'profession'   => $data['profession'],
                    'lieu_travail' => $data['lieu_travail'],
                    'user_id'      => $user->id,
                ]
            );
        }
    }
}
