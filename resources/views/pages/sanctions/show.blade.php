@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="shield"></i></div>
                            Détails de la Sanction
                        </h1>
                        <p class="text-muted">Consultez les informations détaillées de cette sanction disciplinaire</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_sanctions.index') }}" class="btn btn-dark">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations Générales
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Élève</p>
                                <p class="h5">{{ $sanction->eleve->prenom ?? '' }} {{ $sanction->eleve->nom ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Type</p>
                                <p class="h6"><span class="badge bg-secondary">{{ ucfirst($sanction->type) }}</span></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Date début</p>
                                <p class="h6">{{ $sanction->date_debut ? $sanction->date_debut->format('d/m/Y') : '-' }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Date fin</p>
                                <p class="h6">{{ $sanction->date_fin ? $sanction->date_fin->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Durée</p>
                                <p class="h6">{{ $sanction->duree ? $sanction->duree . ' jours' : '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Statut</p>
                                <p class="h6">
                                    @if ($sanction->status === 'completed')
                                        <span class="badge bg-success">Terminée</span>
                                    @elseif($sanction->status === 'active')
                                        <span class="badge bg-primary">Active</span>
                                    @elseif($sanction->status === 'cancelled')
                                        <span class="badge bg-danger">Annulée</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Imposée par</p>
                                <p class="h6">
                                    @if ($sanction->imposedBy)
                                        {{ $sanction->imposedBy->prenom }} {{ $sanction->imposedBy->nom }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted mb-1">Incident lié</p>
                            <p class="h6">
                                @if ($sanction->incidentDisciplinaire)
                                    #{{ $sanction->incidentDisciplinaire->id }} -
                                    {{ $sanction->incidentDisciplinaire->type }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted mb-1">Description</p>
                            <p class="h6">{{ $sanction->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="settings" class="me-2"></i>Actions
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_sanctions.edit', $sanction->id) }}" class="btn btn-dark btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i data-feather="trash-2" class="me-2"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="clock" class="me-2"></i>Historique
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body small">
                        <div class="mb-2">
                            <p class="text-muted mb-1">Créé le</p>
                            <p class="h6">{{ $sanction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Modifié le</p>
                            <p class="h6">{{ $sanction->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">
                        <i data-feather="alert-triangle" class="me-2"></i>Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette sanction ?</p>
                    <p class="text-muted">
                        <strong>{{ $sanction->eleve->prenom ?? '' }} {{ $sanction->eleve->nom ?? '' }}</strong>
                    </p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_sanctions.destroy', $sanction->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2" class="me-2"></i>&nbsp;Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
