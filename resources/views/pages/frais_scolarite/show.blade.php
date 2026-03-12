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
                            <div class="page-header-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            {{ $frai->libelle }}
                        </h1>
                        <div class="page-header-subtitle">Détails du frais de scolarité</div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_frais_scolarite.index') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Info principale -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Libellé</p>
                                <p class="mb-0"><strong>{{ $frai->libelle }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Établissement</p>
                                <p class="mb-0">
                                    @if ($frai->etablissement)
                                        <a href="{{ route('gestion_etablissements.show', $frai->etablissement->id) }}">
                                            {{ $frai->etablissement->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Montant</p>
                                <p class="mb-0"><strong>{{ number_format($frai->montant, 2, ',', ' ') }} {{ $frai->devise }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Fréquence</p>
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
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Obligatoire</p>
                                @if ($frai->est_obligatoire)
                                    <span class="badge bg-danger">Oui</span>
                                @else
                                    <span class="badge bg-secondary">Non</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paiements liés -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>Paiements associés
                        </h5>
                        <span class="badge bg-secondary">{{ $frai->paiements->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if ($frai->paiements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant payé</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($frai->paiements as $paiement)
                                            <tr>
                                                <td>{{ $paiement->created_at->format('d/m/Y') }}</td>
                                                <td>{{ number_format($paiement->montant ?? 0, 0, ',', ' ') }} {{ $frai->devise }}</td>
                                                <td>
                                                    <span class="badge bg-success">Payé</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-2 d-block opacity-50"></i>
                                Aucun paiement enregistré pour ce frais
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Dates -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-flag me-2"></i>Résumé</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted small mb-1">Créé le</h6>
                            <p class="mb-0">{{ $frai->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted small mb-1">Modifié le</h6>
                            <p class="mb-0">{{ $frai->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <hr>
                        <div class="text-center">
                            <span class="badge bg-{{ $frequenceColors[$frai->frequence] ?? 'secondary' }} fs-6 px-3 py-2">
                                {{ ucfirst($frai->frequence) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('gestion_frais_scolarite.edit', $frai->id) }}"
                            class="btn btn-1 btn-sm w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>&nbsp; Modifier
                        </a>
                        <button type="button" class="btn btn-danger btn-sm w-100"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt me-1"></i>&nbsp; Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce frais ?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $frai->libelle }}</strong><br>
                        <small>{{ number_format($frai->montant, 0, ',', ' ') }} {{ $frai->devise }} — {{ ucfirst($frai->frequence) }}</small>
                    </div>
                    <p class="text-danger small">Cette action est irréversible.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_frais_scolarite.destroy', $frai->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">
                            <i class="fas fa-trash-alt me-1"></i>&nbsp; Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
