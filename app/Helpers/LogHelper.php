<?php

namespace App\Helpers;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogHelper
{
    /**
     * Enregistre une action dans les logs système.
     *
     * @param string $action      Ex: connexion, création, modification, suppression
     * @param string $module      Ex: Utilisateurs, Paiements, Rôles
     * @param string|null $description
     */
    public static function log(string $action, string $module, ?string $description = null): void
    {
        try {
            SystemLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'ip_address'  => Request::ip(),
                'user_agent'  => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Ne pas bloquer l'application si le log échoue
        }
    }
}
