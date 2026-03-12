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
                            <div class="page-header-icon"><i data-feather="clock"></i></div>
                            Gestion des Retards
                        </h1>
                        <p class="text-white">Gérer les retards des élèves</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_retards.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter un retard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
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

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Retards</h6>
                            <h3 class="mb-0">{{ $retards->total() }}</h3>
                        </div>
                        <div>
                            <i data-feather="clock" style="width: 48px; height: 48px; color: #fd7e14;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Justifiés</h6>
                            <h3 class="mb-0">{{ $retards->where('is_justified', true)->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="check-circle" style="width: 48px; height: 48px; color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Non justifiés</h6>
                            <h3 class="mb-0">{{ $retards->where('is_justified', false)->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="x-circle" style="width: 48px; height: 48px; color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Page Actuelle</h6>
                            <h3 class="mb-0">{{ $retards->currentPage() }} / {{ $retards->lastPage() }}</h3>
                        </div>
                        <div>
                            <i data-feather="layers" style="width: 48px; height: 48px; color: #0d6efd;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i data-feather="list" class="me-2"></i>Liste des Retards
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Matière</th>
                                <th>Heure d'arrivée</th>
                                <th>Durée (min)</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($retards as $retard)
                                <tr>
                                    <td><strong>{{ $retard->date ? $retard->date->format('d/m/Y') : '-' }}</strong></td>
                                    <td>{{ $retard->eleve->prenom ?? '' }} {{ $retard->eleve->nom ?? 'N/A' }}</td>
                                    <td><span class="badge bg-secondary">{{ $retard->classe->nom ?? 'N/A' }}</span></td>
                                    <td>
                                        @if ($retard->matiere)
                                            <span class="badge bg-light text-dark">{{ $retard->matiere->intitule }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $retard->heure_arrivee ?? '-' }}</td>
                                    <td>{{ $retard->duree ?? '-' }}</td>
                                    <td>
                                        @if ($retard->is_justified)
                                            <span class="badge bg-success">Justifié</span>
                                        @else
                                            <span class="badge bg-danger">Non justifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{ route('gestion_retards.show', $retard->id) }}" class="text-danger" title="Voir les détails">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $retard->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i data-feather="alert-triangle" class="me-2"></i>Confirmation de suppression
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer ce retard ?</p>
                                                <p class="text-muted">
                                                    <strong>{{ $retard->eleve->prenom ?? '' }} {{ $retard->eleve->nom ?? '' }}</strong>
                                                    - {{ $retard->date ? $retard->date->format('d/m/Y') : '-' }}
                                                </p>
                                                <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('gestion_retards.destroy', $retard->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 40px; height: 40px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                        Aucun retard trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <nav aria-label="Page navigation">
                    {{ $retards->links() }}
                </nav>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
