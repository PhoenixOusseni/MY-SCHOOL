<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'date',
        'periode',
        'is_justified',
        'justification_document',
        'raison',
        'reported_at',
        'eleve_id',
        'classe_id',
        'matiere_id',
        'reported_by',
    ];

    protected $casts = [
        'date' => 'date',
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
