<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $casts = [
        'capacite' => 'integer',
    ];

    // Relations
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function eleves()
    {
        return $this->hasManyThrough(
            Eleve::class,
            Inscription::class,
            'classe_id',   // FK dans inscriptions → classes
            'id',          // PK dans eleves
            'id',          // PK dans classes
            'eleve_id'     // FK dans inscriptions → eleves
        );
    }

    public function professeurPrincipals()
    {
        return $this->hasMany(ProfesseurPrincipal::class);
    }

    public function enseignementMatiereClasses()
    {
        return $this->hasMany(EnseignementMatiereClasse::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function retards()
    {
        return $this->hasMany(Retard::class);
    }

    public function moyenneMatieres()
    {
        return $this->hasMany(MoyenneMatiere::class);
    }

    public function bulletins()
    {
        return $this->hasMany(Bulletin::class);
    }
}
