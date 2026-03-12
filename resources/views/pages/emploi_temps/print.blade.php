<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps — {{ $classe?->nom ?? 'Classe' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 10px;
            color: #1a1a2e;
            background: #fff;
            line-height: 1.4;
        }

        /* ===== PAGE ===== */
        .page {
            width: 297mm;
            min-height: 210mm;
            margin: 0 auto;
            padding: 12mm 12mm 10mm;
            background: #fff;
        }

        @page {
            size: A4 landscape;
            margin: 10mm 12mm 8mm;
        }

        @media print {
            body { margin: 0; }
            .page { width: 100%; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }

        /* ===== COULEURS ===== */
        :root {
            --primary:    #1e3a5f;
            --primary-lt: #eef3fa;
            --accent:     #c41e3a;
            --gray:       #64748b;
            --gray-lt:    #f8fafc;
            --border:     #e2e8f0;
        }

        /* ===== EN-TÊTE ===== */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .school-block { flex: 1; }

        .school-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .school-sub {
            font-size: 9px;
            color: var(--gray);
            margin-top: 2px;
        }

        .doc-title-block { text-align: right; }

        .doc-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .doc-meta {
            font-size: 9px;
            color: var(--gray);
            margin-top: 2px;
        }

        /* ===== BANDEAU CLASSE ===== */
        .class-banner {
            background: linear-gradient(135deg, var(--primary) 0%, #2d5986 100%);
            color: #fff;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .class-name { font-size: 15px; font-weight: 800; }

        .class-sub {
            font-size: 9px;
            opacity: .85;
            margin-top: 2px;
        }

        .banner-stats {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .bstat { text-align: center; }
        .bstat-val { font-size: 16px; font-weight: 800; line-height: 1; }
        .bstat-lbl { font-size: 8px; opacity: .75; margin-top: 1px; }
        .bstat-sep { width: 1px; background: rgba(255,255,255,.25); align-self: stretch; }

        /* ===== GRILLE ===== */
        .timetable {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9px;
        }

        .timetable th {
            background: var(--primary);
            color: #fff;
            text-align: center;
            padding: 5px 4px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .04em;
            border: 1px solid #fff;
        }

        .timetable th.col-heure {
            width: 46px;
            background: #2d5986;
        }

        .timetable td {
            border: 1px solid var(--border);
            vertical-align: top;
            padding: 0;
            height: 24px;
        }

        .timetable td.heure-cell {
            background: var(--primary-lt);
            color: var(--primary);
            font-weight: 700;
            font-size: 8.5px;
            text-align: center;
            vertical-align: middle;
            border-right: 2px solid var(--primary);
        }

        .timetable tr:nth-child(even) td:not(.heure-cell) {
            background: #fafbfc;
        }

        /* ===== CRÉNEAUX ===== */
        .creneau-cell {
            padding: 3px 4px;
            border-radius: 3px;
            margin: 1px 2px;
            overflow: hidden;
        }

        .creneau-matiere {
            font-weight: 700;
            font-size: 9px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .creneau-enseignant {
            font-size: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            opacity: .85;
        }

        .creneau-time {
            font-size: 7.5px;
            opacity: .7;
            margin-top: 1px;
        }

        .creneau-salle {
            font-size: 7.5px;
            opacity: .7;
        }

        /* Cellule vide dans la grille compacte */
        .td-vide { background: #fff; }

        /* ===== LÉGENDE ===== */
        .legend {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 8.5px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* ===== PIED DE PAGE ===== */
        .doc-footer {
            border-top: 2px solid var(--border);
            padding-top: 6px;
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 8.5px;
            color: var(--gray);
        }

        .signature-block {
            text-align: center;
            border-top: 1px solid #999;
            padding-top: 3px;
            width: 120px;
            font-size: 8.5px;
        }

        /* ===== BOUTONS ===== */
        .print-actions {
            position: fixed;
            top: 14px;
            right: 14px;
            display: flex;
            gap: 8px;
            z-index: 999;
        }

        .btn-print {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
            text-decoration: none;
        }

        .btn-back {
            background: #334155;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }

        .empty-msg {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-style: italic;
            border: 1px dashed var(--border);
            border-radius: 6px;
            font-size: 11px;
        }
    </style>
</head>
<body>

{{-- ===== BOUTONS (masqués à l'impression) ===== --}}
<div class="print-actions no-print">
    <a href="{{ route('gestion_emploi_temps.index', ['annee_id' => $annee?->id, 'classe_id' => $classeId]) }}"
       class="btn-back">&#8592; Retour</a>
    <button onclick="window.print()" class="btn-print">&#128438; Imprimer / PDF</button>
</div>

<div class="page">

    @php
        $palette = [
            '#4e73df','#1cc88a','#36b9cc','#f6c23e',
            '#e74a3b','#6f42c1','#fd7e14','#20c9a6',
            '#e83e8c','#17a2b8','#28a745','#dc3545',
        ];
        $colorMap = [];
        foreach ($creneaux as $c) {
            $mid = $c->enseignementMatiereClasse?->matiere?->id ?? 0;
            if (!isset($colorMap[$mid])) {
                $colorMap[$mid] = $palette[count($colorMap) % count($palette)];
            }
        }

        // Collecter toutes les plages horaires présentes
        $heures = [];
        foreach ($creneaux as $c) {
            $debut = substr($c->heure_debut, 0, 5);
            $fin   = substr($c->heure_fin,   0, 5);
            if (!in_array($debut, $heures)) $heures[] = $debut;
            if (!in_array($fin,   $heures)) $heures[] = $fin;
        }
        sort($heures);

        // Construire les lignes : chaque ligne = [heure_debut → heure_fin]
        $lignes = [];
        for ($i = 0; $i < count($heures) - 1; $i++) {
            $lignes[] = ['debut' => $heures[$i], 'fin' => $heures[$i + 1]];
        }

        // Si aucun créneau, lignes par défaut (07:00 → 18:00 toutes les heures)
        if (empty($lignes)) {
            for ($h = 7; $h < 18; $h++) {
                $lignes[] = [
                    'debut' => str_pad($h,   2, '0', STR_PAD_LEFT) . ':00',
                    'fin'   => str_pad($h+1, 2, '0', STR_PAD_LEFT) . ':00',
                ];
            }
        }

        $jours = \App\Models\EmploiTemp::JOURS;
        $totalCreneaux = $creneaux->count();
        $matieres = $creneaux->map(fn($c) => $c->enseignementMatiereClasse?->matiere?->intitule)
                             ->filter()->unique()->count();
    @endphp

    {{-- ===== EN-TÊTE ===== --}}
    <div class="doc-header">
        <div class="school-block">
            <div class="school-name">{{ $classe?->etablissement?->nom ?? 'Établissement scolaire' }}</div>
            @if($classe?->etablissement)
                @if($classe->etablissement->adresse ?? null)
                    <div class="school-sub">&#128205; {{ $classe->etablissement->adresse }}</div>
                @endif
                @if($classe->etablissement->telephone ?? null)
                    <div class="school-sub">&#128222; {{ $classe->etablissement->telephone }}</div>
                @endif
                @if($classe->etablissement->email ?? null)
                    <div class="school-sub">&#9993; {{ $classe->etablissement->email }}</div>
                @endif
            @endif
        </div>
        <div class="doc-title-block">
            <div class="doc-title">Emploi du Temps</div>
            <div class="doc-meta">
                Année scolaire : <strong>{{ $annee?->libelle ?? 'N/A' }}</strong>
            </div>
            <div class="doc-meta">Édité le {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY [à] HH:mm') }}</div>
            <div class="doc-meta">
                Réf : <strong>EDT-{{ str_pad($classe?->id ?? 0, 4, '0', STR_PAD_LEFT) }}-{{ date('Y') }}</strong>
            </div>
        </div>
    </div>

    {{-- ===== BANDEAU CLASSE ===== --}}
    <div class="class-banner">
        <div>
            <div class="class-name">{{ $classe?->nom ?? '—' }}</div>
            <div class="class-sub">
                @if($classe?->niveau)
                    Niveau : {{ $classe->niveau->nom }} &nbsp;&bull;&nbsp;
                @endif
                @if($classe?->salle)
                    Salle : {{ $classe->salle }} &nbsp;&bull;&nbsp;
                @endif
                Année scolaire : {{ $annee?->libelle ?? '—' }}
            </div>
        </div>
        <div class="banner-stats">
            <div class="bstat">
                <div class="bstat-val">{{ $totalCreneaux }}</div>
                <div class="bstat-lbl">Créneaux</div>
            </div>
            <div class="bstat-sep"></div>
            <div class="bstat">
                <div class="bstat-val">{{ $matieres }}</div>
                <div class="bstat-lbl">Matières</div>
            </div>
            <div class="bstat-sep"></div>
            <div class="bstat">
                <div class="bstat-val">{{ count($jours) }}</div>
                <div class="bstat-lbl">Jours</div>
            </div>
        </div>
    </div>

    {{-- ===== GRILLE ===== --}}
    @if($creneaux->isEmpty())
        <div class="empty-msg">
            Aucun créneau enregistré pour cette classe.
        </div>
    @else
        <table class="timetable">
            <thead>
                <tr>
                    <th class="col-heure">Horaire</th>
                    @foreach($jours as $jour)
                        <th>{{ $jour }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($lignes as $ligne)
                    <tr>
                        <td class="heure-cell">
                            {{ $ligne['debut'] }}<br><span style="font-size:7px;opacity:.7;">{{ $ligne['fin'] }}</span>
                        </td>
                        @foreach($jours as $jour)
                            @php
                                // Chercher un créneau qui couvre exactement cette plage
                                $match = collect($grille[$jour] ?? [])->first(function($c) use ($ligne) {
                                    return substr($c->heure_debut, 0, 5) === $ligne['debut']
                                        && substr($c->heure_fin,   0, 5) === $ligne['fin'];
                                });

                                // Créneau qui englobe cette plage (multi-lignes)
                                if (!$match) {
                                    $match = collect($grille[$jour] ?? [])->first(function($c) use ($ligne) {
                                        return substr($c->heure_debut, 0, 5) <= $ligne['debut']
                                            && substr($c->heure_fin,   0, 5) >= $ligne['fin'];
                                    });
                                }

                                $emc   = $match?->enseignementMatiereClasse;
                                $mid   = $emc?->matiere?->id ?? 0;
                                $color = $colorMap[$mid] ?? '#888';
                            @endphp
                            <td>
                                @if($match && substr($match->heure_debut, 0, 5) === $ligne['debut'])
                                    @php
                                        // Calculer le rowspan
                                        $debutIdx = array_search($ligne['debut'], $heures);
                                        $finIdx   = array_search(substr($match->heure_fin, 0, 5), $heures);
                                        $span     = max(1, $finIdx - $debutIdx);
                                    @endphp
                                    <div class="creneau-cell"
                                         style="background:{{ $color }}18; border-left:3px solid {{ $color }}; min-height:{{ $span * 22 - 4 }}px;">
                                        <div class="creneau-matiere" style="color:{{ $color }};">
                                            {{ $emc?->matiere?->intitule ?? '—' }}
                                        </div>
                                        <div class="creneau-enseignant">
                                            {{ $emc?->enseignant?->prenom }} {{ $emc?->enseignant?->nom }}
                                        </div>
                                        @if($match->salle)
                                            <div class="creneau-salle">&#128205; {{ $match->salle }}</div>
                                        @endif
                                        <div class="creneau-time">
                                            {{ substr($match->heure_debut, 0, 5) }}–{{ substr($match->heure_fin, 0, 5) }}
                                        </div>
                                    </div>
                                @elseif($match)
                                    {{-- Continuation d'un créneau multi-lignes (cellule vide) --}}
                                    <div style="background:{{ $color }}0a; height:100%; min-height:20px;"></div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ===== LÉGENDE ===== --}}
        @php
            $legendItems = [];
            foreach ($creneaux as $c) {
                $emc = $c->enseignementMatiereClasse;
                $mid = $emc?->matiere?->id ?? 0;
                if (!isset($legendItems[$mid])) {
                    $legendItems[$mid] = [
                        'matiere'     => $emc?->matiere?->intitule ?? '—',
                        'enseignant'  => trim(($emc?->enseignant?->prenom ?? '') . ' ' . ($emc?->enseignant?->nom ?? '')),
                        'color'       => $colorMap[$mid] ?? '#888',
                    ];
                }
            }
        @endphp
        <div class="legend">
            @foreach($legendItems as $item)
                <div class="legend-item">
                    <span class="legend-dot" style="background:{{ $item['color'] }};"></span>
                    <strong>{{ $item['matiere'] }}</strong>
                    @if($item['enseignant'])
                        <span style="color:var(--gray);">— {{ $item['enseignant'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ===== PIED DE PAGE ===== --}}
    <div class="doc-footer">
        <div>
            <div>{{ $classe?->etablissement?->nom ?? 'Établissement' }}</div>
            <div>
                Classe : <strong>{{ $classe?->nom ?? '—' }}</strong>
                &nbsp;&bull;&nbsp;
                Année : <strong>{{ $annee?->libelle ?? 'N/A' }}</strong>
                &nbsp;&bull;&nbsp;
                {{ $totalCreneaux }} créneau(x) &bull; {{ $matieres }} matière(s)
            </div>
            <div>Document généré le {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY [à] HH:mm') }}</div>
        </div>
        <div style="display:flex; gap:20px; align-items:flex-end;">
            <div class="signature-block">Signature du Directeur</div>
            <div class="signature-block">Cachet de l'établissement</div>
        </div>
    </div>

</div><!-- /page -->

<script>
    const params = new URLSearchParams(window.location.search);
    if (params.get('autoprint') === '1') {
        window.addEventListener('load', function () { window.print(); });
    }
</script>
</body>
</html>
