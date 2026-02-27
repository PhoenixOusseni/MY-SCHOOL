<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'titre', 'description', 'date_examen', 'heure_debut', 'duree',
        'salle', 'note_max', 'coefficient', 'instructions', 'est_publie',
        'type', 'enseignement_matiere_classe_id', 'period_evaluation_id'
    ];

    // Relations
    public function enseignementMatiereClasse()
    {
        return $this->belongsTo(EnseignementMatiereClasse::class);
    }

    public function typEvaluation()
    {
        return $this->belongsTo(TypEvaluation::class);
    }

    public function periodEvaluation()
    {
        return $this->belongsTo(PeriodEvaluation::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
