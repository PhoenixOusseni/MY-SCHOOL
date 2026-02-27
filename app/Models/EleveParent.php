<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EleveParent extends Model
{
    protected $fillable = [
        'eleve_id',
        'tuteur_id',
        'is_primary',
        'can_pickup',
        'emergency_contact',
    ];

    // Relations
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function tuteur()
    {
        return $this->belongsTo(Tuteur::class);
    }
}
