<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'login',
        'email',
        'password',
        'role_id',
        'actif',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif'   => 'boolean',
        ];
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function etablissement()
    {
        return $this->hasOne(Etablissement::class);
    }

    public function eleve()
    {
        return $this->hasOne(Eleve::class);
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }

    public function tuteur()
    {
        return $this->hasOne(Tuteur::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class, 'reported_by');
    }

    public function retards()
    {
        return $this->hasMany(Retard::class, 'reported_by');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'received_by');
    }

    public function sanctions()
    {
        return $this->hasMany(Sanction::class, 'imposed_by');
    }

    public function systemLogs()
    {
        return $this->hasMany(SystemLog::class);
    }
}
