<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatiereNiveau extends Model
{
    // Relations
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
