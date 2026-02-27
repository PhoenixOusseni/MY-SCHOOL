<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'montant',
        'reste_a_payer',
        'date_paiement',
        'methode_paiement',
        'reference',
        'status',
        'notes',
        'eleve_id',
        'frai_scolarite_id',
        'annee_scolaire_id',
        'received_by',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
    ];

    // Relations
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function fraiScolarite()
    {
        return $this->belongsTo(FraiScolarite::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
