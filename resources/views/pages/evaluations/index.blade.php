@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="check-square"></i></div>
                            Gestion des Évaluations
                        </h1>
                        <p class="text-muted">Créez et gérez les évaluations des classes</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_evaluations.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter une évaluation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Évaluations total</p>
                            <h3 class="mb-0">{{ $evaluations->total() }}</h3>
                        </div>
                        <i data-feather="check-square" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Publiées</p>
                            <h3 class="mb-0">{{ $evaluations->where('est_publie', true)->count() }}</h3>
                        </div>
                        <i data-feather="eye" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Examens</p>
                            <h3 class="mb-0">{{ $evaluations->where('type', 'examen')->count() }}</h3>
                        </div>
                        <i data-feather="book" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Page actuelle</p>
                            <h3 class="mb-0">{{ $evaluations->count() }}</h3>
                        </div>
                        <i data-feather="list" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table -->
        <div class="card">
            <div class="table-responsive p-3">
                <table id="datatablesSimple">
                    <thead class="table-light">
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Classe - Matière</th>
                            <th>Date examen</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr>
                                <td>
                                    <strong>{{ $evaluation->titre }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $evaluation->type)) }}</span>
                                </td>
                                <td>
                                    <small>
                                        <strong>{{ $evaluation->enseignementMatiereClasse->classe->nom ?? 'N/A' }}</strong><br>
                                        <span style="background-color: {{ $evaluation->enseignementMatiereClasse->matiere->color ?? '#007bff' }}; color: white;" class="badge">
                                            {{ $evaluation->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                        </span>
                                    </small>
                                </td>
                                <td>
                                    @if($evaluation->date_examen)
                                        {{ \Carbon\Carbon::parse($evaluation->date_examen)->format('d/m/Y') }}
                                        @if($evaluation->heure_debut)
                                            <br><small class="text-muted">{{ $evaluation->heure_debut }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($evaluation->periodEvaluation)
                                        <small>{{ $evaluation->periodEvaluation->libelle ?? 'N/A' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($evaluation->est_publie)
                                        <span class="badge bg-success"><i data-feather="eye" style="width: 0.9rem; height: 0.9rem;"></i> Publiée</span>
                                    @else
                                        <span class="badge bg-secondary"><i data-feather="eye-off" style="width: 0.9rem; height: 0.9rem;"></i> Brouillon</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('gestion_evaluations.show', $evaluation->id) }}" class="text-danger" title="Voir">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i data-feather="inbox" style="width: 3rem; height: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                    <p class="mb-0">Aucune évaluation trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4 mb-4">
            {{ $evaluations->links() }}
        </div>
    </div>

    <script>
        feather.replace();
    </script>

@endsection
