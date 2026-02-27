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
                            <div class="page-header-icon"><i class="fas fa-layer-group"></i></div>
                            Niveau {{ $niveau->nom }}
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $niveau->code }}
                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_niveaux.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <a href="{{ route('gestion_niveaux.edit', $niveau->id) }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-edit"></i>
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
                <i class="fas fa-check-circle me-2"></i>
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
                                <h3 class="mb-0">{{ $niveau->classes->count() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-book-open text-light fa-lg"></i>
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
                                <h3 class="mb-0">
                                    @php
                                        $totalStudents = $niveau->classes->sum(function ($classe) {
                                            return $classe->eleves->count();
                                        });
                                    @endphp
                                    {{ $totalStudents }}
                                </h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-users text-light fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-start-5 border-start-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Matières</h6>
                                <h3 class="mb-0">{{ $niveau->matiereNiveaux->count() }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-book text-light fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Establishment Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-start-5 border-start-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Établissement</h6>
                                <h3 class="mb-0 text-truncate" style="font-size: 1rem;">
                                    {{ $niveau->etablissement->nom ?? 'Tous' }}
                                </h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-building text-light fa-lg"></i>
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
                            <i class="fas fa-info-circle"></i>
                            Informations générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Code</h6>
                                <p class="mb-0"><strong>{{ $niveau->code }}</strong></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Nom</h6>
                                <p class="mb-0"><strong>{{ $niveau->nom }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Ordre d'affichage</h6>
                                <p class="mb-0">{{ $niveau->order_index }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-2">Établissement</h6>
                                <p class="mb-0">
                                    @if ($niveau->etablissement)
                                        <a href="{{ route('gestion_etablissements.show', $niveau->etablissement->id) }}">
                                            {{ $niveau->etablissement->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">Tous les établissements</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-flag"></i>
                            Résumé
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted small mb-2">Créé le</h6>
                            <p class="mb-0">{{ $niveau->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted small mb-2">Dernier modificateur</h6>
                            <p class="mb-0">{{ $niveau->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <hr>
                        <div class="text-center">
                            <p class="text-muted mb-2">Vue d'ensemble</p>
                            <div class="d-flex justify-content-between text-center">
                                <div>
                                    <h6>{{ $niveau->classes->count() }}</h6>
                                    <small class="text-muted">Classes</small>
                                </div>
                                <div class="vr"></div>
                                <div>
                                    <h6>{{ $totalStudents }}</h6>
                                    <small class="text-muted">Élèves</small>
                                </div>
                                <div class="vr"></div>
                                <div>
                                    <h6>{{ $niveau->matiereNiveaux->count() }}</h6>
                                    <small class="text-muted">Matières</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="niveauTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes"
                            type="button" role="tab" aria-controls="classes" aria-selected="true">
                            <i class="fas fa-book-open"></i>
                            Classes ({{ $niveau->classes->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects"
                            type="button" role="tab" aria-controls="subjects" aria-selected="false">
                            <i class="fas fa-book"></i></i>
                            Matières ({{ $niveau->matiereNiveaux->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students"
                            type="button" role="tab" aria-controls="students" aria-selected="false">
                            <i class="fas fa-users"></i>
                            Élèves ({{ $totalStudents }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="niveauTabsContent">
                    <!-- Classes Tab -->
                    <div class="tab-pane fade show active" id="classes" role="tabpanel" aria-labelledby="classes-tab">
                        @if ($niveau->classes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Classe</th>
                                            <th>Capacité</th>
                                            <th>Élèves</th>
                                            <th>Salle</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($niveau->classes->sortBy('nom') as $classe)
                                            <tr>
                                                <td><strong>{{ $classe->nom }}</strong></td>
                                                <td>
                                                    <small class="text-muted">{{ $classe->capacite ?? 'N/A' }} Places</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $classe->eleves->count() }}</span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $classe->salle ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_classes.show', $classe->id) }}"
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
                            <div class="alert alert-info mb-0 text-center">
                                <i class="fas fa-info-circle"></i>
                                <strong>Aucune classe.</strong> Aucune classe n'est associée à ce niveau.
                            </div>
                        @endif
                    </div>

                    <!-- Subjects Tab -->
                    <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
                        @if ($niveau->matiereNiveaux->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Matière</th>
                                            <th>Code</th>
                                            <th>Coefficient</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($niveau->matiereNiveaux as $matiereNiveau)
                                            <tr>
                                                <td><strong>{{ $matiereNiveau->matiere->libelle ?? 'N/A' }}</strong></td>
                                                <td>
                                                    <small
                                                        class="text-muted">{{ $matiereNiveau->matiere->code ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-light text-dark">{{ $matiereNiveau->coefficient ?? 1 }}</span>
                                                </td>
                                                <td>
                                                    <small
                                                        class="text-muted">{{ substr($matiereNiveau->matiere->description ?? 'N/A', 0, 50) }}{{ strlen($matiereNiveau->matiere->description ?? 'N/A') > 50 ? '...' : '' }}</small>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-1"
                                                        title="Voir">
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
                                <i class="fas fa-info-circle"></i>
                                <strong>Aucune matière.</strong> Aucune matière n'est associée à ce niveau.
                            </div>
                        @endif
                    </div>

                    <!-- Students Tab -->
                    <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                        @php
                            $allStudents = [];
                            foreach ($niveau->classes as $classe) {
                                foreach ($classe->eleves as $eleve) {
                                    if (!isset($allStudents[$eleve->id])) {
                                        $allStudents[$eleve->id] = [
                                            'eleve' => $eleve,
                                            'classes' => [],
                                        ];
                                    }
                                    $allStudents[$eleve->id]['classes'][] = $classe->nom;
                                }
                            }
                        @endphp

                        @if (count($allStudents) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Élève</th>
                                            <th>Matricule</th>
                                            <th>Classes</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allStudents as $studentData)
                                            <tr>
                                                <td><strong>{{ $studentData['eleve']->nom ?? 'N/A' }} {{ $studentData['eleve']->prenom ?? 'N/A' }}</strong></td>
                                                <td>
                                                    <small class="text-muted">{{ $studentData['eleve']->registration_number ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ implode(', ', $studentData['classes']) }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $studentData['eleve']->user->email ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_eleves.show', $studentData['eleve']->id) }}" class="btn btn-sm btn-1" title="Voir">
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
                                <i class="fas fa-info-circle"></i>
                                <strong>Aucun élève.</strong> Aucun élève n'est inscrit dans les classes de ce niveau.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


