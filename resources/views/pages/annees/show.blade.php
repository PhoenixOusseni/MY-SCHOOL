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
                            <div class="page-header-icon"><i data-feather="calendar"></i></div>
                            Année scolaire {{ $annee->libelle }}
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $annee->date_debut->format('d/m/Y') }} - {{ $annee->date_fin->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_annees_scolaires.index') }}" class="btn btn-light ms-2">
                            <i data-feather="arrow-left"></i>
                        </a>
                        <a href="{{ route('gestion_annees_scolaires.edit', $annee->id) }}" class="btn btn-dark ms-2">
                            <i data-feather="edit-2"></i>
                        </a>
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

        <!-- Statistics Cards Row -->
        <div class="row mb-4">
            <!-- Classes Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-start-5 border-start-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Classes</h6>
                                <h3 class="mb-0">{{ $annee->classes->count() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i data-feather="book-open" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-start-5 border-start-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Élèves</h6>
                                <h3 class="mb-0">{{ $annee->inscriptions->count() }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i data-feather="users" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-start-5 border-start-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Enseignants</h6>
                                <h3 class="mb-0">{{ $annee->enseignementMatiereClasses->pluck('enseignant_id')->unique()->count() }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i data-feather="user-check" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluation Periods Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-start-5 border-start-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Périodes évaluation</h6>
                                <h3 class="mb-0">{{ $annee->periodEvaluations->count() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i data-feather="layers" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Section & Status -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i data-feather="info" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Informations générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Libellé</h6>
                                <p class="mb-0"><strong>{{ $annee->libelle }}</strong></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Établissement</h6>
                                <p class="mb-0">
                                    <a href="{{ route('gestion_etablissements.show', $annee->etablissement->id) }}">
                                        {{ $annee->etablissement->nom }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Date de début</h6>
                                <p class="mb-0">{{ $annee->date_debut->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Date de fin</h6>
                                <p class="mb-0">{{ $annee->date_fin->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-0">
                                <h6 class="text-muted small mb-2">Durée</h6>
                                <p class="mb-0">{{ $annee->date_debut->diffInDays($annee->date_fin) }} jours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i data-feather="flag" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Statut
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if ($annee->is_current)
                            <div class="mb-3">
                                <span class="badge bg-success p-2" style="font-size: 14px;">
                                    <i data-feather="check-circle" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                    En cours
                                </span>
                            </div>
                            <p class="text-muted small mb-0">Cette année est actuellement active</p>
                        @else
                            <div class="mb-3">
                                <span class="badge bg-secondary p-2" style="font-size: 14px;">
                                    <i data-feather="archive" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                    Archivée
                                </span>
                            </div>
                            <p class="text-muted small mb-0">Cette année a été archivée</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i data-feather="calendar" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Calendrier
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted small mb-2">Jours restants</h6>
                            <p class="mb-0">
                                @php
                                    $remainingDays = now()->diffInDays($annee->date_fin, false);
                                    if ($remainingDays > 0) {
                                        echo $remainingDays . ' jours';
                                    } elseif ($remainingDays == 0) {
                                        echo 'Dernier jour';
                                    } else {
                                        echo 'Terminée';
                                    }
                                @endphp
                            </p>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            @php
                                $totalDays = $annee->date_debut->diffInDays($annee->date_fin);
                                $elapsedDays = $annee->date_debut->diffInDays(now());
                                $percentage = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                            @endphp
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">{{ intval($percentage) }}% de progression</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="yearTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab" aria-controls="classes" aria-selected="true">
                            <i data-feather="book-open" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                            Classes ({{ $annee->classes->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">
                            <i data-feather="users" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                            Élèves ({{ $annee->inscriptions->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab" aria-controls="teachers" aria-selected="false">
                            <i data-feather="user-check" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                            Enseignants ({{ $annee->enseignementMatiereClasses->pluck('enseignant_id')->unique()->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="periods-tab" data-bs-toggle="tab" data-bs-target="#periods" type="button" role="tab" aria-controls="periods" aria-selected="false">
                            <i data-feather="layers" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                            Périodes évaluation ({{ $annee->periodEvaluations->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab" aria-controls="payments" aria-selected="false">
                            <i data-feather="dollar-sign" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                            Paiements ({{ $annee->paiements->count() }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="yearTabsContent">
                    <!-- Classes Tab -->
                    <div class="tab-pane fade show active" id="classes" role="tabpanel" aria-labelledby="classes-tab">
                        @if ($annee->classes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Classe</th>
                                            <th>Niveau</th>
                                            <th>Élèves</th>
                                            <th>Professeur Principal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($annee->classes as $classe)
                                            <tr>
                                                <td><strong>{{ $classe->nom ?? 'N/A' }}</strong></td>
                                                <td>
                                                    <small class="text-muted">{{ $classe->niveau->nom ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $classe->eleves->count() }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $profPrincipal = $annee->professeurPrincipals()
                                                            ->where('classe_id', $classe->id)->first();
                                                    @endphp
                                                    @if ($profPrincipal)
                                                        {{ $profPrincipal->enseignant->user->nom ?? 'N/A' }}
                                                    @else
                                                        <small class="text-muted">Aucun assigné</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_classes.show', $classe->id) }}" class="btn btn-sm btn-1">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0 text-center">
                                <i data-feather="info" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucune classe.</strong> Aucune classe n'est associée à cette année scolaire.
                            </div>
                        @endif
                    </div>

                    <!-- Students Tab -->
                    <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                        @if ($annee->inscriptions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nom</th>
                                            <th>Classe</th>
                                            <th>Matricule</th>
                                            <th>Date d'inscription</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($annee->inscriptions as $inscription)
                                            <tr>
                                                <td>
                                                    <strong>{{ $inscription->eleve->nom ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $inscription->classe->nom ?? 'N/A' }} {{ $inscription->classe->prenom ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $inscription->eleve->registration_number ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $inscription->created_at->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_eleves.show', $inscription->eleve->id) }}" class="btn btn-sm btn-1">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0 text-center">
                                <i data-feather="info" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucun élève.</strong> Aucun élève n'est inscrit pour cette année.
                            </div>
                        @endif
                    </div>

                    <!-- Teachers Tab -->
                    <div class="tab-pane fade" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
                        @php
                            $teachers = $annee->enseignementMatiereClasses()
                                ->with('enseignant.user', 'matiere', 'classe')
                                ->get()
                                ->groupBy('enseignant_id');
                        @endphp
                        @if ($teachers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Enseignant</th>
                                            <th>Matières</th>
                                            <th>Classes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachers as $enseignantId => $assignments)
                                            @php
                                                $enseignant = $assignments->first()->enseignant;
                                                $matieres = $assignments->pluck('matiere.intitule')->filter()->unique();
                                                $classes = $assignments->pluck('classe.nom')->filter()->unique();
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $enseignant ? $enseignant->prenom . ' ' . $enseignant->nom : 'N/A' }}</strong></td>
                                                <td>
                                                    @foreach ($matieres as $matiere)
                                                        <span class="badge bg-light text-dark">{{ $matiere }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    {{ implode(', ', $classes->toArray()) }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_enseignants.show', $enseignant->id) }}" class="btn btn-sm btn-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0 text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Aucun enseignant.</strong> Aucun enseignant n'est assigné pour cette année.
                            </div>
                        @endif
                    </div>

                    <!-- Periods Tab -->
                    <div class="tab-pane fade" id="periods" role="tabpanel" aria-labelledby="periods-tab">
                        @if ($annee->periodEvaluations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Période</th>
                                            <th>Date Début</th>
                                            <th>Date Fin</th>
                                            <th>Durée</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($annee->periodEvaluations as $period)
                                            <tr>
                                                <td><strong>{{ $period->libelle }}</strong></td>
                                                <td>
                                                    <small>{{ $period->date_debut }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $period->date_fin }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $period->date_debut->diffInDays($period->date_fin) }} jours</small>
                                                </td>
                                                <td>
                                                    @if (now()->between($period->date_debut, $period->date_fin))
                                                        <span class="badge bg-success">En cours</span>
                                                    @elseif (now()->isBefore($period->date_debut))
                                                        <span class="badge bg-warning">À venir</span>
                                                    @else
                                                        <span class="badge bg-secondary">Terminée</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_periodes_evaluation.show', $period->id) }}" class="btn btn-sm btn-1" title="Voir">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0 text-center">
                                <i data-feather="info" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucune période.</strong> Aucune période d'évaluation n'est définie pour cette année.
                            </div>
                        @endif
                    </div>

                    <!-- Payments Tab -->
                    <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                        @if ($annee->paiements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Élève</th>
                                            <th>Montant</th>
                                            <th>Date de Paiement</th>
                                            <th>Méthode</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($annee->paiements as $paiement)
                                            <tr>
                                                <td>
                                                    <strong>{{ $paiement->inscription->eleve->user->name ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ number_format($paiement->montant, 2, ',', ' ') }} XOF</strong>
                                                </td>
                                                <td>
                                                    <small>{{ $paiement->date_paiement->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $paiement->methode ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @if ($paiement->statut === 'completed')
                                                        <span class="badge bg-success">Reçu</span>
                                                    @elseif ($paiement->statut === 'pending')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @else
                                                        <span class="badge bg-danger">Échoué</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary" title="Voir">
                                                        <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0 text-center">
                                <i data-feather="info" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucun paiement.</strong> Aucun paiement n'est enregistré pour cette année.
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
        });
    </script>
@endsection
