<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentDisciplinaire extends Model
{
    protected $fillable = [
        'date_incident',
        'heure_incident',
        'type',
        'gravité',
        'description',
        'emplacement',
        'temoins',
        'action_pris',
        'parent_notifie',
        'date_notification',
        'statut',
        'eleve_id',
        'reported_by',
    ];

    protected $casts = [
        'date_incident' => 'date',
        'heure_incident' => 'datetime:H:i',
        'parent_notifie' => 'boolean',
        'date_notification' => 'date',
    ];

    // Relations
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(Enseignant::class, 'reported_by');
    }

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }
}
