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
                            <div class="page-header-icon"><i class="fas fa-user"></i></div>
                            Détails de l'Élève
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            {{ $eleve->prenom }} {{ $eleve->nom }}
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_eleves.edit', $eleve->id) }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-edit"></i>&nbsp; Modifier
                        </a>
                        <a href="{{ route('gestion_eleves.index') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10 mb-5">
        <!-- Info Personnelles et Photo -->
        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body text-center">
                        @if ($eleve->photo)
                            <img src="{{ asset('storage/' . $eleve->photo) }}"
                                alt="{{ $eleve->prenom }} {{ $eleve->nom }}" class="rounded-circle img-fluid mb-3"
                                style="max-width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-5x text-white"></i>
                            </div>
                        @endif
                        <h5 class="card-title">{{ $eleve->prenom }} {{ $eleve->nom }}</h5>
                        <p class="card-text text-muted small">{{ $eleve->registration_number }}</p>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Voir le compte
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Informations Personnelles -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-light text-dark">
                        <h3 class="mb-0"><i class="fas fa-user me-2"></i> Informations Personnelles</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Prénom</label>
                                <p class="h6 mb-0">{{ $eleve->prenom }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Nom</label>
                                <p class="h6 mb-0">{{ $eleve->nom }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Genre</label>
                                <p class="h6 mb-0">
                                    @if ($eleve->genre)
                                        <span class="badge bg-info">
                                            {{ $eleve->genre === 'M' ? 'Masculin' : ($eleve->genre === 'F' ? 'Féminin' : 'Autres') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Date de Naissance</label>
                                <p class="h6 mb-0">
                                    @if ($eleve->date_naissance)
                                        {{ \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') }}
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($eleve->date_naissance)->age }}
                                            ans)</small>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Lieu de Naissance</label>
                                <p class="h6 mb-0">{{ $eleve->lieu_naissance ?? 'Non spécifié' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Nationalité</label>
                                <p class="h6 mb-0">{{ $eleve->nationalite ?? 'Non spécifié' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Adresse</label>
                                <p class="h6 mb-0">{{ $eleve->adresse ?? 'Non spécifié' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="small mb-1 text-muted text-uppercase">Statut</label>
                                <p>
                                    <span
                                        class="badge bg-{{ $eleve->statut === 'actif' ? 'success' : ($eleve->statut === 'suspendu' ? 'warning' : ($eleve->statut === 'diplome' ? 'info' : 'danger')) }} ms-2">
                                        {{ ucfirst($eleve->statut) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de Contact -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h3 class="mb-0"><i class="fas fa-phone me-2"></i> Informations de Contact</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Téléphone</label>
                            <p class="h6 mb-0">
                                @if ($eleve->telephone)
                                    <a href="tel:{{ $eleve->telephone }}">{{ $eleve->telephone }}</a>
                                @else
                                    <span class="text-muted">Non spécifié</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Email</label>
                            <p class="h6 mb-0">
                                @if ($eleve->email)
                                    <a href="mailto:{{ $eleve->email }}">{{ $eleve->email }}</a>
                                @else
                                    <span class="text-muted">Non spécifié</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de Santé -->
            <div class="col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h3 class="mb-0"><i class="fas fa-heart me-2"></i> Informations de Santé</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1 text-muted text-uppercase">Groupe Sanguin</label>
                            <p class="h6 mb-0">
                                @if ($eleve->groupe_sanguin)
                                    <span class="badge bg-danger">{{ $eleve->groupe_sanguin }}</span>
                                @else
                                    <span class="text-muted">Non spécifié</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-0">
                            <label class="small mb-1 text-muted text-uppercase">Notes Médicales</label>
                            <p class="h6 mb-0">{{ $eleve->notes_medicales ?? 'Aucune' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Scolaires -->
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h3 class="mb-0"><i class="fas fa-building me-2"></i>Établissement</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <label class="small mb-1 text-muted text-uppercase">Nom</label>
                            <p class="h6 mb-0">
                                @if ($eleve->etablissement)
                                    <a href="{{ route('gestion_etablissements.show', $eleve->etablissement_id) }}">
                                        {{ $eleve->etablissement->nom }}
                                    </a>
                                @else
                                    <span class="text-muted">Non assoċié</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Dates</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="mb-2">
                                <label class="small mb-1 text-muted text-uppercase">Date d'inscription</label>
                                <p class="h6 mb-0">
                                    {{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : 'Non spécifié' }}
                                </p>
                            </div>
                            <div class="mb-0">
                                <label class="small mb-1 text-muted text-uppercase">Inscrit depuis</label>
                                <p class="h6 mb-0">
                                    @if ($eleve->created_at)
                                        {{ $eleve->created_at->diffForHumans() }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h3 class="mb-0"><i class="fas fa-user-check me-2"></i> Compte</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <label class="small mb-1 text-muted text-uppercase">Utilisateur</label>
                            <p class="h6 mb-0">
                                @if ($eleve->user)
                                    <span class="badge bg-primary">{{ $eleve->user->login }}</span>
                                @else
                                    <span class="badge bg-danger">Pas de compte</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inscriptions -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light text-dark">
                <h3 class="mb-0"><i class="fas fa-list me-2"></i>Inscriptions aux Classes</h3>
            </div>
            <div class="card-body">
                @if ($eleve->inscriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Année Scolaire</th>
                                    <th>Classe</th>
                                    <th>Niveau</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eleve->inscriptions as $inscription)
                                    <tr>
                                        <td>
                                            <strong>{{ $inscription->anneeScolaire->libelle ?? 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $inscription->classe->nom ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-dark">
                                                {{ $inscription->classe->niveau->nom ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">Inscrit</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Aucune inscription enregistrée.
                    </div>
                @endif
            </div>
        </div>

        <!-- Tuteurs/Parents -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light text-dark">
                <h3 class="mb-0"><i class="fas fa-users me-2"></i>Tuteurs/Parents</h3>
            </div>
            <div class="card-body">
                @if ($eleve->eleveParents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Relation</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eleve->eleveParents as $eleveParent)
                                    @if ($eleveParent->tuteur)
                                        <tr>
                                            <td><strong>{{ $eleveParent->tuteur->nom }}</strong></td>
                                            <td>{{ $eleveParent->tuteur->prenom }}</td>
                                            <td>
                                                <span class="badge bg-dark">
                                                    {{ $eleveParent->tuteur->relationship ?? 'Parent' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($eleveParent->tuteur->telephone)
                                                    <a href="tel:{{ $eleveParent->tuteur->telephone }}">
                                                        {{ $eleveParent->tuteur->telephone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($eleveParent->tuteur->email)
                                                    <a href="mailto:{{ $eleveParent->tuteur->email }}">
                                                        {{ $eleveParent->tuteur->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Aucun tuteur/parent enregistré.
                    </div>
                @endif
            </div>
        </div>

        <!-- *** Tabs for Academic Records *** -->
        <ul class="nav nav-tabs mb-4" id="academicTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes"
                    type="button" role="tab" aria-controls="notes" aria-selected="true">
                    <i class="fas fa-award me-2"></i> Notes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="moyennes-tab" data-bs-toggle="tab" data-bs-target="#moyennes"
                    type="button" role="tab" aria-controls="moyennes" aria-selected="false">
                    <i class="fas fa-chart-line me-2"></i> Moyennes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bulletins-tab" data-bs-toggle="tab" data-bs-target="#bulletins"
                    type="button" role="tab" aria-controls="bulletins" aria-selected="false">
                    <i class="fas fa-file-alt me-2"></i> Bulletins
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="absences-tab" data-bs-toggle="tab" data-bs-target="#absences"
                    type="button" role="tab" aria-controls="absences" aria-selected="false">
                    <i class="fas fa-times-circle me-2"></i> Absences
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="retards-tab" data-bs-toggle="tab" data-bs-target="#retards" type="button"
                    role="tab" aria-controls="retards" aria-selected="false">
                    <i class="fas fa-clock me-2"></i> Retards
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="incidents-tab" data-bs-toggle="tab" data-bs-target="#incidents"
                    type="button" role="tab" aria-controls="incidents" aria-selected="false">
                    <i class="fas fa-exclamation-triangle me-2"></i> Incidents
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sanctions-tab" data-bs-toggle="tab" data-bs-target="#sanctions"
                    type="button" role="tab" aria-controls="sanctions" aria-selected="false">
                    <i class="fas fa-ban me-2"></i> Sanctions
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="devoirs-tab" data-bs-toggle="tab" data-bs-target="#devoirs" type="button"
                    role="tab" aria-controls="devoirs" aria-selected="false">
                    <i class="fas fa-book me-2"></i> Devoirs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="paiements-tab" data-bs-toggle="tab" data-bs-target="#paiements"
                    type="button" role="tab" aria-controls="paiements" aria-selected="false">
                    <i class="fas fa-credit-card me-2"></i> Frais et
                    Paiements
                </button>
            </li>
        </ul>

        <div class="tab-content" id="academicTabContent">
            <!-- Notes Tab -->
            <div class="tab-pane fade show active" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0"><i class="fas fa-award me-2"></i>Notes de l'Élève</h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->notes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Matière</th>
                                            <th>Évaluation</th>
                                            <th>Note</th>
                                            <th>Coefficient</th>
                                            <th>Date</th>
                                            <th>Entré par</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->notes as $note)
                                            <tr>
                                                <td><strong>{{ $note->evaluation->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark">
                                                        {{ $note->evaluation->titre ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong
                                                        class="badge bg-dark">{{ $note->score ?? 'N/A' }}/{{ $note->evaluation->note_max ?? 20 }}</strong>
                                                </td>
                                                <td>{{ $note->evaluation->coefficient ?? 1 }}</td>
                                                <td>{{ $note->created_at ? $note->created_at->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $note->enteredBy ? $note->enteredBy->prenom . ' ' . $note->enteredBy->nom : 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune note enregistrée.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Moyennes Tab -->
            <div class="tab-pane fade" id="moyennes" role="tabpanel" aria-labelledby="moyennes-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Moyennes par Matière</h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->moyenneMatieres->count() > 0)
                            <div class="row">
                                @foreach ($eleve->moyenneMatieres as $moyenne)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-left-primary">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $moyenne->matiere->libelle ?? 'N/A' }}</h6>
                                                <div class="d-flex align-items-end justify-content-between">
                                                    <div>
                                                        <p class="mb-0 text-muted small">Moyenne</p>
                                                        <h4 class="card-text text-primary mb-0">
                                                            {{ number_format($moyenne->valeur ?? 0, 2) }}/20
                                                        </h4>
                                                    </div>
                                                    <div class="progress" style="height: 25px; width: 60px;">
                                                        <div class="progress-bar bg-success"
                                                            style="width: {{ ($moyenne->valeur ?? 0) * 5 }}%;"
                                                            role="progressbar"
                                                            aria-valuenow="{{ $moyenne->valeur ?? 0 }}" aria-valuemin="0"
                                                            aria-valuemax="20"></div>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    Dernier calcul:
                                                    {{ $moyenne->updated_at ? $moyenne->updated_at->format('d/m/Y') : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune moyenne calculée.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bulletins Tab -->
            <div class="tab-pane fade" id="bulletins" role="tabpanel" aria-labelledby="bulletins-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Bulletins Scolaires</h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->bulletins->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Année Scolaire</th>
                                            <th>Période</th>
                                            <th>Moyenne Générale</th>
                                            <th>Classement</th>
                                            <th>Observations</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->bulletins as $bulletin)
                                            <tr>
                                                <td><strong>{{ $bulletin->periodEvaluation->anneeScolaire->libelle ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark">
                                                        {{ $bulletin->periodEvaluation->libelle ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-primary">
                                                        {{ number_format($bulletin->moyenne_globale ?? 0, 2) }}/20
                                                    </strong>
                                                </td>
                                                <td>{{ $bulletin->rang ?? 'N/A' }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($bulletin->commentaire_principal ?? 'Aucun', 30) }}
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-1">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Aucun bulletin disponible.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Absences Tab -->
            <div class="tab-pane fade" id="absences" role="tabpanel" aria-labelledby="absences-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-times-circle me-2"></i>
                            Absences ({{ $eleve->absences->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->absences->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Matière/Classe</th>
                                            <th>Période</th>
                                            <th>Justifiée</th>
                                            <th>Motif</th>
                                            <th>Signalé par</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->absences as $absence)
                                            <tr>
                                                <td><strong>{{ $absence->date ? \Carbon\Carbon::parse($absence->date)->format('d/m/Y') : 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    {{ $absence->matiere->intitule ?? ($absence->classe->nom ?? 'N/A') }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark">
                                                        {{ $absence->periode ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($absence->justified)
                                                        <span class="badge bg-success">Oui</span>
                                                    @else
                                                        <span class="badge bg-danger">Non</span>
                                                    @endif
                                                </td>
                                                <td>{{ $absence->raison ?? 'Non spécifié' }}</td>
                                                <td>{{ $absence->reportedBy->prenom ?? 'N/A' }}
                                                    {{ $absence->reportedBy->nom ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Aucune absence enregistrée.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Retards Tab -->
            <div class="tab-pane fade" id="retards" role="tabpanel" aria-labelledby="retards-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Retards ({{ $eleve->retards->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->retards->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Heure d'Arrivée</th>
                                            <th>Minutes de Retard</th>
                                            <th>Matière/Classe</th>
                                            <th>Motif</th>
                                            <th>Excusé</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->retards as $retard)
                                            <tr>
                                                <td><strong>{{ $retard->date ? \Carbon\Carbon::parse($retard->date)->format('d/m/Y') : 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $retard->heure_arrivee ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        {{ $retard->minutes ?? 0 }} min
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $retard->matiere->intitule ?? ($retard->classe->nom ?? 'N/A') }}
                                                </td>
                                                <td>{{ $retard->raison ?? 'Non spécifié' }}</td>
                                                <td>
                                                    @if ($retard->excused)
                                                        <span class="badge bg-success">Oui</span>
                                                    @else
                                                        <span class="badge bg-danger">Non</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Aucun retard enregistré.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Incidents Tab -->
            <div class="tab-pane fade" id="incidents" role="tabpanel" aria-labelledby="incidents-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Incidents Disciplinaires ({{ $eleve->incidentsDisciplinaires->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->incidentsDisciplinaires->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Type incident</th>
                                            <th>Emplacement</th>
                                            <th>Description</th>
                                            <th>Gravité</th>
                                            <th>Signalé par</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->incidentsDisciplinaires as $incident)
                                            <tr>
                                                <td><strong>{{ $incident->date_incident ? \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') : 'N/A' }}</strong>
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit($incident->type ?? 'N/A', 40) }}</td>
                                                <td>{{ $incident->emplacement ?? 'N/A' }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($incident->description ?? 'N/A', 40) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $gravite = strtolower($incident->gravite ?? 'moyen');
                                                        $color =
                                                            $gravite === 'grave'
                                                                ? 'danger'
                                                                : ($gravite === 'moyen'
                                                                    ? 'warning'
                                                                    : 'info');
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">
                                                        {{ ucfirst($incident->gravite ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td>{{ $incident->reportedBy->prenom ?? 'N/A' }}
                                                    {{ $incident->reportedBy->nom ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestion_incidents.show', $incident->id) }}"
                                                        class="btn btn-sm btn-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Aucun incident enregistré.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sanctions Tab -->
            <div class="tab-pane fade" id="sanctions" role="tabpanel" aria-labelledby="sanctions-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-ban me-2"></i>
                            Sanctions ({{ $eleve->sanctions->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->sanctions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date debut</th>
                                            <th>Date fin</th>
                                            <th>Type</th>
                                            <th>Raison</th>
                                            <th>Durée/Détails</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->sanctions as $sanction)
                                            <tr>
                                                <td><strong>{{ $sanction->date_debut ? \Carbon\Carbon::parse($sanction->date_debut)->format('d/m/Y') : 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $sanction->date_fin ? \Carbon\Carbon::parse($sanction->date_fin)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        {{ ucfirst($sanction->type ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit($sanction->incidentDisciplinaire->type ?? 'N/A', 40) }}
                                                </td>
                                                <td>{{ $sanction->duree ?? 'N/A' }}</td>
                                                <td>
                                                    {{ $sanction->status ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Aucune sanction enregistrée.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Devoirs Tab -->
            <div class="tab-pane fade" id="devoirs" role="tabpanel" aria-labelledby="devoirs-tab">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-book me-2"></i>
                            Soumissions de Devoirs
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->soumissionsDevoirs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Devoir</th>
                                            <th>Matière</th>
                                            <th>Date d'Échéance</th>
                                            <th>Date de Soumission</th>
                                            <th>Statut</th>
                                            <th>Note/Évaluation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->soumissionsDevoirs as $soumission)
                                            <tr>
                                                <td><strong>{{ $soumission->devoir->title ?? 'N/A' }}</strong></td>
                                                <td>{{ $soumission->devoir->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $soumission->devoir->date_echeance ? \Carbon\Carbon::parse($soumission->devoir->date_echeance)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $soumission->date_submission ? \Carbon\Carbon::parse($soumission->date_submission)->format('d/m/Y H:i') : 'Non soumis' }}
                                                </td>
                                                <td>
                                                    @php
                                                        $statut = $soumission->status ?? 'pending';
                                                        $color =
                                                            $statut === 'submitted'
                                                                ? 'success'
                                                                : ($statut === 'graded'
                                                                    ? 'primary'
                                                                    : ($statut === 'late'
                                                                        ? 'warning'
                                                                        : 'secondary'));
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">
                                                        {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($soumission->score !== null)
                                                        <strong
                                                            class="text-primary">{{ $soumission->score }}/{{ $soumission->devoir->note_max ?? 20 }}</strong>
                                                    @else
                                                        <span class="text-muted">En attente</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune soumission de devoir enregistrée.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Paiements Tab -->
            <div class="tab-pane fade" id="paiements" role="tabpanel" aria-labelledby="paiements-tab">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>
                            Frais de Scolarité
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->fraisScolarites && $eleve->fraisScolarites->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Année/Période</th>
                                            <th>Montant</th>
                                            <th>Payé</th>
                                            <th>Reste</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->fraisScolarites as $frais)
                                            <tr>
                                                <td><strong>{{ ucfirst($frais->libelle ?? 'N/A') }}</strong></td>
                                                <td>{{ number_format($frais->montant ?? 0, 2) }} FCFA</td>
                                                <td class="text-success">{{ number_format($frais->montant_paye ?? 0, 2) }}
                                                    FCFA
                                                </td>
                                                <td class="text-danger">
                                                    {{ number_format(($frais->montant ?? 0) - ($frais->montant_paye ?? 0), 2) }}
                                                    FCFA</td>
                                                <td>
                                                    @php
                                                        $solde = ($frais->montant ?? 0) - ($frais->montant_paye ?? 0);
                                                        $color =
                                                            $solde <= 0
                                                                ? 'success'
                                                                : ($solde < ($frais->montant ?? 0) / 2
                                                                    ? 'warning'
                                                                    : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">
                                                        {{ $solde <= 0 ? 'Payé' : 'En cours' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucun frais de scolarité associé.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>
                            Historique des Paiements
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($eleve->paiements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Frais</th>
                                            <th>Montant</th>
                                            <th>Reste à payer</th>
                                            <th>Mode de Paiement</th>
                                            <th>Référence</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleve->paiements as $paiement)
                                            <tr>
                                                <td><strong>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $paiement->fraiScolarite->libelle ?? 'N/A' }}</td>
                                                <td class="text-success">
                                                    <strong>{{ number_format($paiement->montant ?? 0, 2) }} FCFA</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark fw-bold">
                                                        {{ number_format($paiement->reste_a_payer) }}
                                                        FCFA
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ ucfirst($paiement->methode_paiement ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td><code>{{ $paiement->reference ?? 'N/A' }}</code></td>
                                                <td>
                                                    @php
                                                        $statut = $paiement->statut ?? 'en_attente';
                                                        $color =
                                                            $statut === 'approuve'
                                                                ? 'success'
                                                                : ($statut === 'rejete'
                                                                    ? 'danger'
                                                                    : 'warning');
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">
                                                        {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestion_paiements.show', $paiement->id) }}"
                                                        class="btn btn-sm btn-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Aucun paiement enregistré.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
