@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Modifier une note
                        </h1>
                        <p class="text-muted">Mettre à jour les informations de la note</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_evaluations.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
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
                        <form action="{{ route('gestion_notes.update', $note->id) }}" method="POST" id="noteForm">
                            @csrf
                            @method('PUT')

                            <!-- Évaluation -->
                            <div class="mb-3">
                                <label for="evaluation_id" class="form-label">Évaluation <span class="text-danger">*</span></label>
                                <select class="form-select @error('evaluation_id') is-invalid @enderror" id="evaluation_id" name="evaluation_id" required>
                                    <option value="">Sélectionner une évaluation</option>
                                    @foreach($evaluations as $evaluation)
                                        <option value="{{ $evaluation->id }}" @selected(old('evaluation_id', $note->evaluation_id) == $evaluation->id)>
                                            {{ $evaluation->titre }} - {{ $evaluation->enseignementMatiereClasse->classe->nom ?? 'N/A' }} - {{ $evaluation->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }} ({{ $evaluation->date_examen }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('evaluation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Élève -->
                            <div class="mb-3">
                                <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                <select class="form-select @error('eleve_id') is-invalid @enderror" id="eleve_id" name="eleve_id" required>
                                    <option value="">Sélectionner un élève</option>
                                    @foreach($eleves as $eleve)
                                        <option value="{{ $eleve->id }}" @selected(old('eleve_id', $note->eleve_id) == $eleve->id)>
                                            {{ $eleve->nom }} {{ $eleve->prenom }} ({{ $eleve->registration_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('eleve_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Absence -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_absent" name="is_absent" @checked(old('is_absent', $note->is_absent))>
                                    <label class="form-check-label" for="is_absent">Élève absent</label>
                                </div>
                            </div>

                            <div id="absenceFields" style="display: {{ old('is_absent', $note->is_absent) ? 'block' : 'none' }};">
                                <!-- Absence justifiée -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="absence_justified" name="absence_justified" @checked(old('absence_justified', $note->absence_justified))>
                                        <label class="form-check-label" for="absence_justified">Absence justifiée</label>
                                    </div>
                                </div>
                            </div>

                            <div id="scoreFields" style="display: {{ old('is_absent', $note->is_absent) ? 'none' : 'block' }};">
                                <!-- Row: Score et Note max -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="score" class="form-label">Note obtenue</label>
                                        <input type="number" class="form-control @error('score') is-invalid @enderror" id="score" name="score" value="{{ old('score', $note->score) }}" placeholder="ex: 15" step="0.01" min="0">
                                        @error('score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="max_score" class="form-label">Note maximale <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('max_score') is-invalid @enderror" id="max_score" name="max_score" value="{{ old('max_score', $note->max_score) }}" placeholder="20" step="0.01" min="0" required>
                                        @error('max_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Saisi par -->
                            <div class="mb-3">
                                <label for="entered_by" class="form-label">Saisi par (Enseignant)</label>
                                <select class="form-select @error('entered_by') is-invalid @enderror" id="entered_by" name="entered_by">
                                    <option value="">Sélectionner un enseignant</option>
                                    @foreach($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" @selected(old('entered_by', $note->entered_by) == $enseignant->id)>
                                            {{ $enseignant->nom }} {{ $enseignant->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('entered_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commentaire -->
                            <div class="mb-3">
                                <label for="comment" class="form-label">Commentaire</label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" placeholder="Ajouter un commentaire...">{{ old('comment', $note->comment) }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="mt-3">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&nbsp; Enregistrer la modification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <i data-feather="info"></i> Informations
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">Détails de la note</h6>
                        <ul class="small">
                            <li><strong>Date de saisie:</strong> {{ $note->entered_at ? $note->entered_at->format('d/m/Y H:i') : 'N/A' }}</li>
                            <li><strong>Créée le:</strong> {{ $note->created_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Dernière modification:</strong> {{ $note->updated_at->format('d/m/Y H:i') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isAbsentCheckbox = document.getElementById('is_absent');
            const absenceFields = document.getElementById('absenceFields');
            const scoreFields = document.getElementById('scoreFields');
            const scoreInput = document.getElementById('score');

            function toggleFields() {
                if (isAbsentCheckbox.checked) {
                    absenceFields.style.display = 'block';
                    scoreFields.style.display = 'none';
                    scoreInput.required = false;
                    scoreInput.value = '';
                } else {
                    absenceFields.style.display = 'none';
                    scoreFields.style.display = 'block';
                    document.getElementById('absence_justified').checked = false;
                }
            }

            isAbsentCheckbox.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
    @endpush

@endsection
