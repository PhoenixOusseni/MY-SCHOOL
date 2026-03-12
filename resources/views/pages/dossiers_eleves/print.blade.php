<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Élève — {{ $eleve->prenom }} {{ $eleve->nom }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            line-height: 1.5;
        }

        /* ===== PAGE ===== */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 14mm 14mm 12mm;
            background: #fff;
        }

        @page {
            size: A4 portrait;
            margin: 12mm 12mm 10mm;
        }

        @media print {
            body { margin: 0; }
            .page { width: 100%; padding: 0; margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            section { page-break-inside: avoid; }
        }

        /* ===== COULEURS ===== */
        :root {
            --primary:   #c41e3a;
            --primary-lt: #fdf2f4;
            --accent:    #1e3a5f;
            --gray:      #64748b;
            --gray-lt:   #f8fafc;
            --border:    #e2e8f0;
            --success:   #16a34a;
            --danger:    #dc2626;
            --warning:   #d97706;
            --info:      #0284c7;
        }

        /* ===== EN-TÊTE DOCUMENT ===== */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .doc-header .school-block { flex: 1; }
        .doc-header .school-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .doc-header .school-sub {
            font-size: 10px;
            color: var(--gray);
            margin-top: 2px;
        }
        .doc-header .doc-title-block {
            text-align: right;
        }
        .doc-header .doc-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .doc-header .doc-meta {
            font-size: 9px;
            color: var(--gray);
            margin-top: 3px;
        }
        .doc-header .confidential {
            display: inline-block;
            font-size: 8px;
            font-weight: 700;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 1px 6px;
            border-radius: 3px;
            letter-spacing: .08em;
            margin-top: 4px;
        }

        /* ===== FICHE IDENTITÉ ===== */
        .identity-card {
            display: flex;
            gap: 14px;
            background: linear-gradient(135deg, var(--accent) 0%, #2d5986 100%);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 16px;
            color: #fff;
        }
        .identity-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            border: 2px solid rgba(255,255,255,.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            flex-shrink: 0;
            letter-spacing: -.5px;
        }
        .identity-info { flex: 1; }
        .identity-name {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: .02em;
            margin-bottom: 2px;
        }
        .identity-sub {
            font-size: 10px;
            opacity: .85;
            margin-bottom: 6px;
        }
        .identity-badges { display: flex; flex-wrap: wrap; gap: 6px; }
        .ibadge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 20px;
            padding: 2px 9px;
            font-size: 9.5px;
            font-weight: 600;
        }
        .identity-stats {
            display: flex;
            flex-direction: column;
            gap: 8px;
            justify-content: center;
            border-left: 1px solid rgba(255,255,255,.2);
            padding-left: 14px;
        }
        .istat { text-align: center; }
        .istat-val { font-size: 18px; font-weight: 800; line-height: 1; }
        .istat-lbl { font-size: 8.5px; opacity: .75; }

        /* ===== SECTION HEADERS ===== */
        .section {
            margin-bottom: 14px;
        }
        .section-title {
            display: flex;
            align-items: center;
            gap: 7px;
            background: var(--accent);
            color: #fff;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .section-title .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--primary);
            flex-shrink: 0;
        }

        /* ===== GRILLES D'INFO ===== */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
        }
        .info-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0 16px;
        }
        .info-row {
            display: flex;
            border-bottom: 1px solid var(--border);
            padding: 4px 0;
            align-items: baseline;
        }
        .info-label {
            width: 38%;
            color: var(--gray);
            font-size: 10px;
            flex-shrink: 0;
        }
        .info-value {
            flex: 1;
            font-weight: 600;
            font-size: 10.5px;
            color: #1a1a2e;
        }

        /* ===== TABLEAU ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        thead th {
            background: var(--accent);
            color: #fff;
            padding: 5px 7px;
            font-weight: 700;
            text-align: left;
            font-size: 9.5px;
            letter-spacing: .04em;
        }
        thead th.text-center { text-align: center; }
        thead th.text-end    { text-align: right;  }
        tbody tr { border-bottom: 1px solid var(--border); }
        tbody tr:nth-child(even) { background: var(--gray-lt); }
        tbody td { padding: 4px 7px; vertical-align: middle; }
        tbody td.text-center { text-align: center; }
        tbody td.text-end    { text-align: right; }
        .table-empty { text-align: center; padding: 10px; color: var(--gray); font-style: italic; }

        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .03em;
        }
        .badge-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
        .badge-primary { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-info    { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
        .badge-secondary { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .badge-blood   { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; font-size: 10px; font-weight: 800; }

        /* ===== TIMELINE ===== */
        .timeline { padding-left: 0; list-style: none; }
        .timeline-item {
            display: flex;
            gap: 10px;
            padding-bottom: 10px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 8px; top: 18px;
            width: 1px;
            height: calc(100% - 8px);
            background: var(--border);
        }
        .timeline-item:last-child::before { display: none; }
        .tl-dot {
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--primary);
            flex-shrink: 0;
            margin-top: 1px;
        }
        .tl-body { flex: 1; }
        .tl-title { font-weight: 700; font-size: 10.5px; color: var(--accent); }
        .tl-sub { font-size: 9.5px; color: var(--gray); margin-top: 1px; }

        /* ===== ALERTES ===== */
        .alert-item {
            border-radius: 5px;
            padding: 6px 10px;
            margin-bottom: 5px;
            border-left: 3px solid;
            font-size: 10px;
        }
        .alert-danger  { background: #fff5f5; border-color: var(--danger);  }
        .alert-warning { background: #fffbeb; border-color: var(--warning); }
        .alert-title { font-weight: 700; font-size: 10.5px; }
        .alert-date  { font-size: 9px; color: var(--gray); }
        .alert-desc  { margin-top: 2px; color: #374151; }

        /* ===== FINANCE SUMMARY ===== */
        .finance-summary {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            margin-bottom: 10px;
        }
        .finance-card {
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }
        .fc-paye    { background: #dcfce7; border: 1px solid #bbf7d0; }
        .fc-reste   { background: #fee2e2; border: 1px solid #fecaca; }
        .fc-nb      { background: #dbeafe; border: 1px solid #bfdbfe; }
        .fc-val  { font-size: 13px; font-weight: 800; }
        .fc-lbl  { font-size: 9px; color: var(--gray); }
        .fc-paye  .fc-val  { color: var(--success); }
        .fc-reste .fc-val  { color: var(--danger);  }
        .fc-nb    .fc-val  { color: var(--info);    }

        /* ===== PARENTS ===== */
        .parent-card {
            display: flex;
            gap: 10px;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 6px;
        }
        .parent-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800;
            flex-shrink: 0;
        }
        .parent-info { flex: 1; }
        .parent-name { font-weight: 700; font-size: 11px; }
        .parent-role { font-size: 9.5px; color: var(--gray); }
        .parent-contacts { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
        .parent-contact-item { font-size: 9.5px; color: var(--accent); }

        /* ===== PIED DE PAGE ===== */
        .doc-footer {
            border-top: 2px solid var(--border);
            padding-top: 8px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 9px;
            color: var(--gray);
        }
        .signature-block {
            text-align: center;
            border-top: 1px solid #999;
            padding-top: 4px;
            width: 120px;
            font-size: 9px;
        }

        /* ===== BOUTON IMPRESSION ===== */
        .print-actions {
            position: fixed;
            top: 16px;
            right: 16px;
            display: flex;
            gap: 8px;
            z-index: 999;
        }
        .btn-print {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .btn-back {
            background: #334155;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }

        /* ===== COLONNES ===== */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .col-span-full { grid-column: 1 / -1; }

        code {
            font-family: 'Courier New', monospace;
            background: var(--gray-lt);
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 10px;
            color: var(--accent);
        }
    </style>
</head>
<body>

<!-- Boutons d'action (masqués à l'impression) -->
<div class="print-actions no-print">
    <a href="{{ route('dossiers_eleves.show', $eleve->id) }}" class="btn-back">
        &#8592; Retour au dossier
    </a>
    <button onclick="window.print()" class="btn-print">
        &#128438; Imprimer / PDF
    </button>
</div>

<div class="page">

    {{-- ===== EN-TÊTE DOCUMENT ===== --}}
    <div class="doc-header">
        <div class="school-block">
            <div class="school-name">{{ $eleve->etablissement->nom ?? 'Établissement scolaire' }}</div>
            @if ($eleve->etablissement)
                @if ($eleve->etablissement->adresse ?? null)
                    <div class="school-sub">{{ $eleve->etablissement->adresse }}</div>
                @endif
                @if ($eleve->etablissement->telephone ?? null)
                    <div class="school-sub">Tél. : {{ $eleve->etablissement->telephone }}</div>
                @endif
                @if ($eleve->etablissement->email ?? null)
                    <div class="school-sub">Email : {{ $eleve->etablissement->email }}</div>
                @endif
            @endif
        </div>
        <div class="doc-title-block">
            <div class="doc-title">Dossier Scolaire</div>
            <div class="doc-meta">Édité le {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY [à] HH:mm') }}</div>
            <div class="doc-meta">Réf : <strong>DOS-{{ str_pad($eleve->id, 5, '0', STR_PAD_LEFT) }}</strong></div>
            <div class="confidential">CONFIDENTIEL</div>
        </div>
    </div>

    {{-- ===== FICHE IDENTITÉ ===== --}}
    <div class="identity-card">
        <div class="identity-avatar">
            {{ strtoupper(substr($eleve->prenom, 0, 1)) }}{{ strtoupper(substr($eleve->nom, 0, 1)) }}
        </div>
        <div class="identity-info">
            <div class="identity-name">{{ strtoupper($eleve->nom) }} {{ ucfirst(strtolower($eleve->prenom)) }}</div>
            <div class="identity-sub">
                @if ($eleve->date_naissance)
                    Né(e) le {{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMMM YYYY') }}
                    ({{ \Carbon\Carbon::parse($eleve->date_naissance)->age }} ans)
                    @if ($eleve->lieu_naissance) à {{ $eleve->lieu_naissance }}@endif
                @endif
            </div>
            <div class="identity-badges">
                <span class="ibadge">&#35; {{ $eleve->registration_number }}</span>
                @if ($eleve->genre)
                    <span class="ibadge">{{ $eleve->genre == 'M' ? '♂ Masculin' : '♀ Féminin' }}</span>
                @endif
                @if ($derniereInscription)
                    <span class="ibadge">&#127979; {{ $derniereInscription->classe->nom ?? '—' }}</span>
                    <span class="ibadge">&#128197; {{ $derniereInscription->anneeScolaire->libelle ?? '—' }}</span>
                @endif
                @php
                    $badgeColors = ['actif' => 'badge-success', 'suspendu' => 'badge-warning', 'diplome' => 'badge-primary', 'abandonne' => 'badge-danger'];
                    $statusLabel = ['actif' => '✓ Actif', 'suspendu' => '⚠ Suspendu', 'diplome' => '🎓 Diplômé', 'abandonne' => '✗ Abandonné'];
                @endphp
                <span class="ibadge">{{ $statusLabel[$eleve->statut] ?? ucfirst($eleve->statut ?? 'N/A') }}</span>
            </div>
        </div>
        <div class="identity-stats">
            <div class="istat">
                <div class="istat-val">{{ $totalAbsences }}</div>
                <div class="istat-lbl">Absences</div>
            </div>
            <div class="istat">
                <div class="istat-val">{{ $totalRetards }}</div>
                <div class="istat-lbl">Retards</div>
            </div>
            <div class="istat">
                <div class="istat-val">{{ $totalIncidents }}</div>
                <div class="istat-lbl">Incidents</div>
            </div>
            <div class="istat">
                <div class="istat-val">{{ number_format($totalPaye / 1000, 0) }}k</div>
                <div class="istat-lbl">Payé FCFA</div>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 1 : INFORMATIONS PERSONNELLES ===== --}}
    <section class="section">
        <div class="section-title"><span class="dot"></span> 1. Informations personnelles & scolaires</div>
        <div class="two-col">
            <!-- Colonne gauche : Infos personnelles -->
            <div>
                <div class="info-row">
                    <span class="info-label">Nom complet</span>
                    <span class="info-value">{{ $eleve->prenom }} {{ $eleve->nom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Genre</span>
                    <span class="info-value">{{ $eleve->genre == 'M' ? 'Masculin' : ($eleve->genre == 'F' ? 'Féminin' : ($eleve->genre ?? '—')) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de naissance</span>
                    <span class="info-value">{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lieu de naissance</span>
                    <span class="info-value">{{ $eleve->lieu_naissance ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nationalité</span>
                    <span class="info-value">{{ $eleve->nationalite ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Adresse</span>
                    <span class="info-value">{{ $eleve->adresse ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone</span>
                    <span class="info-value">{{ $eleve->telephone ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $eleve->user->email ?? '—' }}</span>
                </div>
            </div>
            <!-- Colonne droite : Infos scolaires & médicales -->
            <div>
                <div class="info-row">
                    <span class="info-label">Matricule</span>
                    <span class="info-value"><code>{{ $eleve->registration_number }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Établissement</span>
                    <span class="info-value">{{ $eleve->etablissement->nom ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date d'inscription</span>
                    <span class="info-value">{{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut</span>
                    <span class="info-value">
                        @php $bc = $badgeColors[$eleve->statut] ?? 'badge-secondary'; @endphp
                        <span class="badge {{ $bc }}">{{ ucfirst($eleve->statut ?? 'N/A') }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Classe actuelle</span>
                    <span class="info-value">{{ $derniereInscription->classe->nom ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Niveau</span>
                    <span class="info-value">{{ $derniereInscription->classe->niveau->libelle ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Groupe sanguin</span>
                    <span class="info-value">
                        @if ($eleve->groupe_sanguin)
                            <span class="badge badge-blood">{{ $eleve->groupe_sanguin }}</span>
                        @else —@endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Notes médicales</span>
                    <span class="info-value">{{ $eleve->notes_medicales ?? '—' }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 2 : HISTORIQUE SCOLARITÉ ===== --}}
    <section class="section">
        <div class="section-title"><span class="dot"></span> 2. Historique de scolarité</div>
        @if ($eleve->inscriptions->isEmpty())
            <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucune inscription enregistrée.</p>
        @else
            <ul class="timeline">
                @foreach ($eleve->inscriptions->sortByDesc('created_at') as $inscription)
                    <li class="timeline-item">
                        <div class="tl-dot"></div>
                        <div class="tl-body">
                            <div class="tl-title">
                                {{ $inscription->classe->nom ?? '—' }}
                                @if ($inscription->classe->niveau ?? null)
                                    <span class="badge badge-secondary" style="margin-left:5px;">{{ $inscription->classe->niveau->libelle }}</span>
                                @endif
                            </div>
                            <div class="tl-sub">
                                Année scolaire : <strong>{{ $inscription->anneeScolaire->libelle ?? '—' }}</strong>
                                &nbsp;&bull;&nbsp;
                                Inscrit le : {{ $inscription->date_inscription ? \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') : '—' }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- ===== SECTION 3 : BULLETINS ===== --}}
    @if ($eleve->bulletins->isNotEmpty())
    <section class="section">
        <div class="section-title"><span class="dot"></span> 3. Résultats scolaires & bulletins</div>
        <table>
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Classe</th>
                    <th class="text-center">Moyenne</th>
                    <th class="text-center">Rang</th>
                    <th class="text-center">Absences</th>
                    <th class="text-center">Mention</th>
                    <th>Appréciation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eleve->bulletins->sortByDesc('created_at') as $bulletin)
                    @php $moy = (float)($bulletin->moyenne_globale ?? 0); @endphp
                    <tr>
                        <td><strong>{{ $bulletin->periodEvaluation->libelle ?? '—' }}</strong></td>
                        <td>{{ $bulletin->classe->nom ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $moy >= 10 ? 'badge-success' : 'badge-danger' }}">
                                {{ number_format($moy, 2) }}/20
                            </span>
                        </td>
                        <td class="text-center">{{ $bulletin->rang ?? '—' }}/{{ $bulletin->total_eleves ?? '?' }}</td>
                        <td class="text-center">{{ $bulletin->absences ?? 0 }}</td>
                        <td class="text-center">
                            @if ($bulletin->mention_conduite)
                                <span class="badge badge-info">{{ $bulletin->mention_conduite }}</span>
                            @else —@endif
                        </td>
                        <td style="font-size:9.5px;color:#374151;">{{ Str::limit($bulletin->commentaire_principal ?? '—', 70) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    @endif

    {{-- ===== SECTION 4 : ASSIDUITÉ ===== --}}
    <section class="section">
        <div class="section-title"><span class="dot"></span> 4. Assiduité</div>
        <div class="two-col">
            <!-- Absences -->
            <div>
                <p style="font-weight:700;font-size:10px;margin-bottom:5px;color:var(--accent);">
                    Absences <span class="badge badge-danger">{{ $totalAbsences }}</span>
                </p>
                @if ($eleve->absences->isEmpty())
                    <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucune absence enregistrée.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Motif</th>
                                <th class="text-center">Justifiée</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eleve->absences->sortByDesc('date_absence') as $absence)
                                <tr>
                                    <td>{{ $absence->date_absence ? \Carbon\Carbon::parse($absence->date_absence)->format('d/m/Y') : '—' }}</td>
                                    <td>{{ $absence->motif ?? '—' }}</td>
                                    <td class="text-center">
                                        @if ($absence->justifiee ?? false)
                                            <span class="badge badge-success">Oui</span>
                                        @else
                                            <span class="badge badge-secondary">Non</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <!-- Retards -->
            <div>
                <p style="font-weight:700;font-size:10px;margin-bottom:5px;color:var(--accent);">
                    Retards <span class="badge badge-warning">{{ $totalRetards }}</span>
                </p>
                @if ($eleve->retards->isEmpty())
                    <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucun retard enregistré.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Durée (min)</th>
                                <th>Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eleve->retards->sortByDesc('date_retard') as $retard)
                                <tr>
                                    <td>{{ $retard->date_retard ? \Carbon\Carbon::parse($retard->date_retard)->format('d/m/Y') : '—' }}</td>
                                    <td class="text-center">{{ $retard->duree ?? '—' }}</td>
                                    <td>{{ $retard->motif ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== SECTION 5 : DISCIPLINE ===== --}}
    @if ($totalIncidents > 0 || $eleve->sanctions->isNotEmpty())
    <section class="section page-break">
        <div class="section-title"><span class="dot"></span> 5. Discipline</div>
        <div class="two-col">
            <!-- Incidents -->
            <div>
                <p style="font-weight:700;font-size:10px;margin-bottom:5px;color:var(--accent);">
                    Incidents <span class="badge badge-danger">{{ $totalIncidents }}</span>
                </p>
                @if ($eleve->incidentsDisciplinaires->isEmpty())
                    <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucun incident enregistré.</p>
                @else
                    @foreach ($eleve->incidentsDisciplinaires->sortByDesc('date_incident') as $incident)
                        <div class="alert-item alert-danger">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span class="alert-title">{{ $incident->type ?? 'Incident' }}</span>
                                <span class="alert-date">{{ $incident->date_incident ? \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') : '' }}</span>
                            </div>
                            @if ($incident->description)
                                <div class="alert-desc">{{ $incident->description }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            <!-- Sanctions -->
            <div>
                <p style="font-weight:700;font-size:10px;margin-bottom:5px;color:var(--accent);">
                    Sanctions <span class="badge badge-warning">{{ $eleve->sanctions->count() }}</span>
                </p>
                @if ($eleve->sanctions->isEmpty())
                    <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucune sanction enregistrée.</p>
                @else
                    @foreach ($eleve->sanctions->sortByDesc('date_sanction') as $sanction)
                        <div class="alert-item alert-warning">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span class="alert-title">{{ $sanction->type ?? 'Sanction' }}</span>
                                <span class="alert-date">{{ $sanction->date_sanction ? \Carbon\Carbon::parse($sanction->date_sanction)->format('d/m/Y') : '' }}</span>
                            </div>
                            @if ($sanction->description)
                                <div class="alert-desc">{{ $sanction->description }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ===== SECTION 6 : PAIEMENTS ===== --}}
    <section class="section">
        <div class="section-title"><span class="dot"></span> 6. Situation financière & paiements</div>
        <div class="finance-summary">
            <div class="finance-card fc-paye">
                <div class="fc-val">{{ number_format($totalPaye, 0, ',', ' ') }}</div>
                <div class="fc-lbl">Total payé (FCFA)</div>
            </div>
            <div class="finance-card fc-reste">
                <div class="fc-val">{{ number_format($totalReste, 0, ',', ' ') }}</div>
                <div class="fc-lbl">Reste à payer (FCFA)</div>
            </div>
            <div class="finance-card fc-nb">
                <div class="fc-val">{{ $eleve->paiements->count() }}</div>
                <div class="fc-lbl">Transactions</div>
            </div>
        </div>
        @if ($eleve->paiements->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Type de frais</th>
                        <th>Année scolaire</th>
                        <th>Date</th>
                        <th class="text-end">Montant (FCFA)</th>
                        <th class="text-end">Reste (FCFA)</th>
                        <th class="text-center">Méthode</th>
                        <th class="text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eleve->paiements->sortByDesc('date_paiement') as $paiement)
                        @php
                            $sc = match($paiement->status ?? '') {
                                'complet'    => 'badge-success',
                                'partiel'    => 'badge-warning',
                                'en_attente' => 'badge-secondary',
                                default      => 'badge-secondary',
                            };
                        @endphp
                        <tr>
                            <td><code>{{ $paiement->reference ?? '—' }}</code></td>
                            <td>{{ $paiement->fraiScolarite->libelle ?? '—' }}</td>
                            <td>{{ $paiement->anneeScolaire->libelle ?? '—' }}</td>
                            <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : '—' }}</td>
                            <td class="text-end" style="font-weight:700;color:var(--success);">{{ number_format($paiement->montant ?? 0, 0, ',', ' ') }}</td>
                            <td class="text-end" style="color:{{ ($paiement->reste_a_payer ?? 0) > 0 ? 'var(--danger)' : 'var(--success)' }};">{{ number_format($paiement->reste_a_payer ?? 0, 0, ',', ' ') }}</td>
                            <td class="text-center">
                                <span class="badge badge-secondary">{{ $paiement->methode_paiement ?? '—' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $sc }}">{{ ucfirst($paiement->status ?? '—') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucun paiement enregistré.</p>
        @endif
    </section>

    {{-- ===== SECTION 7 : PARENTS / TUTEURS ===== --}}
    <section class="section">
        <div class="section-title"><span class="dot"></span> 7. Parents & Tuteurs</div>
        @if ($eleve->eleveParents->isEmpty())
            <p style="color:var(--gray);font-style:italic;font-size:10px;">Aucun parent ou tuteur associé.</p>
        @else
            <div class="two-col">
                @foreach ($eleve->eleveParents as $ep)
                    @php $tuteur = $ep->tuteur; @endphp
                    @if ($tuteur)
                        <div class="parent-card">
                            <div class="parent-avatar">
                                {{ strtoupper(substr($tuteur->prenom, 0, 1)) }}{{ strtoupper(substr($tuteur->nom, 0, 1)) }}
                            </div>
                            <div class="parent-info">
                                <div class="parent-name">{{ $tuteur->prenom }} {{ $tuteur->nom }}</div>
                                <div class="parent-role">{{ $tuteur->relationship ?? 'Tuteur' }}</div>
                                <div class="parent-contacts">
                                    @if ($tuteur->telephone)
                                        <span class="parent-contact-item">📞 {{ $tuteur->telephone }}</span>
                                    @endif
                                    @if ($tuteur->email)
                                        <span class="parent-contact-item">✉ {{ $tuteur->email }}</span>
                                    @endif
                                </div>
                                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:4px;">
                                    @if ($ep->is_primary)
                                        <span class="badge badge-primary">Contact principal</span>
                                    @endif
                                    @if ($ep->can_pickup)
                                        <span class="badge badge-success">Peut récupérer</span>
                                    @endif
                                    @if ($ep->emergency_contact)
                                        <span class="badge badge-danger">Urgence</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </section>

    {{-- ===== PIED DE PAGE ===== --}}
    <div class="doc-footer">
        <div>
            <div>{{ $eleve->etablissement->nom ?? 'Établissement' }}</div>
            <div>Dossier n° <strong>DOS-{{ str_pad($eleve->id, 5, '0', STR_PAD_LEFT) }}</strong>
                &nbsp;&bull;&nbsp; Élève : <strong>{{ $eleve->prenom }} {{ $eleve->nom }}</strong>
                &nbsp;&bull;&nbsp; Matricule : <strong>{{ $eleve->registration_number }}</strong>
            </div>
            <div>Document généré le {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY [à] HH:mm') }}</div>
        </div>
        <div style="display:flex;gap:24px;align-items:flex-end;">
            <div class="signature-block">Signature du Directeur</div>
            <div class="signature-block">Cachet de l'établissement</div>
        </div>
    </div>

</div><!-- /page -->

<script>
    // Auto-print si paramètre ?autoprint=1
    const params = new URLSearchParams(window.location.search);
    if (params.get('autoprint') === '1') {
        window.addEventListener('load', function() { window.print(); });
    }
</script>
</body>
</html>
