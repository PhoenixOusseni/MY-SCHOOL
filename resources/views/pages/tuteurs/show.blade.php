@extends('layouts.master')

@section('title', 'Détails du Tuteur')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Gestion des parents / Tuteurs
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            Consultez les détails du tuteur, les élèves rattachés et gérez les informations du tuteur.
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_tuteurs.index') }}" class="btn btn-light btn-sm me-2">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <!-- Colonne Gauche: Détails du Tuteur -->
            <div class="col-md-8">
                <!-- Informations Personnelles -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="user" class="me-2"></i>Informations Personnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Nom</p>
                                <p class="fw-bold">{{ $tuteur->nom }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Prénom</p>
                                <p class="fw-bold">{{ $tuteur->prenom }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Lien de Parenté</p>
                                <p>
                                    @php
                                        $relationshipLabel = [
                                            'pere' => 'Père',
                                            'mere' => 'Mère',
                                            'tuteur' => 'Tuteur',
                                            'autre' => 'Autre',
                                        ];
                                        $relationshipColor = [
                                            'pere' => 'info',
                                            'mere' => 'success',
                                            'tuteur' => 'primary',
                                            'autre' => 'secondary',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $relationshipColor[$tuteur->relationship] ?? 'secondary' }}">
                                        {{ $relationshipLabel[$tuteur->relationship] ?? 'Autre' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de Contact -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="phone" class="me-2"></i>Informations de Contact
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Téléphone</p>
                            @if ($tuteur->telephone)
                                <p class="fw-bold">
                                    <a href="tel:{{ $tuteur->telephone }}" class="text-decoration-none">
                                        {{ $tuteur->telephone }}
                                    </a>
                                </p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <p class="text-muted small mb-1">Email</p>
                            @if ($tuteur->email)
                                <p class="fw-bold">
                                    <a href="mailto:{{ $tuteur->email }}" class="text-decoration-none">
                                        {{ $tuteur->email }}
                                    </a>
                                </p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>

                        <div class="mb-0">
                            <p class="text-muted small mb-1">Adresse</p>
                            @if ($tuteur->adresse)
                                <p class="fw-bold">{{ $tuteur->adresse }}</p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations Professionnelles -->
                <div class="card border-0 shadow-sm">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="briefcase" class="me-2"></i>Informations Professionnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Profession</p>
                            @if ($tuteur->profession)
                                <p class="fw-bold">{{ $tuteur->profession }}</p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>

                        <div class="mb-0">
                            <p class="text-muted small mb-1">Lieu de Travail</p>
                            @if ($tuteur->lieu_travail)
                                <p class="fw-bold">{{ $tuteur->lieu_travail }}</p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne Droite: Listes -->
            <div class="col-md-4">
                <!-- Compte Utilisateur -->
                @if ($tuteur->user)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header d-flex align-items-center">
                            <h6 class="mb-0">
                                <i data-feather="lock" class="me-2"></i>Compte Utilisateur
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted small mb-1">Identifiant</p>
                                <p class="fw-bold">{{ $tuteur->user->email }}</p>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted small mb-1">Rôle</p>
                                <p>
                                    <span class="badge bg-primary">{{ $tuteur->user->role->libelle ?? 'N/A' }}</span>
                                </p>
                            </div>

                            <div class="mb-0">
                                <p class="text-muted small mb-1">Statut du Compte</p>
                                <p>
                                    <span class="badge bg-success">Actif</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Élèves Attachés -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="users" class="me-2"></i>Élèves Attachés
                        </h6>
                        <span class="badge bg-light text-dark">{{ $tuteur->eleves()->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if ($tuteur->eleves()->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($tuteur->eleves as $eleve)
                                    <a href="{{ route('gestion_eleves.show', $eleve->id) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $eleve->prenom . ' ' . strtoupper($eleve->nom) }}
                                                </h6>
                                                <small class="text-muted">
                                                    Matricule: {{ $eleve->registration_number }}
                                                </small>
                                            </div>
                                            <span class="badge bg-light text-dark">
                                                {{ $eleve->inscriptions()->first()?->classe?->nom ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i data-feather="inbox" style="width: 40px; height: 40px;" class="mb-2"></i>
                                <p class="mb-0">Aucun élève attaché</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Résumé Général -->
                <div class="card border-0 shadow-sm mt-3 bg-light">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div>
                                    <h4 class="text-primary mb-0">{{ $tuteur->eleves()->count() }}</h4>
                                    <small class="text-muted">Élève(s) rattaché(s)</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <h4 class="text-info mb-0">
                                        {{ (new DateTime($tuteur->created_at))->diff(new DateTime())->days }}</h4>
                                    <small class="text-muted">Jours d'inscription</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i data-feather="calendar" style="width: 14px; height: 14px; display: inline;"
                                    class="me-1"></i>
                                Créé le: {{ $tuteur->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                        <div>
                            <small class="text-muted">
                                <i data-feather="edit-2" style="width: 14px; height: 14px; display: inline;"
                                    class="me-1"></i>
                                Modifié le: {{ $tuteur->updated_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="users" class="me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('gestion_tuteurs.edit', $tuteur->id) }}"
                                class="btn btn-1">
                                <i data-feather="edit" class="me-2"></i>Modifier les informations
                            </a>
                            <form action="{{ route('gestion_tuteurs.destroy', $tuteur->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-2 w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tuteur ? Cette action est irréversible.')">
                                    <i data-feather="trash-2" class="me-2"></i>Supprimer le tuteur
                                </button>
                            </form>
                        </div>
                    </div>
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
