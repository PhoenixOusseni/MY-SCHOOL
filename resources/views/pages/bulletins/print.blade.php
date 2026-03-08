<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin — {{ strtoupper($bulletin->eleve->nom ?? '') }} {{ $bulletin->eleve->prenom ?? '' }}</title>
    <style>
        /* ── Variables ─────────────────────────────────────────────── */
        :root {
            --navy: #1a3557;
            --gold: #c8972b;
            --light: #eef2f7;
            --border: #b0bec5;
            --pass: #2e7d32;
            --fail: #c62828;
            --text: #1c1c1c;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #cfd8dc;
            font-family: "Times New Roman", Times, serif;
            color: var(--text);
            padding: 20px;
            font-size: 13px;
        }

        /* ── Toolbar (screen only) ─────────────────────────────────── */
        .toolbar {
            text-align: center;
            margin-bottom: 14px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .toolbar button {
            background: var(--navy);
            color: #fff;
            border: none;
            padding: 9px 22px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            letter-spacing: .5px;
        }

        .toolbar button:hover {
            background: #24476e;
        }

        /* ── Sheet ─────────────────────────────────────────────────── */
        .sheet {
            max-width: 960px;
            margin: 0 auto;
            background: #fff;
            border: 1.5px solid #90a4ae;
            border-radius: 4px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .22);
            overflow: hidden;
        }

        /* ── Header band ───────────────────────────────────────────── */
        .header-band {
            background: var(--navy);
            color: #fff;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            padding: 12px 18px;
            gap: 12px;
        }

        .header-band .school-left {
            font-size: 11px;
            line-height: 1.55;
        }

        .header-band .school-left strong {
            font-size: 13px;
            display: block;
        }

        .header-band .school-center {
            text-align: center;
            border-left: 1px solid rgba(255, 255, 255, .3);
            border-right: 1px solid rgba(255, 255, 255, .3);
            padding: 0 18px;
        }

        .header-band .school-center .bulletin-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header-band .school-center .period-label {
            font-size: 12px;
            color: #b0c4de;
            margin-top: 2px;
        }

        .header-band .school-right {
            text-align: right;
            font-size: 11px;
            line-height: 1.55;
        }

        .header-band .school-right strong {
            font-size: 13px;
            display: block;
        }

        /* ── Gold accent line ──────────────────────────────────────── */
        .accent-line {
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, #e8b84b 50%, var(--gold) 100%);
        }

        /* ── Identity block ────────────────────────────────────────── */
        .identity {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border-bottom: 1.5px solid var(--navy);
        }

        .identity .id-col {
            padding: 10px 16px;
            border-right: 1px solid #cfd8dc;
            background: var(--light);
        }

        .identity .id-col:last-child {
            border-right: none;
        }

        .identity .id-row {
            display: flex;
            gap: 6px;
            margin-bottom: 3px;
            font-size: 12.5px;
            align-items: baseline;
        }

        .identity .id-row .lbl {
            font-weight: 700;
            color: var(--navy);
            white-space: nowrap;
            min-width: 110px;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .identity .id-row .val {
            font-size: 13px;
        }

        /* ── Section heading ───────────────────────────────────────── */
        .section-heading {
            background: var(--navy);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 4px 14px;
        }

        /* ── Marks table ───────────────────────────────────────────── */
        table.marks {
            width: 100%;
            border-collapse: collapse;
        }

        table.marks thead tr th {
            background: #263e5a;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 6px 8px;
            border: 1px solid #1a3050;
            text-align: center;
        }

        table.marks thead tr th:first-child {
            text-align: left;
        }

        table.marks tbody tr td {
            padding: 4px 8px;
            border: 1px solid #cfd8dc;
            font-size: 12.5px;
            vertical-align: middle;
        }

        table.marks tbody tr:nth-child(even) td {
            background: #f5f8fb;
        }

        table.marks td.num {
            text-align: center;
        }

        .grade-pass {
            color: var(--pass);
            font-weight: 700;
        }

        .grade-fail {
            color: var(--fail);
            font-weight: 700;
        }

        .grade-avg {
            color: #e65100;
            font-weight: 700;
        }

        tr.totaux td {
            background: var(--navy) !important;
            color: #fff;
            font-weight: 700;
            font-size: 12.5px;
            border-color: #1a3050 !important;
            padding: 5px 8px;
        }

        tr.totaux td .avg-big {
            font-size: 15px;
            letter-spacing: .5px;
        }

        /* ── Stats grid ────────────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border-top: 1.5px solid var(--navy);
        }

        .stats-grid .s-box {
            padding: 10px 14px;
            border-right: 1px solid #cfd8dc;
            font-size: 12px;
            line-height: 1.7;
        }

        .stats-grid .s-box:last-child {
            border-right: none;
        }

        .stats-grid .s-box .s-title {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--navy);
            border-bottom: 1px solid #cfd8dc;
            margin-bottom: 6px;
            padding-bottom: 3px;
        }

        .stats-grid .s-box .s-row {
            display: flex;
            justify-content: space-between;
            gap: 6px;
        }

        .stats-grid .s-box .s-row span:first-child {
            color: #546e7a;
            font-size: 11.5px;
        }

        .stats-grid .s-box .s-row span:last-child {
            font-weight: 700;
            font-size: 12.5px;
        }

        /* ── Honours ───────────────────────────────────────────────── */
        .honours-row {
            display: flex;
            gap: 22px;
            margin-top: 4px;
        }

        .honours-row .h-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }

        .h-box {
            width: 16px;
            height: 16px;
            border: 1.5px solid #546e7a;
            border-radius: 2px;
            display: inline-block;
            flex-shrink: 0;
        }

        /* ── Avg card ──────────────────────────────────────────────── */
        .avg-card {
            background: var(--navy);
            color: #fff;
            text-align: center;
            padding: 6px 10px;
            border-radius: 4px;
            margin-top: 4px;
            display: inline-block;
            min-width: 90px;
        }

        .avg-card .avg-num {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.1;
        }

        .avg-card .avg-label {
            font-size: 10px;
            color: #b0c4de;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ── Signature zone ────────────────────────────────────────── */
        .sig-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            border-top: 1px solid #cfd8dc;
        }

        .sig-grid .sig-cell {
            padding: 10px 14px;
            border-right: 1px solid #cfd8dc;
            font-size: 12px;
            line-height: 1.7;
            min-height: 80px;
        }

        .sig-grid .sig-cell:last-child {
            border-right: none;
        }

        .sig-cell .sig-lbl {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .sig-line {
            border-bottom: 1px dashed #90a4ae;
            margin-top: 18px;
        }

        /* ── Footer bar ────────────────────────────────────────────── */
        .footer-bar {
            background: #f0f4f8;
            border-top: 1.5px solid var(--navy);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 16px;
            font-size: 10.5px;
            color: #546e7a;
        }

        .footer-bar .cert-stamp {
            font-style: italic;
        }

        /* ── Print ─────────────────────────────────────────────────── */
        @page {
            size: A4;
            margin: 18mm 15mm;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar {
                display: none;
            }

            .sheet {
                max-width: 100%;
                border: none;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    @php
        $details = $bulletin->detailBulletins ?? collect();
        $sommeCoeff = (float) ($bulletin->total_coefficient ?? $details->sum(fn($i) => (float) ($i->coefficient ?? 0)));
        $sommePoints =
            (float) ($bulletin->total_points ?? $details->sum(fn($i) => (float) ($i->moyenne_ponderee ?? 0)));
        $moyenne = $bulletin->moyenne_globale;
        if ($moyenne === null && $sommeCoeff > 0) {
            $moyenne = $sommePoints / $sommeCoeff;
        }
        $moyClasse = $details->whereNotNull('moyenne_classe')->avg('moyenne_classe');
        $maxClasse = $details->whereNotNull('point_max')->max('point_max');
        $minClasse = $details->whereNotNull('point_min')->min('point_min');

        $fmt = fn($v, $d = 2) => $v === null ? '—' : number_format((float) $v, $d, ',', ' ');

        $rangTexte = '—';
        if ($bulletin->rang && $bulletin->total_eleves) {
            $ordinal = $bulletin->rang === 1 ? 'er' : 'ème';
            $rangTexte = $bulletin->rang . $ordinal . ' / ' . $bulletin->total_eleves;
        }

        $gradeClass = function ($val) {
            if ($val === null) {
                return '';
            }
            $v = (float) $val;
            if ($v >= 12) {
                return 'grade-pass';
            }
            if ($v >= 9) {
                return 'grade-avg';
            }
            return 'grade-fail';
        };

        $periode = $bulletin->periodEvaluation->libelle ?? ($bulletin->periodEvaluation->nom ?? null);
        $annee = $bulletin->periodEvaluation->anneeScolaire->libelle ?? '—';
    @endphp

    <div class="toolbar">
        <button onclick="window.print()">&#128438;&nbsp; Imprimer</button>
        <button onclick="window.history.back()">&#8592;&nbsp; Retour</button>
    </div>

    <div class="sheet">

        {{-- ── En-tête ──────────────────────────────────────────── --}}
        <div class="header-band">
            <div class="school-left">
                <strong>SCHOOL MANAGER</strong>
                Établissement scolaire<br>
                Année scolaire : {{ $annee }}
            </div>
            <div class="school-center">
                <div class="bulletin-title">Bulletin Scolaire</div>
                @if ($periode)
                    <div class="period-label">{{ $periode }}</div>
                @endif
            </div>
            <div class="school-right">
                <strong>{{ $bulletin->classe->nom ?? '—' }}</strong>
                Effectif : {{ $bulletin->total_eleves ?? '—' }} élève(s)<br>
                Matricule : {{ $bulletin->eleve->registration_number ?? '—' }}
            </div>
        </div>
        <div class="accent-line"></div>

        {{-- ── Identité ─────────────────────────────────────────── --}}
        <div class="identity">
            <div class="id-col">
                <div class="id-row">
                    <span class="lbl">Nom</span>
                    <span class="val"><strong>{{ strtoupper($bulletin->eleve->nom ?? '—') }}</strong></span>
                </div>
                <div class="id-row">
                    <span class="lbl">Prénom(s)</span>
                    <span class="val">{{ $bulletin->eleve->prenom ?? '—' }}</span>
                </div>
            </div>
            <div class="id-col">
                <div class="id-row">
                    <span class="lbl">Date de naissance</span>
                    <span class="val">{{ optional($bulletin->eleve->date_naissance)->format('d/m/Y') ?? '—' }}</span>
                </div>
                <div class="id-row">
                    <span class="lbl">Classe</span>
                    <span class="val">{{ $bulletin->classe->nom ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- ── Tableau des matières ─────────────────────────────── --}}
        <div class="section-heading">Résultats par matière</div>

        <table class="marks">
            <thead>
                <tr>
                    <th style="width:28%; text-align:left;">Matière</th>
                    <th>Coef.</th>
                    <th>Moyenne</th>
                    <th>Pondérée</th>
                    <th>Moy. Classe</th>
                    <th>Appréciation</th>
                    <th style="width:15%">Professeur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $detail)
                    @php $gc = $gradeClass($detail->moyenne); @endphp
                    <tr>
                        <td><strong>{{ $detail->matiere->intitule ?? '—' }}</strong></td>
                        <td class="num">{{ $fmt($detail->coefficient, 0) }}</td>
                        <td class="num {{ $gc }}">{{ $fmt($detail->moyenne) }}</td>
                        <td class="num">{{ $fmt($detail->moyenne_ponderee) }}</td>
                        <td class="num" style="color:#546e7a">{{ $fmt($detail->moyenne_classe) }}</td>
                        <td style="font-style:italic; font-size:11.5px">{{ $detail->appreciation ?? '—' }}</td>
                        <td style="font-size:11px; color:#546e7a">
                            {{ $detail->enseignant->nom ?? '—' }} {{ $detail->enseignant->prenom ?? '' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:12px; color:#888; font-style:italic">
                            Aucune matière enregistrée pour ce bulletin.
                        </td>
                    </tr>
                @endforelse

                <tr class="totaux">
                    <td><strong>TOTAUX &amp; MOYENNE GÉNÉRALE</strong></td>
                    <td class="num">{{ $fmt($sommeCoeff, 0) }}</td>
                    <td class="num">
                        <span class="avg-big">{{ $fmt($moyenne) }}</span>
                        <span style="font-size:10px; opacity:.8"> /20</span>
                    </td>
                    <td class="num">{{ $fmt($sommePoints) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>

        {{-- ── Bilan de la période ──────────────────────────────── --}}
        <div class="section-heading">Bilan de la période</div>

        <div class="stats-grid">

            <div class="s-box">
                <div class="s-title">Résultats de l'élève</div>
                <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                    <div class="avg-card">
                        <div class="avg-label">Moyenne</div>
                        <div class="avg-num">{{ $fmt($moyenne) }}</div>
                        <div class="avg-label">sur 20</div>
                    </div>
                    <div style="flex:1; line-height:1.9; font-size:12px;">
                        <div class="s-row"><span>Rang</span><span>{{ $rangTexte }}</span></div>
                        <div class="s-row"><span>Absences</span><span>{{ $bulletin->absences ?? 0 }} h</span></div>
                        <div class="s-row"><span>Retards</span><span>{{ $bulletin->retards ?? 0 }}</span></div>
                        <div class="s-row"><span>Conduite</span><span>{{ $bulletin->mention_conduite ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="s-box">
                <div class="s-title">Statistiques de la classe</div>
                <div class="s-row"><span>Moyenne la plus haute</span><span>{{ $fmt($maxClasse) }} /20</span></div>
                <div class="s-row"><span>Moyenne la plus basse</span><span>{{ $fmt($minClasse) }} /20</span></div>
                <div class="s-row"><span>Moyenne de la classe</span><span>{{ $fmt($moyClasse) }} /20</span></div>
                <div class="s-row"><span>Effectif total</span><span>{{ $bulletin->total_eleves ?? '—' }}</span></div>
            </div>

            <div class="s-box">
                <div class="s-title">Distinctions &amp; Mention</div>
                <div class="honours-row">
                    <div class="h-item"><span class="h-box"></span> Tableau d'honneur</div>
                    <div class="h-item"><span class="h-box"></span> Félicitations</div>
                </div>
                <div class="honours-row" style="margin-top:6px;">
                    <div class="h-item"><span class="h-box"></span> Encouragements</div>
                    <div class="h-item"><span class="h-box"></span> Avertissement</div>
                </div>
            </div>
        </div>

        {{-- ── Observations & Signatures ───────────────────────── --}}
        <div class="section-heading">Observations &amp; Signatures</div>

        <div class="sig-grid">
            <div class="sig-cell">
                <div class="sig-lbl">Observations du conseil de classe</div>
                <div style="font-size:12.5px; color:#333; min-height:36px; padding-top:4px;">
                    {{ $bulletin->commentaire_principal ?? 'Néant.' }}
                </div>
                @if ($bulletin->commentaire_directeur)
                    <div style="margin-top:6px;">
                        <span style="font-weight:700; color:var(--navy); font-size:11px;">DIRECTEUR :&nbsp;</span>
                        {{ $bulletin->commentaire_directeur }}
                    </div>
                @endif
            </div>
            <div class="sig-cell">
                <div class="sig-lbl">Signature du directeur</div>
                <div class="sig-line"></div>
                <div style="margin-top:16px;">
                    <div class="sig-lbl">Signature du parent / tuteur</div>
                    <div class="sig-line"></div>
                </div>
            </div>
        </div>

        {{-- ── Pied de page ─────────────────────────────────────── --}}
        <div class="footer-bar">
            <span>
                <strong>{{ strtoupper($bulletin->eleve->nom ?? '') }} {{ $bulletin->eleve->prenom ?? '' }}</strong>
                &mdash; {{ $bulletin->classe->nom ?? '' }}
                &mdash; {{ $annee }}
            </span>
            <span class="cert-stamp">
                Bulletin certifié le
                {{ optional($bulletin->generated_at ?? ($bulletin->updated_at ?? now()))->format('d/m/Y') }}
            </span>
        </div>
    </div>
</body>

</html>
