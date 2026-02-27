@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Détails de l'enseignement
                        </h1>
                        <p class="text-muted">{{ $enseignementMatiereClasse->enseignant->prenom }}
                            {{ $enseignementMatiereClasse->enseignant->nom }} -
                            {{ $enseignementMatiereClasse->matiere->intitule }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignement_matieres.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_enseignement_matieres.edit', $enseignementMatiereClasse->id) }}"
                            class="btn btn-dark btn-sm">
                            <i data-feather="edit"></i>&nbsp; Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-xl px-4 mt-n10 mb-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Enseignement Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Professeur</p>
                                <p class="mb-0">
                                    <strong>{{ $enseignementMatiereClasse->enseignant->prenom }}
                                        {{ $enseignementMatiereClasse->enseignant->nom }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ $enseignementMatiereClasse->enseignant->numero_employe }}</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Matière</p>
                                <p class="mb-0">
                                    <span class="badge"
                                        style="background-color: {{ $enseignementMatiereClasse->matiere->color ?? '#007bff' }}; color: #fff;">
                                        {{ $enseignementMatiereClasse->matiere->intitule }}
                                    </span>
                                    <br>
                                    <small class="text-muted">Code: {{ $enseignementMatiereClasse->matiere->code }}</small>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Classe</p>
                                <p class="mb-0"><strong>{{ $enseignementMatiereClasse->classe->nom }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Année scolaire</p>
                                <p class="mb-0"><strong>{{ $enseignementMatiereClasse->anneeScolaire->libelle }}</strong>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Heures/Semaine</p>
                                <p class="mb-0"><strong
                                        class="badge bg-info">{{ $enseignementMatiereClasse->heure_par_semaine }}h</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Devoirs Section -->
                @if ($enseignementMatiereClasse->devoirs && $enseignementMatiereClasse->devoirs->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">
                            <h5 class="mb-0">
                                <i data-feather="book" style="width: 1.2rem; height: 1.2rem;"></i>
                                Devoirs ({{ $enseignementMatiereClasse->devoirs->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Titre</th>
                                            <th>Date limite</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enseignementMatiereClasse->devoirs as $devoir)
                                            <tr>
                                                <td>{{ $devoir->titre }}</td>
                                                <td>{{ \Carbon\Carbon::parse($devoir->date_limite)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-success">Assigné</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Evaluations Section -->
                @if ($enseignementMatiereClasse->evaluations && $enseignementMatiereClasse->evaluations->count() > 0)
                    <div class="card">
                        <div class="card-header bg-light text-dark">
                            <h5 class="mb-0">
                                <i data-feather="award" style="width: 1.2rem; height: 1.2rem;"></i>
                                Evaluations ({{ $enseignementMatiereClasse->evaluations->count() }})
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
                                        @foreach ($enseignementMatiereClasse->evaluations as $evaluation)
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
                        <p class="text-muted mb-2">Enseignement actif et assigné</p>
                        <div class="alert alert-info mb-0">
                            <small><strong>{{ $enseignementMatiereClasse->enseignant->prenom }}
                                    {{ $enseignementMatiereClasse->enseignant->nom }}</strong> enseigne
                                <strong>{{ $enseignementMatiereClasse->matiere->intitule }}</strong> à la classe
                                <strong>{{ $enseignementMatiereClasse->classe->nom }}</strong></small>
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
                            <p class="text-muted mb-1">Devoirs assignés</p>
                            <p class="h4 mb-0">
                                {{ $enseignementMatiereClasse->devoirs ? $enseignementMatiereClasse->devoirs->count() : 0 }}
                            </p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Evaluations prévues</p>
                            <p class="h4 mb-0">
                                {{ $enseignementMatiereClasse->evaluations ? $enseignementMatiereClasse->evaluations->count() : 0 }}
                            </p>
                        </div>
                        <hr>
                        <div>
                            <p class="text-muted mb-1">Heures par semaine</p>
                            <p class="h4 mb-0"><span
                                    class="badge bg-info">{{ $enseignementMatiereClasse->heure_par_semaine }}h</span></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('gestion_enseignement_matieres.edit', $enseignementMatiereClasse->id) }}"
                            class="btn btn-1 btn-sm w-100 mb-2">
                            <i data-feather="edit"></i>&nbsp; Modifier les heures
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
                    <p>Êtes-vous sûr de vouloir supprimer cet enseignement?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $enseignementMatiereClasse->enseignant->prenom }}
                            {{ $enseignementMatiereClasse->enseignant->nom }}</strong> -
                        <strong>{{ $enseignementMatiereClasse->matiere->intitule }}</strong> -
                        <strong>{{ $enseignementMatiereClasse->classe->nom }}</strong> -
                        <strong>{{ $enseignementMatiereClasse->anneeScolaire->annee }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est irréversible.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_enseignement_matieres.destroy', $enseignementMatiereClasse->id) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">Supprimer</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
