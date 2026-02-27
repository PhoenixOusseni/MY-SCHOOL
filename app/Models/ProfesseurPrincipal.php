<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesseurPrincipal extends Model
{
    protected $fillable = [
        'enseignant_id',
        'classe_id',
        'annee_scolaire_id',
        'is_main',
    ];

    // Relations
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }
}
