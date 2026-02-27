<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement N°{{ $paiement->id }} — {{ $paiement->eleve->prenom ?? '' }} {{ $paiement->eleve->nom ?? '' }}</title>
    @include('partials.style')
    <style>
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .receipt-container { box-shadow: none !important; padding: 0 !important; }
            body { padding: 0; }
        }

        body {
            padding: 10px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .receipt-container {
            max-width: 750px;
            margin: 0 auto;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        /* En-tête */
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #c41e3a;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .school-name {
            font-size: 20px;
            font-weight: 700;
            color: #c41e3a;
            margin-bottom: 4px;
        }
        .school-sub {
            font-size: 12px;
            color: #666;
        }
        .receipt-title-box {
            text-align: right;
        }
        .receipt-title-box h2 {
            font-size: 22px;
            font-weight: 800;
            color: #c41e3a;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .receipt-number {
            font-size: 13px;
            color: #555;
            margin-top: 4px;
        }

        /* Badge statut */
        .status-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .status-Terminé    { background: #d4edda; color: #155724; }
        .status-En-attente { background: #fff3cd; color: #856404; }
        .status-Annulé     { background: #f8d7da; color: #721c24; }
        .status-Remboursé  { background: #e2e3e5; color: #383d41; }

        /* Section info */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #999;
            margin-bottom: 3px;
        }
        .section-value {
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
        }

        /* Montant encadré */
        .amount-box {
            background: #c41e3a;
            color: white;
            border-radius: 8px;
            padding: 18px 24px;
            text-align: center;
            margin: 25px 0;
        }
        .amount-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.85;
            margin-bottom: 6px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .amount-sub {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 4px;
        }
        .amount-reste {
            background: #fff3cd;
            color: #856404;
            border-radius: 8px;
            padding: 10px 24px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        /* Tableau de détails */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .details-table td:first-child {
            color: #777;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 40%;
        }
        .details-table td:last-child {
            font-weight: 500;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }

        /* Signatures */
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }
        .signature-slot {
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #555;
            margin-bottom: 6px;
            margin-top: 45px;
        }
        .signature-slot p {
            font-size: 12px;
            color: #777;
            margin: 0;
        }

        /* Pied de page */
        .receipt-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 14px;
        }

        /* Filigrane statut annulé */
        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(220, 53, 69, 0.08);
            text-transform: uppercase;
            pointer-events: none;
            letter-spacing: 10px;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    @if ($paiement->status === 'Annulé')
        <div class="watermark">ANNULÉ</div>
    @endif

    <div class="receipt-container">

        <!-- En-tête -->
        <div class="receipt-header">
            <div>
                <div class="school-name">
                    {{ $paiement->eleve->etablissement->nom ?? config('app.name', 'École') }}
                </div>
                <div class="school-sub">
                    {{ $paiement->anneeScolaire->libelle ?? '' }}
                </div>
            </div>
            <div class="receipt-title-box">
                <h2>REÇU</h2>
                <div class="receipt-number">N° {{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="mt-2">
                    @php
                        $statusClass = str_replace(' ', '-', $paiement->status);
                    @endphp
                </div>
            </div>
        </div>

        <!-- Infos élève -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="section-label">Reçu de</div>
                <div class="section-value fw-bold" style="font-size: 16px;">
                    {{ strtoupper($paiement->eleve->nom ?? '') }}
                    {{ $paiement->eleve->prenom ?? '' }}
                </div>
                <div class="text-muted" style="font-size: 12px;">
                    Matricule : {{ $paiement->eleve->registration_number ?? '—' }}
                </div>
            </div>
            <div class="col-6 text-end">
                <div class="section-label">Date du paiement</div>
                <div class="section-value fw-bold">
                    {{ $paiement->date_paiement->format('d/m/Y') }}
                </div>
                @if ($paiement->reference)
                    <div class="text-muted" style="font-size: 12px;">
                        Réf : {{ $paiement->reference }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Montant encadré -->
        <div class="amount-box">
            <div class="amount-label">Montant encaissé</div>
            <div class="amount-value">
                {{ number_format($paiement->montant, 0, ',', ' ') }}
            </div>
            <div class="amount-sub">
                {{ $paiement->fraiScolarite->devise ?? 'XOF' }} —
                @php
                    $methodeLabels = [
                        'Especes'      => 'Espèces',
                        'cheque'       => 'Chèque',
                        'virement'     => 'Virement',
                        'mobile_money' => 'Mobile Money',
                        'carte'        => 'Carte',
                    ];
                @endphp
                {{ $methodeLabels[$paiement->methode_paiement] ?? $paiement->methode_paiement }}
            </div>
        </div>

        @if ($paiement->reste_a_payer > 0)
            <div class="amount-reste">
                ⚠️ Reste à payer :
                <strong>{{ number_format($paiement->reste_a_payer, 0, ',', ' ') }} {{ $paiement->fraiScolarite->devise ?? 'XOF' }}</strong>
            </div>
        @elseif ($paiement->reste_a_payer <= 0)
            <div style="background:#d4edda; color:#155724; border-radius:8px; padding:10px 24px; text-align:center; margin-bottom:20px; font-size:13px;">
                ✅ <strong>Solde entièrement réglé</strong>
            </div>
        @endif

        <!-- Tableau des détails -->
        <table class="details-table">
            <tr>
                <td>Type de frais</td>
                <td><strong>{{ $paiement->fraiScolarite->libelle ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
                <td>Montant total attendu</td>
                <td>{{ number_format($paiement->fraiScolarite->montant ?? 0, 0, ',', ' ') }} {{ $paiement->fraiScolarite->devise ?? '' }}</td>
            </tr>
            <tr>
                <td>Montant payé</td>
                <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->fraiScolarite->devise ?? '' }}</strong></td>
            </tr>
            <tr>
                <td>Reste à payer</td>
                <td style="{{ $paiement->reste_a_payer > 0 ? 'color:#856404; font-weight:700' : 'color:#155724; font-weight:700' }}">
                    {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }} {{ $paiement->fraiScolarite->devise ?? '' }}
                </td>
            </tr>
            <tr>
                <td>Méthode de paiement</td>
                <td>{{ $methodeLabels[$paiement->methode_paiement] ?? $paiement->methode_paiement }}</td>
            </tr>
            <tr>
                <td>Année scolaire</td>
                <td>{{ $paiement->anneeScolaire->libelle ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Reçu par</td>
                <td>{{ $paiement->receivedBy->prenom ?? '' }} {{ $paiement->receivedBy->nom ?? '—' }}</td>
            </tr>
            @if ($paiement->notes)
                <tr>
                    <td>Notes</td>
                    <td>{{ $paiement->notes }}</td>
                </tr>
            @endif
        </table>

        <!-- Signatures -->
        <div class="signature-grid">
            <div class="signature-slot">
                <div class="signature-line"></div>
                <p>Signature de l'élève / Parent</p>
            </div>
            <div class="signature-slot">
                <div class="signature-line"></div>
                <p>Sceau & Signature de l'établissement</p>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="receipt-footer">
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>Ce reçu est un document officiel. Conservez-le précieusement.</p>
        </div>
    </div>

    <!-- Boutons no-print -->
    <div class="no-print" style="text-align:center; margin-top:28px; padding-bottom: 30px;">
        <button onclick="window.print()" class="btn btn-danger px-4 me-2">
            🖨️ Imprimer
        </button>
    </div>

    <script src="{{ asset('cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script>
        // Impression automatique optionnelle (décommenter si souhaité)
        // window.onload = () => window.print();
    </script>
</body>

</html>
