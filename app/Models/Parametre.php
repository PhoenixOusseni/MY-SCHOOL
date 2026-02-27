<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Parametre extends Model
{
    protected $fillable = ['cle', 'valeur', 'groupe', 'type', 'libelle', 'description'];

    /** Lire un paramètre (avec cache 1h) */
    public static function get(string $cle, mixed $default = null): mixed
    {
        return Cache::remember("parametre_{$cle}", 3600, function () use ($cle, $default) {
            $p = static::where('cle', $cle)->first();
            return $p ? $p->valeur : $default;
        });
    }

    /** Écrire / mettre à jour un paramètre */
    public static function set(string $cle, mixed $valeur): void
    {
        static::where('cle', $cle)->update(['valeur' => $valeur]);
        Cache::forget("parametre_{$cle}");
    }

    /** Récupérer tous les paramètres d'un groupe sous forme de tableau cle => valeur */
    public static function groupe(string $groupe): array
    {
        return static::where('groupe', $groupe)
            ->pluck('valeur', 'cle')
            ->toArray();
    }
}
