<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $fillable = [
        'numero_employe',
        'prenom',
        'nom',
        'date_naissance',
        'sexe',
        'telephone',
        'email',
        'adresse',
        'photo',
        'qualification',
        'specialisation',
        'date_embauche',
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

    public function professeurPrincipals()
    {
        return $this->hasMany(ProfesseurPrincipal::class);
    }

    public function enseignementMatiereClasses()
    {
        return $this->hasMany(EnseignementMatiereClasse::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'entered_by');
    }

    public function soumissionsDevoirs()
    {
        return $this->hasMany(SoumissionDevoir::class, 'graded_by');
    }

    public function detailBulletins()
    {
        return $this->hasMany(DetailBulletin::class);
    }

    public function incidentsDisciplinaires()
    {
        return $this->hasMany(IncidentDisciplinaire::class, 'reported_by');
    }
}
