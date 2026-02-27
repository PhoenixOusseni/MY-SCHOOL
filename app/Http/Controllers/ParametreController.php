<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller
{
    /* ═══════════════════════════════════════════════════════════
     |  CONFIGURATION GÉNÉRALE
     ═══════════════════════════════════════════════════════════ */

    public function configuration()
    {
        $params = Parametre::where('groupe', 'general')
            ->orderBy('id')
            ->get()
            ->keyBy('cle');

        $timezones = [
            'Africa/Abidjan'     => 'Abidjan (UTC+0)',
            'Africa/Dakar'       => 'Dakar (UTC+0)',
            'Africa/Douala'      => 'Douala (UTC+1)',
            'Africa/Lagos'       => 'Lagos (UTC+1)',
            'Africa/Nairobi'     => 'Nairobi (UTC+3)',
            'Europe/Paris'       => 'Paris (UTC+1/2)',
            'UTC'                => 'UTC',
        ];

        $langues = ['fr' => 'Français', 'en' => 'English'];

        return view('pages.parametres.configuration', compact('params', 'timezones', 'langues'));
    }

    public function saveConfiguration(Request $request)
    {
        $request->validate([
            'app_nom'       => 'required|string|max:150',
            'app_email'     => 'nullable|email|max:150',
            'app_couleur'   => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'app_pagination'=> 'required|integer|min:5|max:200',
        ]);

        $champs = [
            'app_nom', 'app_soustitre', 'app_email', 'app_telephone',
            'app_adresse', 'app_devise', 'app_timezone', 'app_langue',
            'app_couleur', 'app_pagination',
        ];

        foreach ($champs as $cle) {
            Parametre::set($cle, $request->input($cle, ''));
        }

        // Booléen maintenance
        Parametre::set('app_maintenance', $request->boolean('app_maintenance') ? '1' : '0');

        LogHelper::log('modification', 'Paramètres', 'Mise à jour de la configuration générale.');

        return redirect()->route('parametres.configuration')
            ->with('success', 'Configuration générale enregistrée avec succès.');
    }

    /* ═══════════════════════════════════════════════════════════
     |  NOTIFICATIONS
     ═══════════════════════════════════════════════════════════ */

    public function notifications()
    {
        $params = Parametre::where('groupe', 'notifications')
            ->orderBy('id')
            ->get()
            ->keyBy('cle');

        return view('pages.parametres.notifications', compact('params'));
    }

    public function saveNotifications(Request $request)
    {
        $request->validate([
            'notif_email_expediteur' => 'nullable|email|max:150',
            'notif_absence_seuil'    => 'nullable|integer|min:1|max:100',
        ]);

        $booleans = [
            'notif_email_actif', 'notif_paiement_retard',
            'notif_absence_actif', 'notif_nouveau_utilisateur', 'notif_bulletin_genere',
        ];

        $textes = ['notif_email_expediteur', 'notif_email_nom', 'notif_absence_seuil'];

        foreach ($booleans as $cle) {
            Parametre::set($cle, $request->boolean($cle) ? '1' : '0');
        }
        foreach ($textes as $cle) {
            Parametre::set($cle, $request->input($cle, ''));
        }

        LogHelper::log('modification', 'Paramètres', 'Mise à jour des paramètres de notifications.');

        return redirect()->route('parametres.notifications')
            ->with('success', 'Paramètres de notifications enregistrés.');
    }

    /* ═══════════════════════════════════════════════════════════
     |  SAUVEGARDE & RESTAURATION
     ═══════════════════════════════════════════════════════════ */

    public function sauvegardes()
    {
        // Liste des fichiers de sauvegarde existants
        $fichiers = [];
        $disk     = Storage::disk('local');

        if ($disk->exists('backups')) {
            foreach ($disk->files('backups') as $path) {
                $fichiers[] = [
                    'nom'         => basename($path),
                    'taille'      => $this->formatBytes($disk->size($path)),
                    'taille_raw'  => $disk->size($path),
                    'date'        => date('d/m/Y H:i', $disk->lastModified($path)),
                    'timestamp'   => $disk->lastModified($path),
                ];
            }
        }

        // Trier du plus récent au plus ancien
        usort($fichiers, fn($a, $b) => $b['timestamp'] - $a['timestamp']);

        $dbName = env('DB_DATABASE', 'school_manager');
        $dbSize = $this->getDatabaseSize($dbName);

        return view('pages.parametres.sauvegardes', compact('fichiers', 'dbName', 'dbSize'));
    }

    public function creerSauvegarde(Request $request)
    {
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE', 'school_manager');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');

        $timestamp  = now()->format('Ymd_His');
        $filename   = "backup_{$dbName}_{$timestamp}.sql";
        $backupDir  = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filepath = "{$backupDir}/{$filename}";

        // Construire la commande mysqldump (chemin complet pour XAMPP/WAMP)
        $mysqldump  = $this->mysqlBin('mysqldump');
        $passOption = $dbPass ? "-p{$dbPass}" : '--password=';
        $command    = "\"{$mysqldump}\" -h{$dbHost} -P{$dbPort} -u{$dbUser} {$passOption} {$dbName} > \"{$filepath}\" 2>&1";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($filepath) || filesize($filepath) === 0) {
            // Fallback : dump via PHP/PDO sans mysqldump
            $this->phpBackup($filepath, $dbName);
        }

        if (!file_exists($filepath) || filesize($filepath) === 0) {
            return redirect()->route('parametres.sauvegardes')
                ->with('error', 'Erreur lors de la création de la sauvegarde. Vérifiez que mysqldump est accessible.');
        }

        LogHelper::log('création', 'Sauvegardes', "Sauvegarde créée : {$filename} (" . $this->formatBytes(filesize($filepath)) . ")");

        return redirect()->route('parametres.sauvegardes')
            ->with('success', "Sauvegarde « {$filename} » créée avec succès (" . $this->formatBytes(filesize($filepath)) . ").");
    }

    public function telecharger(string $filename)
    {
        // Sécuriser le nom de fichier
        $filename = basename($filename);
        $path     = storage_path("app/backups/{$filename}");

        if (!file_exists($path)) {
            return redirect()->route('parametres.sauvegardes')->with('error', 'Fichier introuvable.');
        }

        LogHelper::log('téléchargement', 'Sauvegardes', "Téléchargement de la sauvegarde : {$filename}");

        return response()->download($path);
    }

    public function supprimerSauvegarde(string $filename)
    {
        $filename = basename($filename);
        $path     = "backups/{$filename}";

        if (!Storage::disk('local')->exists($path)) {
            return redirect()->route('parametres.sauvegardes')->with('error', 'Fichier introuvable.');
        }

        Storage::disk('local')->delete($path);
        LogHelper::log('suppression', 'Sauvegardes', "Suppression de la sauvegarde : {$filename}");

        return redirect()->route('parametres.sauvegardes')
            ->with('success', "Sauvegarde « {$filename} » supprimée.");
    }

    public function restaurer(Request $request)
    {
        $request->validate([
            'fichier_sql' => 'required|file|mimes:sql,txt|max:102400', // max 100 Mo
        ]);

        $file     = $request->file('fichier_sql');
        $dbHost   = env('DB_HOST', '127.0.0.1');
        $dbPort   = env('DB_PORT', '3306');
        $dbName   = env('DB_DATABASE', 'school_manager');
        $dbUser   = env('DB_USERNAME', 'root');
        $dbPass   = env('DB_PASSWORD', '');

        $tmpPath    = $file->getRealPath();
        $mysqlBin   = $this->mysqlBin('mysql');
        $passOption = $dbPass ? "-p{$dbPass}" : '--password=';
        $command    = "\"{$mysqlBin}\" -h{$dbHost} -P{$dbPort} -u{$dbUser} {$passOption} {$dbName} < \"{$tmpPath}\" 2>&1";

        exec($command, $output, $returnCode);

        // Si la commande mysql échoue (binaire introuvable), fallback PHP/PDO
        if ($returnCode !== 0) {
            try {
                $this->phpRestore($tmpPath);
            } catch (\Throwable $e) {
                return redirect()->route('parametres.sauvegardes')
                    ->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
            }
        }

        LogHelper::log('restauration', 'Sauvegardes', "Restauration depuis le fichier : {$file->getClientOriginalName()}");

        return redirect()->route('parametres.sauvegardes')
            ->with('success', 'Base de données restaurée avec succès depuis « ' . $file->getClientOriginalName() . ' ».');
    }

    /* ═══════════════════════════════════════════════════════════
     |  HELPERS PRIVÉS
     ═══════════════════════════════════════════════════════════ */

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' Mo';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' Ko';
        return $bytes . ' o';
    }

    private function getDatabaseSize(string $dbName): string
    {
        try {
            $result = DB::selectOne("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$dbName]);
            return ($result->size_mb ?? 0) . ' Mo';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Retourne le chemin complet vers un binaire MySQL (mysql / mysqldump).
     * Cherche d'abord dans les emplacements XAMPP/WAMP courants sous Windows,
     * puis dans le PATH système.
     */
    private function mysqlBin(string $binary): string
    {
        $candidates = [
            "C:\\xampp\\mysql\\bin\\{$binary}.exe",
            "C:\\xampp\\mysql\\bin\\{$binary}",
            "D:\\xampp\\mysql\\bin\\{$binary}.exe",
            "C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\{$binary}.exe",
            "C:\\wamp\\bin\\mysql\\mysql8.0.31\\bin\\{$binary}.exe",
            "/usr/bin/{$binary}",
            "/usr/local/bin/{$binary}",
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Dernier recours : laisser le système chercher dans le PATH
        return $binary;
    }

    /**
     * Restaure la base depuis un fichier SQL via PDO (sans binaire mysql).
     * Découpe le fichier en statements individuels pour éviter les timeouts.
     */
    private function phpRestore(string $sqlPath): void
    {
        $sql = file_get_contents($sqlPath);

        // Supprimer les commentaires de ligne (-- ...)
        $sql = preg_replace('/^--[^\r\n]*[\r\n]*/m', '', $sql);
        // Supprimer les commentaires bloc (/* ... */)
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        // Couper en statements sur « ; » suivi d'une nouvelle ligne ou fin de fichier
        $statements = preg_split('/;\s*[\r\n]+/', $sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') continue;
            $pdo->exec($statement);
        }
    }

    /** Fallback backup via PDO si mysqldump n'est pas disponible */
    private function phpBackup(string $filepath, string $dbName): void
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $column = "Tables_in_{$dbName}";
            $sql    = "-- School Manager Backup\n-- Généré le : " . now()->toDateTimeString() . "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$column ?? array_values((array)$table)[0];

                // Structure
                $create = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createSql = array_values((array)$create[0])[1];
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n{$createSql};\n\n";

                // Données
                $rows = DB::table($tableName)->get();
                if ($rows->isNotEmpty()) {
                    $cols = array_keys((array)$rows->first());
                    $colList = implode('`, `', $cols);
                    $sql .= "INSERT INTO `{$tableName}` (`{$colList}`) VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $vals = array_map(function ($v) {
                            if (is_null($v)) return 'NULL';
                            return "'" . addslashes((string)$v) . "'";
                        }, (array)$row);
                        $values[] = '(' . implode(', ', $vals) . ')';
                    }
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents($filepath, $sql);
        } catch (\Exception $e) {
            // Silencieux — l'appelant vérifiera si le fichier existe
        }
    }
}
