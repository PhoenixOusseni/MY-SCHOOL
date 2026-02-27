<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodEvaluation extends Model
{
    protected $fillable = [
        'libelle',
        'type',
        'date_debut',
        'date_fin',
        'order_index',
        'annee_scolaire_id',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    // Relations
    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function moyenneMatieres()
    {
        return $this->hasMany(MoyenneMatiere::class);
    }

    public function bulletins()
    {
        return $this->hasMany(Bulletin::class);
    }
}
