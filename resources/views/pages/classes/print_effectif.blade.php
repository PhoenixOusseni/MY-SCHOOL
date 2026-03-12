<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Effectif — {{ $classe->nom }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            body {
                margin: 0;
            }

            .page {
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        /* ===== COULEURS ===== */
        :root {
            --primary: #1e3a5f;
            --primary-lt: #eef3fa;
            --accent: #c41e3a;
            --gray: #64748b;
            --gray-lt: #f8fafc;
            --border: #e2e8f0;
            --success: #16a34a;
            --danger: #dc2626;
        }

        /* ===== EN-TÊTE ===== */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .school-block {
            flex: 1;
        }

        .school-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .school-sub {
            font-size: 10px;
            color: var(--gray);
            margin-top: 2px;
        }

        .doc-title-block {
            text-align: right;
        }

        .doc-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .doc-meta {
            font-size: 9px;
            color: var(--gray);
            margin-top: 3px;
        }

        /* ===== BANDEAU CLASSE ===== */
        .class-banner {
            background: linear-gradient(135deg, var(--primary) 0%, #2d5986 100%);
            color: #fff;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .class-banner .class-name {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: .03em;
        }

        .class-banner .class-sub {
            font-size: 10px;
            opacity: .85;
            margin-top: 3px;
        }

        .class-stats {
            display: flex;
            gap: 20px;
        }

        .cstat {
            text-align: center;
        }

        .cstat-val {
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
        }

        .cstat-lbl {
            font-size: 8.5px;
            opacity: .75;
            margin-top: 2px;
        }

        .cstat-sep {
            width: 1px;
            background: rgba(255, 255, 255, .25);
            align-self: stretch;
        }

        /* ===== RÉSUMÉ GENRE ===== */
        .gender-row {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
        }

        .gender-card {
            flex: 1;
            border-radius: 6px;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .gc-m {
            background: #dbeafe;
            border: 1px solid #bfdbfe;
        }

        .gc-f {
            background: #fce7f3;
            border: 1px solid #fbcfe8;
        }

        .gc-t {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }

        .gc-icon {
            font-size: 20px;
        }

        .gc-val {
            font-size: 18px;
            font-weight: 800;
        }

        .gc-m .gc-val {
            color: #1d4ed8;
        }

        .gc-f .gc-val {
            color: #be185d;
        }

        .gc-t .gc-val {
            color: #15803d;
        }

        .gc-lbl {
            font-size: 9px;
            color: var(--gray);
        }

        /* ===== TABLEAU ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead th {
            background: var(--primary);
            color: #fff;
            padding: 6px 8px;
            font-weight: 700;
            text-align: left;
            font-size: 9.5px;
            letter-spacing: .04em;
        }

        thead th.text-center {
            text-align: center;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
        }

        tbody tr:nth-child(even) {
            background: var(--gray-lt);
        }

        tbody td {
            padding: 5px 8px;
            vertical-align: middle;
        }

        tbody td.text-center {
            text-align: center;
        }

        .num-col {
            color: var(--gray);
            font-size: 9px;
            text-align: center;
            width: 28px;
        }

        .mat-col {
            font-family: 'Courier New', monospace;
            font-size: 9.5px;
            color: var(--primary);
            font-weight: 700;
        }

        .name-col {
            font-weight: 600;
        }

        .gender-badge {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        .badge-m {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-f {
            background: #fce7f3;
            color: #be185d;
            border: 1px solid #fbcfe8;
        }

        .badge-n {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

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
            width: 130px;
            font-size: 9px;
        }

        /* ===== BOUTONS D'ACTION ===== */
        .print-actions {
            position: fixed;
            top: 16px;
            right: 16px;
            display: flex;
            gap: 8px;
            z-index: 999;
        }

        .btn-print {
            background: var(--accent);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
            text-decoration: none;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
        }

        /* ===== VIDE ===== */
        .empty-msg {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-style: italic;
            border: 1px dashed var(--border);
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <!-- Boutons d'action (masqués à l'impression) -->
    <div class="print-actions no-print">
        <a href="{{ route('gestion_classes.show', $classe->id) }}" class="btn-back">
            &#8592; Retour à la classe
        </a>
        <button onclick="window.print()" class="btn-print">
            &#128438; Imprimer / PDF
        </button>
    </div>

    <div class="page">

        {{-- ===== EN-TÊTE ===== --}}
        <div class="doc-header">
            <div class="school-block">
                <div class="school-name">{{ $classe->etablissement->nom ?? 'Établissement scolaire' }}</div>
                @if ($classe->etablissement)
                    @if ($classe->etablissement->adresse ?? null)
                        <div class="school-sub">&#128205; {{ $classe->etablissement->adresse }}</div>
                    @endif
                    @if ($classe->etablissement->telephone ?? null)
                        <div class="school-sub">&#128222; {{ $classe->etablissement->telephone }}</div>
                    @endif
                    @if ($classe->etablissement->email ?? null)
                        <div class="school-sub">&#9993; {{ $classe->etablissement->email }}</div>
                    @endif
                @endif
            </div>
            <div class="doc-title-block">
                <div class="doc-title">Liste d'Effectif</div>
                <div class="doc-meta">
                    Année scolaire :
                    <strong>{{ $classe->anneeScolaire->libelle ?? 'N/A' }}</strong>
                </div>
                <div class="doc-meta">Édité le {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY [à] HH:mm') }}</div>
                <div class="doc-meta">Réf :
                    <strong>EFF-{{ str_pad($classe->id, 4, '0', STR_PAD_LEFT) }}-{{ date('Y') }}</strong></div>
            </div>
        </div>

        {{-- ===== BANDEAU CLASSE ===== --}}
        @php
            $inscriptions = $classe->inscriptions ?? collect();
            $total = $inscriptions->count();
            $garcons = $inscriptions->filter(fn($i) => strtoupper($i->eleve->genre ?? '') === 'M')->count();
            $filles = $inscriptions->filter(fn($i) => strtoupper($i->eleve->genre ?? '') === 'F')->count();
            $autres = $total - $garcons - $filles;
            $taux = $classe->capacite > 0 ? round(($total / $classe->capacite) * 100, 1) : 0;
        @endphp

        <div class="class-banner">
            <div>
                <div class="class-name">{{ $classe->nom }}</div>
                <div class="class-sub">
                    @if ($classe->niveau)
                        Niveau : {{ $classe->niveau->nom }} &nbsp;&bull;&nbsp;
                    @endif
                    @if ($classe->salle)
                        Salle : {{ $classe->salle }} &nbsp;&bull;&nbsp;
                    @endif
                    Capacité : {{ $classe->capacite ?? '—' }} élèves
                </div>
            </div>
            <div class="class-stats">
                <div class="cstat">
                    <div class="cstat-val">{{ $total }}</div>
                    <div class="cstat-lbl">Effectif</div>
                </div>
                <div class="cstat-sep"></div>
                <div class="cstat">
                    <div class="cstat-val">{{ $classe->capacite ?? '—' }}</div>
                    <div class="cstat-lbl">Capacité</div>
                </div>
                <div class="cstat-sep"></div>
                <div class="cstat">
                    <div class="cstat-val">{{ $taux }}%</div>
                    <div class="cstat-lbl">Remplissage</div>
                </div>
            </div>
        </div>



        {{-- ===== LISTE DES ÉLÈVES ===== --}}
        @if ($total > 0)
            <table>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Matricule</th>
                        <th>Nom &amp; Prénom</th>
                        <th class="text-center">Genre</th>
                        <th>Date de naissance</th>
                        <th>Lieu de naissance</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inscriptions->sortBy(fn($i) => $i->eleve->nom ?? '') as $index => $inscription)
                        @php $eleve = $inscription->eleve; @endphp
                        <tr>
                            <td class="num-col">{{ $index + 1 }}</td>
                            <td class="mat-col">{{ $eleve->registration_number ?? '—' }}</td>
                            <td class="name-col">
                                {{ strtoupper($eleve->nom ?? '') }}
                                {{ ucfirst(strtolower($eleve->prenom ?? '')) }}
                            </td>
                            <td class="text-center">
                                @if (strtoupper($eleve->genre ?? '') === 'M')
                                    <span class="gender-badge badge-m">&#9794; M</span>
                                @elseif (strtoupper($eleve->genre ?? '') === 'F')
                                    <span class="gender-badge badge-f">&#9792; F</span>
                                @else
                                    <span class="gender-badge badge-n">—</span>
                                @endif
                            </td>
                            <td>
                                {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : '—' }}
                            </td>
                            <td>{{ $eleve->lieu_naissance ?? '—' }}</td>
                            <td>{{ $eleve->telephone ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Récap bas de tableau --}}
            <div style="margin-top:8px;display:flex;gap:16px;font-size:9.5px;color:var(--gray);">
                <span><strong>Total :</strong> {{ $total }} élève(s)</span>
                <span><strong>Garçons :</strong> {{ $garcons }}</span>
                <span><strong>Filles :</strong> {{ $filles }}</span>
                @if ($autres > 0)
                    <span><strong>Non renseigné :</strong> {{ $autres }}</span>
                @endif
            </div>
        @else
            <div class="empty-msg">
                Aucun élève inscrit dans cette classe.
            </div>
        @endif

        {{-- ===== PIED DE PAGE ===== --}}
        <div class="doc-footer">
            <div>
                <div>{{ $classe->etablissement->nom ?? 'Établissement' }}</div>
                <div>
                    Classe : <strong>{{ $classe->nom }}</strong>
                    &nbsp;&bull;&nbsp;
                    Effectif : <strong>{{ $total }}</strong> élève(s)
                    &nbsp;&bull;&nbsp;
                    Année : <strong>{{ $classe->anneeScolaire->libelle ?? 'N/A' }}</strong>
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
        const params = new URLSearchParams(window.location.search);
        if (params.get('autoprint') === '1') {
            window.addEventListener('load', function() {
                window.print();
            });
        }
    </script>
</body>

</html>
