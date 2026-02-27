<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retard extends Model
{
    protected $fillable = [
        'date',
        'heure_arrivee',
        'duree',
        'is_justified',
        'raison',
        'reported_at',
        'eleve_id',
        'classe_id',
        'matiere_id',
        'reported_by',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_arrivee' => 'datetime:H:i',
        'duree' => 'integer',
        'is_justified' => 'boolean',
        'reported_at' => 'datetime',
    ];

    // Relations
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
