@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Gestion des Professeurs Principaux
                        </h1>
                        <p class="text-muted">Gérez les professeurs principaux de votre établissement</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignants.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Messages -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <!-- Card: Total Professeurs Principaux -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Professeurs Principaux</h6>
                            <h3 class="mb-0">{{ $professeursRetour->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="users" style="width: 48px; height: 48px; color: #007bff;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Enseignants Disponibles -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Enseignants Disponibles</h6>
                            <h3 class="mb-0">{{ $enseignants->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="user-check" style="width: 48px; height: 48px; color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Nombre de Classes -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Nombre de Classes</h6>
                            <h3 class="mb-0">{{ $classes->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="grid" style="width: 48px; height: 48px; color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Années Scolaires -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Années Scolaires Actives</h6>
                            <h3 class="mb-0">{{ $anneesScolaires->count() }}</h3>
                        </div>
                        <div>
                            <i data-feather="calendar" style="width: 48px; height: 48px; color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 offset-lg-2 mb-4">
            <!-- Formulaire d'Ajout -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="m-2">
                    <h6 class="mb-0">
                        <i data-feather="plus-circle" class="me-2"></i>Attribuer un Professeur Principal
                    </h6>
                </div>
                <hr>
                <div class="card-body p-4">
                    <form action="{{ route('gestion_enseignants.professeur_principal') }}" method="POST"
                        id="professeurForm">
                        @csrf

                        <div class="row">
                            <!-- Enseignant -->
                            <div class="col-md-6 mb-3">
                                <label for="enseignant_id" class="form-label">Enseignant <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="enseignant_id" name="enseignant_id" required>
                                    <option value="" selected disabled>-- Sélectionner un enseignant --</option>
                                    @foreach ($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}"
                                            {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                            {{ $enseignant->prenom }} {{ $enseignant->nom }}
                                            ({{ $enseignant->numero_employe }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Classe -->
                            <div class="col-md-6 mb-3">
                                <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select class="form-select" id="classe_id" name="classe_id" required>
                                    <option value="" selected disabled>-- Sélectionner une classe --</option>
                                    @foreach ($classes as $classe)
                                        <option value="{{ $classe->id }}"
                                            {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Année Scolaire -->
                            <div class="col-md-6 mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année Scolaire <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="annee_scolaire_id" name="annee_scolaire_id" required>
                                    <option value="" selected disabled>-- Sélectionner une année --</option>
                                    @foreach ($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}"
                                            {{ old('annee_scolaire_id') == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Principal -->
                            <div class="col-md-6 mb-3">
                                <label for="is_main" class="form-label">&nbsp;</label>
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" id="is_main" name="is_main"
                                        value="1" {{ old('is_main') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_main">
                                        Marquer comme professeur principal
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save" class="me-2"></i>&nbsp; Ajouter l'attribution
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des Professeurs Principaux -->
        <div class="card border-0 shadow-sm">
            <div class="m-2">
                <h6 class="mb-0">
                    <i data-feather="list" class="me-2"></i>Attributions des Professeurs Principaux
                </h6>
            </div>
            <hr>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th>Professeur</th>
                                <th>Numéro d'Emploi</th>
                                <th>Classe</th>
                                <th>Année Scolaire</th>
                                <th>Principal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($professeursRetour) && count($professeursRetour) > 0)
                                @foreach ($professeursRetour as $prof)
                                    <tr>
                                        <td>
                                            <strong>{{ $prof->enseignant->prenom }} {{ $prof->enseignant->nom }}</strong>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark">{{ $prof->enseignant->numero_employe }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $prof->classe->nom }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $prof->anneeScolaire->libelle }}</span>
                                        </td>
                                        <td>
                                            @if ($prof->is_main)
                                                <span class="badge bg-success">
                                                    <i data-feather="check" style="width: 14px; height: 14px;"></i> Oui
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    <i data-feather="x" style="width: 14px; height: 14px;"></i> Non
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm gap-2" role="group">
                                                <button type="button" class="btn btn-1" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $prof->id }}">
                                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $prof->id }}">
                                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $prof->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light text-dark">
                                                    <h5 class="modal-title">Modifier l'Attribution</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('gestion_enseignants.professeur_principal') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="prof_principal_id"
                                                        value="{{ $prof->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="is_main_{{ $prof->id }}"
                                                                class="form-label">Professeur Principal</label>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="is_main_{{ $prof->id }}" name="is_main"
                                                                    value="1" {{ $prof->is_main ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_main_{{ $prof->id }}">
                                                                    Marquer comme principal
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="m-3">
                                                        <button type="submit" class="btn btn-1">
                                                            <i data-feather="save" class="me-2"></i>&nbsp; Enregistrer la modification
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="deleteModal{{ $prof->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light text-dark">
                                                    <h5 class="modal-title">
                                                        <i data-feather="alert-triangle" class="me-2"></i>Confirmation
                                                        de suppression
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-dark"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer cette attribution ?</p>
                                                    <div class="bg-light p-3 rounded">
                                                        <p class="mb-1"><strong>{{ $prof->enseignant->prenom }}
                                                                {{ $prof->enseignant->nom }}</strong></p>
                                                        <p class="mb-1">Classe:
                                                            <strong>{{ $prof->classe->nom }}</strong>
                                                        </p>
                                                        <p class="mb-0">Année:
                                                            <strong>{{ $prof->anneeScolaire->libelle }}</strong>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="m-3">
                                                    <form
                                                        action="{{ route('gestion_enseignants.professeur_principal_delete', $prof->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-1">
                                                            <i data-feather="trash-2" class="me-2"></i>&nbsp; Confirmer la suppression
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i data-feather="inbox"
                                            style="width: 40px; height: 40px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                        Aucun professeur principal n'a été attribué
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Validation du formulaire
            document.getElementById('professeurForm').addEventListener('submit', function(e) {
                const enseignantId = document.getElementById('enseignant_id').value;
                const classeId = document.getElementById('classe_id').value;
                const anneeScolaireId = document.getElementById('annee_scolaire_id').value;

                if (!enseignantId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un enseignant');
                    return false;
                }

                if (!classeId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une classe');
                    return false;
                }

                if (!anneeScolaireId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une année scolaire');
                    return false;
                }
            });
        });
    </script>
@endsection
