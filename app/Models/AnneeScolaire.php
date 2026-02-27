<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'is_current' => 'boolean',
    ];

    // Relations
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function periodEvaluations()
    {
        return $this->hasMany(PeriodEvaluation::class);
    }

    public function professeurPrincipals()
    {
        return $this->hasMany(ProfesseurPrincipal::class);
    }

    public function enseignementMatiereClasses()
    {
        return $this->hasMany(EnseignementMatiereClasse::class);
    }

    public function emploiTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
