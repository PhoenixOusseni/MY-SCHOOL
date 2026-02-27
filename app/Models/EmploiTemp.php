<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiTemp extends Model
{
    protected $fillable = [
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'salle',
        'effective_from',
        'effective_to',
        'enseignement_matiere_classe_id',
        'annee_scolaire_id',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to'   => 'date',
    ];

    const JOURS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

    public function enseignementMatiereClasse()
    {
        return $this->belongsTo(EnseignementMatiereClasse::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    /** Durée du créneau en minutes */
    public function getDureeAttribute(): int
    {
        $debut = \Carbon\Carbon::parse($this->heure_debut);
        $fin   = \Carbon\Carbon::parse($this->heure_fin);
        return (int) $debut->diffInMinutes($fin);
    }

    /** Libellé court affiché dans la grille */
    public function getLabelAttribute(): string
    {
        $emc = $this->enseignementMatiereClasse;
        return $emc?->matiere?->intitule ?? '—';
    }
}
