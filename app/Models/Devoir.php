<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devoir extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'date_assignation',
        'date_echeance',
        'note_max',
        'attachment',
        'est_note',
        'enseignement_matiere_classe_id',
    ];

    // Relations
    public function enseignementMatiereClasse()
    {
        return $this->belongsTo(EnseignementMatiereClasse::class);
    }

    public function soumissionsDevoirs()
    {
        return $this->hasMany(SoumissionDevoir::class);
    }
}
