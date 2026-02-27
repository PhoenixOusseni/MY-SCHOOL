<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = [
        'moyenne_globale',
        'rang',
        'total_eleves',
        'total_points',
        'total_coefficient',
        'mention_conduite',
        'absences',
        'justification_absences',
        'retards',
        'commentaire_principal',
        'commentaire_directeur',
        'status',
        'published_at',
        'generated_at',
        'eleve_id',
        'classe_id',
        'period_evaluation_id',
    ];

    protected $casts = [
        'moyenne_globale' => 'decimal:2',
        'total_points' => 'decimal:2',
        'total_coefficient' => 'decimal:2',
        'absences' => 'integer',
        'justification_absences' => 'integer',
        'retards' => 'integer',
        'published_at' => 'datetime',
        'generated_at' => 'datetime',
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

    public function periodEvaluation()
    {
        return $this->belongsTo(PeriodEvaluation::class);
    }

    public function detailBulletins()
    {
        return $this->hasMany(DetailBulletin::class);
    }
}
