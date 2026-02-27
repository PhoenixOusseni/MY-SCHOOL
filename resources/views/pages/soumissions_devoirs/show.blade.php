@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                            Détails de la soumission
                        </h1>
                        <p class="text-muted">{{ $soumission->eleve->prenom ?? 'N/A' }}
                            {{ $soumission->eleve->nom ?? 'N/A' }} -
                            {{ $soumission->devoir->title ?? 'N/A' }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_devoirs.index') }}" class="btn btn-light">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_soumissions.edit', $soumission->id) }}" class="btn btn-dark">
                            <i data-feather="edit"></i>&nbsp; Modifier
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
                <!-- Soumission Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Élève</p>
                                <p class="mb-0">
                                    <strong>{{ $soumission->eleve->prenom ?? 'N/A' }}
                                        {{ $soumission->eleve->nom ?? 'N/A' }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Devoir</p>
                                <p class="mb-0">
                                    <strong>{{ $soumission->devoir->title ?? 'N/A' }}</strong>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Statut</p>
                                <p class="mb-0">
                                    @if ($soumission->status === 'noté')
                                        <span class="badge bg-success">Noté</span>
                                    @elseif($soumission->status === 'soumis')
                                        <span class="badge bg-info">Soumis</span>
                                    @elseif($soumission->status === 'en retard')
                                        <span class="badge bg-warning text-dark">En retard</span>
                                    @else
                                        <span class="badge bg-secondary">En cours</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Note</p>
                                <p class="mb-0">
                                    @if ($soumission->score !== null)
                                        <strong
                                            class="badge bg-light text-dark">{{ $soumission->score }}/{{ $soumission->devoir->note_max ?? '20' }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Date soumission</p>
                                <p class="mb-0">
                                    @if ($soumission->date_submission)
                                        {{ \Carbon\Carbon::parse($soumission->date_submission)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                @if ($soumission->content)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Contenu / Observations</h5>
                        </div>
                        <div class="card-body">
                            {{ $soumission->content }}
                        </div>
                    </div>
                @endif

                <!-- Feedback -->
                @if ($soumission->feedback)
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">
                            <h5 class="mb-0"><i data-feather="message-square"></i> Feedback / Commentaires</h5>
                        </div>
                        <div class="card-body">
                            {{ $soumission->feedback }}
                        </div>
                    </div>
                @endif

                <!-- Grading Info -->
                @if ($soumission->status === 'noté')
                    <div class="card">
                        <div class="card-header bg-light text-dark">
                            <h5 class="mb-0">Informations d'évaluation</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Évalué par</p>
                                    <p class="mb-3">
                                        @if ($soumission->gradedBy)
                                            <strong>{{ $soumission->gradedBy->prenom }}
                                                {{ $soumission->gradedBy->nom }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Date d'évaluation</p>
                                    <p class="mb-3">
                                        @if ($soumission->graded_at)
                                            {{ \Carbon\Carbon::parse($soumission->graded_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
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
                            @if ($soumission->status === 'noté')
                                <i data-feather="check-circle" style="width: 3rem; height: 3rem; color: #28a745;"></i>
                            @elseif($soumission->status === 'en retard')
                                <i data-feather="alert-circle" style="width: 3rem; height: 3rem; color: #ffc107;"></i>
                            @else
                                <i data-feather="send" style="width: 3rem; height: 3rem; color: #0dcaf0;"></i>
                            @endif
                        </div>
                        <p class="text-muted mb-2">
                            @if ($soumission->status === 'noté')
                                Soumission notée
                            @elseif($soumission->status === 'soumis')
                                Soumission en attente d'évaluation
                            @elseif($soumission->status === 'en retard')
                                Soumission en retard
                            @else
                                Soumission en cours
                            @endif
                        </p>
                        @if ($soumission->score !== null)
                            <div class="alert alert-info mb-0">
                                <strong
                                    class="h4">{{ $soumission->score }}/{{ $soumission->devoir->note_max ?? '20' }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Classe</p>
                            <p class="mb-2">
                                <strong>{{ $soumission->devoir->enseignementMatiereClasse->classe->nom ?? 'N/A' }}</strong>
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Matière</p>
                            <p class="mb-2">
                                <span class="badge"
                                    style="background-color: {{ $soumission->devoir->enseignementMatiereClasse->matiere->color ?? '#007bff' }}; color: #fff;">
                                    {{ $soumission->devoir->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Pièce jointe</p>
                            @if ($soumission->attachment)
                                <p class="mb-0">
                                    <a href="{{ asset('storage/' . $soumission->attachment) }}" target="_blank"
                                        class="btn btn-sm btn-warning">
                                        <i data-feather="download"></i> Télécharger
                                    </a>
                                </p>
                            @else
                                <p class="text-muted mb-0">Aucune</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('gestion_soumissions.edit', $soumission->id) }}"
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
                    <p>Êtes-vous sûr de vouloir supprimer cette soumission?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $soumission->eleve->prenom ?? 'N/A' }} {{ $soumission->eleve->nom ?? 'N/A' }}</strong>
                        -
                        <strong>{{ $soumission->devoir->title ?? 'N/A' }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est irréversible.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_soumissions.destroy', $soumission->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">Supprimer</button>
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
