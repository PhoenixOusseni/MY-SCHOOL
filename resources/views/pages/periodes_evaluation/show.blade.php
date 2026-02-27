@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="info"></i></div>
                            Détails de la période d'évaluation
                        </h1>
                        <p class="text-muted">{{ $periode->libelle }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_periodes_evaluation.index') }}" class="btn btn-dark">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Période Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Libellé</p>
                                <p class="mb-0">
                                    <strong>{{ $periode->libelle }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Type</p>
                                <p class="mb-0">
                                    @if ($periode->type === 'trimester')
                                        <span class="badge bg-primary">Trimestre</span>
                                    @elseif($periode->type === 'semester')
                                        <span class="badge bg-info">Semestre</span>
                                    @elseif($periode->type === 'quarter')
                                        <span class="badge bg-secondary">Quart</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Année scolaire</p>
                                <p class="mb-0"><strong>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Ordre d'affichage</p>
                                <p class="mb-0">
                                    @if ($periode->order_index)
                                        <span class="badge bg-light text-dark">{{ $periode->order_index }}</span>
                                    @else
                                        <span class="text-muted">Non défini</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Date de début</p>
                                <p class="mb-0">
                                    <strong>{{ \Carbon\Carbon::parse($periode->date_debut)->format('d/m/Y') }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($periode->date_debut)->translatedFormat('l j F Y') }}</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Date de fin</p>
                                <p class="mb-0">
                                    <strong>{{ \Carbon\Carbon::parse($periode->date_fin)->format('d/m/Y') }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($periode->date_fin)->translatedFormat('l j F Y') }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Durée -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i data-feather="clock" style="width: 1.2rem; height: 1.2rem;"></i> Durée</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <strong>{{ \Carbon\Carbon::parse($periode->date_debut)->diffInDays(\Carbon\Carbon::parse($periode->date_fin)) + 1 }}
                                jours</strong>
                            (Du {{ \Carbon\Carbon::parse($periode->date_debut)->format('d/m/Y') }} au
                            {{ \Carbon\Carbon::parse($periode->date_fin)->format('d/m/Y') }})
                        </div>
                    </div>
                </div>

                <!-- Evaluations Section -->
                @if ($periode->evaluations && $periode->evaluations->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i data-feather="award" style="width: 1.2rem; height: 1.2rem;"></i>
                                Évaluations ({{ $periode->evaluations->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($periode->evaluations as $evaluation)
                                            <tr>
                                                <td>{{ $evaluation->nom ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($evaluation->typEvaluation)
                                                        <span
                                                            class="badge bg-secondary">{{ $evaluation->typEvaluation->nom }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $evaluation->date_evaluation ? \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Statut</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i data-feather="check-circle" style="width: 3rem; height: 3rem; color: #28a745;"></i>
                        </div>
                        <p class="text-muted mb-2">Période active</p>
                        <div class="alert alert-info">
                            <small><strong>{{ $periode->libelle }}</strong> pour l'année
                                <strong>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</strong></small>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted mb-1">Évaluations</p>
                            <p class="h4 mb-0">{{ $periode->evaluations ? $periode->evaluations->count() : 0 }}</p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Moyennes matières</p>
                            <p class="h4 mb-0">{{ $periode->moyenneMatieres ? $periode->moyenneMatieres->count() : 0 }}</p>
                        </div>
                        <hr>
                        <div>
                            <p class="text-muted mb-1">Bulletins</p>
                            <p class="h4 mb-0">{{ $periode->bulletins ? $periode->bulletins->count() : 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('gestion_periodes_evaluation.edit', $periode->id) }}"
                            class="btn btn-1 btn-sm w-100 mb-2">
                            <i data-feather="edit"></i>&nbsp; Modifier
                        </a>
                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i data-feather="trash-2"></i>&nbsp; Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette période d'évaluation?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $periode->libelle }}</strong> -
                        <strong>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est irréversible.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_periodes_evaluation.destroy', $periode->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
