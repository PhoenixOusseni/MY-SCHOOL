@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit-3"></i></div>
                            Modifier une soumission de devoir
                        </h1>
                        <p class="text-muted">{{ $soumission->eleve->prenom ?? 'N/A' }} {{ $soumission->eleve->nom ?? 'N/A' }} -
                    {{ $soumission->devoir->title ?? 'N/A' }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_devoirs.index') }}" class="btn btn-light">
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
                        <form action="{{ route('gestion_soumissions.update', $soumission->id) }}" method="POST"
                            enctype="multipart/form-data" id="soumissionForm">
                            @csrf
                            @method('PUT')

                            <!-- Élève -->
                            <div class="mb-3">
                                <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                <select class="form-select @error('eleve_id') is-invalid @enderror" id="eleve_id"
                                    name="eleve_id" required>
                                    @foreach ($eleves as $eleve)
                                        <option value="{{ $eleve->id }}" @selected(old('eleve_id', $soumission->eleve_id) == $eleve->id)>
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
                                <select class="form-select @error('devoir_id') is-invalid @enderror" id="devoir_id"
                                    name="devoir_id" required>
                                    @foreach ($devoirs as $devoir)
                                        <option value="{{ $devoir->id }}" @selected(old('devoir_id', $soumission->devoir_id) == $devoir->id)>
                                            {{ $devoir->title }} -
                                            {{ $devoir->enseignementMatiereClasse->classe->nom ?? 'N/A' }}
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
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $soumission->status) == $value)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date soumission -->
                            <div class="mb-3">
                                <label for="date_submission" class="form-label">Date de soumission</label>
                                <input type="datetime-local"
                                    class="form-control @error('date_submission') is-invalid @enderror" id="date_submission"
                                    name="date_submission"
                                    value="{{ old('date_submission', $soumission->date_submission ? $soumission->date_submission : '') }}">
                                @error('date_submission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contenu -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Contenu / Observations</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4"
                                    placeholder="Notes ou contenu de la soumission...">{{ old('content', $soumission->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pièce jointe -->
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Pièce jointe</label>
                                @if ($soumission->attachment)
                                    <div class="alert alert-info mb-2">
                                        <i data-feather="file"></i>
                                        Fichier actuel:
                                        <a href="{{ asset('storage/' . $soumission->attachment) }}"
                                            target="_blank">{{ basename($soumission->attachment) }}</a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                    id="attachment" name="attachment"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.zip,.rar">
                                <small class="text-muted">Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG,
                                    JPEG, PNG, ZIP, RAR (Laissez vide pour garder le fichier actuel)</small>
                                @error('attachment')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Score -->
                            <div class="mb-3">
                                <label for="score" class="form-label">Note / Score</label>
                                <input type="number" class="form-control @error('score') is-invalid @enderror"
                                    id="score" name="score" value="{{ old('score', $soumission->score) }}"
                                    placeholder="ex: 18.5" step="0.5" min="0">
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">En remplissant ce champ, le statut passera automatiquement à
                                    "Noté"</small>
                            </div>

                            <!-- Graded By -->
                            <div class="mb-3">
                                <label for="graded_by" class="form-label">Évalué par (Enseignant)</label>
                                <select class="form-select @error('graded_by') is-invalid @enderror" id="graded_by"
                                    name="graded_by">
                                    <option value="">Aucun</option>
                                    @foreach ($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" @selected(old('graded_by', $soumission->graded_by) == $enseignant->id)>
                                            {{ $enseignant->prenom }} {{ $enseignant->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('graded_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Feedback -->
                            <div class="mb-3">
                                <label for="feedback" class="form-label">Feedback / Commentaires</label>
                                <textarea class="form-control @error('feedback') is-invalid @enderror" id="feedback" name="feedback" rows="3"
                                    placeholder="Commentaires pour l'élève...">{{ old('feedback', $soumission->feedback) }}</textarea>
                                @error('feedback')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&nbsp; Enregistrer les modifications
                                </button>
                                <a href="{{ route('gestion_soumissions.index') }}" class="btn btn-dark">
                                    <i data-feather="x"></i> Annuler
                                </a>
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
                <!-- Current Status -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Statut actuel</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Élève</p>
                            <p class="mb-2">
                                <strong>{{ $soumission->eleve->prenom ?? 'N/A' }}
                                    {{ $soumission->eleve->nom ?? 'N/A' }}</strong>
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Statut</p>
                            <p class="mb-2">
                                @if ($soumission->status === 'noté')
                                    <span class="badge bg-success">Noté</span>
                                @elseif($soumission->status === 'soumis')
                                    <span class="badge bg-info">Soumis</span>
                                @elseif($soumission->status === 'en retard')
                                    <span class="badge bg-warning text-dark">En retard</span>
                                @else
                                    <span class="badge bg-secondary">En cours</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Note</p>
                            <p class="mb-2">
                                @if ($soumission->score !== null)
                                    <strong
                                        class="badge bg-light text-dark">{{ $soumission->score }}/{{ $soumission->devoir->note_max ?? '20' }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Date soumission</p>
                            <p class="mb-0">
                                @if ($soumission->date_submission)
                                    {{ \Carbon\Carbon::parse($soumission->date_submission)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modification History -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Historique</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i data-feather="calendar"></i>
                            <strong>Créée:</strong> {{ $soumission->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-muted small mb-0">
                            <i data-feather="refresh-cw"></i>
                            <strong>Modifiée:</strong> {{ $soumission->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Zone de danger</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Supprimer cette soumission la supprimera définitivement du système.
                        </p>
                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i data-feather="trash-2"></i> Supprimer la soumission
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
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette soumission?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $soumission->eleve->prenom ?? 'N/A' }} {{ $soumission->eleve->nom ?? 'N/A' }}</strong>
                        -
                        <strong>{{ $soumission->devoir->title ?? 'N/A' }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est
                        <strong>irréversible</strong>.</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('gestion_soumissions.destroy', $soumission->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">Supprimer définitivement</button>
                    </form>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">&nbsp; Annuler</button>
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
