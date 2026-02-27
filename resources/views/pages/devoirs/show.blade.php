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
                            Détails du devoir
                        </h1>
                        <p class="text-muted">{{ $devoir->title }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_devoirs.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                        <a href="#" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i data-feather="edit-3"></i>&nbsp; Soumettre une note
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Devoir Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Titre</p>
                                <p class="mb-0">
                                    <strong>{{ $devoir->title }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Type</p>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">{{ $devoir->type }}</span>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Classe</p>
                                <p class="mb-0">
                                    <strong>{{ $devoir->enseignementMatiereClasse->classe->nom ?? 'N/A' }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Matière</p>
                                <p class="mb-0">
                                    <span class="badge bg-light text-dark"
                                        style="background-color: {{ $devoir->enseignementMatiereClasse->matiere->color ?? '#007bff' }}; color: #fff;">
                                        {{ $devoir->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Professeur</p>
                                <p class="mb-0">
                                    <strong>{{ $devoir->enseignementMatiereClasse->enseignant->prenom ?? 'N/A' }}
                                        {{ $devoir->enseignementMatiereClasse->enseignant->nom ?? 'N/A' }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ $devoir->enseignementMatiereClasse->enseignant->numero_employe ?? 'N/A' }}</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Noté</p>
                                <p class="mb-0">
                                    @if ($devoir->est_note)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if ($devoir->description)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            {{ $devoir->description }}
                        </div>
                    </div>
                @endif

                <!-- Dates -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i data-feather="calendar" style="width: 1.2rem; height: 1.2rem;"></i> Dates</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Assignation</p>
                                <p class="mb-3">
                                    @if ($devoir->date_assignation)
                                        <strong>{{ \Carbon\Carbon::parse($devoir->date_assignation)->format('d/m/Y') }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Échéance</p>
                                <p class="mb-3">
                                    @if ($devoir->date_echeance)
                                        <strong>{{ \Carbon\Carbon::parse($devoir->date_echeance)->format('d/m/Y') }}</strong>
                                        @if (\Carbon\Carbon::parse($devoir->date_echeance)->isPast())
                                            <br>
                                            <small class="text-danger">Expiré</small>
                                        @elseif(\Carbon\Carbon::parse($devoir->date_echeance)->diffInDays() <= 3)
                                            <br>
                                            <small class="text-warning">Urgent</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if ($devoir->note_max)
                            <hr>
                            <p class="text-muted mb-1">Note maximale</p>
                            <p class="mb-0"><strong class="badge bg-light text-dark">/{{ $devoir->note_max }}</strong>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Submissions Section -->
                @if ($devoir->soumissionsDevoirs && $devoir->soumissionsDevoirs->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i data-feather="send" style="width: 1.2rem; height: 1.2rem;"></i>
                                Soumissions ({{ $devoir->soumissionsDevoirs->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom</th>
                                            <th>Date soumission</th>
                                            <th>Note</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($devoir->soumissionsDevoirs as $soumission)
                                            <tr>
                                                <td>{{ $soumission->eleve->registration_number ?? 'N/A' }}</td>
                                                <td>
                                                    {{ $soumission->eleve->prenom ?? 'N/A' }}
                                                    {{ $soumission->eleve->nom ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($soumission->date_submission)
                                                        {{ \Carbon\Carbon::parse($soumission->date_submission)->format('d/m/Y H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($soumission->score !== null)
                                                        <strong>{{ $soumission->score }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('gestion_soumissions.show', $soumission->id) }}"
                                                        class="btn btn-sm btn-1">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Statut</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i data-feather="check-circle" style="width: 3rem; height: 3rem; color: #28a745;"></i>
                        </div>
                        <p class="text-muted mb-2">Devoir actif</p>
                        <div class="alert alert-info">
                            <small><strong>{{ $devoir->title }}</strong></small>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted mb-1">Soumissions</p>
                            <p class="h4 mb-0">
                                {{ $devoir->soumissionsDevoirs ? $devoir->soumissionsDevoirs->count() : 0 }}</p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Note max</p>
                            <p class="h4 mb-0">
                                @if ($devoir->note_max)
                                    <span class="badge bg-light text-dark">/{{ $devoir->note_max }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <hr>
                        <div>
                            <p class="text-muted mb-1">Pièce jointe</p>
                            @if ($devoir->attachment)
                                <p class="mb-0">
                                    <a href="{{ asset('storage/' . $devoir->attachment) }}" target="_blank"
                                        class="btn btn-sm btn-1">
                                        <i data-feather="download"></i> Télécharger
                                    </a>
                                </p>
                            @else
                                <p class="text-muted mb-0">Aucune</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="" class="btn btn-dark btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i data-feather="send"></i>&nbsp; Soumettre une note
                        </a>
                        <a href="{{ route('gestion_devoirs.edit', $devoir->id) }}" class="btn btn-1 btn-sm w-100 mb-2">
                            <i data-feather="edit"></i>&nbsp; Modifier
                        </a>
                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i data-feather="trash-2"></i>&nbsp; Supprimer
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
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce devoir?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $devoir->title }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est irréversible.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_devoirs.destroy', $devoir->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">
                            <i data-feather="trash-2" class="me-2"></i>&nbsp; Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">Soumettre une note — {{ $devoir->title }}</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('gestion_soumissions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="devoir_id" value="{{ $devoir->id }}">
                    <div class="modal-body">

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Élève -->
                        <div class="mb-3">
                            <label for="eleve_id" class="form-label fw-semibold">Élève <span class="text-danger">*</span></label>
                            <select class="form-select @error('eleve_id') is-invalid @enderror" name="eleve_id" id="eleve_id" required>
                                <option value="" disabled selected>-- Sélectionner un élève --</option>
                                @foreach($eleves as $eleve)
                                    <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                        {{ $eleve->prenom }} {{ strtoupper($eleve->nom) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('eleve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <!-- Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Date de soumission -->
                            <div class="col-md-6 mb-3">
                                <label for="date_submission" class="form-label fw-semibold">Date de soumission</label>
                                <input type="date" class="form-control @error('date_submission') is-invalid @enderror"
                                    name="date_submission" id="date_submission"
                                    value="{{ old('date_submission', date('Y-m-d')) }}">
                                @error('date_submission')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        @if($devoir->est_note)
                        <div class="row">
                            <!-- Score -->
                            <div class="col-md-6 mb-3">
                                <label for="score" class="form-label fw-semibold">
                                    Note
                                    @if($devoir->note_max)<span class="text-muted">(/ {{ $devoir->note_max }})</span>@endif
                                </label>
                                <input type="number" step="0.01" min="0"
                                    max="{{ $devoir->note_max ?? '' }}"
                                    class="form-control @error('score') is-invalid @enderror"
                                    name="score" id="score" value="{{ old('score') }}"
                                    placeholder="Ex: 15">
                                @error('score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Corrigé par -->
                            <div class="col-md-6 mb-3">
                                <label for="graded_by" class="form-label fw-semibold">Corrigé par</label>
                                <select class="form-select @error('graded_by') is-invalid @enderror" name="graded_by" id="graded_by">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($enseignants as $ens)
                                        <option value="{{ $ens->id }}"
                                            {{ old('graded_by', $devoir->enseignementMatiereClasse?->enseignant?->id) == $ens->id ? 'selected' : '' }}>
                                            {{ $ens->prenom }} {{ $ens->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('graded_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        @endif

                        <!-- Contenu / Commentaire -->
                        <div class="mb-3">
                            <label for="content" class="form-label fw-semibold">Contenu / Commentaire</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                name="content" id="content" rows="3"
                                placeholder="Réponse ou commentaire de l'élève...">{{ old('content') }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Feedback enseignant -->
                        <div class="mb-3">
                            <label for="feedback" class="form-label fw-semibold">Feedback enseignant</label>
                            <textarea class="form-control @error('feedback') is-invalid @enderror"
                                name="feedback" id="feedback" rows="2"
                                placeholder="Remarques du professeur...">{{ old('feedback') }}</textarea>
                            @error('feedback')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Pièce jointe -->
                        <div class="mb-3">
                            <label for="attachment" class="form-label fw-semibold">Pièce jointe</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                name="attachment" id="attachment"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.zip,.rar">
                            <div class="form-text">PDF, Word, Excel, Image, ZIP — max recommandé : 10 Mo</div>
                            @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-1">
                            <i data-feather="send" class="me-2"></i>&nbsp; Soumettre la note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
