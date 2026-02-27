<?php

namespace Database\Seeders;

use App\Models\Parametre;
use Illuminate\Database\Seeder;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        $parametres = [
            // ── Général ───────────────────────────────────────────
            ['groupe' => 'general', 'cle' => 'app_nom',          'libelle' => "Nom de l'application",    'type' => 'text',    'valeur' => 'School Manager',      'description' => "Affiché dans l'entête et les documents"],
            ['groupe' => 'general', 'cle' => 'app_soustitre',    'libelle' => 'Sous-titre',               'type' => 'text',    'valeur' => 'Système de Gestion Scolaire', 'description' => null],
            ['groupe' => 'general', 'cle' => 'app_email',        'libelle' => 'Email de contact',         'type' => 'email',   'valeur' => 'contact@school.ci',   'description' => 'Email général de l\'établissement'],
            ['groupe' => 'general', 'cle' => 'app_telephone',    'libelle' => 'Téléphone',                'type' => 'text',    'valeur' => '',                    'description' => null],
            ['groupe' => 'general', 'cle' => 'app_adresse',      'libelle' => 'Adresse',                  'type' => 'text',    'valeur' => '',                    'description' => null],
            ['groupe' => 'general', 'cle' => 'app_devise',       'libelle' => 'Devise monétaire',         'type' => 'text',    'valeur' => 'XOF',                 'description' => 'Ex: XOF, EUR, USD'],
            ['groupe' => 'general', 'cle' => 'app_timezone',     'libelle' => 'Fuseau horaire',           'type' => 'select',  'valeur' => 'Africa/Abidjan',      'description' => null],
            ['groupe' => 'general', 'cle' => 'app_langue',       'libelle' => 'Langue',                   'type' => 'select',  'valeur' => 'fr',                  'description' => null],
            ['groupe' => 'general', 'cle' => 'app_couleur',      'libelle' => 'Couleur principale',       'type' => 'color',   'valeur' => '#2f663f',             'description' => 'Couleur du thème (entête, boutons)'],
            ['groupe' => 'general', 'cle' => 'app_pagination',   'libelle' => 'Éléments par page',        'type' => 'number',  'valeur' => '20',                  'description' => 'Nombre de lignes par défaut dans les tableaux'],
            ['groupe' => 'general', 'cle' => 'app_maintenance',  'libelle' => 'Mode maintenance',         'type' => 'boolean', 'valeur' => '0',                   'description' => 'Bloquer l\'accès aux utilisateurs non-admins'],

            // ── Notifications ─────────────────────────────────────
            ['groupe' => 'notifications', 'cle' => 'notif_email_actif',       'libelle' => 'Activer les notifications email',        'type' => 'boolean', 'valeur' => '0',  'description' => null],
            ['groupe' => 'notifications', 'cle' => 'notif_email_expediteur',  'libelle' => 'Email expéditeur',                       'type' => 'email',   'valeur' => '',   'description' => 'From: des emails envoyés'],
            ['groupe' => 'notifications', 'cle' => 'notif_email_nom',         'libelle' => 'Nom expéditeur',                         'type' => 'text',    'valeur' => '',   'description' => null],
            ['groupe' => 'notifications', 'cle' => 'notif_paiement_retard',   'libelle' => 'Notifier les paiements en retard',       'type' => 'boolean', 'valeur' => '0',  'description' => null],
            ['groupe' => 'notifications', 'cle' => 'notif_absence_seuil',     'libelle' => 'Alerter au-delà de N absences',          'type' => 'number',  'valeur' => '5',  'description' => 'Nombre d\'absences déclenchant une alerte'],
            ['groupe' => 'notifications', 'cle' => 'notif_absence_actif',     'libelle' => 'Activer alertes absences',               'type' => 'boolean', 'valeur' => '0',  'description' => null],
            ['groupe' => 'notifications', 'cle' => 'notif_nouveau_utilisateur','libelle' => 'Notifier à la création d\'un compte',   'type' => 'boolean', 'valeur' => '0',  'description' => null],
            ['groupe' => 'notifications', 'cle' => 'notif_bulletin_genere',   'libelle' => 'Notifier à la génération d\'un bulletin','type' => 'boolean', 'valeur' => '0',  'description' => null],

            // ── Sécurité ──────────────────────────────────────────
            ['groupe' => 'securite', 'cle' => 'sec_session_duree',          'libelle' => 'Durée de session (minutes)',       'type' => 'number',  'valeur' => '120',  'description' => 'Déconnexion automatique après inactivité'],
            ['groupe' => 'securite', 'cle' => 'sec_mdp_longueur_min',       'libelle' => 'Longueur min. du mot de passe',   'type' => 'number',  'valeur' => '8',    'description' => null],
            ['groupe' => 'securite', 'cle' => 'sec_logs_retention_jours',   'libelle' => 'Rétention des logs (jours)',      'type' => 'number',  'valeur' => '90',   'description' => 'Logs plus anciens supprimés automatiquement'],
        ];

        foreach ($parametres as $p) {
            Parametre::firstOrCreate(['cle' => $p['cle']], $p);
        }

        $this->command->info('✔ ' . count($parametres) . ' paramètres créés/vérifiés.');
    }
}
