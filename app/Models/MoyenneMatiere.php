<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoyenneMatiere extends Model
{
    protected $fillable = [
        'moyenne',
        'coefficient',
        'moyenne_ponderee',
        'rang',
        'total_eleve',
        'appreciation',
        'calculated_at',
        'eleve_id',
        'matiere_id',
        'classe_id',
        'period_evaluation_id',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    // Relations
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function periodEvaluation()
    {
        return $this->belongsTo(PeriodEvaluation::class);
    }
}
