<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $casts = [];

    protected $fillable = [
        'registration_number',
        'nom',
        'prenom',
        'date_naissance',
        'genre',
        'lieu_naissance',
        'nationalite',
        'adresse',
        'telephone',
        'email',
        'pieces_jointes',
        'photo',
        'groupe_sanguin',
        'notes_medicales',
        'date_inscription',
        'statut',
        'user_id',
        'etablissement_id',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function classes()
    {
        // Relation via inscriptions
        return $this->hasManyThrough(Classe::class, Inscription::class, 'eleve_id', 'id', 'id', 'classe_id');
    }

    public function eleveParents()
    {
        return $this->hasMany(EleveParent::class);
    }

    public function tuteurs()
    {
        return $this->hasManyThrough(Tuteur::class, EleveParent::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function retards()
    {
        return $this->hasMany(Retard::class);
    }

    public function incidentsDisciplinaires()
    {
        return $this->hasMany(IncidentDisciplinaire::class);
    }

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }

    public function soumissionsDevoirs()
    {
        return $this->hasMany(SoumissionDevoir::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function moyenneMatieres()
    {
        return $this->hasMany(MoyenneMatiere::class);
    }

    public function bulletins()
    {
        return $this->hasMany(Bulletin::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
