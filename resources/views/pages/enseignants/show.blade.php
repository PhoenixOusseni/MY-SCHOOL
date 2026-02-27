@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            Détails de l'Enseignant
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignants.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Messages -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Colonne gauche: Informations personnelles -->
            <div class="col-lg-8 mb-4">
                <!-- Informations Personnelles -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="user-check" class="me-2"></i>Informations Personnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Nom</p>
                                <p class="h6">{{ $enseignant->nom }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Prénom</p>
                                <p class="h6">{{ $enseignant->prenom }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Genre</p>
                                <p class="h6">
                                    @if ($enseignant->sexe === 'M')
                                        <i data-feather="user" class="me-1"></i>Masculin
                                    @elseif ($enseignant->sexe === 'F')
                                        <i data-feather="user" class="me-1"></i>Féminin
                                    @else
                                        {{ $enseignant->sexe ?? 'N/A' }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Date de Naissance</p>
                                <p class="h6">
                                    @if ($enseignant->date_naissance)
                                        {{ \Carbon\Carbon::parse($enseignant->date_naissance)->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Téléphone</p>
                                <p class="h6">
                                    @if ($enseignant->telephone)
                                        <a href="tel:{{ $enseignant->telephone }}">{{ $enseignant->telephone }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Email</p>
                                <p class="h6">
                                    @if ($enseignant->email)
                                        <a href="mailto:{{ $enseignant->email }}">{{ $enseignant->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted mb-1">Adresse</p>
                                <p class="h6">{{ $enseignant->adresse ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Professionnelles -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="briefcase" class="me-2"></i>Informations Professionnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Numéro d'Emploi</p>
                                <p class="h6">{{ $enseignant->numero_employe }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Établissement</p>
                                <p class="h6">{{ $enseignant->etablissement->nom ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Qualification/Diplômes</p>
                                <p class="h6">{{ $enseignant->qualification ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Spécialisation</p>
                                <p class="h6">{{ $enseignant->specialisation ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Date d'Embauche</p>
                                <p class="h6">
                                    @if ($enseignant->date_embauche)
                                        {{ \Carbon\Carbon::parse($enseignant->date_embauche)->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Statut</p>
                                <p class="h6">
                                    @if ($enseignant->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                    @elseif ($enseignant->statut === 'en_conge')
                                        <span class="badge bg-warning text-dark">En congé</span>
                                    @elseif ($enseignant->statut === 'retraite')
                                        <span class="badge bg-info">Retraite</span>
                                    @elseif ($enseignant->statut === 'termine')
                                        <span class="badge bg-danger">Terminé</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enseignements -->
                @if ($enseignant->enseignementMatiereClasses->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light text-dark">
                            <h6 class="mb-0">
                                <i data-feather="book" class="me-2"></i>Enseignements
                                <span
                                    class="badge bg-light text-dark ms-2">{{ $enseignant->enseignementMatiereClasses->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Classe</th>
                                            <th>Matière</th>
                                            <th>Heures/Semaine</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enseignant->enseignementMatiereClasses as $enseignement)
                                            <tr>
                                                <td>
                                                    @if ($enseignement->classe)
                                                        <span
                                                            class="badge bg-light text-dark">{{ $enseignement->classe->nom }}</span>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($enseignement->matiere)
                                                        <span class="badge" style="background-color: {{ $enseignement->matiere->color ?? '#007bff' }}; color: #fff;">
                                                            {{ $enseignement->matiere->intitule }}
                                                        </span>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $enseignement->heure_par_semaine ?? 'N/A' }} Heure</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne droite: Actions et Compte -->
            <div class="col-lg-4 mb-4">
                <!-- Compte Utilisateur -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="lock" class="me-2"></i>Compte Utilisateur
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($enseignant->user)
                            <div class="mb-3">
                                <p class="text-muted mb-1">Identifiant</p>
                                <p class="h6">{{ $enseignant->user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">Rôle</p>
                                <p class="h6">
                                    @if ($enseignant->user->role)
                                        <span class="badge bg-primary">{{ $enseignant->user->role->libelle }}</span>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">Statut du Compte</p>
                                <p class="h6">
                                    @if ($enseignant->user->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i data-feather="alert-circle" class="me-2"></i>
                                Aucun compte utilisateur associé
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="settings" class="me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_enseignants.edit', $enseignant->id) }}"
                                class="btn btn-dark btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i data-feather="trash-2" class="me-2"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Historique -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="clock" class="me-2"></i>Historique
                        </h6>
                    </div>
                    <div class="card-body small">
                        <div class="mb-2">
                            <p class="text-muted mb-1">Créé le</p>
                            <p class="h6">{{ $enseignant->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Modifié le</p>
                            <p class="h6">{{ $enseignant->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">
                        <i data-feather="alert-triangle" class="me-2"></i>Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet enseignant ?</p>
                    <p class="text-muted"><strong>{{ $enseignant->prenom }} {{ $enseignant->nom }}</strong></p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>

                <div class="m-3">
                    <form action="{{ route('gestion_enseignants.destroy', $enseignant->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
