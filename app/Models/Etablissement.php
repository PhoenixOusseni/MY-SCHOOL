<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    protected $guarded = [];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function anneesScolaires()
    {
        return $this->hasMany(AnneeScolaire::class);
    }

    public function niveaux()
    {
        return $this->hasMany(Niveau::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    public function enseignants()
    {
        return $this->hasMany(Enseignant::class);
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    public function fraisScolarites()
    {
        return $this->hasMany(FraiScolarite::class);
    }

    public function typEvaluations()
    {
        return $this->hasMany(TypEvaluation::class);
    }
}
