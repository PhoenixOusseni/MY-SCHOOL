<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnseignementMatiereClasse extends Model
{
    protected $fillable = [
        'enseignant_id',
        'matiere_id',
        'classe_id',
        'annee_scolaire_id',
        'heure_par_semaine',
    ];

    // Relations
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function devoirs()
    {
        return $this->hasMany(Devoir::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function emploiTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }
}
