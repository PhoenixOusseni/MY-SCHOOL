<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypEvaluation extends Model
{
    // Relations
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
