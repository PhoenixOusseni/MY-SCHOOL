<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // ── Système ──────────────────────────────────────
            ['module' => 'Système', 'name' => 'manage_users',        'label' => 'Gérer utilisateurs',       'description' => 'Créer, modifier, supprimer des comptes utilisateurs'],
            ['module' => 'Système', 'name' => 'manage_roles',        'label' => 'Gérer rôles & permissions', 'description' => 'Créer et configurer les rôles'],
            ['module' => 'Système', 'name' => 'view_logs',           'label' => 'Voir les logs système',     'description' => 'Consulter et purger les journaux'],

            // ── Académique ────────────────────────────────────
            ['module' => 'Académique', 'name' => 'view_eleves',      'label' => 'Voir élèves',               'description' => null],
            ['module' => 'Académique', 'name' => 'manage_eleves',    'label' => 'Gérer élèves',              'description' => 'Créer, modifier, supprimer des élèves'],
            ['module' => 'Académique', 'name' => 'manage_inscriptions', 'label' => 'Gérer inscriptions',    'description' => null],
            ['module' => 'Académique', 'name' => 'manage_classes',   'label' => 'Gérer classes',             'description' => null],
            ['module' => 'Académique', 'name' => 'manage_niveaux',   'label' => 'Gérer niveaux',             'description' => null],
            ['module' => 'Académique', 'name' => 'manage_annees',    'label' => 'Gérer années scolaires',    'description' => null],
            ['module' => 'Académique', 'name' => 'view_enseignants', 'label' => 'Voir enseignants',          'description' => null],
            ['module' => 'Académique', 'name' => 'manage_enseignants', 'label' => 'Gérer enseignants',      'description' => null],
            ['module' => 'Académique', 'name' => 'manage_tuteurs',   'label' => 'Gérer parents/tuteurs',    'description' => null],

            // ── Pédagogie ─────────────────────────────────────
            ['module' => 'Pédagogie', 'name' => 'manage_matieres',   'label' => 'Gérer matières',            'description' => null],
            ['module' => 'Pédagogie', 'name' => 'manage_devoirs',    'label' => 'Gérer devoirs',             'description' => null],
            ['module' => 'Pédagogie', 'name' => 'manage_evaluations', 'label' => 'Gérer évaluations',       'description' => null],
            ['module' => 'Pédagogie', 'name' => 'manage_notes',      'label' => 'Saisir les notes',          'description' => null],
            ['module' => 'Pédagogie', 'name' => 'manage_bulletins',  'label' => 'Gérer bulletins',           'description' => null],

            // ── Finances ──────────────────────────────────────
            ['module' => 'Finances', 'name' => 'view_paiements',     'label' => 'Voir paiements',            'description' => null],
            ['module' => 'Finances', 'name' => 'manage_paiements',   'label' => 'Gérer paiements',           'description' => 'Enregistrer, modifier, supprimer des paiements'],
            ['module' => 'Finances', 'name' => 'manage_frais',       'label' => 'Gérer frais scolarité',     'description' => null],
            ['module' => 'Finances', 'name' => 'view_situation_financiere', 'label' => 'Voir situation financière', 'description' => null],

            // ── Vie Scolaire ──────────────────────────────────
            ['module' => 'Vie Scolaire', 'name' => 'manage_absences',   'label' => 'Gérer absences',         'description' => null],
            ['module' => 'Vie Scolaire', 'name' => 'manage_retards',    'label' => 'Gérer retards',          'description' => null],
            ['module' => 'Vie Scolaire', 'name' => 'manage_discipline', 'label' => 'Gérer discipline',       'description' => 'Incidents et sanctions'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        $this->command->info('✔ ' . count($permissions) . ' permissions créées/vérifiées.');
    }
}
