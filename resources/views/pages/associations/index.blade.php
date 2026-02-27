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
                            Associations Élèves - Tuteurs
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_associations.create') }}" class="btn btn-dark btn-sm me-2">
                            <i data-feather="plus"></i>&nbsp; Ajouter une association
                        </a>
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtre par Tuteur (si applicable) -->
        @if ($tuteur)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            <i data-feather="filter" class="me-2"></i>Filtré par Tuteur
                        </h6>
                        <p class="mb-0 fw-bold">{{ $tuteur->prenom . ' ' . strtoupper($tuteur->nom) }}</p>
                    </div>
                    <a href="{{ route('gestion_associations.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i data-feather="x" class="me-1"></i>Réinitialiser le filtre
                    </a>
                </div>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Total Associations</h6>
                                <h3 class="mb-0 text-primary">{{ $eleveParents->total() }}</h3>
                            </div>
                            <i data-feather="link-2" class="text-primary" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Tuteurs Principaux</h6>
                                <h3 class="mb-0 text-success">
                                    {{ $eleveParents->getCollection()->where('is_primary', true)->count() }}</h3>
                            </div>
                            <i data-feather="star" class="text-success" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Peuvent Chercher</h6>
                                <h3 class="mb-0 text-info">
                                    {{ $eleveParents->getCollection()->where('can_pickup', true)->count() }}</h3>
                            </div>
                            <i data-feather="check-circle" class="text-info" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Contact d'Urgence</h6>
                                <h3 class="mb-0 text-warning">
                                    {{ $eleveParents->getCollection()->where('emergency_contact', true)->count() }}</h3>
                            </div>
                            <i data-feather="alert-circle" class="text-warning" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des associations -->
        <div class="card border-0 shadow">
            <div class="m-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">
                        <i data-feather="list" class="me-2"></i> Liste des Associations
                    </h6>
                    <span class="badge bg-light text-primary">{{ $eleveParents->total() }}</span>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Tuteur</th>
                                <th>Lien</th>
                                <th>Principal</th>
                                <th>Peut Chercher</th>
                                <th>Urgence</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eleveParents as $association)
                                <tr>
                                    <td class="fw-bold">
                                        <a href="{{ route('gestion_eleves.show', $association->eleve->id) }}" class="text-decoration-none">
                                            {{ $association->eleve->prenom . ' ' . strtoupper($association->eleve->nom) }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $association->eleve->inscriptions()->first()?->classe?->libelle ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">
                                        <a href="{{ route('gestion_tuteurs.show', $association->tuteur->id) }}" class="text-decoration-none">
                                            {{ $association->tuteur->prenom . ' ' . strtoupper($association->tuteur->nom) }}
                                        </a>
                                    </td>
                                    <td>
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
                                        <span class="badge bg-{{ $relationshipColor[$association->tuteur->relationship] ?? 'secondary' }}">
                                            {{ $relationshipLabel[$association->tuteur->relationship] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($association->is_primary)
                                            <span class="badge bg-success">
                                                <i data-feather="star" style="width: 14px; height: 14px; display: inline;"></i> Oui
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($association->can_pickup)
                                            <span class="badge bg-success">
                                                <i data-feather="check" style="width: 14px; height: 14px; display: inline;"></i> Oui
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($association->emergency_contact)
                                            <span class="badge bg-danger">
                                                <i data-feather="alert-circle" style="width: 14px; height: 14px; display: inline;"></i> Oui
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{ route('gestion_associations.show', $association->id) }}" class="text-danger" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i data-feather="inbox" class="mb-2"></i>
                                        <p>Aucune association disponible</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $eleveParents->links() }}
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
                    <p>Êtes-vous sûr de vouloir supprimer l'association de <strong id="eleveName"></strong> ?</p>
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
        function confirmerSuppression(associationId, eleveName) {
            document.getElementById('eleveName').textContent = eleveName;
            document.getElementById('deleteForm').action = '/gestion_associations/' + associationId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
