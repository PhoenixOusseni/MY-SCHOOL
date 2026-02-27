<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraiScolarite extends Model
{
    protected $fillable = [
        'libelle',
        'montant',
        'devise',
        'frequence',
        'est_obligatoire',
        'etablissement_id',
    ];

    protected $casts = [
        'est_obligatoire' => 'boolean',
        'montant' => 'decimal:2',
    ];

    // Relations
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
