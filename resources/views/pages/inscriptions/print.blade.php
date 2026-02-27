<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche d'Inscription - {{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</title>
    <link href="{{ asset('cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css') }}" rel="stylesheet"
        crossorigin="anonymous">
    <style>
        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                page-break-inside: avoid;
            }

            a {
                color: inherit;
                text-decoration: none;
            }
        }

        body {
            background-color: #f8f9fa;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .print-container {
            max-width: 850px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header-print {
            border-bottom: 3px solid #1a73e8;
            padding-bottom: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .header-print h2 {
            color: #1a73e8;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-print p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }

        .section-title {
            border-left: 4px solid #1a73e8;
            padding-left: 10px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
            color: #202124;
        }

        .info-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .info-item {
            border: 1px solid #e0e0e0;
            padding: 12px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }

        .info-label {
            font-size: 11px;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            color: #202124;
            font-weight: 500;
        }

        .badge-custom {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background-color: #e8f0fe;
            color: #1a73e8;
        }

        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .signature-box {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 40px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            font-size: 12px;
            color: #666;
        }

        .footer-print {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }

        @media print {
            .print-container {
                padding: 0;
                box-shadow: none;
                max-width: 100%;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- En-tête -->
        <div class="header-print">
            @if ($inscription->classe->etablissement)
                <h2>{{ $inscription->classe->etablissement->nom }}</h2>
            @endif
            <p>FICHE D'INSCRIPTION À UNE CLASSE</p>
            <p style="font-size: 12px; margin-top: 8px;">
                Année Scolaire: <strong>{{ $inscription->anneeScolaire->libelle ?? 'N/A' }}</strong>
            </p>
        </div>

        <!-- Section: Élève -->
        <div class="section-title">
            <i style="color: #1a73e8;">👤</i> INFORMATIONS DE L'ÉLÈVE
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Prénom</div>
                <div class="info-value">{{ $inscription->eleve->prenom }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nom</div>
                <div class="info-value">{{ $inscription->eleve->nom }}</div>
            </div>
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Numéro d'Immatriculation</div>
                <div class="info-value"><code>{{ $inscription->eleve->registration_number }}</code></div>
            </div>
            <div class="info-item">
                <div class="info-label">Date de Naissance</div>
                <div class="info-value">
                    @if ($inscription->eleve->date_naissance)
                        {{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Lieu de Naissance</div>
                <div class="info-value">{{ $inscription->eleve->lieu_naissance ?? 'Non spécifié' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nationalité</div>
                <div class="info-value">{{ $inscription->eleve->nationalite ?? 'Non spécifié' }}</div>
            </div>
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Genre</div>
                <div class="info-value">
                    {{ $inscription->eleve->genre === 'M' ? 'Masculin' : ($inscription->eleve->genre === 'F' ? 'Féminin' : ($inscription->eleve->genre ?? 'Non spécifié')) }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Adresse</div>
                <div class="info-value">{{ $inscription->eleve->adresse ?? 'Non spécifié' }}</div>
            </div>
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $inscription->eleve->telephone ?? 'Non spécifié' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $inscription->eleve->user->email ?? 'Non spécifié' }}</div>
            </div>
        </div>

        <!-- Section: Classe -->
        <div class="section-title">
            <i style="color: #1a73e8;">📚</i> INFORMATIONS DE LA CLASSE
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Classe</div>
                <div class="info-value">{{ $inscription->classe->libelle }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Niveau</div>
                <div class="info-value">{{ $inscription->classe->niveau->libelle ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Établissement</div>
                <div class="info-value">{{ $inscription->classe->etablissement->nom ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Capacité de la Classe</div>
                <div class="info-value">{{ $inscription->classe->capacite ?? 'N/A' }} élèves</div>
            </div>
        </div>

        <!-- Section: Inscription -->
        <div class="section-title">
            <i style="color: #1a73e8;">📋</i> DÉTAILS DE L'INSCRIPTION
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Date d'Inscription</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="badge-custom">{{ ucfirst($inscription->statut) }}</span>
                </div>
            </div>
        </div>
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Année Scolaire</div>
                <div class="info-value">{{ $inscription->anneeScolaire->libelle ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">ID de l'Inscription</div>
                <div class="info-value"><code>#{{ $inscription->id }}</code></div>
            </div>
        </div>

        <!-- Section: Signatures -->
        <div class="signature-section">
            <div class="section-title">
                <i style="color: #1a73e8;">✍️</i> SIGNATURES
            </div>
            <div class="signature-box">
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 30px;">Parent/Tuteur</p>
                    <div class="signature-line"></div>
                </div>
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 30px;">Élève</p>
                    <div class="signature-line"></div>
                </div>
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 30px;">Établissement</p>
                    <div class="signature-line"></div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer-print">
            <p style="margin: 0;">
                Document généré le {{ now()->format('d/m/Y à H:i') }}
            </p>
            <p style="margin: 0; font-size: 11px;">
                Impression: {{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }} - {{ $inscription->classe->libelle }}
            </p>
        </div>
    </div>

    <!-- Boutons de contrôle (masqués à l'impression) -->
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i data-feather="printer"></i> Imprimer
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i data-feather="x"></i> Fermer
        </button>
    </div>

    <script src="{{ asset('cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js') }}"
        crossorigin="anonymous"></script>
</body>

</html>
