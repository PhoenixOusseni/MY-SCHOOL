@extends('layouts.master')

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Modifier un enseignement
                        </h1>
                        <p class="text-muted">{{ $enseignement->enseignant->prenom }} {{ $enseignement->enseignant->nom }} -
                            {{ $enseignement->matiere->intitule }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignement_matieres.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_enseignement_matieres.update', $enseignement->id) }}" method="POST"
                            id="enseignementForm">
                            @csrf
                            @method('PUT')

                            <!-- Immutable Fields Section -->
                            <div class="alert alert-info mb-4">
                                <i data-feather="lock" style="width: 1.2rem; height: 1.2rem;"></i>
                                <strong>Attention:</strong> Les champs professeur, matière, classe et année scolaire ne
                                peuvent pas être modifiés après création.
                            </div>

                            <!-- Professeur (Read-only) -->
                            <div class="mb-3">
                                <label for="professor" class="form-label">Professeur <span
                                        class="badge bg-warning text-dark">Immutable</span></label>
                                <input type="text" class="form-control" id="professor"
                                    value="{{ $enseignement->enseignant->prenom }} {{ $enseignement->enseignant->nom }} ({{ $enseignement->enseignant->numero_employe }})"
                                    disabled readonly>
                                <small class="text-muted">Cet enseignement a été assigné à ce professeur et ne peut pas être
                                    changé.</small>
                            </div>

                            <!-- Matière (Read-only) -->
                            <div class="mb-3">
                                <label for="matiere" class="form-label">Matière <span
                                        class="badge bg-warning text-dark">Immutable</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <span
                                            style="display: inline-block; width: 20px; height: 20px; background-color: {{ $enseignement->matiere->color ?? '#007bff' }}; border-radius: 3px;"></span>
                                    </span>
                                    <input type="text" class="form-control" id="matiere"
                                        value="{{ $enseignement->matiere->intitule }} ({{ $enseignement->matiere->code }})"
                                        disabled readonly>
                                </div>
                                <small class="text-muted">Cette matière a été assignée à cet enseignement et ne peut pas
                                    être changée.</small>
                            </div>

                            <!-- Classe (Read-only) -->
                            <div class="mb-3">
                                <label for="classe" class="form-label">Classe <span
                                        class="badge bg-warning text-dark">Immutable</span></label>
                                <input type="text" class="form-control" id="classe"
                                    value="{{ $enseignement->classe->nom }}" disabled readonly>
                                <small class="text-muted">Cette classe a été assignée et ne peut pas être changée.</small>
                            </div>

                            <!-- Année scolaire (Read-only) -->
                            <div class="mb-3">
                                <label for="annee" class="form-label">Année scolaire <span
                                        class="badge bg-warning text-dark">Immutable</span></label>
                                <input type="text" class="form-control" id="annee"
                                    value="{{ $enseignement->anneeScolaire->annee }}" disabled readonly>
                                <small class="text-muted">Cette année scolaire a été assignée et ne peut pas être
                                    changée.</small>
                            </div>

                            <hr>

                            <!-- Editable Field -->
                            <div class="alert alert-success mb-4">
                                <i data-feather="edit" style="width: 1.2rem; height: 1.2rem;"></i>
                                <strong>Vous pouvez modifier:</strong> Seul le nombre d'heures par semaine peut être ajusté.
                            </div>

                            <!-- Heures par semaine (Editable) -->
                            <div class="mb-3">
                                <label for="heure_par_semaine" class="form-label">Heures par semaine <span
                                        class="text-danger">*</span> <span
                                        class="badge bg-success">Modifiable</span></label>
                                <input type="number" class="form-control @error('heure_par_semaine') is-invalid @enderror"
                                    id="heure_par_semaine" name="heure_par_semaine"
                                    value="{{ old('heure_par_semaine', $enseignement->heure_par_semaine) }}" min="0.5"
                                    step="0.5" required>
                                @error('heure_par_semaine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Nombre d'heures d'enseignement par semaine pour cette
                                    classe.</small>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&nbsp; Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="col-lg-4">
                <!-- Current Status -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Statut actuel</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Professeur</p>
                            <p class="mb-2">
                                <strong>{{ $enseignement->enseignant->prenom }}
                                    {{ $enseignement->enseignant->nom }}</strong>
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Matière</p>
                            <p class="mb-2">
                                <span class="badge"
                                    style="background-color: {{ $enseignement->matiere->color ?? '#007bff' }}; color: #fff;">
                                    {{ $enseignement->matiere->intitule }}
                                </span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Classe</p>
                            <p class="mb-2"><strong>{{ $enseignement->classe->nom }}</strong></p>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Année</p>
                            <p class="mb-0"><strong>{{ $enseignement->anneeScolaire->annee }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Modification History -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Historique</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i data-feather="calendar"></i>
                            <strong>Créé:</strong> {{ $enseignement->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-muted small mb-0">
                            <i data-feather="refresh-cw"></i>
                            <strong>Modifié:</strong> {{ $enseignement->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Zone de danger</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Supprimer cet enseignement supprimera définitivement l'assignation du professeur à cette classe.
                        </p>
                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i data-feather="trash-2"></i>&nbsp; Supprimer l'enseignement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet enseignement?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $enseignement->enseignant->prenom }} {{ $enseignement->enseignant->nom }}</strong> -
                        <strong>{{ $enseignement->matiere->intitule }}</strong> -
                        <strong>{{ $enseignement->classe->nom }}</strong> -
                        <strong>{{ $enseignement->anneeScolaire->annee }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est
                        <strong>irréversible</strong>.
                    </p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_enseignement_matieres.destroy', $enseignement->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2"></i>&nbsp; Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Form validation
        document.getElementById('enseignementForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>
@endsection
