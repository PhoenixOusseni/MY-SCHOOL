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
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Gestion des Matières
                        </h1>
                        <p class="page-header-subtitle">
                            Gérer les matières de votre établissement, ajouter de nouvelles matières, et consulter les détails de chaque matière.
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_matieres.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter une matière
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
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

        <!-- Statistics -->
        <div class="row mb-4">
            <!-- Total Matières -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Matières</h6>
                            <h3 class="mb-0">{{ $matieres->total() }}</h3>
                        </div>
                        <div>
                            <i data-feather="book" style="width: 48px; height: 48px; color: #007bff;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matières Actives -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Matières Actives</h6>
                            <h3 class="mb-0">{{ $matieres->where('is_active', true)->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="check-circle" style="width: 48px; height: 48px; color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matières Inactives -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Matières Inactives</h6>
                            <h3 class="mb-0">{{ $matieres->where('is_active', false)->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="x-circle" style="width: 48px; height: 48px; color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Page Actuelle</h6>
                            <h3 class="mb-0">{{ $matieres->currentPage() }} / {{ $matieres->lastPage() }}</h3>
                        </div>
                        <div>
                            <i data-feather="layers" style="width: 48px; height: 48px; color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i data-feather="list" class="me-2"></i>Liste des Matières
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Intitulé</th>
                                <th>Code</th>
                                <th>Couleur</th>
                                <th>Établissement</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($matieres as $matiere)
                                <tr>
                                    <td>
                                        <strong>{{ $matiere->intitule }}</strong>
                                    </td>
                                    <td>
                                        @if ($matiere->code)
                                            <span class="badge bg-light text-dark">{{ $matiere->code }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: inline-flex; align-items: center; gap: 8px;">
                                            <div style="width: 24px; height: 24px; background-color: {{ $matiere->color ?? '#007bff' }}; border-radius: 4px; border: 1px solid #ddd;"></div>
                                            <span class="small text-muted">{{ $matiere->color ?? '#007bff' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">{{ $matiere->etablissement->nom ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if ($matiere->is_active)
                                            <span class="badge bg-success">
                                                <i data-feather="check" style="width: 14px; height: 14px;"></i> Actif
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i data-feather="x" style="width: 14px; height: 14px;"></i> Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{ route('gestion_matieres.show', $matiere->id) }}" class="text-danger" title="Voir les détails">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Delete -->
                                <div class="modal fade" id="deleteModal{{ $matiere->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i data-feather="alert-triangle" class="me-2"></i>Confirmation de suppression
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer cette matière ?</p>
                                                <p class="text-muted"><strong>{{ $matiere->intitule }}</strong></p>
                                                <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('gestion_matieres.destroy', $matiere->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 40px; height: 40px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                        Aucune matière trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <nav aria-label="Page navigation">
                    {{ $matieres->links() }}
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
