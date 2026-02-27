<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoyenneMatiere extends Model
{
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
