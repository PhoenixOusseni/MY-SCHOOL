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
                            <div class="page-header-icon"><i data-feather="link-2"></i></div>
                            Détails de l'Association
                        </h1>
                        <p class="page-header-subtitle">
                            Visualisez les détails de l'association entre l'élève et son tuteur, y compris les rôles et les permissions.
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <div class="btn-group gap-2">
                            <a href="{{ route('gestion_associations.index') }}" class="btn btn-light btn-sm">
                                <i data-feather="arrow-left"></i>&nbsp; Retour
                            </a>
                            <a href="{{ route('gestion_associations.edit', $eleveParent->id) }}"
                                class="btn btn-dark btn-sm">
                                <i data-feather="edit"></i>&nbsp; Modifier
                            </a>
                            <form action="{{ route('gestion_associations.destroy', $eleveParent->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmerSuppression({{ $eleveParent->id }})">
                                    <i data-feather="trash-2"></i>&nbsp; Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Messages -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Colonne Gauche: Élèves Associés -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="users" class="me-2"></i>Élève(s) Associé(s)
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-0">
                        @if ($elevesAssocies->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($elevesAssocies as $eleve)
                                    <a href="{{ route('gestion_eleves.show', $eleve->id) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-primary fw-bold">
                                                    {{ $eleve->prenom . ' ' . strtoupper($eleve->nom) }}
                                                </h6>
                                                <small class="text-muted d-block">
                                                    Matricule: {{ $eleve->registration_number }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    Naissance:
                                                    {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}
                                                </small>
                                            </div>
                                            <div class="text-end ms-2">
                                                <span class="badge bg-light text-dark d-block mb-2">
                                                    {{ $eleve->inscriptions()->first()?->classe?->libelle ?? 'Sans classe' }}
                                                </span>
                                                <span
                                                    class="badge {{ $eleve->genre == 'M' ? 'bg-info' : 'bg-success' }} d-block">
                                                    {{ $eleve->genre == 'M' ? 'M' : 'F' }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i data-feather="inbox" style="width: 40px; height: 40px;" class="mb-2"></i>
                                <p class="mb-0">Aucun élève associé</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne Droite: Tuteur -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="user-check" class="me-2"></i>Tuteur
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Nom Complet</p>
                            <a href="{{ route('gestion_tuteurs.show', $eleveParent->tuteur->id) }}"
                                class="fw-bold text-decoration-none text-success">
                                {{ $eleveParent->tuteur->prenom . ' ' . strtoupper($eleveParent->tuteur->nom) }}
                            </a>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted small mb-1">Lien de Parenté</p>
                            <p>
                                @php
                                    $relationshipLabel = [
                                        'pere' => 'Père',
                                        'mere' => 'Mère',
                                        'tuteur' => 'Tuteur',
                                        'autre' => 'Autre',
                                    ];
                                    $relationshipColor = [
                                        'pere' => 'info',
                                        'mere' => 'success',
                                        'tuteur' => 'primary',
                                        'autre' => 'secondary',
                                    ];
                                @endphp
                                <span
                                    class="badge bg-{{ $relationshipColor[$eleveParent->tuteur->relationship] ?? 'secondary' }}">
                                    {{ $relationshipLabel[$eleveParent->tuteur->relationship] ?? 'N/A' }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted small mb-1">Téléphone</p>
                            @if ($eleveParent->tuteur->telephone)
                                <p class="fw-bold">
                                    <a href="tel:{{ $eleveParent->tuteur->telephone }}" class="text-decoration-none">
                                        {{ $eleveParent->tuteur->telephone }}
                                    </a>
                                </p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>

                        <div class="mb-0">
                            <p class="text-muted small mb-1">Email</p>
                            @if ($eleveParent->tuteur->email)
                                <p class="fw-bold">
                                    <a href="mailto:{{ $eleveParent->tuteur->email }}" class="text-decoration-none">
                                        {{ $eleveParent->tuteur->email }}
                                    </a>
                                </p>
                            @else
                                <p class="text-muted">—</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres de l'Association -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="settings" class="me-2"></i>Paramètres de l'Association
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 border-end">
                                    <h6 class="mb-2">
                                        <i data-feather="star" class="me-2" style="width: 24px; height: 24px;"></i>Tuteur
                                        Principal
                                    </h6>
                                    @if ($eleveParent->is_primary)
                                        <span class="badge bg-success" style="font-size: 14px;">
                                            <i data-feather="check" style="width: 16px; height: 16px; display: inline;"></i>
                                            OUI
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark" style="font-size: 14px;">NON</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center p-3 border-end">
                                    <h6 class="mb-2">
                                        <i data-feather="check-circle" class="me-2"
                                            style="width: 24px; height: 24px;"></i>Peut Chercher
                                    </h6>
                                    @if ($eleveParent->can_pickup)
                                        <span class="badge bg-success" style="font-size: 14px;">
                                            <i data-feather="check"
                                                style="width: 16px; height: 16px; display: inline;"></i> OUI
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark" style="font-size: 14px;">NON</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <h6 class="mb-2">
                                        <i data-feather="alert-circle" class="me-2"
                                            style="width: 24px; height: 24px;"></i>Contact d'Urgence
                                    </h6>
                                    @if ($eleveParent->emergency_contact)
                                        <span class="badge bg-danger" style="font-size: 14px;">
                                            <i data-feather="check"
                                                style="width: 16px; height: 16px; display: inline;"></i> OUI
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark" style="font-size: 14px;">NON</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="calendar" class="me-2"></i>Historique
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Créée le</p>
                                <p class="fw-bold">{{ $eleveParent->created_at->format('d/m/Y à H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Modifiée le</p>
                                <p class="fw-bold">{{ $eleveParent->updated_at->format('d/m/Y à H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i data-feather="alert-triangle" class="me-2"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette association ?</p>
                    <p class="text-muted">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
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
        function confirmerSuppression(associationId) {
            document.getElementById('deleteForm').action = '/gestion_associations/' + associationId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
