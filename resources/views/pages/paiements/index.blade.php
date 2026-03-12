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
                            <div class="page-header-icon"><i class="fas fa-money-bill-wave"></i></div>
                            Gestion des Paiements
                        </h1>
                        <p class="text-muted">Gérez les paiements de vos élèves</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_paiements.create') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-plus me-1"></i>&nbsp; Enregistrer un paiement
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
                            <h6 class="text-muted mb-2">Total Paiements</h6>
                            <h3 class="mb-0">{{ $paiements->total() }}</h3>
                        </div>
                        <div><i class="fas fa-money-bill-wave fa-3x text-primary opacity-50"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Terminés</h6>
                            <h3 class="mb-0">{{ $paiements->where('status', 'Terminé')->count() }}</h3>
                        </div>
                        <div><i class="fas fa-check-circle fa-3x text-success opacity-50"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">En attente</h6>
                            <h3 class="mb-0">{{ $paiements->where('status', 'En attente')->count() }}</h3>
                        </div>
                        <div><i class="fas fa-clock fa-3x text-warning opacity-50"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Annulés / Remboursés</h6>
                            <h3 class="mb-0">{{ $paiements->whereIn('status', ['Annulé', 'Remboursé'])->count() }}</h3>
                        </div>
                        <div><i class="fas fa-times-circle fa-3x text-danger opacity-50"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des Paiements
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Élève</th>
                                <th>Frais</th>
                                <th>Montant</th>
                                <th>Reste à payer</th>
                                <th>Mode paie</th>
                                <th>Statut</th>
                                <th>Année scolaire</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paiements as $index => $paiement)
                                @php
                                    $statusColors = [
                                        'Terminé'   => 'success',
                                        'En attente' => 'warning',
                                        'Annulé'    => 'danger',
                                        'Remboursé' => 'secondary',
                                    ];
                                    $methodeIcons = [
                                        'Especes'      => 'fa-money-bill',
                                        'cheque'       => 'fa-file-alt',
                                        'virement'     => 'fa-exchange-alt',
                                        'mobile_money' => 'fa-mobile-alt',
                                        'carte'        => 'fa-credit-card',
                                    ];
                                    $methodeLabels = [
                                        'Especes'      => 'Espèces',
                                        'cheque'       => 'Chèque',
                                        'virement'     => 'Virement',
                                        'mobile_money' => 'Mobile Money',
                                        'carte'        => 'Carte',
                                    ];
                                @endphp
                                <tr>
                                    <td><strong>{{ $paiement->date_paiement->format('d/m/Y') }}</strong></td>
                                    <td>
                                        {{ $paiement->eleve->prenom ?? '' }}
                                        {{ $paiement->eleve->nom ?? 'N/A' }} <br>
                                        <small class="text-muted">{{ $paiement->eleve->registration_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $paiement->fraiScolarite->libelle ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-bold">
                                            {{ number_format($paiement->montant, 0, ',', ' ') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-bold">
                                            {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas {{ $methodeIcons[$paiement->methode_paiement] ?? 'fa-question' }} me-1 text-muted"></i>
                                        {{ $methodeLabels[$paiement->methode_paiement] ?? $paiement->methode_paiement }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColors[$paiement->status] ?? 'secondary' }}">
                                            {{ $paiement->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $paiement->anneeScolaire->libelle ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('gestion_paiements.show', $paiement->id) }}"
                                                class="btn btn-sm btn-1" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $paiement->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Supprimer ce paiement ?</p>
                                                <div class="alert alert-warning">
                                                    <strong>{{ $paiement->eleve->prenom ?? '' }} {{ $paiement->eleve->nom ?? '' }}</strong> —
                                                    {{ number_format($paiement->montant, 0, ',', ' ') }} —
                                                    {{ $paiement->date_paiement->format('d/m/Y') }}
                                                </div>
                                                <p class="text-danger small">Cette action est irréversible.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('gestion_paiements.destroy', $paiement->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-2 d-block opacity-50"></i>
                                        Aucun paiement enregistré
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <nav>{{ $paiements->links() }}</nav>
            </div>
        </div>
    </div>
@endsection
