<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    protected $fillable = [
        'type',
        'description',
        'date_debut',
        'date_fin',
        'duree',
        'imposed_by',
        'incident_disciplinaire_id',
        'eleve_id',
        'status',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'duree' => 'integer',
    ];

    // Relations
    public function imposedBy()
    {
        return $this->belongsTo(User::class, 'imposed_by');
    }

    public function incidentDisciplinaire()
    {
        return $this->belongsTo(IncidentDisciplinaire::class);
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
