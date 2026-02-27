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
                            <div class="page-header-icon"><i data-feather="alert-triangle"></i></div>
                            Détails de l'Incident Disciplinaire
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_incidents.index') }}" class="btn btn-light btn-sm">
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
                                <p class="text-muted mb-1">Date de l'incident</p>
                                <p class="h5">
                                    {{ $incident->date_incident ? $incident->date_incident->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Heure de l'incident</p>
                                <p class="h6">{{ $incident->heure_incident ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Élève</p>
                                <p class="h6">{{ $incident->eleve->prenom ?? '' }} {{ $incident->eleve->nom ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Signalé par</p>
                                <p class="h6">
                                    @if ($incident->reportedBy)
                                        {{ $incident->reportedBy->prenom }} {{ $incident->reportedBy->nom }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @php $gravite = $incident->{'gravité'}; @endphp
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Type</p>
                                <p class="h6"><span class="badge bg-secondary">{{ ucfirst($incident->type) }}</span></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Gravité</p>
                                <p class="h6"><span class="badge bg-warning text-dark">{{ ucfirst($gravite) }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Statut</p>
                                <p class="h6"><span
                                        class="badge bg-light text-dark">{{ str_replace('_', ' ', ucfirst($incident->statut)) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted mb-1">Description</p>
                            <p class="h6">{{ $incident->description }}</p>
                        </div>

                        @if ($incident->emplacement)
                            <div class="mb-3">
                                <p class="text-muted mb-1">Emplacement</p>
                                <p class="h6">{{ $incident->emplacement }}</p>
                            </div>
                        @endif

                        @if ($incident->temoins)
                            <div class="mb-3">
                                <p class="text-muted mb-1">Témoins</p>
                                <p class="h6">{{ $incident->temoins }}</p>
                            </div>
                        @endif

                        @if ($incident->action_pris)
                            <div class="mb-3">
                                <p class="text-muted mb-1">Actions prises</p>
                                <p class="h6">{{ $incident->action_pris }}</p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Parent notifié</p>
                                <p class="h6">
                                    @if ($incident->parent_notifie)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-danger">Non</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Date de notification</p>
                                <p class="h6">
                                    {{ $incident->date_notification ? $incident->date_notification->format('d/m/Y') : '-' }}
                                </p>
                            </div>
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
                            <a href="{{ route('gestion_incidents.edit', $incident->id) }}" class="btn btn-dark btn-sm">
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
                            <p class="h6">{{ $incident->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Modifié le</p>
                            <p class="h6">{{ $incident->updated_at->format('d/m/Y H:i') }}</p>
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
                    <p>Êtes-vous sûr de vouloir supprimer cet incident ?</p>
                    <p class="text-muted">
                        <strong>{{ $incident->eleve->prenom ?? '' }} {{ $incident->eleve->nom ?? '' }}</strong>
                        - {{ $incident->date_incident ? $incident->date_incident->format('d/m/Y') : '-' }}
                    </p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_incidents.destroy', $incident->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2" class="me-2"></i>Supprimer
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
