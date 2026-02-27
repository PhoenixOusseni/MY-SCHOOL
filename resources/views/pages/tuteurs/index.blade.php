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
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Gestion des Tuteurs
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_tuteurs.create') }}" class="btn btn-dark me-2">
                            <i data-feather="plus"></i>&nbsp; Ajouter un tuteur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Total Tuteurs</h6>
                                <h3 class="mb-0 text-primary">{{ $tuteurs->total() }}</h3>
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
                                <h6 class="text-muted mb-0">Pères</h6>
                                <h3 class="mb-0 text-info">
                                    {{ $tuteurs->getCollection()->where('relationship', 'pere')->count() }}</h3>
                            </div>
                            <i data-feather="user" class="text-info" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">Mères</h6>
                                <h3 class="mb-0 text-success">
                                    {{ $tuteurs->getCollection()->where('relationship', 'mere')->count() }}</h3>
                            </div>
                            <i data-feather="heart" class="text-success" style="width: 40px; height: 40px;"></i>
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
                                <h3 class="mb-0 text-warning">
                                    {{ $tuteurs->getCollection()->whereNotNull('email')->count() }}</h3>
                            </div>
                            <i data-feather="mail" class="text-warning" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des tuteurs -->
        <div class="card border-0 shadow">
            <div class="m-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">
                        <i data-feather="list" class="me-2"></i> Liste des Tuteurs
                    </h6>
                    <span class="badge bg-light text-primary">{{ $tuteurs->total() }}</span>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Nom Complet</th>
                                <th>Lien de Parenté</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Profession</th>
                                <th>Élèves Rattachés</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tuteurs as $tuteur)
                                <tr>
                                    <td class="fw-bold">
                                        {{ $tuteur->prenom . ' ' . strtoupper($tuteur->nom) }}
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
                                        <span
                                            class="badge bg-{{ $relationshipColor[$tuteur->relationship] ?? 'secondary' }}">
                                            {{ $relationshipLabel[$tuteur->relationship] ?? 'Autre' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($tuteur->telephone)
                                            <a href="tel:{{ $tuteur->telephone }}">{{ $tuteur->telephone }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($tuteur->email)
                                            <a href="mailto:{{ $tuteur->email }}">{{ $tuteur->email }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($tuteur->profession)
                                            {{ $tuteur->profession }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $tuteur->eleves()->count() }} élève(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{ route('gestion_tuteurs.show', $tuteur->id) }}"
                                                class="text-danger" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i data-feather="inbox" style="width: 40px; height: 40px;" class="mb-2"></i>
                                        <p>Aucun tuteur disponible</p>
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
            {{ $tuteurs->links() }}
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
                    <p>Êtes-vous sûr de vouloir supprimer le tuteur <strong id="tuteurName"></strong> ?</p>
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
        function confirmerSuppression(tuteurId, tuteurName) {
            document.getElementById('tuteurName').textContent = tuteurName;
            document.getElementById('deleteForm').action = '/gestion_tuteurs/' + tuteurId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Initialiser les icônes Feather
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
