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
                            <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                            Gestion des Enseignants
                        </h1>
                        <p class="text-muted">Liste de tous les enseignants de l'établissement</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignants.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter un enseignant
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

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Total Enseignants</h6>
                                <h3 class="mb-0 text-primary">{{ $enseignants->total() }}</h3>
                            </div>
                            <i data-feather="users" class="text-primary" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Actifs</h6>
                                <h3 class="mb-0 text-success">{{ $enseignants->getCollection()->where('statut', 'actif')->count() }}</h3>
                            </div>
                            <i data-feather="check-circle" class="text-success" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">En congé</h6>
                                <h3 class="mb-0 text-warning">{{ $enseignants->getCollection()->where('statut', 'en_conge')->count() }}</h3>
                            </div>
                            <i data-feather="calendar" class="text-warning" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Avec Email</h6>
                                <h3 class="mb-0 text-info">{{ $enseignants->getCollection()->whereNotNull('email')->count() }}</h3>
                            </div>
                            <i data-feather="mail" class="text-info" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des enseignants -->
        <div class="card border-0 shadow">
            <div class="m-2">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">
                        <i data-feather="list" class="me-2"></i> Liste des Enseignants
                    </h6>
                    <span class="badge bg-light text-primary">{{ $enseignants->total() }}</span>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre d'Emploi</th>
                                <th>Nom Complet</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Spécialisation</th>
                                <th>Statut</th>
                                <th>Établissement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enseignants as $enseignant)
                                <tr>
                                    <td class="fw-bold">{{ $enseignant->numero_employe }}</td>
                                    <td class="fw-bold">
                                        {{ $enseignant->prenom . ' ' . strtoupper($enseignant->nom) }}
                                    </td>
                                    <td>
                                        @if ($enseignant->email)
                                            <a href="mailto:{{ $enseignant->email }}">{{ $enseignant->email }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($enseignant->telephone)
                                            <a href="tel:{{ $enseignant->telephone }}">{{ $enseignant->telephone }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($enseignant->specialisation)
                                            <span class="badge bg-light text-dark">{{ $enseignant->specialisation }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statutLabel = [
                                                'actif' => 'Actif',
                                                'en_conge' => 'En congé',
                                                'retraite' => 'Retraite',
                                                'termine' => 'Terminé',
                                            ];
                                            $statutColor = [
                                                'actif' => 'success',
                                                'en_conge' => 'warning',
                                                'retraite' => 'info',
                                                'termine' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statutColor[$enseignant->statut] ?? 'secondary' }}">
                                            {{ $statutLabel[$enseignant->statut] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $enseignant->etablissement->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{ route('gestion_enseignants.show', $enseignant->id) }}" class="text-danger" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i data-feather="inbox" style="width: 40px; height: 40px;" class="mb-2"></i>
                                        <p>Aucun enseignant disponible</p>
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
            {{ $enseignants->links() }}
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
                    <p>Êtes-vous sûr de vouloir supprimer l'enseignant <strong id="enseignantName"></strong> ?</p>
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
        function confirmerSuppression(enseignantId, enseignantName) {
            document.getElementById('enseignantName').textContent = enseignantName;
            document.getElementById('deleteForm').action = '/gestion_enseignants/' + enseignantId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
