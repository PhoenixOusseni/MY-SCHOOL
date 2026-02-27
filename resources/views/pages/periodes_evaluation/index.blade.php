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
                            Gestion des Périodes d'Évaluation
                        </h1>
                        <p class="text-muted">Gérez les périodes de notation et d'évaluation</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_periodes_evaluation.create') }}" class="btn btn-dark">
                            <i data-feather="plus"></i>&nbsp; Ajouter une période
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-xl px-4 mt-n10">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Périodes total</p>
                            <h3 class="mb-0">{{ $periodes->total() }}</h3>
                        </div>
                        <i data-feather="calendar" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Années actives</p>
                            <h3 class="mb-0">{{ $anneesScolaires->count() }}</h3>
                        </div>
                        <i data-feather="layers" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Trimestres</p>
                            <h3 class="mb-0">{{ $periodes->where('type', 'trimester')->count() }}</h3>
                        </div>
                        <i data-feather="bar-chart-2" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Page actuelle</p>
                            <h3 class="mb-0">{{ $periodes->count() }}</h3>
                        </div>
                        <i data-feather="list" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                @if ($periodes->count() > 0)
                    <div class="table-responsive">
                        <table id="datatablesSimple">
                            <thead class="table-light">
                                <tr>
                                    <th>Libellé</th>
                                    <th>Type</th>
                                    <th>Année scolaire</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Ordre</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($periodes as $periode)
                                    <tr>
                                        <td>
                                            <strong>{{ $periode->libelle }}</strong>
                                        </td>
                                        <td>
                                            @if ($periode->type === 'trimester')
                                                <span class="badge bg-primary">Trimestre</span>
                                            @elseif($periode->type === 'semester')
                                                <span class="badge bg-info">Semestre</span>
                                            @elseif($periode->type === 'quarter')
                                                <span class="badge bg-secondary">Quart</span>
                                            @endif
                                        </td>
                                        <td>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($periode->date_debut)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($periode->date_fin)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($periode->order_index)
                                                <span class="badge bg-light text-dark">{{ $periode->order_index }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('gestion_periodes_evaluation.show', $periode->id) }}"
                                                class="text-danger" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $periode->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Confirmation de suppression</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer cette période d'évaluation?</p>
                                                    <div class="alert alert-info">
                                                        <strong>{{ $periode->libelle }}</strong> -
                                                        <strong>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <form
                                                        action="{{ route('gestion_periodes_evaluation.destroy', $periode->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $periodes->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i data-feather="inbox" style="width: 3rem; height: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p class="mb-0">Aucune période d'évaluation trouvée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
