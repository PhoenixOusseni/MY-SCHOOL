@extends('layouts.master')

@section('style')
    @include('partials.style')
    <style>
        .stat-card {
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
        }

        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #202124;
        }

        .stat-label {
            font-size: 13px;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #f0f4ff;
            color: #1a73e8;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: #1a73e8;
        }

        .info-group {
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .info-label {
            font-size: 12px;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            color: #202124;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .table-responsive {
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }

        .table-responsive table {
            margin: 0;
        }

        .table-responsive thead {
            background-color: #f8f9fa;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #5f6368;
        }

        .empty-state i {
            font-size: 48px;
            color: #dadce0;
            display: block;
            margin-bottom: 16px;
        }

        /* Tabs styling */
        .nav-pills .nav-link.active {
            background-color: #212529 !important;
            color: white !important;
        }

        .nav-pills .nav-link {
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                            {{ $etablissement->nom }}
                        </h1>
                        <p class="text-white-75 mt-2">
                            <span class="header-badge">Code: {{ $etablissement->code ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <div class="action-buttons">
                            <a href="{{ route('gestion_etablissements.index') }}" class="btn btn-light btn-sm">
                                <i data-feather="arrow-left"></i>
                            </a>
                            <a href="{{ route('gestion_etablissements.edit', $etablissement->id) }}" class="btn btn-dark btn-sm">
                                <i data-feather="edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10">
        <!-- Statistiques -->
        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="stat-label">Élèves</div>
                                <div class="stat-value">{{ $etablissement->eleves()->count() }}</div>
                            </div>
                            <div class="stat-icon" style="background: #e8f0fe;">
                                <i data-feather="users" style="color: #1a73e8; width: 28px; height: 28px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="stat-label">Enseignants</div>
                                <div class="stat-value">{{ $etablissement->enseignants()->count() }}</div>
                            </div>
                            <div class="stat-icon" style="background: #fcf0f0;">
                                <i data-feather="award" style="color: #d33827; width: 28px; height: 28px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="stat-label">Classes</div>
                                <div class="stat-value">{{ $etablissement->classes()->count() }}</div>
                            </div>
                            <div class="stat-icon" style="background: #fff3e0;">
                                <i data-feather="book" style="color: #f57c00; width: 28px; height: 28px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="stat-label">Niveaux</div>
                                <div class="stat-value">{{ $etablissement->niveaux()->count() }}</div>
                            </div>
                            <div class="stat-icon" style="background: #f3e5f5;">
                                <i data-feather="layers" style="color: #7b1fa2; width: 28px; height: 28px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations détaillées et Contenu en onglets -->
        <div class="row">
            <div class="col-xl-4 mb-5">
                <!-- Informations de l'établissement -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i data-feather="info" style="width: 18px; height: 18px;"></i> Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($etablissement->logo)
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $etablissement->logo) }}" alt="Logo"
                                    style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <div
                                    style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                    <i data-feather="briefcase" style="width: 60px; height: 60px; color: white;"></i>
                                </div>
                            </div>
                        @endif

                        <div class="info-group">
                            <div class="info-label">Nom</div>
                            <div class="info-value">{{ $etablissement->nom }}</div>
                        </div>

                        @if ($etablissement->code)
                            <div class="info-group">
                                <div class="info-label">Code</div>
                                <div class="info-value">{{ $etablissement->code }}</div>
                            </div>
                        @endif

                        <div class="info-group">
                            <div class="info-label">Adresse</div>
                            <div class="info-value">{{ $etablissement->adresse }}</div>
                        </div>

                        @if ($etablissement->telephone)
                            <div class="info-group">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">
                                    <a href="tel:{{ $etablissement->telephone }}"
                                        class="text-primary text-decoration-none">
                                        <i data-feather="phone" style="width: 16px; height: 16px;"></i>
                                        {{ $etablissement->telephone }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($etablissement->email)
                            <div class="info-group">
                                <div class="info-label">Email</div>
                                <div class="info-value">
                                    <a href="mailto:{{ $etablissement->email }}" class="text-primary text-decoration-none">
                                        <i data-feather="mail" style="width: 16px; height: 16px;"></i>
                                        {{ $etablissement->email }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($etablissement->nom_directeur)
                            <div class="info-group">
                                <div class="info-label">Directeur</div>
                                <div class="info-value">{{ $etablissement->nom_directeur }}</div>
                            </div>
                        @endif

                        <div class="info-group">
                            <div class="info-label">Date de création</div>
                            <div class="info-value">{{ $etablissement->created_at->format('d/m/Y') }}</div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Dernière modification</div>
                            <div class="info-value">{{ $etablissement->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i data-feather="zap" style="width: 18px; height: 18px;"></i> Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('gestion_etablissements.edit', $etablissement->id) }}"
                            class="btn btn-dark w-100 mb-2">
                            <i data-feather="edit"></i>&nbsp; Modifier l'établissement
                        </a>
                        <form action="{{ route('gestion_etablissements.destroy', $etablissement->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?');">
                                <i data-feather="trash-2"></i>&nbsp; Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 mb-5">
                <!-- Onglets de contenu -->
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills bg-danger rounded" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-white" href="#classes" role="tab" data-bs-toggle="tab">
                                    <i data-feather="book" style="width: 16px; height: 16px;"></i> Classes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#enseignants" role="tab" data-bs-toggle="tab">
                                    <i data-feather="award" style="width: 16px; height: 16px;"></i> Enseignants
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#eleves" role="tab" data-bs-toggle="tab">
                                    <i data-feather="users" style="width: 16px; height: 16px;"></i> Élèves
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#matieres" role="tab" data-bs-toggle="tab">
                                    <i data-feather="layers" style="width: 16px; height: 16px;"></i> Matières
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#annees" role="tab" data-bs-toggle="tab">
                                    <i data-feather="calendar" style="width: 16px; height: 16px;"></i> Années academiques
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Tab: Classes -->
                        <div class="tab-pane fade show active" id="classes" role="tabpanel">
                            <h5 class="section-title mb-4">
                                <i data-feather="book"></i> Classes de l'établissement
                            </h5>
                            @if ($etablissement->classes()->exists())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Classe</th>
                                                <th>Niveau</th>
                                                <th>Capacité</th>
                                                <th>Salle</th>
                                                <th>Année</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($etablissement->classes as $classe)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $classe->nom }}</strong>
                                                    </td>
                                                    <td>{{ $classe->niveau->nom ?? 'N/A' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light text-dark">{{ $classe->capacite }}</span>
                                                    </td>
                                                    <td>{{ $classe->salle ?? '-' }}</td>
                                                    <td>{{ $classe->anneeScolaire->libelle ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i data-feather="inbox"></i>
                                    <p>Aucune classe n'a été créée pour cet établissement</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab: Enseignants -->
                        <div class="tab-pane fade" id="enseignants" role="tabpanel">
                            <h5 class="section-title mb-4">
                                <i data-feather="award"></i> Enseignants
                            </h5>
                            @if ($etablissement->enseignants()->exists())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                                <th>Qualification</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($etablissement->enseignants as $enseignant)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $enseignant->prenom }} {{ $enseignant->nom }}</strong>
                                                    </td>
                                                    <td>{{ $enseignant->email ?? '-' }}</td>
                                                    <td>{{ $enseignant->telephone ?? '-' }}</td>
                                                    <td>{{ $enseignant->qualification ?? '-' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-success">{{ ucfirst($enseignant->statut) }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i data-feather="inbox"></i>
                                    <p>Aucun enseignant n'a été assigné à cet établissement</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab: Élèves -->
                        <div class="tab-pane fade" id="eleves" role="tabpanel">
                            <h5 class="section-title mb-4">
                                <i data-feather="users"></i> Élèves inscrits
                            </h5>
                            @if ($etablissement->eleves()->exists())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Date d'inscription</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($etablissement->eleves()->limit(10)->get() as $eleve)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $eleve->prenom }} {{ $eleve->nom }}</strong>
                                                    </td>
                                                    <td>{{ $eleve->email ?? '-' }}</td>
                                                    <td>{{ $eleve->date_inscription }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ ucfirst($eleve->statut) }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($etablissement->eleves()->count() > 10)
                                    <p class="text-muted text-center mt-3">
                                        <small>Affichage des 10 premiers élèves sur {{ $etablissement->eleves()->count() }}
                                            total</small>
                                    </p>
                                @endif
                            @else
                                <div class="empty-state">
                                    <i data-feather="inbox"></i>
                                    <p>Aucun élève n'est actuellement inscrit dans cet établissement</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab: Matières -->
                        <div class="tab-pane fade" id="matieres" role="tabpanel">
                            <h5 class="section-title mb-4">
                                <i data-feather="layers"></i> Matières proposées
                            </h5>
                            @if ($etablissement->matieres()->exists())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Matière</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>État</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($etablissement->matieres as $matiere)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $matiere->intitule }}</strong>
                                                    </td>
                                                    <td>{{ $matiere->code ?? '-' }}</td>
                                                    <td>{{ Str::limit($matiere->description ?? '-', 50) }}</td>
                                                    <td>
                                                        @if ($matiere->is_active)
                                                            <span class="badge bg-success">Actif</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactif</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i data-feather="inbox"></i>
                                    <p>Aucune matière n'a été créée pour cet établissement</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab: Années scolaires -->
                        <div class="tab-pane fade" id="annees" role="tabpanel">
                            <h5 class="section-title mb-4">
                                <i data-feather="calendar"></i> Années scolaires
                            </h5>
                            @if ($etablissement->anneesScolaires()->exists())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Année</th>
                                                <th>Début</th>
                                                <th>Fin</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($etablissement->anneesScolaires as $annee)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $annee->libelle }}</strong>
                                                    </td>
                                                    <td>{{ $annee->date_debut }}</td>
                                                    <td>{{ $annee->date_fin }}</td>
                                                    <td>
                                                        @if ($annee->is_current)
                                                            <span class="badge bg-primary">En cours</span>
                                                        @else
                                                            <span class="badge bg-light text-dark">Archivée</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i data-feather="inbox"></i>
                                    <p>Aucune année scolaire n'a été configurée</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Réinitialiser les icônes Feather
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Gestion des onglets
            $('[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        });
    </script>
@endsection
