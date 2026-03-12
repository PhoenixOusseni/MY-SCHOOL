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
                            {{ $classe->nom }}
                        </h1>
                        <p class="text-muted">{{ $classe->description ?? 'Aucune description disponible.' }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_classes.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_classes.edit', $classe->id) }}" class="btn btn-dark btn-sm">
                            <i data-feather="edit"></i>&nbsp; Modifier
                        </a>
                        {{-- Impression de l'effectif de la classe --}}
                        <a href="{{ route('gestion_classes.print_effectif', $classe->id) }}" class="btn btn-success btn-sm" target="_blank">
                            <i data-feather="printer"></i>&nbsp; Effectif
                        </a>
                        {{-- Impression des cartes scolaires --}}
                        <a href="{{ route('gestion_classes.print_cartes', $classe->id) }}" class="btn btn-info btn-sm" target="_blank">
                            <i data-feather="credit-card"></i>&nbsp; Cartes
                        </a>
                        <form action="{{ route('gestion_classes.destroy', $classe->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i data-feather="trash-2"></i>&nbsp; Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10">
        <!-- Messages d'alerte -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
                <i data-feather="check-circle" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Informations Principales -->
        <div class="row mb-4">
            <div class="col-lg-3 mb-4">
                <div class="card bg-light text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-0">Capacité</p>
                                <h4 class="mb-0">{{ $classe->capacite ?? 'N/A' }}</h4>
                            </div>
                            <div>
                                <i data-feather="users" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-4">
                <div class="card bg-light text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-0">Élèves Inscrits</p>
                                <h4 class="mb-0">{{ $classe->inscriptions->count() }}</h4>
                            </div>
                            <div>
                                <i data-feather="user-check" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-4">
                <div class="card bg-light text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-0">Taux Remplissage</p>
                                <h4 class="mb-0">
                                    @if ($classe->capacite > 0)
                                        {{ round(($classe->inscriptions->count() / $classe->capacite) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </h4>
                            </div>
                            <div>
                                <i data-feather="percent" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-4">
                <div class="card bg-light text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-0">Places Libres</p>
                                <h4 class="mb-0">
                                    @if ($classe->capacite > 0)
                                        {{ $classe->capacite - $classe->inscriptions->count() }}
                                    @else
                                        0
                                    @endif
                                </h4>
                            </div>
                            <div>
                                <i data-feather="inbox" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails Généraux -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-dark">
                            <i data-feather="info"
                                style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Informations Générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom de la classe</label>
                                <p class="text-muted">{{ $classe->nom }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Salle</label>
                                <p class="text-muted">{{ $classe->salle ?? 'Non spécifiée' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Niveau</label>
                                <p class="text-muted">
                                    @if ($classe->niveau)
                                        <span class="badge bg-dark">{{ $classe->niveau->nom }}</span>
                                    @else
                                        <span class="badge bg-light text-white">N/A</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Établissement</label>
                                <p class="text-muted">
                                    @if ($classe->etablissement)
                                        <span class="badge bg-dark">{{ $classe->etablissement->nom }}</span>
                                    @else
                                        <span class="badge bg-light text-white">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Année Scolaire</label>
                                <p class="text-muted">
                                    @if ($classe->anneeScolaire)
                                        <span class="badge bg-dark">{{ $classe->anneeScolaire->libelle }}</span>
                                    @else
                                        <span class="badge bg-light text-white">N/A</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Capacité</label>
                                <p class="text-muted">
                                    <span class="badge bg-dark">{{ $classe->capacite }} élèves</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-dark">
                            <i data-feather="calendar"
                                style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Dates
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Créée le</label>
                                <p class="text-muted">
                                    {{ $classe->created_at ? $classe->created_at->format('d/m/Y à H:i') : 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Modifiée le</label>
                                <p class="text-muted">
                                    {{ $classe->updated_at ? $classe->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fw-bold">ID</label>
                                <p class="text-muted"><code>{{ $classe->id }}</code></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets pour les détails en profondeur -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-dark">
                    <i data-feather="list" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                    Détails
                </h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="detailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="eleves-tab" data-bs-toggle="tab" data-bs-target="#eleves"
                            type="button" role="tab" aria-controls="eleves" aria-selected="true">
                            <i data-feather="users"
                                style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                            Élèves ({{ $classe->inscriptions->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="professeurs-tab" data-bs-toggle="tab" data-bs-target="#professeurs"
                            type="button" role="tab" aria-controls="professeurs" aria-selected="false">
                            <i data-feather="award"
                                style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                            Professeurs ({{ $classe->professeurPrincipals->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="matieres-tab" data-bs-toggle="tab" data-bs-target="#matieres"
                            type="button" role="tab" aria-controls="matieres" aria-selected="false">
                            <i data-feather="book"
                                style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                            Matières ({{ $classe->enseignementMatiereClasses->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="absences-tab" data-bs-toggle="tab" data-bs-target="#absences"
                            type="button" role="tab" aria-controls="absences" aria-selected="false">
                            <i data-feather="slash"
                                style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                            Absences ({{ $classe->absences->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="retards-tab" data-bs-toggle="tab" data-bs-target="#retards"
                            type="button" role="tab" aria-controls="retards" aria-selected="false">
                            <i data-feather="clock"
                                style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                            Retards ({{ $classe->retards->count() }})
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="detailsTabContent" style="padding-top: 20px;">
                    <!-- Élèves Tab -->
                    <div class="tab-pane fade show active" id="eleves" role="tabpanel" aria-labelledby="eleves-tab">
                        @if ($classe->inscriptions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Date d'Inscription</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classe->inscriptions as $index => $inscription)
                                            <tr>
                                                <td><strong>{{ $inscription->eleve->registration_number ?? 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $inscription->eleve->nom ?? 'N/A' }}
                                                    {{ $inscription->eleve->prenom ?? 'N/A' }}</td>
                                                <td>{{ $inscription->eleve->email ?? 'N/A' }}</td>
                                                <td><small>{{ $inscription->eleve->telephone ?? 'N/A' }}</small></td>
                                                <td>
                                                    <small>
                                                        @if ($inscription->created_at)
                                                            {{ $inscription->created_at->format('d/m/Y') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestion_eleves.show', $inscription->eleve->id) }}"
                                                        class="btn btn-sm btn-1" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                <i data-feather="info"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>N/A</strong> - Aucun élève inscrit dans cette classe.
                            </div>
                        @endif
                    </div>

                    <!-- Professeurs Tab -->
                    <div class="tab-pane fade" id="professeurs" role="tabpanel" aria-labelledby="professeurs-tab">
                        @if ($classe->professeurPrincipals->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Établissement</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classe->professeurPrincipals as $index => $professeur)
                                            <tr>
                                                <td><strong>{{ $index + 1 }}</strong></td>
                                                <td>{{ $professeur->enseignant->nom ?? 'N/A' }}
                                                    {{ $professeur->enseignant->prenom ?? 'N/A' }}</td>
                                                <td><small>{{ $professeur->enseignant->email ?? 'N/A' }}</small></td>
                                                <td><small>{{ $professeur->enseignant->telephone ?? 'N/A' }}</small>
                                                </td>
                                                <td><small>{{ $professeur->enseignant->etablissement->nom ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestion_enseignants.show', $professeur->id) }}"
                                                        class="btn btn-sm btn-1" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                <i data-feather="info"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucun professeur principal</strong> assigné à cette classe.
                            </div>
                        @endif
                    </div>

                    <!-- Matières Tab -->
                    <div class="tab-pane fade" id="matieres" role="tabpanel" aria-labelledby="matieres-tab">
                        @if ($classe->enseignementMatiereClasses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Matière</th>
                                            <th>Enseignant</th>
                                            <th>Email</th>
                                            <th>Crédits</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classe->enseignementMatiereClasses as $index => $enseignement)
                                            <tr>
                                                <td><strong>{{ $index + 1 }}</strong></td>
                                                <td>{{ $enseignement->matiere->intitule ?? 'N/A' }}</td>
                                                <td>{{ $enseignement->enseignant->prenom ?? 'N/A' }}
                                                    {{ $enseignement->enseignant->nom ?? '' }}</td>
                                                <td><small>{{ $enseignement->enseignant->email ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-success">{{ $enseignement->credits ?? 0 }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestion_enseignants.show', $enseignement->enseignant->id) }}"
                                                        class="btn btn-sm btn-1" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                <i class="fas fa-info-circle"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucune matière</strong> assignée à cette classe.
                            </div>
                        @endif
                    </div>

                    <!-- Absences Tab -->
                    <div class="tab-pane fade" id="absences" role="tabpanel" aria-labelledby="absences-tab">
                        @if ($classe->absences->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Élève</th>
                                            <th>Date</th>
                                            <th>Durée</th>
                                            <th>Justifiée</th>
                                            <th>Motif</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classe->absences->take(50) as $index => $absence)
                                            <tr>
                                                <td><strong>{{ $index + 1 }}</strong></td>
                                                <td>{{ $absence->eleve->prenom ?? 'N/A' }}
                                                    {{ $absence->eleve->nom ?? '' }} -
                                                    {{ $absence->eleve->registration_number ?? 'N/A' }}</td>
                                                <td>{{ $absence->date ? $absence->date->format('d/m/Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-warning">{{ $absence->duree ?? 0 }}h</span>
                                                </td>
                                                <td>
                                                    @if ($absence->justifiee)
                                                        <span class="badge bg-success">Oui</span>
                                                    @else
                                                        <span class="badge bg-danger">Non</span>
                                                    @endif
                                                </td>
                                                <td><small>{{ $absence->motif ?? 'N/A' }}</small></td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($classe->absences->count() > 50)
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i data-feather="info"
                                            style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                                        Affichage limité à 50 entrées. Total:
                                        <strong>{{ $classe->absences->count() }}</strong>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                <i data-feather="info"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucune absence</strong> enregistrée pour cette classe.
                            </div>
                        @endif
                    </div>

                    <!-- Retards Tab -->
                    <div class="tab-pane fade" id="retards" role="tabpanel" aria-labelledby="retards-tab">
                        @if ($classe->retards->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Matricule</th>
                                            <th>Élève</th>
                                            <th>Date</th>
                                            <th>Durée</th>
                                            <th>Justifié</th>
                                            <th>Motif</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classe->retards->take(50) as $index => $retard)
                                            <tr>
                                                <td><strong>{{ $index + 1 }}</strong></td>
                                                <td>{{ $retard->eleve->registration_number ?? 'N/A' }}</td>
                                                <td>{{ $retard->eleve->prenom ?? 'N/A' }}
                                                    {{ $retard->eleve->nom ?? '' }}</td>
                                                <td>{{ $retard->date ? $retard->date->format('d/m/Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-warning">{{ $retard->duree ?? 0 }}min</span>
                                                </td>
                                                <td>
                                                    @if ($retard->justifie)
                                                        <span class="badge bg-success">Oui</span>
                                                    @else
                                                        <span class="badge bg-danger">Non</span>
                                                    @endif
                                                </td>
                                                <td><small>{{ $retard->raison ?? 'N/A' }}</small></td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($classe->retards->count() > 50)
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i data-feather="info"
                                            style="width: 16px; height: 16px; display: inline; margin-right: 6px;"></i>
                                        Affichage limité à 50 entrées. Total:
                                        <strong>{{ $classe->retards->count() }}</strong>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                <i data-feather="info"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucun retard</strong> enregistré pour cette classe.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Réinitialiser les icônes Feather
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Activer les tooltips si nécessaire
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
