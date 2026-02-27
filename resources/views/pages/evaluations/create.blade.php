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
                            Ajouter une évaluation
                        </h1>
                        <p class="text-muted">Créer une nouvelle évaluation pour une classe</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_evaluations.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="list"></i>&nbsp; Liste des évaluations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_evaluations.store') }}" method="POST" id="evaluationForm">
                            @csrf

                            <!-- Titre -->
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" placeholder="ex: Examen Mathématiques" required>
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Détails de l'évaluation...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Classe - Matière -->
                            <div class="mb-3">
                                <label for="enseignement_matiere_classe_id" class="form-label">Classe - Matière <span class="text-danger">*</span></label>
                                <select class="form-select @error('enseignement_matiere_classe_id') is-invalid @enderror" id="enseignement_matiere_classe_id" name="enseignement_matiere_classe_id" required>
                                    <option value="" disabled>Sélectionner une classe-matière</option>
                                    @foreach($enseignementMatiereClasses as $emc)
                                        <option value="{{ $emc->id }}" @selected(old('enseignement_matiere_classe_id') == $emc->id)>
                                            {{ $emc->classe->nom }} - {{ $emc->matiere->intitule }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignement_matiere_classe_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Période d'évaluation -->
                            <div class="mb-3">
                                <label for="period_evaluation_id" class="form-label">Période d'évaluation <span class="text-danger">*</span></label>
                                <select class="form-select @error('period_evaluation_id') is-invalid @enderror" id="period_evaluation_id" name="period_evaluation_id" required>
                                    <option value="" disabled>Sélectionner une période</option>
                                    @foreach($periodEvaluations as $period)
                                        <option value="{{ $period->id }}" @selected(old('period_evaluation_id') == $period->id)>
                                            {{ $period->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('period_evaluation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Type d'évaluation <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="" disabled>Sélectionner un type</option>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" @selected(old('type') == $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Row: Date et Heure -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_examen" class="form-label">Date d'examen <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_examen') is-invalid @enderror" id="date_examen" name="date_examen" value="{{ old('date_examen') }}" required>
                                    @error('date_examen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="heure_debut" class="form-label">Heure de début</label>
                                    <input type="time" class="form-control @error('heure_debut') is-invalid @enderror" id="heure_debut" name="heure_debut" value="{{ old('heure_debut') }}">
                                    @error('heure_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row: Durée et Salle -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="duree" class="form-label">Durée (en minutes)</label>
                                    <input type="number" class="form-control @error('duree') is-invalid @enderror" id="duree" name="duree" value="{{ old('duree') }}" placeholder="ex: 120" min="1">
                                    @error('duree')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="salle" class="form-label">Salle</label>
                                    <input type="text" class="form-control @error('salle') is-invalid @enderror" id="salle" name="salle" value="{{ old('salle') }}" placeholder="ex: Salle A101">
                                    @error('salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row: Note max et Coefficient -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="note_max" class="form-label">Note maximale</label>
                                    <input type="number" class="form-control @error('note_max') is-invalid @enderror" id="note_max" name="note_max" value="{{ old('note_max', '20') }}" placeholder="20" step="0.5" min="0">
                                    @error('note_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="coefficient" class="form-label">Coefficient</label>
                                    <input type="number" class="form-control @error('coefficient') is-invalid @enderror" id="coefficient" name="coefficient" value="{{ old('coefficient', '1') }}" placeholder="1" step="0.5" min="0">
                                    @error('coefficient')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="mb-3">
                                <label for="instructions" class="form-label">Instructions</label>
                                <textarea class="form-control @error('instructions') is-invalid @enderror" id="instructions" name="instructions" rows="3" placeholder="Instructions pour les élèves...">{{ old('instructions') }}</textarea>
                                @error('instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Publié -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="est_publie" name="est_publie" value="1" @checked(old('est_publie'))>
                                    <label class="form-check-label" for="est_publie">
                                        Publier cette évaluation
                                    </label>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&nbsp; Créer l'évaluation
                                </button>
                                <a href="{{ route('gestion_evaluations.index') }}" class="btn btn-dark">
                                     <i data-feather="x"></i>&nbsp; Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <h5 class="alert-heading"><i data-feather="alert-circle"></i> Erreurs de validation</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i data-feather="info"></i> Information</h5>
                        <p class="card-text small">
                            Cette page permet de créer une nouvelle évaluation pour une classe.
                        </p>
                        <hr>
                        <p class="card-text small"><strong>Types d'évaluation:</strong></p>
                        <ul class="small">
                            <li><strong>Examen:</strong> Examen semestriel ou annuel</li>
                            <li><strong>Contrôle:</strong> Test de contrôle continu</li>
                            <li><strong>Interrogation:</strong> Question rapide</li>
                            <li><strong>Devoir sur table:</strong> Devoir écrit en classe</li>
                            <li><strong>Autre:</strong> Autre type</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        document.getElementById('evaluationForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>

@endsection
