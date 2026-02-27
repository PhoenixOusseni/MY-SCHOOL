<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = [
        'intitule',
        'code',
        'description',
        'color',
        'is_active',
        'etablissement_id',
    ];

    // Relations
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function matiereNiveaux()
    {
        return $this->hasMany(MatiereNiveau::class);
    }

    public function enseignementMatiereClasses()
    {
        return $this->hasMany(EnseignementMatiereClasse::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function retards()
    {
        return $this->hasMany(Retard::class);
    }

    public function detailBulletins()
    {
        return $this->hasMany(DetailBulletin::class);
    }

    public function moyenneMatieres()
    {
        return $this->hasMany(MoyenneMatiere::class);
    }
}
