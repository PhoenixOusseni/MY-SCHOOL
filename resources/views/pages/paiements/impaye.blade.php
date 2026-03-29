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
                            <div class="page-header-icon"><i class="fas fa-exclamation-circle"></i></div>
                            Paiements Impayés
                        </h1>
                        <p class="text-muted">Élèves ayant un solde restant à régler</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_paiements.index') }}" class="btn btn-dark btn-sm">
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

        <!-- Stat rapide -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-start-5 border-start-danger h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total impayés</h6>
                            <h3 class="mb-0 text-danger">{{ $impayes->count() }}</h3>
                            <small class="text-muted">élève(s) concerné(s)</small>
                        </div>
                        <div class="bg-danger rounded p-3">
                            <i class="fas fa-users text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-5 border-start-warning h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Montant total restant</h6>
                            <h3 class="mb-0 text-warning">
                                {{ number_format($impayes->sum('reste_a_payer'), 0, ',', ' ') }}
                            </h3>
                            <small class="text-muted">XOF</small>
                        </div>
                        <div class="bg-warning rounded p-3">
                            <i class="fas fa-coins text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                @php
                    $urgents = $impayes
                        ->filter(
                            fn($p) => ($p->prochaine_paie &&
                                \Carbon\Carbon::parse($p->prochaine_paie)->diffInDays(now(), false) >= 0) ||
                                ($p->prochaine_paie &&
                                    \Carbon\Carbon::parse($p->prochaine_paie)->diffInDays(now()) <= 20 &&
                                    \Carbon\Carbon::parse($p->prochaine_paie)->isFuture()),
                        )
                        ->count();
                @endphp
                <div class="card border-start-5 border-start-danger h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Échéances urgentes</h6>
                            <h3 class="mb-0 text-danger">{{ $urgents }}</h3>
                            <small class="text-muted">dans moins de 20 jours</small>
                        </div>
                        <div class="bg-danger rounded p-3">
                            <i class="fas fa-bell text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des impayés
                    <span class="badge bg-danger ms-2">{{ $impayes->count() }}</span>
                </h5>
                <small class="text-muted">Triés par date de prochain paiement</small>
            </div>
            <div class="card-body p-0">
                @if ($impayes->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-muted">Aucun impayé en cours</h5>
                        <p class="text-muted">Tous les paiements sont à jour.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="datatablesSimple">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Élève</th>
                                    <th>Type de frais</th>
                                    <th>Année</th>
                                    <th class="text-end">Montant total</th>
                                    <th class="text-end">Déjà payé</th>
                                    <th class="text-end">Reste à payer</th>
                                    <th class="text-center">Prochain paiement</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($impayes as $i => $p)
                                    @php
                                        $dejaPayé = ($p->fraiScolarite->montant ?? 0) - $p->reste_a_payer;
                                        $isUrgent = false;
                                        $isEnRetard = false;
                                        $joursRestants = null;

                                        if ($p->prochaine_paie) {
                                            $echeance = \Carbon\Carbon::parse($p->prochaine_paie);
                                            $joursRestants = (int) now()->diffInDays($echeance, false);
                                            $isEnRetard = $joursRestants < 0;
                                            $isUrgent = !$isEnRetard && $joursRestants <= 20;
                                        }

                                        $rowClass = $isEnRetard ? 'table-danger' : ($isUrgent ? 'table-warning' : '');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="text-muted small">{{ $i + 1 }}</td>
                                        <td>
                                            <a href="{{ route('gestion_eleves.show', $p->eleve->id) }}"
                                                class="fw-bold text-decoration-none">
                                                {{ $p->eleve->prenom ?? '' }}
                                                {{ strtoupper($p->eleve->nom ?? '') }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $p->eleve->registration_number ?? '' }}</small>
                                        </td>
                                        <td>{{ $p->fraiScolarite->libelle ?? 'N/A' }}</td>
                                        <td><small>{{ $p->anneeScolaire->libelle ?? 'N/A' }}</small></td>
                                        <td class="text-end">
                                            {{ number_format($p->fraiScolarite->montant ?? 0, 0, ',', ' ') }}
                                            <small class="text-muted">{{ $p->fraiScolarite->devise ?? 'XOF' }}</small>
                                        </td>
                                        <td class="text-end text-success fw-semibold">
                                            {{ number_format($dejaPayé, 0, ',', ' ') }}
                                        </td>
                                        <td class="text-end fw-bold text-danger">
                                            {{ number_format($p->reste_a_payer, 0, ',', ' ') }}
                                            <small
                                                class="text-muted d-block">{{ $p->fraiScolarite->devise ?? 'XOF' }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if ($p->prochaine_paie)
                                                @if ($isEnRetard)
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        {{ \Carbon\Carbon::parse($p->prochaine_paie)->format('d/m/Y') }}
                                                    </span>
                                                    <br><small class="text-danger fw-bold">{{ abs($joursRestants) }}j de
                                                        retard</small>
                                                @elseif ($isUrgent)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($p->prochaine_paie)->format('d/m/Y') }}
                                                    </span>
                                                    <br><small class="text-warning fw-bold">dans
                                                        {{ $joursRestants }}j</small>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        {{ \Carbon\Carbon::parse($p->prochaine_paie)->format('d/m/Y') }}
                                                    </span>
                                                    <br><small class="text-muted">dans {{ $joursRestants }}j</small>
                                                @endif
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('gestion_paiements.show', $p->id) }}"
                                                    class="btn btn-sm btn-1" title="Voir le paiement">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success btn-solder"
                                                    title="Solder le reste" data-id="{{ $p->id }}"
                                                    data-nom="{{ $p->eleve->prenom ?? '' }} {{ strtoupper($p->eleve->nom ?? '') }}"
                                                    data-frais="{{ $p->fraiScolarite->libelle ?? '' }}"
                                                    data-reste="{{ $p->reste_a_payer }}"
                                                    data-devise="{{ $p->fraiScolarite->devise ?? 'XOF' }}"
                                                    data-bs-toggle="modal" data-bs-target="#solderModal">
                                                    <i class="fas fa-check-double me-1"></i> Solder
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Légende -->
                    <div class="p-3 border-top d-flex gap-3 flex-wrap">
                        <small><span class="badge bg-danger me-1">Rouge</span> Échéance dépassée</small>
                        <small><span class="badge bg-warning text-dark me-1">Orange</span> Échéance dans ≤ 20 jours</small>
                        <small><span class="badge bg-secondary me-1">Gris</span> Échéance à venir</small>
                        <small class="text-muted">— = pas de date planifiée</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Solder -->
    <div class="modal fade" id="solderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-hand-holding-usd me-2"></i>Enregistrer un versement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="solderForm" method="POST" action="#">
                    @csrf
                    <div class="modal-body">

                        <!-- Résumé élève -->
                        <div class="d-flex justify-content-between align-items-center p-3 rounded mb-3"
                            style="background:#f8f9fa; border-left:4px solid #dc3545;">
                            <div>
                                <strong id="modalNomEleve" class="d-block"></strong>
                                <small id="modalFrais" class="text-muted"></small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Reste à payer</small>
                                <span class="fw-bold fs-5 text-danger" id="modalReste"></span>
                            </div>
                        </div>

                        <!-- Montant -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Montant à verser <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" class="form-control" name="montant"
                                    id="modalMontant" required>
                                <span class="input-group-text" id="modalDevise"></span>
                            </div>
                        </div>

                        <!-- Aperçu reste -->
                        <div class="alert mb-3" id="modalApercu" style="display:none;">
                            <i class="fas fa-info-circle me-1"></i>
                            Nouveau reste après ce versement :
                            <strong id="modalApercuVal"></strong>
                        </div>

                        <!-- Méthode -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Méthode de paiement <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="methode_paiement" required>
                                @foreach ($methodes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date paiement -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date du paiement <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_paiement" value="{{ date('Y-m-d') }}"
                                required>
                        </div>

                        <!-- Prochain paiement (visible si reste > 0) -->
                        <div class="mb-3" id="modalProchainePaieWrapper" style="display:none;">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt me-1 text-warning"></i>
                                Date du prochain paiement
                                <small class="text-muted fw-normal">(montant non soldé)</small>
                            </label>
                            <input type="date" class="form-control" name="prochaine_paie" id="modalProchainePaie">
                        </div>

                        <!-- Référence -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Référence / N° reçu</label>
                            <input type="text" class="form-control" name="reference" placeholder="Ex: REC-2026-001">
                        </div>

                        <!-- Notes -->
                        <div class="mb-1">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Observations..."></textarea>
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
            var resteMax = 0;
            var devise = 'XOF';
            var solderUrlPattern = '{{ route("gestion_paiements.solder", ":id") }}';

            // Pré-remplir le modal via l'événement Bootstrap (avant affichage)
            var solderModal = document.getElementById('solderModal');
            solderModal.addEventListener('show.bs.modal', function(event) {
                var btn = event.relatedTarget;
                var id  = btn.dataset.id;
                var nom = btn.dataset.nom;
                var frais = btn.dataset.frais;
                resteMax = parseFloat(btn.dataset.reste);
                devise   = btn.dataset.devise;

                document.getElementById('modalNomEleve').textContent = nom;
                document.getElementById('modalFrais').textContent    = frais;
                document.getElementById('modalReste').textContent    = resteMax.toLocaleString('fr-FR') + ' ' + devise;
                document.getElementById('modalDevise').textContent   = devise;
                document.getElementById('modalMontant').max          = resteMax;
                document.getElementById('modalMontant').value        = resteMax;
                document.getElementById('solderForm').action         = solderUrlPattern.replace(':id', id);

                updateApercu();
            });

            // Aperçu dynamique + affichage champ prochaine_paie
            var input = document.getElementById('modalMontant');
            var apercu = document.getElementById('modalApercu');
            var apercuVal = document.getElementById('modalApercuVal');
            var ppWrapper = document.getElementById('modalProchainePaieWrapper');

            function updateApercu() {
                var val = parseFloat(input.value) || 0;
                var reste = resteMax - val;

                if (val <= 0 || val > resteMax) {
                    apercu.style.display = 'none';
                    ppWrapper.style.display = 'none';
                    return;
                }

                apercu.style.display = 'block';

                if (reste <= 0) {
                    apercu.className = 'alert alert-success mb-3';
                    apercuVal.textContent = '0 ' + devise + ' — Paiement entièrement soldé ✓';
                    ppWrapper.style.display = 'none';
                } else {
                    apercu.className = 'alert alert-warning mb-3';
                    apercuVal.textContent = reste.toLocaleString('fr-FR') + ' ' + devise;
                    ppWrapper.style.display = 'block';
                }
            }

            input.addEventListener('input', updateApercu);
        })();
    </script>
@endsection
