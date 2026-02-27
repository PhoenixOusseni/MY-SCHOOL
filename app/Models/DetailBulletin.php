<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBulletin extends Model
{
    protected $fillable = [
        'moyenne',
        'coefficient',
        'moyenne_ponderee',
        'moyenne_classe',
        'point_min',
        'point_max',
        'rang',
        'appreciation',
        'commentaire_enseignant',
        'bulletin_id',
        'matiere_id',
        'enseignant_id',
    ];

    protected $casts = [
        'moyenne' => 'decimal:2',
        'coefficient' => 'decimal:2',
        'moyenne_ponderee' => 'decimal:2',
        'moyenne_classe' => 'decimal:2',
        'point_min' => 'decimal:2',
        'point_max' => 'decimal:2',
        'rang' => 'integer',
    ];

    // Relations
    public function bulletin()
    {
        return $this->belongsTo(Bulletin::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
}
