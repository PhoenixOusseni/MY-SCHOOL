<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $casts = [
        'order_index' => 'integer',
    ];

    // Relations
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function matiereNiveaux()
    {
        return $this->hasMany(MatiereNiveau::class);
    }
}
