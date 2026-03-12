<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartes Scolaires — {{ $classe->nom }}</title>
    <style>
        /* ==========================================
           RESET & BASE
        ========================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Arial', sans-serif;
            background: #e8ecf0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px 40px;
            color: #1a1a2e;
        }

        /* ==========================================
           TOOLBAR
        ========================================== */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 12px;
            padding: 14px 22px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .10);
            width: 100%;
            max-width: 820px;
        }

        .toolbar h2 {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a5f;
            flex: 1;
            letter-spacing: .02em;
        }

        .toolbar h2 span { color: #c41e3a; }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #c41e3a;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-print:hover { background: #a01830; }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f1f5f9;
            color: #1e3a5f;
            border: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-back:hover { background: #e2e8f0; }

        /* ==========================================
           RÉSUMÉ AVANT IMPRESSION
        ========================================== */
        .summary-bar {
            width: 100%;
            max-width: 820px;
            margin-bottom: 20px;
            background: #fff;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 12px;
            color: #475569;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .summary-bar strong { color: #1e3a5f; }

        /* ==========================================
           PAGE A4 (écran)
        ========================================== */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,.13);
            padding: 10mm;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            gap: 6mm;
        }

        /* En-tête de page */
        .page-header-print {
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 4mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-shrink: 0;
        }
        .ph-school {
            font-size: 11px;
            font-weight: 800;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .ph-sub {
            font-size: 8px;
            color: #64748b;
            margin-top: 1mm;
        }
        .ph-title {
            text-align: right;
            font-size: 10px;
            font-weight: 700;
            color: #c41e3a;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .ph-meta {
            font-size: 8px;
            color: #64748b;
            margin-top: 1mm;
            text-align: right;
        }

        /* ==========================================
           GRILLE DES CARTES (1 paire par ligne)
        ========================================== */
        .cards-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 5mm;
        }

        /* Bloc élève (recto + verso) */
        .student-card-block {
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .student-label {
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #94a3b8;
            text-align: center;
        }

        .cards-row-inner {
            display: flex;
            gap: 2mm;
            justify-content: center;
        }

        .card-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1mm;
        }

        .card-side-label {
            font-size: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #94a3b8;
        }

        /* ==========================================
           CARTE CR-80 — 85.6 × 54 mm
        ========================================== */
        .id-card {
            width: 85.6mm;
            height: 54mm;
            border-radius: 3.5mm;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,.18), 0 0 0 0.3mm #d1d5db;
        }

        /* ==========================================
           RECTO
        ========================================== */
        .card-front {
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
            padding: 2.5mm 3.5mm 2mm;
            display: flex;
            align-items: center;
            gap: 2.5mm;
            flex-shrink: 0;
        }

        .card-header-logo {
            width: 9mm;
            height: 9mm;
            border-radius: 1.5mm;
            background: rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .card-header-logo img { width: 100%; height: 100%; object-fit: cover; }
        .card-header-logo-placeholder {
            color: #fff;
            font-size: 5mm;
            font-weight: 900;
            opacity: .9;
        }

        .card-header-text { flex: 1; overflow: hidden; }

        .card-school-name {
            font-size: 5.2px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .06em;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-school-sub {
            font-size: 4px;
            color: rgba(255,255,255,.75);
            margin-top: .5mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-type-badge {
            background: rgba(255,255,255,.22);
            color: #fff;
            font-size: 4px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .8mm 1.8mm;
            border-radius: 1mm;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .card-body {
            flex: 1;
            display: flex;
            gap: 3mm;
            padding: 2.5mm 3mm 0;
        }

        .card-photo-col { flex-shrink: 0; }
        .card-photo {
            width: 17mm;
            height: 20mm;
            border-radius: 1.5mm;
            overflow: hidden;
            border: 0.5mm solid #e2e8f0;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-photo img { width: 100%; height: 100%; object-fit: cover; }
        .card-photo-placeholder { color: #94a3b8; font-size: 8mm; }

        .card-info-col {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: .5mm;
        }

        .card-student-name {
            font-size: 6.5px;
            font-weight: 800;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: .04em;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-reg {
            font-size: 4.5px;
            color: #c41e3a;
            font-weight: 700;
            letter-spacing: .06em;
            margin-bottom: .5mm;
        }

        .card-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .2mm 2mm;
        }

        .card-info-item {
            padding-bottom: .2mm;
            border-bottom: 0.25mm solid #f1f5f9;
            line-height: 1;
        }

        .card-info-label {
            font-size: 3.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            display: block;
            line-height: 1;
        }

        .card-info-value {
            font-size: 4.8px;
            color: #1a1a2e;
            line-height: 1;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-footer {
            background: #1e3a5f;
            padding: 1.5mm 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .card-footer-year {
            font-size: 4px;
            color: rgba(255,255,255,.8);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .card-footer-validity {
            font-size: 3.8px;
            color: rgba(255,255,255,.6);
        }

        .card-footer-dots { display: flex; gap: 1mm; }
        .card-footer-dot { width: 3mm; height: 3mm; border-radius: 50%; }

        .card-stripe {
            position: absolute;
            right: 0; top: 0;
            width: 2.5mm;
            height: 100%;
            background: linear-gradient(180deg, #c41e3a 0%, #8b1a2e 50%, #1e3a5f 100%);
        }

        /* ==========================================
           VERSO
        ========================================== */
        .card-back {
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .card-back-header {
            background: #1e3a5f;
            padding: 2mm 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .card-back-header-title {
            font-size: 4.8px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .card-back-header-sub {
            font-size: 3.5px;
            color: rgba(255,255,255,.6);
        }

        .card-back-body {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .card-back-left {
            flex: 1;
            padding: 2mm 2mm 1.5mm 3mm;
            border-right: 0.3mm solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
        }

        .card-back-right {
            width: 28mm;
            padding: 2mm 2.5mm 1.5mm 2mm;
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
        }

        .back-section-title {
            font-size: 3.8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #c41e3a;
            border-bottom: 0.3mm solid #fecdd3;
            padding-bottom: .5mm;
            margin-bottom: .5mm;
        }

        .back-info-row {
            display: flex;
            gap: 1.5mm;
            align-items: baseline;
        }

        .back-info-label {
            font-size: 3.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
            flex-shrink: 0;
            width: 14mm;
        }

        .back-info-value {
            font-size: 4.2px;
            color: #1a1a2e;
            font-weight: 600;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .blood-badge {
            display: inline-block;
            background: #fee2e2;
            color: #c41e3a;
            font-size: 5.5px;
            font-weight: 900;
            padding: 1mm 2mm;
            border-radius: 1mm;
            border: 0.4mm solid #fca5a5;
        }

        .qr-placeholder {
            width: 20mm;
            height: 20mm;
            border: 0.4mm solid #e2e8f0;
            border-radius: 1mm;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1mm;
        }
        .qr-placeholder svg { width: 13mm; height: 13mm; opacity: .2; }
        .qr-label { font-size: 3px; color: #94a3b8; text-align: center; }

        .card-back-stripe {
            position: absolute;
            left: 0; top: 0;
            width: 2.5mm;
            height: 100%;
            background: linear-gradient(180deg, #1e3a5f 0%, #c41e3a 100%);
        }

        .magnetic-stripe {
            height: 7.5mm;
            background: #1a1a1a;
            width: 100%;
            flex-shrink: 0;
            margin-top: 3mm;
        }

        .card-back-footer {
            background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
            padding: 1.5mm 3mm;
            flex-shrink: 0;
        }
        .card-back-footer-text {
            font-size: 3.5px;
            color: rgba(255,255,255,.85);
            text-align: center;
            font-style: italic;
        }

        /* ==========================================
           NOTES
        ========================================== */
        .print-notes {
            width: 100%;
            max-width: 820px;
            margin-top: 16px;
            padding: 14px 18px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            font-size: 12px;
            color: #92400e;
            line-height: 1.6;
        }
        .print-notes strong { display: block; margin-bottom: 4px; font-size: 13px; }

        /* ==========================================
           IMPRESSION A4
        ========================================== */
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                align-items: stretch;
            }

            .toolbar,
            .summary-bar,
            .print-notes {
                display: none !important;
            }

            .a4-page {
                width: 100%;
                min-height: auto;
                border-radius: 0;
                box-shadow: none;
                padding: 0;
                margin: 0;
                page-break-after: always;
                break-after: page;
            }

            .a4-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            .id-card {
                box-shadow: 0 0 0 0.4mm #94a3b8;
            }

            .student-card-block {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- ======================== TOOLBAR ======================== --}}
    <div class="toolbar no-print">
        <h2>
            Cartes Scolaires —
            <span>{{ $classe->nom }}</span>
            &nbsp;·&nbsp;
            <span style="color:#64748b;font-weight:500;">{{ $classe->anneeScolaire->libelle ?? '' }}</span>
        </h2>
        <a href="{{ route('gestion_classes.show', $classe->id) }}" class="btn-back">
            &#8592; Retour à la classe
        </a>
        <button onclick="window.print()" class="btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
            </svg>
            Imprimer toutes les cartes
        </button>
    </div>

    {{-- Résumé --}}
    @php
        $inscriptions = $classe->inscriptions ?? collect();
        $total = $inscriptions->count();
        $cardsPerPage = 3; // 3 élèves par page (1 colonne, 3 paires recto/verso)
        $pages = $inscriptions->chunk($cardsPerPage);
        $etab = $classe->etablissement;
    @endphp

    <div class="summary-bar no-print">
        <span><strong>Classe :</strong> {{ $classe->nom }}</span>
        <span><strong>Établissement :</strong> {{ $etab->nom ?? '—' }}</span>
        <span><strong>Année scolaire :</strong> {{ $classe->anneeScolaire->libelle ?? '—' }}</span>
        <span><strong>Cartes à imprimer :</strong> {{ $total }}</span>
        <span><strong>Pages :</strong> {{ $pages->count() }}</span>
    </div>

    @forelse ($pages as $pageInscriptions)

        {{-- ======================== PAGE A4 ======================== --}}
        <div class="a4-page">

            {{-- En-tête de page --}}
            <div class="page-header-print">
                <div>
                    <div class="ph-school">{{ strtoupper($etab->nom ?? 'Établissement Scolaire') }}</div>
                    @if ($etab)
                        @if ($etab->adresse ?? null)
                            <div class="ph-sub">{{ $etab->adresse }}</div>
                        @endif
                        @if ($etab->telephone ?? null)
                            <div class="ph-sub">Tél. {{ $etab->telephone }}</div>
                        @endif
                    @endif
                </div>
                <div>
                    <div class="ph-title">Cartes Scolaires — {{ $classe->nom }}</div>
                    <div class="ph-meta">
                        Année : <strong>{{ $classe->anneeScolaire->libelle ?? '—' }}</strong>
                        &nbsp;·&nbsp;
                        Édité le {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- Grille des cartes (2 colonnes) --}}
            <div class="cards-grid">

                @foreach ($pageInscriptions as $inscription)
                    @php
                        $eleve  = $inscription->eleve;
                        $tuteur = $eleve->eleveParents->first()?->tuteur ?? null;
                        $anneeLibelle = $classe->anneeScolaire->libelle
                            ?? \Carbon\Carbon::now()->year . '–' . (\Carbon\Carbon::now()->year + 1);
                    @endphp

                    <div class="student-card-block">

                        <div class="student-label">
                            {{ strtoupper($eleve->nom ?? '') }} {{ ucfirst(strtolower($eleve->prenom ?? '')) }}
                            &nbsp;·&nbsp; {{ $eleve->registration_number ?? '' }}
                        </div>

                        <div class="cards-row-inner">

                            {{-- ===== RECTO ===== --}}
                            <div class="card-wrap">
                                <span class="card-side-label">Recto</span>
                                <div class="id-card card-front">

                                    <div class="card-stripe"></div>

                                    <div class="card-header">
                                        <div class="card-header-logo">
                                            @if ($etab && ($etab->logo ?? null))
                                                <img src="{{ asset('storage/' . $etab->logo) }}" alt="Logo">
                                            @else
                                                <span class="card-header-logo-placeholder">
                                                    {{ strtoupper(substr($etab->nom ?? 'S', 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="card-header-text">
                                            <div class="card-school-name">
                                                {{ strtoupper($etab->nom ?? 'Établissement Scolaire') }}
                                            </div>
                                            <div class="card-school-sub">
                                                {{ $etab->adresse ?? '' }}
                                                @if ($etab && ($etab->telephone ?? null))
                                                    &nbsp;·&nbsp; Tél. {{ $etab->telephone }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-type-badge">Carte Élève</div>
                                    </div>

                                    <div class="card-body">
                                        <div class="card-photo-col">
                                            <div class="card-photo">
                                                @if ($eleve->photo ?? null)
                                                    <img src="{{ asset('storage/' . $eleve->photo) }}" alt="Photo">
                                                @else
                                                    <span class="card-photo-placeholder">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#94a3b8" viewBox="0 0 16 16">
                                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4z"/>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card-info-col">
                                            <div class="card-student-name">
                                                {{ strtoupper($eleve->nom ?? '') }}<br>
                                                <span style="font-weight:600;font-size:5.5px;color:#334155;">
                                                    {{ ucwords(strtolower($eleve->prenom ?? '')) }}
                                                </span>
                                            </div>
                                            <div class="card-reg">N° {{ $eleve->registration_number ?? '—' }}</div>

                                            <div class="card-info-grid">
                                                <div class="card-info-item" style="margin-top:10px;margin-bottom:10px;">
                                                    <span class="card-info-label">Classe : &nbsp;
                                                        <span class="card-info-value">{{ $classe->nom }}</span>
                                                    </span>
                                                </div>
                                                <div class="card-info-item" style="margin-top:10px;margin-bottom:10px;">
                                                    <span class="card-info-label">Niveau : &nbsp;
                                                        <span class="card-info-value">{{ $classe->niveau->nom ?? '—' }}</span>
                                                    </span>
                                                </div>
                                                <div class="card-info-item" style="margin-bottom:10px;">
                                                    <span class="card-info-label">Date naissance : &nbsp;
                                                        <span class="card-info-value">
                                                            {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : '—' }}
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="card-info-item" style="margin-bottom:10px;">
                                                    <span class="card-info-label">Lieu naissance : &nbsp;
                                                        <span class="card-info-value">{{ $eleve->lieu_naissance ?? '—' }}</span>
                                                    </span>
                                                </div>
                                                <div class="card-info-item" style="margin-bottom:10px;">
                                                    <span class="card-info-label">Sexe : &nbsp;
                                                        <span class="card-info-value">
                                                            @if (($eleve->genre ?? '') === 'M') Masculin
                                                            @elseif (($eleve->genre ?? '') === 'F') Féminin
                                                            @else {{ $eleve->genre ?? '—' }}
                                                            @endif
                                                        </span>
                                                    </span>
                                                </div>
                                                @if ($eleve->groupe_sanguin ?? null)
                                                    <div class="card-info-item" style="margin-bottom:10px;">
                                                        <span class="card-info-label">Groupe sanguin : &nbsp;
                                                            <span class="card-info-value" style="color:#c41e3a;font-weight:800;">
                                                                {{ $eleve->groupe_sanguin }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <div>
                                            <div class="card-footer-year">Année {{ $anneeLibelle }}</div>
                                            <div class="card-footer-validity">Valable pour l'année scolaire en cours</div>
                                        </div>
                                        <div class="card-footer-dots">
                                            <div class="card-footer-dot" style="background:#c41e3a;"></div>
                                            <div class="card-footer-dot" style="background:#fff;opacity:.5;"></div>
                                            <div class="card-footer-dot" style="background:#c41e3a;opacity:.7;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin recto --}}

                            {{-- ===== VERSO ===== --}}
                            <div class="card-wrap">
                                <span class="card-side-label">Verso</span>
                                <div class="id-card card-back">

                                    <div class="card-back-stripe"></div>

                                    <div class="card-back-header">
                                        <div>
                                            <div class="card-back-header-title">Informations Complémentaires</div>
                                            <div class="card-back-header-sub">
                                                {{ strtoupper($etab->nom ?? 'Établissement') }}
                                            </div>
                                        </div>
                                        @if ($eleve->groupe_sanguin ?? null)
                                            <div class="blood-badge">{{ $eleve->groupe_sanguin }}</div>
                                        @endif
                                    </div>

                                    <div class="card-back-body">
                                        <div class="card-back-left">

                                            <div>
                                                <div class="back-section-title">Contact d'urgence</div>
                                                @if ($tuteur)
                                                    <div class="back-info-row">
                                                        <span class="back-info-label">Nom</span>
                                                        <span class="back-info-value">{{ $tuteur->prenom }} {{ $tuteur->nom }}</span>
                                                    </div>
                                                    <div class="back-info-row">
                                                        <span class="back-info-label">Relation</span>
                                                        <span class="back-info-value">{{ $tuteur->relationship ?? 'Parent/Tuteur' }}</span>
                                                    </div>
                                                    <div class="back-info-row">
                                                        <span class="back-info-label">Tél.</span>
                                                        <span class="back-info-value">{{ $tuteur->telephone ?? '—' }}</span>
                                                    </div>
                                                @else
                                                    <div class="back-info-row">
                                                        <span class="back-info-value" style="color:#94a3b8;font-style:italic;">Non renseigné</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="back-section-title">École</div>
                                                <div class="back-info-row">
                                                    <span class="back-info-label">Adresse</span>
                                                    <span class="back-info-value">{{ $etab->adresse ?? '—' }}</span>
                                                </div>
                                                @if ($etab && ($etab->telephone ?? null))
                                                    <div class="back-info-row">
                                                        <span class="back-info-label">Tél.</span>
                                                        <span class="back-info-value">{{ $etab->telephone }}</span>
                                                    </div>
                                                @endif
                                                @if ($etab && ($etab->nom_directeur ?? null))
                                                    <div class="back-info-row">
                                                        <span class="back-info-label">Directeur</span>
                                                        <span class="back-info-value">{{ $etab->nom_directeur }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if ($eleve->notes_medicales ?? null)
                                                <div>
                                                    <div class="back-section-title">Note médicale</div>
                                                    <div style="font-size:3.8px;color:#64748b;line-height:1.4;">
                                                        {{ \Illuminate\Support\Str::limit($eleve->notes_medicales, 80) }}
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="card-back-right">
                                            <div class="back-section-title">Scan code QR</div>
                                            <div class="qr-placeholder">
                                                <svg viewBox="0 0 37 37" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="0"  y="0"  width="11" height="11" rx="1" fill="#1e3a5f"/>
                                                    <rect x="2"  y="2"  width="7"  height="7"  rx=".5" fill="#fff"/>
                                                    <rect x="3.5" y="3.5" width="4" height="4" fill="#1e3a5f"/>
                                                    <rect x="26" y="0"  width="11" height="11" rx="1" fill="#1e3a5f"/>
                                                    <rect x="28" y="2"  width="7"  height="7"  rx=".5" fill="#fff"/>
                                                    <rect x="29.5" y="3.5" width="4" height="4" fill="#1e3a5f"/>
                                                    <rect x="0"  y="26" width="11" height="11" rx="1" fill="#1e3a5f"/>
                                                    <rect x="2"  y="28" width="7"  height="7"  rx=".5" fill="#fff"/>
                                                    <rect x="3.5" y="29.5" width="4" height="4" fill="#1e3a5f"/>
                                                    <rect x="14" y="0"  width="2"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="17" y="0"  width="3"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="22" y="0"  width="2"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="14" y="3"  width="3"  height="3"  fill="#1e3a5f"/>
                                                    <rect x="19" y="3"  width="5"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="14" y="7"  width="2"  height="4"  fill="#1e3a5f"/>
                                                    <rect x="17" y="8"  width="4"  height="3"  fill="#1e3a5f"/>
                                                    <rect x="22" y="6"  width="3"  height="5"  fill="#1e3a5f"/>
                                                    <rect x="14" y="14" width="9"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="0"  y="14" width="2"  height="9"  fill="#1e3a5f"/>
                                                    <rect x="3"  y="14" width="3"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="7"  y="14" width="5"  height="4"  fill="#1e3a5f"/>
                                                    <rect x="3"  y="17" width="2"  height="6"  fill="#1e3a5f"/>
                                                    <rect x="6"  y="19" width="5"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="0"  y="24" width="2"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="24" y="14" width="2"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="27" y="14" width="5"  height="5"  fill="#1e3a5f"/>
                                                    <rect x="33" y="14" width="4"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="24" y="17" width="2"  height="8"  fill="#1e3a5f"/>
                                                    <rect x="27" y="20" width="4"  height="3"  fill="#1e3a5f"/>
                                                    <rect x="32" y="17" width="5"  height="4"  fill="#1e3a5f"/>
                                                    <rect x="14" y="17" width="2"  height="10" fill="#1e3a5f"/>
                                                    <rect x="17" y="24" width="5"  height="2"  fill="#1e3a5f"/>
                                                    <rect x="23" y="23" width="2"  height="5"  fill="#1e3a5f"/>
                                                    <rect x="17" y="27" width="2"  height="5"  fill="#1e3a5f"/>
                                                    <rect x="20" y="29" width="2"  height="3"  fill="#1e3a5f"/>
                                                    <rect x="27" y="26" width="10" height="2"  fill="#1e3a5f"/>
                                                    <rect x="27" y="29" width="4"  height="3"  fill="#1e3a5f"/>
                                                    <rect x="33" y="29" width="4"  height="5"  fill="#1e3a5f"/>
                                                    <rect x="27" y="33" width="5"  height="4"  fill="#1e3a5f"/>
                                                </svg>
                                                <span class="qr-label">{{ $eleve->registration_number ?? '' }}</span>
                                            </div>
                                            <div style="font-size:3.5px;color:#94a3b8;text-align:center;margin-top:1mm;">
                                                Inscrit le<br>
                                                <strong style="color:#1e3a5f;font-size:4px;">
                                                    {{ $eleve->date_inscription
                                                        ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y')
                                                        : '—' }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="magnetic-stripe"></div>

                                    <div class="card-back-footer">
                                        <div class="card-back-footer-text">
                                            En cas de perte, veuillez retourner cette carte à l'établissement.
                                            @if ($etab && ($etab->telephone ?? null))
                                                Tél. : {{ $etab->telephone }}
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- Fin verso --}}

                        </div>
                        {{-- Fin cards-row-inner --}}

                    </div>
                    {{-- Fin student-card-block --}}

                @endforeach

            </div>
            {{-- Fin cards-grid --}}

        </div>
        {{-- Fin a4-page --}}

    @empty
        <div style="background:#fff;border-radius:8px;padding:40px;text-align:center;color:#64748b;font-size:14px;box-shadow:0 2px 12px rgba(0,0,0,.08);">
            Aucun élève inscrit dans cette classe.
        </div>
    @endforelse

    {{-- Notes d'impression --}}
    <div class="print-notes no-print">
        <strong>Instructions d'impression</strong>
        • Format papier : A4 Portrait<br>
        • Désactiver les en-têtes et pieds de page du navigateur avant impression<br>
        • Utiliser du papier cartonné (200 g/m²) pour un résultat professionnel<br>
        • Chaque page contient 3 élèves (recto + verso par élève) — dimensions carte : 85.6 × 54 mm (ISO CR-80)
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.get('autoprint') === '1') {
            window.addEventListener('load', function() { window.print(); });
        }
    </script>

</body>
</html>
