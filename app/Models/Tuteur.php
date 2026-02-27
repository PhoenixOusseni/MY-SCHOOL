<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tuteur extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'relationship',
        'telephone',
        'email',
        'adresse',
        'profession',
        'lieu_travail',
        'user_id',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eleveParents()
    {
        return $this->hasMany(EleveParent::class);
    }

    public function eleves()
    {
        return $this->hasManyThrough(Eleve::class, EleveParent::class, 'tuteur_id', 'id', 'id', 'eleve_id');
    }
}
