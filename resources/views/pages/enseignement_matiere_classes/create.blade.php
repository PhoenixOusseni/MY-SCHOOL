@extends('layouts.master')

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                            Ajouter un enseignement
                        </h1>
                        <p class="text-muted">Assigner un professeur et une matière à une classe</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignement_matieres.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_enseignement_matieres.store') }}" method="POST"
                            id="enseignementForm">
                            @csrf

                            <!-- Professeur -->
                            <div class="mb-3">
                                <label for="enseignant_id" class="form-label">Professeur <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('enseignant_id') is-invalid @enderror" id="enseignant_id"
                                    name="enseignant_id" required>
                                    <option value="">Sélectionner un professeur</option>
                                    @foreach ($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" @selected(old('enseignant_id') == $enseignant->id)>
                                            {{ $enseignant->prenom }} {{ $enseignant->nom }}
                                            ({{ $enseignant->numero_employe }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Matière -->
                            <div class="mb-3">
                                <label for="matiere_id" class="form-label">Matière <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('matiere_id') is-invalid @enderror" id="matiere_id"
                                    name="matiere_id" required>
                                    <option value="">Sélectionner une matière</option>
                                    @foreach ($matieres as $matiere)
                                        <option value="{{ $matiere->id }}" @selected(old('matiere_id') == $matiere->id)>
                                            {{ $matiere->intitule }} ({{ $matiere->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('matiere_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Classe -->
                            <div class="mb-3">
                                <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id"
                                    name="classe_id" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach ($classes as $classe)
                                        <option value="{{ $classe->id }}" @selected(old('classe_id') == $classe->id)>
                                            {{ $classe->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('classe_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Année scolaire -->
                            <div class="mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('annee_scolaire_id') is-invalid @enderror"
                                    id="annee_scolaire_id" name="annee_scolaire_id" required>
                                    <option value="">Sélectionner une année</option>
                                    @foreach ($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}" @selected(old('annee_scolaire_id') == $annee->id)>
                                            {{ $annee->annee }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_scolaire_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Heures par semaine -->
                            <div class="mb-3">
                                <label for="heure_par_semaine" class="form-label">Heures par semaine <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('heure_par_semaine') is-invalid @enderror"
                                    id="heure_par_semaine" name="heure_par_semaine"
                                    value="{{ old('heure_par_semaine', 1) }}" min="0.5" step="0.5" required>
                                @error('heure_par_semaine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Enregistrer
                                </button>
                                <a href="{{ route('gestion_enseignement_matieres.index') }}" class="btn btn-secondary">
                                    <i data-feather="x"></i> Annuler
                                </a>
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
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Information</h5>
                        <p class="card-text small">
                            Cette page permet d'assigner une matière à une classe avec un professeur spécifique pour une
                            année scolaire donnée.
                        </p>
                        <hr>
                        <p class="card-text small mb-0">
                            <strong>Champs obligatoires:</strong>
                        <ul class="mb-0 ps-3">
                            <li>Professeur (actif)</li>
                            <li>Matière (active)</li>
                            <li>Classe</li>
                            <li>Année scolaire</li>
                            <li>Heures par semaine</li>
                        </ul>
                        </p>
                    </div>
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
