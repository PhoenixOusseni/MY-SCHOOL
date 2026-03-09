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
                            Ajouter un devoir
                        </h1>
                        <p class="text-white-75">Créer un nouveau devoir pour une classe</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_devoirs.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_devoirs.store') }}" method="POST" enctype="multipart/form-data"
                            id="devoirForm">
                            @csrf

                            <!-- Titre -->
                            <div class="mb-3">
                                <label for="title" class="small">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="ex: Exercices du chapitre 5" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Enseignement -->
                            <div class="mb-3">
                                <label for="enseignement_matiere_classe_id" class="small">Classe / Matière / Professeur
                                    <span class="text-danger">*</span></label>
                                <select class="form-select @error('enseignement_matiere_classe_id') is-invalid @enderror"
                                    id="enseignement_matiere_classe_id" name="enseignement_matiere_classe_id" required>
                                    <option value="">Sélectionner un enseignement</option>
                                    @foreach ($enseignements as $enseignement)
                                        <option value="{{ $enseignement->id }}" @selected(old('enseignement_matiere_classe_id') == $enseignement->id)>
                                            {{ $enseignement->classe->nom ?? 'N/A' }} -
                                            {{ $enseignement->matiere->intitule ?? 'N/A' }} -
                                            {{ $enseignement->enseignant->prenom ?? 'N/A' }}
                                            {{ $enseignement->enseignant->nom ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignement_matiere_classe_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-3">
                                <label for="type" class="small">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    @foreach ($types as $value => $label)
                                        <option value="{{ $value }}" @selected(old('type') == $value)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dates -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_assignation" class="small">Date d'assignation</label>
                                    <input type="date"
                                        class="form-control @error('date_assignation') is-invalid @enderror"
                                        id="date_assignation" name="date_assignation"
                                        value="{{ old('date_assignation') }}">
                                    @error('date_assignation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="date_echeance" class="small">Date d'échéance <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_echeance') is-invalid @enderror"
                                        id="date_echeance" name="date_echeance" value="{{ old('date_echeance') }}"
                                        required>
                                    @error('date_echeance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Note max -->
                            <div class="mb-3">
                                <label for="note_max" class="small">Note maximale</label>
                                <input type="number" class="form-control @error('note_max') is-invalid @enderror"
                                    id="note_max" name="note_max" value="{{ old('note_max') }}" placeholder="ex: 20"
                                    step="0.5" min="0">
                                @error('note_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Checkboxes -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="est_note" name="est_note"
                                        value="1" @checked(old('est_note'))>
                                    <label class="form-check-label" for="est_note">
                                        Ce devoir sera noté
                                    </label>
                                </div>
                            </div>

                            <!-- Attachment -->
                            <div class="mb-3">
                                <label for="attachment" class="small">Pièce jointe</label>
                                <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                    id="attachment" name="attachment"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png">
                                <small class="text-muted">Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG,
                                    JPEG, PNG</small>
                                @error('attachment')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="small">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Description du devoir...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1 btn-sm">
                                    <i data-feather="save"></i>&nbsp; Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <strong>Erreurs de validation:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Information</h5>
                        <hr>
                        <p class="card-text small">
                            Cette page permet de créer un nouveau devoir pour une classe spécifique et une matière.
                        </p>
                        <p class="card-text small mb-0">
                            <strong>Champs obligatoires:</strong>
                        <ul class="mb-0 ps-3">
                            <li>Titre</li>
                            <li>Classe/Matière/Professeur</li>
                            <li>Type</li>
                            <li>Date d'échéance</li>
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
        document.getElementById('devoirForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>
@endsection
