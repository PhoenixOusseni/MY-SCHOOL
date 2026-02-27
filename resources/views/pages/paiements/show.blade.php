@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    @php
        $statusColors = [
            'Terminé' => 'success',
            'En attente' => 'warning',
            'Annulé' => 'danger',
            'Remboursé' => 'secondary',
        ];
        $methodeLabels = [
            'Especes' => 'Espèces',
            'cheque' => 'Chèque',
            'virement' => 'Virement',
            'mobile_money' => 'Mobile Money',
            'carte' => 'Carte',
        ];
        $methodeIcons = [
            'Especes' => 'fa-money-bill',
            'cheque' => 'fa-file-alt',
            'virement' => 'fa-exchange-alt',
            'mobile_money' => 'fa-mobile-alt',
            'carte' => 'fa-credit-card',
        ];
    @endphp

    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-receipt"></i></div>
                            Détails du Paiement
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $paiement->eleve->prenom ?? '' }} {{ $paiement->eleve->nom ?? '' }} —
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_paiements.index') }}" class="btn btn-light btn-sm me-1">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_paiements.edit', $paiement->id) }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-edit me-1"></i>&nbsp; Modifier
                        </a>
                        <a href="{{ route('gestion_paiements.print', $paiement->id) }}" target="_blank"
                            class="btn btn-outline-light btn-sm me-1">
                            <i class="fas fa-print me-1"></i>&nbsp; Imprimer
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

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card border-start-5 border-start-primary h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Montant payé</h6>
                            <h3 class="mb-0">{{ number_format($paiement->montant, 0, ',', ' ') }}</h3>
                            <small class="text-muted">{{ $paiement->fraiScolarite->devise ?? 'XOF' }}</small>
                        </div>
                        <div class="bg-primary rounded p-3">
                            <i class="fas fa-coins text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                @php $resteColor = ($paiement->reste_a_payer > 0) ? 'danger' : 'success'; @endphp
                <div class="card border-start-5 border-start-{{ $resteColor }} h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Reste à payer</h6>
                            <h3 class="mb-0 text-{{ $resteColor }}">
                                {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}</h3>
                            <small class="text-muted">{{ $paiement->fraiScolarite->devise ?? 'XOF' }}</small>
                        </div>
                        <div class="bg-{{ $resteColor }} rounded p-3">
                            <i
                                class="fas fa-{{ $paiement->reste_a_payer > 0 ? 'exclamation-circle' : 'check-circle' }} text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-start-5 border-start-info h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Méthode</h6>
                            <h3 class="mb-0" style="font-size: 1.1rem;">
                                <i class="fas {{ $methodeIcons[$paiement->methode_paiement] ?? 'fa-question' }} me-1"></i>
                                {{ $methodeLabels[$paiement->methode_paiement] ?? $paiement->methode_paiement }}
                            </h3>
                        </div>
                        <div class="bg-info rounded p-3">
                            <i class="fas fa-wallet text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-start-5 border-start-{{ $statusColors[$paiement->status] ?? 'secondary' }} h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Statut</h6>
                            <span class="badge bg-{{ $statusColors[$paiement->status] ?? 'secondary' }} fs-6 px-3 py-2">
                                {{ $paiement->status }}
                            </span>
                        </div>
                        <div class="bg-{{ $statusColors[$paiement->status] ?? 'secondary' }} rounded p-3">
                            <i class="fas fa-flag text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Détails principaux -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations du paiement</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Élève</p>
                                <p class="mb-0">
                                    <a href="{{ route('gestion_eleves.show', $paiement->eleve->id) }}"
                                        class="fw-bold text-decoration-none">
                                        {{ $paiement->eleve->prenom ?? '' }} {{ strtoupper($paiement->eleve->nom ?? '') }}
                                    </a><br>
                                    <small class="text-muted">{{ $paiement->eleve->registration_number ?? '' }}</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Type de frais</p>
                                <p class="mb-0">
                                    <strong>{{ $paiement->fraiScolarite->libelle ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">
                                        Montant attendu :
                                        {{ number_format($paiement->fraiScolarite->montant ?? 0, 0, ',', ' ') }}
                                        {{ $paiement->fraiScolarite->devise ?? '' }}
                                    </small>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Date de paiement</p>
                                <p class="mb-0"><strong>{{ $paiement->date_paiement->format('d/m/Y') }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Année scolaire</p>
                                <p class="mb-0">{{ $paiement->anneeScolaire->libelle ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Référence</p>
                                <p class="mb-0">{{ $paiement->reference ?? '—' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Reçu par</p>
                                <p class="mb-0">
                                    {{ $paiement->receivedBy->prenom ?? '' }} {{ $paiement->receivedBy->nom ?? '—' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Notes</p>
                                <p class="mb-0">{{ $paiement->notes ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Résumé -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-flag me-2"></i>Résumé</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted small mb-1">Créé le</h6>
                            <p class="mb-0">{{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted small mb-1">Modifié le</h6>
                            <p class="mb-0">{{ $paiement->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <hr>
                        <div class="text-center">
                            <span class="badge bg-{{ $statusColors[$paiement->status] ?? 'secondary' }} fs-6 px-3 py-2">
                                {{ $paiement->status }}
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
                        <a href="{{ route('gestion_paiements.print', $paiement->id) }}" target="_blank"
                            class="btn btn-dark btn-sm w-100 mb-2">
                            <i class="fas fa-print me-1"></i>&nbsp; Imprimer ce reçu
                        </a>
                        @if ($paiement->reste_a_payer > 0)
                            <button type="button" class="btn btn-success btn-sm w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#solderModal">
                                <i class="fas fa-check-double me-1"></i>&nbsp; Solder le reste
                                <span class="badge bg-white text-success ms-1">
                                    {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                                </span>
                            </button>
                        @endif
                        <a href="{{ route('gestion_paiements.edit', $paiement->id) }}"
                            class="btn btn-1 btn-sm w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>&nbsp; Modifier
                        </a>
                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt me-1"></i>&nbsp; Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des paiements (même élève / frais / année) -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Historique des paiements
                    <small class="text-muted fw-normal">
                        — {{ $paiement->eleve->prenom ?? '' }} {{ $paiement->eleve->nom ?? '' }}
                        · {{ $paiement->fraiScolarite->libelle ?? '' }}
                        · {{ $paiement->anneeScolaire->libelle ?? '' }}
                    </small>
                </h5>
                <span class="badge bg-secondary">{{ $historique->count() }} paiement(s)</span>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Frais</th>
                                <th class="text-end">Montant payé</th>
                                <th class="text-end">Reste après</th>
                                <th>Méthode</th>
                                <th>Référence</th>
                                <th>Reçu par</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPaye = 0; @endphp
                            @foreach ($historique as $ligne)
                                @php
                                    $totalPaye += $ligne->montant;
                                    $isCurrent = $ligne->id === $paiement->id;
                                    $statusColors2 = [
                                        'Terminé' => 'success',
                                        'En attente' => 'warning',
                                        'Annulé' => 'danger',
                                        'Remboursé' => 'secondary',
                                    ];
                                @endphp
                                <tr class="{{ $isCurrent ? 'table-active fw-bold' : '' }}">
                                    <td>
                                        {{ $ligne->date_paiement->format('d/m/Y') }}
                                        @if ($isCurrent)
                                            <span class="badge bg-primary ms-1" style="font-size:10px;">actuel</span>
                                        @endif
                                    </td>
                                    <td>{{ $ligne->fraiScolarite->libelle ?? 'N/A' }}</td>
                                    <td class="text-end text-success fw-semibold">
                                        {{ number_format($ligne->montant, 0, ',', ' ') }}
                                    </td>
                                    <td
                                        class="text-end {{ $ligne->reste_a_payer > 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                        {{ number_format($ligne->reste_a_payer, 0, ',', ' ') }}
                                    </td>
                                    <td>
                                        <small>{{ $methodes[$ligne->methode_paiement] ?? $ligne->methode_paiement }}</small>
                                    </td>
                                    <td><small class="text-muted">{{ $ligne->reference ?? '—' }}</small></td>
                                    <td><small>{{ $ligne->receivedBy->prenom ?? '' }}
                                            {{ $ligne->receivedBy->nom ?? '—' }}</small></td>
                                    <td>
                                        <span class="badge bg-{{ $statusColors2[$ligne->status] ?? 'secondary' }}">
                                            {{ $ligne->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('gestion_paiements.show', $ligne->id) }}"
                                                class="btn btn-sm btn-1" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('gestion_paiements.print', $ligne->id) }}" target="_blank"
                                                class="btn btn-sm btn-success" title="Imprimer">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td class="fw-bold">Total payé</td>
                                <td class="text-end fw-bold text-success">{{ number_format($totalPaye, 0, ',', ' ') }}
                                </td>
                                <td
                                    class="text-end fw-bold text-{{ $historique->last()?->reste_a_payer > 0 ? 'danger' : 'success' }}">
                                    {{ number_format($historique->last()?->reste_a_payer ?? 0, 0, ',', ' ') }}
                                </td>
                                <td colspan="6"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal : Versement complémentaire -->
    @if ($paiement->reste_a_payer > 0)
        @php
            $resteMax = $paiement->reste_a_payer;
            $devise = $paiement->fraiScolarite->devise ?? 'XOF';
        @endphp
        <div class="modal fade" id="solderModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-hand-holding-usd me-2"></i>Enregistrer un versement
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('gestion_paiements.solder', $paiement->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <!-- Résumé solde actuel -->
                            <div class="d-flex justify-content-between align-items-center p-3 rounded mb-3"
                                style="background:#f8f9fa; border-left:4px solid #dc3545;">
                                <div>
                                    <small class="text-muted d-block">Reste à payer</small>
                                    <span class="fw-bold fs-5 text-danger">
                                        {{ number_format($resteMax, 0, ',', ' ') }} {{ $devise }}
                                    </span>
                                </div>
                                <i class="fas fa-exclamation-circle text-danger fa-2x"></i>
                            </div>

                            <!-- Montant du versement -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Montant à verser maintenant <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0.01" max="{{ $resteMax }}"
                                        class="form-control" name="montant" id="solderMontant"
                                        placeholder="Ex: {{ number_format($resteMax, 0) }}" value="{{ $resteMax }}"
                                        required>
                                    <span class="input-group-text">{{ $devise }}</span>
                                </div>
                                <div class="form-text">
                                    Maximum : <strong>{{ number_format($resteMax, 0, ',', ' ') }}
                                        {{ $devise }}</strong>
                                </div>
                            </div>

                            <!-- Aperçu du nouveau reste -->
                            <div class="alert mb-3" id="resteApercu" style="display:none;">
                                <i class="fas fa-info-circle me-1"></i>
                                Nouveau reste après ce versement :
                                <strong id="resteApercuVal"></strong>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Méthode de paiement <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="methode_paiement" required>
                                    @foreach ($methodes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Date du paiement <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" name="date_paiement"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Référence / N° reçu</label>
                                    <input type="text" class="form-control" name="reference"
                                        placeholder="Ex: REC-2026-002">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Notes</label>
                                    <textarea class="form-control w-100" name="notes" rows="3" placeholder="Observations..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="m-3">
                            <button type="submit" class="btn btn-1 w-100">
                                <i class="fas fa-check-double me-1"></i> Enregistrer le versement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            (function() {
                var resteMax = {{ $resteMax }};
                var input = document.getElementById('solderMontant');
                var apercu = document.getElementById('resteApercu');
                var apercuVal = document.getElementById('resteApercuVal');

                function updateApercu() {
                    var val = parseFloat(input.value) || 0;
                    var reste = resteMax - val;
                    if (val <= 0 || val > resteMax) {
                        apercu.style.display = 'none';
                        return;
                    }
                    apercu.style.display = 'block';
                    if (reste <= 0) {
                        apercu.className = 'alert alert-success mb-3';
                        apercuVal.textContent = '0 {{ $devise }} — Paiement entièrement soldé ✓';
                    } else {
                        apercu.className = 'alert alert-warning mb-3';
                        apercuVal.textContent = reste.toLocaleString('fr-FR') + ' {{ $devise }}';
                    }
                }
                input.addEventListener('input', updateApercu);
                updateApercu();
            })();
        </script>
    @endif
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce paiement ?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $paiement->eleve->prenom ?? '' }} {{ $paiement->eleve->nom ?? '' }}</strong><br>
                        <small>
                            {{ number_format($paiement->montant, 0, ',', ' ') }} —
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </small>
                    </div>
                    <p class="text-danger small">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('gestion_paiements.destroy', $paiement->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
