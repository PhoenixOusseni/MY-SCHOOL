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
                            <div class="page-header-icon"><i class="fas fa-chart-pie"></i></div>
                            Situation Financière
                        </h1>
                        <p class="page-header-subtitle text-white-50">
                            Tableau de bord des paiements de scolarité
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_paiements.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-list me-1"></i>&nbsp; Historique
                        </a>
                        <a href="{{ route('gestion_paiements.create') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-plus me-1"></i>&nbsp; Nouveau paiement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <!-- Filtre année scolaire -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('paiements.situation_financiere') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-1">
                            <i class="fas fa-filter me-1"></i>Filtrer par année scolaire
                        </label>
                        <select name="annee_id" class="form-select form-select-sm">
                            <option value="">-- Toutes les années --</option>
                            @foreach ($annees as $annee)
                                <option value="{{ $annee->id }}" {{ $anneeId == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-1">
                            <i class="fas fa-search me-1"></i> Appliquer
                        </button>
                        @if ($anneeId)
                            <a href="{{ route('paiements.situation_financiere') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                <i class="fas fa-times me-1"></i> Réinitialiser
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-start-5 border-start-success shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total encaissé</p>
                                <h3 class="mb-0 text-success">{{ number_format($totalEncaisse, 0, ',', ' ') }}</h3>
                                <small class="text-muted">Paiements terminés</small>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-start-5 border-start-warning shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">En attente</p>
                                <h3 class="mb-0 text-warning">{{ number_format($totalEnAttente, 0, ',', ' ') }}</h3>
                                <small class="text-muted">Paiements non finalisés</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-start-5 border-start-danger shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Reste à percevoir</p>
                                <h3 class="mb-0 text-danger">{{ number_format($totalResteAPayer, 0, ',', ' ') }}</h3>
                                <small class="text-muted">Soldes impayés</small>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-circle fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-start-5 border-start-primary shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Transactions</p>
                                <h3 class="mb-0 text-primary">{{ $nbPaiements }}</h3>
                                <small class="text-muted">{{ $nbEleves }} élève(s) concerné(s)</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-receipt fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression globale -->
        @if (($totalEncaisse + $totalEnAttente + $totalResteAPayer) > 0)
            @php
                $totalAttendu = $totalEncaisse + $totalResteAPayer;
                $pctEncaisse  = $totalAttendu > 0 ? round(($totalEncaisse / $totalAttendu) * 100, 1) : 0;
            @endphp
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><i class="fas fa-percentage me-2 text-primary"></i>Taux de recouvrement global</h6>
                        <span class="fw-bold text-{{ $pctEncaisse >= 80 ? 'success' : ($pctEncaisse >= 50 ? 'warning' : 'danger') }} fs-5">
                            {{ $pctEncaisse }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 18px; border-radius: 8px;">
                        <div class="progress-bar bg-{{ $pctEncaisse >= 80 ? 'success' : ($pctEncaisse >= 50 ? 'warning' : 'danger') }} progress-bar-striped"
                            role="progressbar"
                            style="width: {{ $pctEncaisse }}%;"
                            aria-valuenow="{{ $pctEncaisse }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ $pctEncaisse }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2 small text-muted">
                        <span><span class="badge bg-success me-1">Encaissé</span>{{ number_format($totalEncaisse, 0, ',', ' ') }}</span>
                        <span><span class="badge bg-danger me-1">Restant</span>{{ number_format($totalResteAPayer, 0, ',', ' ') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4">
            <!-- Situation par type de frais -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Répartition par type de frais</h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($parFrais->isEmpty())
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>Aucune donnée
                            </div>
                        @else
                            <div class="table-responsive p-3">
                                <table class="table table-sm table-hover table-bordered table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Type de frais</th>
                                            <th class="text-end">Payé</th>
                                            <th class="text-end">Reste</th>
                                            <th class="text-center">Élèves</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parFrais as $ligne)
                                            @php
                                                $totalLigne = $ligne->total_paye + $ligne->total_reste;
                                                $pctLigne   = $totalLigne > 0 ? round(($ligne->total_paye / $totalLigne) * 100) : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $ligne->fraiScolarite->libelle ?? 'N/A' }}</strong>
                                                    <div class="progress mt-1" style="height: 4px;">
                                                        <div class="progress-bar bg-{{ $pctLigne >= 80 ? 'success' : ($pctLigne >= 50 ? 'warning' : 'danger') }}"
                                                            style="width: {{ $pctLigne }}%"></div>
                                                    </div>
                                                </td>
                                                <td class="text-end text-success fw-semibold">
                                                    {{ number_format($ligne->total_paye, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-end {{ $ligne->total_reste > 0 ? 'text-danger' : 'text-muted' }} fw-semibold">
                                                    {{ number_format($ligne->total_reste, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark">{{ $ligne->nb_eleves }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td>Total</td>
                                            <td class="text-end text-success">{{ number_format($parFrais->sum('total_paye'), 0, ',', ' ') }}</td>
                                            <td class="text-end text-danger">{{ number_format($parFrais->sum('total_reste'), 0, ',', ' ') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statuts des paiements -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-users me-2"></i>Situation par élève
                            <span class="badge bg-secondary ms-2">{{ $parEleve->total() }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($parEleve->isEmpty())
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>Aucune donnée
                            </div>
                        @else
                            <div class="table-responsive p-3">
                                <table id="datatablesSimple" class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Élève</th>
                                            <th class="text-end">Payé</th>
                                            <th class="text-end">Reste</th>
                                            <th class="text-center">Paiements</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parEleve as $ligne)
                                            <tr>
                                                <td>
                                                    @if ($ligne->eleve)
                                                        <a href="{{ route('gestion_eleves.show', $ligne->eleve->id) }}"
                                                            class="text-decoration-none fw-semibold">
                                                            {{ $ligne->eleve->prenom }} {{ strtoupper($ligne->eleve->nom) }}
                                                        </a>
                                                        <div class="small text-muted">{{ $ligne->eleve->registration_number }}</div>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-end text-success fw-semibold">
                                                    {{ number_format($ligne->total_paye, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-end fw-semibold {{ $ligne->total_reste > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($ligne->total_reste, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark">{{ $ligne->nb_paiements }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($ligne->total_reste <= 0)
                                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Soldé</span>
                                                    @elseif ($ligne->total_paye > 0)
                                                        <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Partiel</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Impayé</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestion_paiements.show', $ligne->dernier_paiement_id) }}" class="btn btn-sm btn-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light">
                                <nav>{{ $parEleve->appends(request()->query())->links() }}</nav>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
