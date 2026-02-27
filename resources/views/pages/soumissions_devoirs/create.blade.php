@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Ajouter une soumission de devoir</h1>
            <p class="text-muted">Enregistrer la soumission d'un élève</p>
        </div>
        <a href="{{ route('gestion_soumissions.index') }}" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Retour
        </a>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('gestion_soumissions.store') }}" method="POST" enctype="multipart/form-data" id="soumissionForm">
                        @csrf

                        <!-- Élève -->
                        <div class="mb-3">
                            <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                            <select class="form-select @error('eleve_id') is-invalid @enderror" id="eleve_id" name="eleve_id" required>
                                <option value="">Sélectionner un élève</option>
                                @foreach($eleves as $eleve)
                                    <option value="{{ $eleve->id }}" @selected(old('eleve_id') == $eleve->id)>
                                        {{ $eleve->prenom }} {{ $eleve->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('eleve_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Devoir -->
                        <div class="mb-3">
                            <label for="devoir_id" class="form-label">Devoir <span class="text-danger">*</span></label>
                            <select class="form-select @error('devoir_id') is-invalid @enderror" id="devoir_id" name="devoir_id" required>
                                <option value="">Sélectionner un devoir</option>
                                @foreach($devoirs as $devoir)
                                    <option value="{{ $devoir->id }}" @selected(old('devoir_id') == $devoir->id)>
                                        {{ $devoir->title }} - {{ $devoir->enseignementMatiereClasse->classe->nom ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('devoir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Sélectionner un statut</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status') == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date soumission -->
                        <div class="mb-3">
                            <label for="date_submission" class="form-label">Date de soumission</label>
                            <input type="datetime-local" class="form-control @error('date_submission') is-invalid @enderror" id="date_submission" name="date_submission" value="{{ old('date_submission') }}">
                            @error('date_submission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contenu -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Contenu / Observations</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" placeholder="Notes ou contenu de la soumission...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pièce jointe -->
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Pièce jointe</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.zip,.rar">
                            <small class="text-muted">Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, JPEG, PNG, ZIP, RAR</small>
                            @error('attachment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Score -->
                        <div class="mb-3">
                            <label for="score" class="form-label">Note / Score</label>
                            <input type="number" class="form-control @error('score') is-invalid @enderror" id="score" name="score" value="{{ old('score') }}" placeholder="ex: 18.5" step="0.5" min="0">
                            @error('score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">En remplissant ce champ, le statut passera automatiquement à "Noté"</small>
                        </div>

                        <!-- Feedback -->
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback / Commentaires</label>
                            <textarea class="form-control @error('feedback') is-invalid @enderror" id="feedback" name="feedback" rows="3" placeholder="Commentaires pour l'élève...">{{ old('feedback') }}</textarea>
                            @error('feedback')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Enregistrer
                            </button>
                            <a href="{{ route('gestion_soumissions.index') }}" class="btn btn-secondary">
                                <i data-feather="x"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Alert Messages -->
            @if($errors->any())
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
                    <p class="card-text small">
                        Cette page permet d'enregistrer la soumission d'un devoir par un élève.
                    </p>
                    <hr>
                    <p class="card-text small mb-0">
                        <strong>Statuts disponibles:</strong>
                        <ul class="mb-0 ps-3">
                            <li><strong>En cours:</strong> Devoir en phase de rédaction</li>
                            <li><strong>Soumis:</strong> Devoir soumis, en attente d'évaluation</li>
                            <li><strong>En retard:</strong> Devoir soumis après la date limite</li>
                            <li><strong>Noté:</strong> Devoir évalué avec une note</li>
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
    document.getElementById('soumissionForm').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
</script>
@endsection
