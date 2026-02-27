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
                            <div class="page-header-icon"><i class="fas fa-dollar-sign"></i></div>
                            Frais de Scolarité
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_frais_scolarite.create') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-plus me-1"></i>&nbsp; Ajouter un frais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Frais</h6>
                            <h3 class="mb-0">{{ $frais->total() }}</h3>
                        </div>
                        <div><i class="fas fa-file-invoice-dollar fa-3x text-primary opacity-50"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Obligatoires</h6>
                            <h3 class="mb-0">{{ $frais->where('est_obligatoire', true)->count() }}</h3>
                        </div>
                        <div><i class="fas fa-exclamation-circle fa-3x text-danger opacity-50"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Facultatifs</h6>
                            <h3 class="mb-0">{{ $frais->where('est_obligatoire', false)->count() }}</h3>
                        </div>
                        <div><i class="fas fa-check-circle fa-3x text-success opacity-50"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Page Actuelle</h6>
                            <h3 class="mb-0">{{ $frais->currentPage() }} / {{ $frais->lastPage() }}</h3>
                        </div>
                        <div><i class="fas fa-layer-group fa-3x text-info opacity-50"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des Frais de Scolarité
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Libellé</th>
                                <th>Montant</th>
                                <th>Fréquence</th>
                                <th>Obligatoire</th>
                                <th>Établissement</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($frais as $index => $frai)
                                <tr>
                                    <td>{{ $frais->firstItem() + $index }}</td>
                                    <td><strong>{{ $frai->libelle }}</strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-bold">
                                            {{ number_format($frai->montant, 0, ',', ' ') }} {{ $frai->devise }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $frequenceColors = [
                                                'unique' => 'secondary',
                                                'mensuelle' => 'info',
                                                'trimestrielle' => 'primary',
                                                'annuelle' => 'success',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $frequenceColors[$frai->frequence] ?? 'secondary' }}">
                                            {{ ucfirst($frai->frequence) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($frai->est_obligatoire)
                                            <span class="badge bg-danger">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                    <td>{{ $frai->etablissement->nom ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('gestion_frais_scolarite.show', $frai->id) }}"
                                                class="btn btn-sm btn-1" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('gestion_frais_scolarite.edit', $frai->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $frai->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $frai->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer ce frais ?</p>
                                                <div class="alert alert-warning">
                                                    <strong>{{ $frai->libelle }}</strong> —
                                                    {{ number_format($frai->montant, 0, ',', ' ') }} {{ $frai->devise }}
                                                </div>
                                                <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('gestion_frais_scolarite.destroy', $frai->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-2 d-block opacity-50"></i>
                                        Aucun frais de scolarité trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <nav>{{ $frais->links() }}</nav>
            </div>
        </div>
    </div>
@endsection
