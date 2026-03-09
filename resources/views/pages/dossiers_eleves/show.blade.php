@extends('layouts.master')

@section('style')
    @include('partials.style')
    <style>
        .avatar-xl {
            width: 90px; height: 90px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: 700;
            flex-shrink: 0;
        }
        .stat-badge {
            border-radius: 12px;
            padding: 14px 18px;
        }
        .nav-tabs .nav-link {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary, #c41e3a);
            border-bottom: 2px solid var(--primary, #c41e3a);
        }
        .timeline-item {
            position: relative;
            padding-left: 28px;
            margin-bottom: 18px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 8px; top: 6px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--primary, #c41e3a);
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: 12px; top: 18px;
            width: 2px; height: calc(100% + 4px);
            background: #e2e8f0;
        }
        .timeline-item:last-child::after { display: none; }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4 d-flex align-items-center gap-3">
                        <div class="avatar-xl bg-white text-primary shadow">
                            {{ strtoupper(substr($eleve->prenom, 0, 1)) }}{{ strtoupper(substr($eleve->nom, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="page-header-title mb-1">
                                {{ $eleve->prenom }} {{ $eleve->nom }}
                            </h1>
                            <p class="text-white-75 mb-1">
                                <i class="fas fa-id-badge me-1"></i>{{ $eleve->registration_number }}
                                &nbsp;|&nbsp;
                                @php
                                    $badgeClass = match($eleve->statut) {
                                        'actif'     => 'success',
                                        'suspendu'  => 'warning',
                                        'diplome'   => 'primary',
                                        'abandonne' => 'danger',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($eleve->statut ?? 'N/A') }}</span>
                            </p>
                            @if ($derniereInscription)
                                <small class="text-white-50">
                                    Classe : <strong class="text-white">{{ $derniereInscription->classe->nom ?? '—' }}</strong>
                                    &nbsp;|&nbsp; Année : <strong class="text-white">{{ $derniereInscription->anneeScolaire->libelle ?? '—' }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('gestion_eleves.edit', $eleve->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i>&nbsp; Modifier
                        </a>
                        <a href="{{ route('gestion_inscriptions.create') }}" class="btn btn-warning btn-sm text-dark">
                            <i class="fas fa-user-plus"></i>&nbsp; Inscrire
                        </a>
                        <a href="{{ route('dossiers_eleves.index') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10 mb-5">

        <!-- KPI Cards -->
        <div class="row mb-4">
            <div class="col-6 col-lg-3 mb-3">
                <div class="card border-0 shadow-sm stat-badge h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-2" style="background:rgba(196,30,58,.1);">
                            <i data-feather="user-x" style="width:22px;height:22px;color:#c41e3a;"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4">{{ $totalAbsences }}</div>
                            <div class="text-muted small">Absences</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="card border-0 shadow-sm stat-badge h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-2" style="background:rgba(245,158,11,.1);">
                            <i data-feather="clock" style="width:22px;height:22px;color:#f59e0b;"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4">{{ $totalRetards }}</div>
                            <div class="text-muted small">Retards</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="card border-0 shadow-sm stat-badge h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-2" style="background:rgba(239,68,68,.1);">
                            <i data-feather="alert-triangle" style="width:22px;height:22px;color:#ef4444;"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4">{{ $totalIncidents }}</div>
                            <div class="text-muted small">Incidents</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="card border-0 shadow-sm stat-badge h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-2" style="background:rgba(16,185,129,.1);">
                            <i data-feather="dollar-sign" style="width:22px;height:22px;color:#10b981;"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4">{{ number_format($totalPaye, 0, ',', ' ') }}</div>
                            <div class="text-muted small">Payé (FCFA)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="dossierTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-info" data-bs-toggle="tab" href="#info" role="tab">
                            <i class="fas fa-user me-1"></i> Informations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-scolarite" data-bs-toggle="tab" href="#scolarite" role="tab">
                            <i class="fas fa-graduation-cap me-1"></i> Scolarité
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-bulletins" data-bs-toggle="tab" href="#bulletins" role="tab">
                            <i class="fas fa-file-alt me-1"></i> Bulletins
                            @if ($eleve->bulletins->count())
                                <span class="badge bg-primary ms-1">{{ $eleve->bulletins->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-assiduite" data-bs-toggle="tab" href="#assiduite" role="tab">
                            <i class="fas fa-calendar-check me-1"></i> Assiduité
                            @if ($totalAbsences + $totalRetards > 0)
                                <span class="badge bg-danger ms-1">{{ $totalAbsences + $totalRetards }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-discipline" data-bs-toggle="tab" href="#discipline" role="tab">
                            <i class="fas fa-gavel me-1"></i> Discipline
                            @if ($totalIncidents > 0)
                                <span class="badge bg-danger ms-1">{{ $totalIncidents }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-paiements" data-bs-toggle="tab" href="#paiements" role="tab">
                            <i class="fas fa-credit-card me-1"></i> Paiements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-parents" data-bs-toggle="tab" href="#parents" role="tab">
                            <i class="fas fa-users me-1"></i> Parents / Tuteurs
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body tab-content" id="dossierTabsContent">

                {{-- ===== ONGLET INFORMATIONS ===== --}}
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <div class="row g-4">
                        <!-- Informations personnelles -->
                        <div class="col-lg-6">
                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Informations personnelles
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-muted" style="width:40%;">Nom complet</td>
                                        <td class="fw-semibold">{{ $eleve->prenom }} {{ $eleve->nom }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Genre</td>
                                        <td>{{ $eleve->genre == 'M' ? 'Masculin' : ($eleve->genre == 'F' ? 'Féminin' : ($eleve->genre ?? '—')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Date de naissance</td>
                                        <td>{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Lieu de naissance</td>
                                        <td>{{ $eleve->lieu_naissance ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nationalité</td>
                                        <td>{{ $eleve->nationalite ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Adresse</td>
                                        <td>{{ $eleve->adresse ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Téléphone</td>
                                        <td>{{ $eleve->telephone ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td>{{ $eleve->user->email ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Informations médicales & scolaires -->
                        <div class="col-lg-6">
                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Informations médicales
                            </h6>
                            <table class="table table-sm table-borderless mb-4">
                                <tbody>
                                    <tr>
                                        <td class="text-muted" style="width:45%;">Groupe sanguin</td>
                                        <td>
                                            @if ($eleve->groupe_sanguin)
                                                <span class="badge bg-danger">{{ $eleve->groupe_sanguin }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Notes médicales</td>
                                        <td>{{ $eleve->notes_medicales ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Informations scolaires
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-muted" style="width:45%;">Matricule</td>
                                        <td><code>{{ $eleve->registration_number }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Établissement</td>
                                        <td>{{ $eleve->etablissement->nom ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Date d'inscription</td>
                                        <td>{{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Statut</td>
                                        <td><span class="badge bg-{{ $badgeClass }}">{{ ucfirst($eleve->statut ?? 'N/A') }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Login système</td>
                                        <td><code>{{ $eleve->user->login ?? '—' }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ===== ONGLET SCOLARITÉ ===== --}}
                <div class="tab-pane fade" id="scolarite" role="tabpanel">
                    <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                        Historique des inscriptions
                    </h6>
                    @if ($eleve->inscriptions->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i data-feather="book-open" style="width:36px;height:36px;opacity:.3;"></i>
                            <p class="mt-2">Aucune inscription enregistrée.</p>
                            <a href="{{ route('gestion_inscriptions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Inscrire l'élève
                            </a>
                        </div>
                    @else
                        <div>
                            @foreach ($eleve->inscriptions->sortByDesc('created_at') as $inscription)
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="fw-semibold">{{ $inscription->classe->nom ?? '—' }}</span>
                                            @if ($inscription->classe->niveau ?? null)
                                                <span class="badge bg-secondary ms-2">{{ $inscription->classe->niveau->libelle }}</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">
                                                Année : {{ $inscription->anneeScolaire->libelle ?? '—' }}
                                                &nbsp;&bull;&nbsp;
                                                Inscrit le : {{ $inscription->date_inscription ? \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') : '—' }}
                                            </small>
                                        </div>
                                        <a href="{{ route('gestion_inscriptions.show', $inscription->id) }}" class="btn btn-xs btn-outline-secondary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ===== ONGLET BULLETINS ===== --}}
                <div class="tab-pane fade" id="bulletins" role="tabpanel">
                    @if ($eleve->bulletins->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i data-feather="file-minus" style="width:36px;height:36px;opacity:.3;"></i>
                            <p class="mt-2">Aucun bulletin disponible.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Période</th>
                                        <th>Classe</th>
                                        <th class="text-center">Moyenne</th>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Abs.</th>
                                        <th class="text-center">Mention</th>
                                        <th>Commentaire</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleve->bulletins->sortByDesc('created_at') as $bulletin)
                                        <tr>
                                            <td>{{ $bulletin->periodEvaluation->libelle ?? '—' }}</td>
                                            <td>{{ $bulletin->classe->nom ?? '—' }}</td>
                                            <td class="text-center">
                                                @php $moy = (float)$bulletin->moyenne_globale; @endphp
                                                <span class="badge {{ $moy >= 10 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ number_format($moy, 2) }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $bulletin->rang ?? '—' }}/{{ $bulletin->total_eleves ?? '?' }}</td>
                                            <td class="text-center">{{ $bulletin->absences ?? 0 }}</td>
                                            <td class="text-center">
                                                @if ($bulletin->mention_conduite)
                                                    <span class="badge bg-info text-dark">{{ $bulletin->mention_conduite }}</span>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td><small class="text-muted">{{ Str::limit($bulletin->commentaire_principal, 60) ?? '—' }}</small></td>
                                            <td class="text-center">
                                                <a href="{{ route('gestion_bulletins.print', $bulletin->id) }}"
                                                   class="btn btn-xs btn-sm btn-outline-danger" target="_blank" title="Imprimer">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- ===== ONGLET ASSIDUITÉ ===== --}}
                <div class="tab-pane fade" id="assiduite" role="tabpanel">
                    <div class="row g-4">
                        <!-- Absences -->
                        <div class="col-lg-6">
                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Absences ({{ $totalAbsences }})
                            </h6>
                            @if ($eleve->absences->isEmpty())
                                <p class="text-muted small">Aucune absence enregistrée.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Motif</th>
                                                <th class="text-center">Justifiée</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eleve->absences->sortByDesc('created_at') as $absence)
                                                <tr>
                                                    <td>{{ $absence->date_absence ? \Carbon\Carbon::parse($absence->date_absence)->format('d/m/Y') : '—' }}</td>
                                                    <td><small>{{ $absence->motif ?? '—' }}</small></td>
                                                    <td class="text-center">
                                                        @if ($absence->justifiee ?? false)
                                                            <span class="badge bg-success">Oui</span>
                                                        @else
                                                            <span class="badge bg-secondary">Non</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <!-- Retards -->
                        <div class="col-lg-6">
                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Retards ({{ $totalRetards }})
                            </h6>
                            @if ($eleve->retards->isEmpty())
                                <p class="text-muted small">Aucun retard enregistré.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Durée (min)</th>
                                                <th>Motif</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eleve->retards->sortByDesc('created_at') as $retard)
                                                <tr>
                                                    <td>{{ $retard->date_retard ? \Carbon\Carbon::parse($retard->date_retard)->format('d/m/Y') : '—' }}</td>
                                                    <td>{{ $retard->duree ?? '—' }}</td>
                                                    <td><small>{{ $retard->motif ?? '—' }}</small></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== ONGLET DISCIPLINE ===== --}}
                <div class="tab-pane fade" id="discipline" role="tabpanel">
                    <div class="row g-4">
                        <!-- Incidents -->
                        <div class="col-lg-6">
                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Incidents ({{ $totalIncidents }})
                            </h6>
                            @if ($eleve->incidentsDisciplinaires->isEmpty())
                                <p class="text-muted small">Aucun incident enregistré.</p>
                            @else
                                @foreach ($eleve->incidentsDisciplinaires->sortByDesc('created_at') as $incident)
                                    <div class="alert alert-danger py-2 mb-2">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $incident->type ?? 'Incident' }}</strong>
                                            <small class="text-muted">{{ $incident->date_incident ? \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') : '' }}</small>
                                        </div>
                                        <small>{{ $incident->description ?? '—' }}</small>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!-- Sanctions -->
                        <div class="col-lg-6">
                            <h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:11px;letter-spacing:.08em;">
                                Sanctions ({{ $eleve->sanctions->count() }})
                            </h6>
                            @if ($eleve->sanctions->isEmpty())
                                <p class="text-muted small">Aucune sanction enregistrée.</p>
                            @else
                                @foreach ($eleve->sanctions->sortByDesc('created_at') as $sanction)
                                    <div class="alert alert-warning py-2 mb-2">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $sanction->type ?? 'Sanction' }}</strong>
                                            <small class="text-muted">{{ $sanction->date_sanction ? \Carbon\Carbon::parse($sanction->date_sanction)->format('d/m/Y') : '' }}</small>
                                        </div>
                                        <small>{{ $sanction->description ?? '—' }}</small>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== ONGLET PAIEMENTS ===== --}}
                <div class="tab-pane fade" id="paiements" role="tabpanel">
                    <!-- Résumé financier -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 bg-success bg-opacity-10 p-3 text-center">
                                <div class="fw-bold fs-5 text-success">{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</div>
                                <div class="text-muted small">Total payé</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-danger bg-opacity-10 p-3 text-center">
                                <div class="fw-bold fs-5 text-danger">{{ number_format($totalReste, 0, ',', ' ') }} FCFA</div>
                                <div class="text-muted small">Reste à payer</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-info bg-opacity-10 p-3 text-center">
                                <div class="fw-bold fs-5 text-info">{{ $eleve->paiements->count() }}</div>
                                <div class="text-muted small">Transactions</div>
                            </div>
                        </div>
                    </div>

                    @if ($eleve->paiements->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i data-feather="credit-card" style="width:36px;height:36px;opacity:.3;"></i>
                            <p class="mt-2">Aucun paiement enregistré.</p>
                            <a href="{{ route('gestion_paiements.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Enregistrer un paiement
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Référence</th>
                                        <th>Type de frais</th>
                                        <th>Année</th>
                                        <th>Date</th>
                                        <th class="text-end">Montant</th>
                                        <th class="text-end">Reste</th>
                                        <th class="text-center">Méthode</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-center">Reçu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleve->paiements->sortByDesc('date_paiement') as $paiement)
                                        <tr>
                                            <td><code>{{ $paiement->reference ?? '—' }}</code></td>
                                            <td>{{ $paiement->fraiScolarite->libelle ?? '—' }}</td>
                                            <td>{{ $paiement->anneeScolaire->libelle ?? '—' }}</td>
                                            <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : '—' }}</td>
                                            <td class="text-end fw-semibold text-success">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                                            <td class="text-end {{ ($paiement->reste_a_payer ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($paiement->reste_a_payer ?? 0, 0, ',', ' ') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $paiement->methode_paiement ?? '—' }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $sc = match($paiement->status ?? '') {
                                                        'complet'    => 'success',
                                                        'partiel'    => 'warning',
                                                        'en_attente' => 'secondary',
                                                        default      => 'light',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $sc }}">{{ ucfirst($paiement->status ?? '—') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('gestion_paiements.print', $paiement->id) }}"
                                                   class="btn btn-xs btn-sm btn-outline-secondary" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- ===== ONGLET PARENTS / TUTEURS ===== --}}
                <div class="tab-pane fade" id="parents" role="tabpanel">
                    @if ($eleve->eleveParents->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i data-feather="users" style="width:36px;height:36px;opacity:.3;"></i>
                            <p class="mt-2">Aucun parent ou tuteur associé.</p>
                            <a href="{{ route('gestion_associations.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-link"></i> Associer un tuteur
                            </a>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach ($eleve->eleveParents as $ep)
                                @php $tuteur = $ep->tuteur; @endphp
                                @if ($tuteur)
                                    <div class="col-lg-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                                         style="width:48px;height:48px;font-size:18px;font-weight:700;">
                                                        {{ strtoupper(substr($tuteur->prenom, 0, 1)) }}{{ strtoupper(substr($tuteur->nom, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold">{{ $tuteur->prenom }} {{ $tuteur->nom }}</div>
                                                        <div class="text-muted small mb-1">{{ $tuteur->relationship ?? '—' }}</div>
                                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                                            @if ($tuteur->telephone)
                                                                <span class="badge bg-light text-dark border">
                                                                    <i class="fas fa-phone me-1"></i>{{ $tuteur->telephone }}
                                                                </span>
                                                            @endif
                                                            @if ($tuteur->email)
                                                                <span class="badge bg-light text-dark border">
                                                                    <i class="fas fa-envelope me-1"></i>{{ $tuteur->email }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="mt-2">
                                                            @if ($ep->is_primary)
                                                                <span class="badge bg-primary me-1">Contact principal</span>
                                                            @endif
                                                            @if ($ep->can_pickup)
                                                                <span class="badge bg-success me-1">Peut récupérer</span>
                                                            @endif
                                                            @if ($ep->emergency_contact)
                                                                <span class="badge bg-danger">Urgence</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

            </div><!-- /tab-content -->
        </div><!-- /card -->

    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        if (typeof feather !== 'undefined') feather.replace();
    });
</script>
@endsection
