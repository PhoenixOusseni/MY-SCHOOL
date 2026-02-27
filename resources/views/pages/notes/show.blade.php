@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="eye"></i></div>
                            Détails de la note
                        </h1>
                        <p class="text-muted">Informations complètes sur la note</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_evaluations.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
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

        <div class="row">
            <div class="col-lg-8">
                <!-- Informations de la note -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <i data-feather="edit"></i> Informations de la note
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Élève</h6>
                                <p class="mb-0">
                                    <strong>{{ $note->eleve->nom }} {{ $note->eleve->prenom }}</strong><br>
                                    <small class="text-muted">{{ $note->eleve->registration_number }}</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Évaluation</h6>
                                <p class="mb-0">
                                    <strong>{{ $note->evaluation->titre }}</strong><br>
                                    <small class="text-muted">{{ $note->evaluation->date_examen }}</small>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Classe</h6>
                                <p class="mb-0">{{ $note->evaluation->enseignementMatiereClasse->classe->nom ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Matière</h6>
                                <p class="mb-0">
                                    {{ $note->evaluation->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-2">Note obtenue</h6>
                                @if ($note->is_absent)
                                    <span class="badge bg-warning fs-5">Absent</span>
                                @else
                                    <p class="mb-0 fs-4"><strong>{{ $note->score ?? 'N/A' }}</strong></p>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-2">Note maximale</h6>
                                <p class="mb-0 fs-4"><strong>{{ $note->max_score }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-2">Pourcentage</h6>
                                @if ($note->percentage)
                                    <span class="badge bg-{{ $note->percentage >= 50 ? 'success' : 'danger' }} fs-5">
                                        {{ number_format($note->percentage, 2) }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-2">Statut</h6>
                                @if ($note->is_absent)
                                    @if ($note->absence_justified)
                                        <span class="badge bg-info">Absent justifié</span>
                                    @else
                                        <span class="badge bg-warning">Absent non justifié</span>
                                    @endif
                                @else
                                    <span class="badge bg-success">Présent</span>
                                @endif
                            </div>
                        </div>

                        @if ($note->comment)
                            <hr>
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Commentaire</h6>
                                <p class="mb-0">{{ $note->comment }}</p>
                            </div>
                        @endif

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Saisi par</h6>
                                <p class="mb-0">
                                    @if ($note->enteredBy)
                                        {{ $note->enteredBy->nom }} {{ $note->enteredBy->prenom }}
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Date de saisie</h6>
                                <p class="mb-0">{{ $note->entered_at ? $note->entered_at->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Informations complémentaires -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <i data-feather="calendar"></i> Dates
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Créée le</h6>
                            <p class="mb-0">{{ $note->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Dernière modification</h6>
                            <p class="mb-0">{{ $note->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <i data-feather="bar-chart"></i> Statistiques
                    </div>
                    <div class="card-body">
                        @if (!$note->is_absent && $note->percentage)
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Performance</h6>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-{{ $note->percentage >= 50 ? 'success' : 'danger' }}"
                                        role="progressbar" style="width: {{ $note->percentage }}%;"
                                        aria-valuenow="{{ $note->percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($note->percentage, 2) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Appréciation</h6>
                                <p class="mb-0">
                                    @if ($note->percentage >= 90)
                                        <span class="badge bg-success">Excellent</span>
                                    @elseif($note->percentage >= 80)
                                        <span class="badge bg-success">Très bien</span>
                                    @elseif($note->percentage >= 70)
                                        <span class="badge bg-info">Bien</span>
                                    @elseif($note->percentage >= 60)
                                        <span class="badge bg-primary">Assez bien</span>
                                    @elseif($note->percentage >= 50)
                                        <span class="badge bg-warning">Passable</span>
                                    @else
                                        <span class="badge bg-danger">Insuffisant</span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <p class="text-muted text-center">Pas de statistiques disponibles</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="settings" class="me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_notes.edit', $note->id) }}" class="btn btn-dark btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i data-feather="trash-2" class="me-2"></i>Supprimer
                            </button>
                        </div>
                    </div>

                    <!-- Modal de confirmation de suppression -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5> <button
                                        type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer cette note ? Cette action est irréversible.
                                </div>
                                <div class="m-3">
                                    <form action="{{ route('gestion_notes.destroy', $note->id) }}" method="POST"
                                        class="d-inline"> @csrf @method('DELETE') <button type="submit"
                                            class="btn btn-danger">
                                            <i data-feather="trash-2" class="me-2"></i>&nbsp; Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
