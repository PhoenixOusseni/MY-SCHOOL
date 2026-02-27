<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Impression Marché - {{ $marcheFind->code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #28a745;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 3px;
            text-transform: uppercase;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 8px 12px;
            font-weight: bold;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            width: 35%;
            color: #495057;
        }

        .info-value {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            background: white;
        }

        .info-value.highlight {
            font-weight: bold;
            color: #28a745;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background: #343a40;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #23272b;
        }

        table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }

        table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }

        .badge-primary {
            background: #007bff;
            color: white;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-muted {
            color: #6c757d;
            font-style: italic;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }

        .signature-block {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature {
            display: table-cell;
            width: 50%;
            padding: 10px;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background: #e9ecef !important;
            font-size: 12px;
        }

        .document-date {
            text-align: right;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 100%;
                padding: 5mm;
            }

            .no-print {
                display: none !important;
            }

            .section {
                page-break-inside: avoid;
            }

            @page {
                margin: 10mm;
                size: A4 portrait;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #218838;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 12px 24px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-decoration: none;
        }

        .back-button:hover {
            background: #5a6268;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-actif {
            background: #28a745;
            color: white;
        }

        .status-termine {
            background: #6c757d;
            color: white;
        }

        .alert-box {
            padding: 12px;
            margin: 15px 0;
            border-left: 4px solid #17a2b8;
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>

<body>
    <!-- Boutons de navigation (masqués à l'impression) -->
    <a href="{{ route('gestion_marche.show', $marcheFind->id) }}" class="back-button no-print">← Retour</a>
    <button onclick="window.print()" class="print-button no-print">🖨️ Imprimer</button>

    <div class="container">
        <!-- En-tête du document -->
        <div class="header">
            {{-- Vous pouvez ajouter un logo ici --}}
            {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo"> --}}
            <h1>Détails du Marché</h1>
            <div class="subtitle">Système d'Information de Gestion - CFE</div>
        </div>

        <!-- Date d'impression -->
        <div class="document-date">
            <strong>Date d'impression :</strong> {{ now()->format('d/m/Y à H:i') }}
        </div>

        <!-- Section 1 : Informations générales -->
        <div class="section">
            <div class="section-title">📋 Informations Générales</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Code du marché</div>
                    <div class="info-value"><strong>{{ $marcheFind->code }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Désignation</div>
                    <div class="info-value">{{ $marcheFind->designation }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Montant du marché</div>
                    <div class="info-value highlight">{{ number_format($marcheFind->montant, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Client (Contribuable)</div>
                    <div class="info-value">
                        {{ $marcheFind->contribuable->assujeti ?? 'Non défini' }}
                        @if ($marcheFind->contribuable && $marcheFind->contribuable->code)
                            <br><span class="text-muted">Code: {{ $marcheFind->contribuable->code }}</span>
                        @endif
                    </div>
                </div>
                @if ($marcheFind->baseTaxable)
                    <div class="info-row">
                        <div class="info-label">Base taxable</div>
                        <div class="info-value">{{ $marcheFind->baseTaxable->libelle ?? 'Non défini' }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 2 : Dates et durée -->
        <div class="section">
            <div class="section-title">📅 Dates et Durée</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Date de début</div>
                    <div class="info-value">
                        @if ($marcheFind->date_debut)
                            {{ \Carbon\Carbon::parse($marcheFind->date_debut)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">Non définie</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date de clôture</div>
                    <div class="info-value">
                        @if ($marcheFind->date_cloture)
                            {{ \Carbon\Carbon::parse($marcheFind->date_cloture)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">Non définie</span>
                        @endif
                    </div>
                </div>
                @if ($marcheFind->date_debut && $marcheFind->date_cloture)
                    <div class="info-row">
                        <div class="info-label">Durée du marché</div>
                        <div class="info-value">
                            <span class="badge badge-info">
                                {{ \Carbon\Carbon::parse($marcheFind->date_debut)->diffInDays(\Carbon\Carbon::parse($marcheFind->date_cloture)) }}
                                jours
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            @php
                                $now = \Carbon\Carbon::now();
                                $dateDebut = \Carbon\Carbon::parse($marcheFind->date_debut);
                                $dateCloture = \Carbon\Carbon::parse($marcheFind->date_cloture);
                            @endphp
                            @if ($now->lt($dateDebut))
                                <span class="status-badge" style="background: #ffc107; color: #000;">À venir</span>
                            @elseif($now->between($dateDebut, $dateCloture))
                                <span class="status-badge status-actif">En cours</span>
                            @else
                                <span class="status-badge status-termine">Terminé</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3 : Équipe du marché -->
        <div class="section">
            <div class="section-title">👥 Équipe du Marché ({{ $marcheFind->details->count() }} membre(s))</div>
            @if ($marcheFind->details->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">Code</th>
                            <th style="width: 25%;">Nom et Prénom</th>
                            <th style="width: 25%;">Service</th>
                            <th style="width: 15%;">Temps</th>
                            <th style="width: 15%;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marcheFind->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $detail->personnel->code ?? 'N/A' }}</strong></td>
                                <td>
                                    <strong>{{ $detail->personnel->nom ?? 'N/A' }}</strong>
                                    {{ $detail->personnel->prenom ?? '' }}
                                </td>
                                <td>{{ $detail->personnel->service->libelle ?? 'Non défini' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $detail->temps ?? 'Non précisé' }}</span>
                                </td>
                                <td>{{ number_format(($detail->personnel->taux_horaire ?? 0) * ($detail->temps ?? 0), 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert-box">
                    Aucun membre n'a été affecté à ce marché.
                </div>
            @endif
        </div>

        <!-- Section 4 : Diligences (si disponibles) -->
        @if ($marcheFind->diligences && $marcheFind->diligences->count() > 0)
            <div class="section">
                <div class="section-title">📝 Diligences ({{ $marcheFind->diligences->count() }})</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">Date</th>
                            <th style="width: 75%;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marcheFind->diligences as $index => $diligence)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $diligence->date ? \Carbon\Carbon::parse($diligence->date)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td>{{ $diligence->description ?? 'Aucune description' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Section 6 : Récapitulatif financier -->
        <div class="section">
            <div class="section-title">💰 Récapitulatif Financier</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 70%;">Libellé</th>
                        <th style="width: 30%; text-align: right;">Montant (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Montant total du marché</td>
                        <td class="text-right"><strong>{{ number_format($marcheFind->montant, 0, ',', ' ') }}</strong>
                        </td>
                    </tr>
                    @if ($marcheFind->baseTaxable)
                        <tr>
                            <td>Base taxable</td>
                            <td class="text-right">{{ $marcheFind->baseTaxable->libelle ?? 'N/A' }}</td>
                        </tr>
                    @endif
                    @php
                        $nombreJours = 0;
                        if ($marcheFind->date_debut && $marcheFind->date_cloture) {
                            $nombreJours = \Carbon\Carbon::parse($marcheFind->date_debut)->diffInDays(
                                \Carbon\Carbon::parse($marcheFind->date_cloture),
                            );
                        }
                        $montantParJour = $nombreJours > 0 ? $marcheFind->montant / $nombreJours : 0;
                    @endphp
                    @if ($nombreJours > 0)
                        <tr>
                            <td>Montant moyen par jour</td>
                            <td class="text-right">{{ number_format($montantParJour, 0, ',', ' ') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Bloc de signature -->
        <div class="signature-block">
            <div class="signature">
                <div><strong>Le Responsable</strong></div>
            </div>
            <div class="signature">
                <div><strong>Le Chef de Service</strong></div>
            </div>
        </div>
    </div>
</body>

</html>
