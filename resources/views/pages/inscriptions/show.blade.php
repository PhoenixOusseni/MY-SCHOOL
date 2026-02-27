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
                            <div class="page-header-icon"><i data-feather="book-open"></i></div>
                            Détails de l'Inscription
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            Inscription de {{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_inscriptions.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10 mb-5">
        <!-- Messages d'alerte -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i data-feather="check-circle" class="me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading mb-0">Succès</h6>
                        {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $statusColor =
                [
                    'inscrit' => 'success',
                    'transfere' => 'warning',
                    'termine' => 'info',
                    'abandonne' => 'danger',
                ][$inscription->statut] ?? 'secondary';
        @endphp

        <div class="row">
            <!-- Colonne gauche : Informations principales -->
            <div class="col-lg-8">
                <!-- Informations de l'Élève -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="user" class="me-2"></i> Informations de l'Élève
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Prénom Nom</label>
                                <p class="h6 mb-0">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Immatriculation</label>
                                <p class="h6 mb-0"><code>{{ $inscription->eleve->registration_number }}</code></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Genre</label>
                                <p class="h6 mb-0">
                                    @if ($inscription->eleve->genre)
                                        <span class="badge bg-info">
                                            {{ $inscription->eleve->genre === 'M' ? 'Masculin' : ($inscription->eleve->genre === 'F' ? 'Féminin' : 'Autres') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Date de Naissance</label>
                                <p class="h6 mb-0">
                                    @if ($inscription->eleve->date_naissance)
                                        {{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->format('d/m/Y') }}
                                        <small
                                            class="text-muted">({{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->age }}
                                            ans)</small>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Téléphone</label>
                                <p class="h6 mb-0">
                                    @if ($inscription->eleve->telephone)
                                        <a
                                            href="tel:{{ $inscription->eleve->telephone }}">{{ $inscription->eleve->telephone }}</a>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Email</label>
                                <p class="h6 mb-0">
                                    @if ($inscription->eleve->email)
                                        <a
                                            href="mailto:{{ $inscription->eleve->email }}">{{ $inscription->eleve->email }}</a>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Adresse</label>
                                <p class="h6 mb-0">{{ $inscription->eleve->adresse ?? 'Non spécifié' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Nationalité</label>
                                <p class="h6 mb-0">{{ $inscription->eleve->nationalite ?? 'Non spécifié' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de la Classe -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="layers" class="me-2"></i> Informations de la Classe
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Classe</label>
                                <p class="h6 mb-0">
                                    <a href="{{ route('gestion_classes.show', $inscription->classe_id) }}">
                                        {{ $inscription->classe->nom }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Niveau</label>
                                <p class="h6 mb-0">
                                    <span class="badge bg-secondary">
                                        {{ $inscription->classe->niveau->nom ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Établissement</label>
                                <p class="h6 mb-0">
                                    @if ($inscription->classe->etablissement)
                                        <a
                                            href="{{ route('gestion_etablissements.show', $inscription->classe->etablissement_id) }}">
                                            {{ $inscription->classe->etablissement->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Capacité</label>
                                <p class="h6 mb-0">{{ $inscription->classe->capacite ?? 'N/A' }} élèves</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Nombre d'inscrits</label>
                                <p class="h6 mb-0">{{ $inscription->classe->inscriptions->count() }} élève(s)</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Taux d'occupation</label>
                                <p class="h6 mb-0">
                                    {{ number_format(($inscription->classe->inscriptions->count() / ($inscription->classe->capacite ?? 1)) * 100, 1) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de l'Année Scolaire -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="calendar" class="me-2"></i> Année Scolaire
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Année</label>
                                <p class="h6 mb-0">{{ $inscription->anneeScolaire->libelle ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted text-uppercase">Statut Année</label>
                                <p class="h6 mb-0">
                                    @php
                                        $now = now();
                                        $dateDebut = $inscription->anneeScolaire?->date_debut;
                                        $dateFin = $inscription->anneeScolaire?->date_fin;
                                    @endphp

                                    @if ($dateDebut && $dateFin)
                                        @if ($now < $dateDebut)
                                            <span class="badge bg-info">À venir</span>
                                        @elseif ($now > $dateFin)
                                            <span class="badge bg-secondary">Terminée</span>
                                        @else
                                            <span class="badge bg-success">En cours</span>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Détails et chronologie -->
            <div class="col-lg-4">
                <!-- Détails de l'Inscription -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="file-text" class="me-2"></i> Détails de l'Inscription
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">ID Inscription</label>
                            <p class="h6 mb-0"><code>#{{ $inscription->id }}</code></p>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Statut</label>
                            <p class="h6 mb-0">
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($inscription->statut) }}
                                </span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Date d'Inscription</label>
                            <p class="h6 mb-0">
                                {{ \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Jour de la semaine</label>
                            <p class="h6 mb-0">
                                @php
                                    $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                                    $jour = $jours[\Carbon\Carbon::parse($inscription->date_inscription)->dayOfWeek];
                                @endphp
                                {{ $jour }}
                            </p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Créée le</label>
                            <p class="h6 mb-0">
                                {{ $inscription->created_at->format('d/m/Y à H:i') }}
                                <br>
                                <small class="text-muted">{{ $inscription->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                        <div class="mb-0">
                            <label class="small mb-1 text-muted text-uppercase">Modifiée le</label>
                            <p class="h6 mb-0">
                                {{ $inscription->updated_at->format('d/m/Y à H:i') }}
                                <br>
                                <small class="text-muted">{{ $inscription->updated_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Documents et Actions -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="file" class="me-2"></i> Documents
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_inscriptions.edit', $inscription->id) }}"
                                class="btn btn-outline-warning btn-sm">
                                <i data-feather="edit" style="width: 16px; height: 16px;"></i>&nbsp; Modifier
                            </a>
                            <a href="{{ route('gestion_inscriptions.print', $inscription->id) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm">
                                <i data-feather="printer" style="width: 16px; height: 16px;"></i>&nbsp; Aperçu
                                d'impression
                            </a>
                            <a href="{{ route('gestion_eleves.show', $inscription->eleve_id) }}"
                                class="btn btn-outline-info btn-sm">
                                <i data-feather="eye" style="width: 16px; height: 16px;"></i>&nbsp; Profil élève
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Résumé -->
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i> Résumé
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <p class="mb-2">
                                <strong>{{ $inscription->eleve->prenom }}</strong> est inscrit(e) en
                                <strong>{{ $inscription->classe->nom }}</strong>
                            </p>
                            <p class="mb-2">
                                Niveau: <strong>{{ $inscription->classe->niveau->nom ?? 'N/A' }}</strong>
                            </p>
                            <p class="mb-0">
                                Année: <strong>{{ $inscription->anneeScolaire->libelle ?? 'N/A' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
