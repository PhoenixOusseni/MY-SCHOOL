@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="eye"></i></div>
                            Détails de l'évaluation
                        </h1>
                        <p class="text-muted">{{ $evaluation->titre }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="#" class="btn btn-1 btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i data-feather="plus"></i>&nbsp; Soumettre une note
                        </a>
                        <a href="{{ route('gestion_evaluations.edit', $evaluation->id) }}" class="btn btn-dark btn-sm">
                            <i data-feather="edit"></i>&nbsp; Modifier
                        </a>
                        <a href="{{ route('gestion_evaluations.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
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
                <!-- General Info -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0"><i data-feather="info"></i> Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Titre</p>
                                <p class="mb-0"><strong>{{ $evaluation->titre }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Type</p>
                                <p class="mb-0"><span
                                        class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $evaluation->type)) }}</span>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Classe</p>
                                <p class="mb-0">
                                    <strong>{{ $evaluation->enseignementMatiereClasse->classe->nom ?? 'N/A' }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Matière</p>
                                <p class="mb-0">
                                    <span
                                        style="background-color: {{ $evaluation->enseignementMatiereClasse->matiere->color ?? '#007bff' }}; color: white;"
                                        class="badge">
                                        {{ $evaluation->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Statut</p>
                                <p class="mb-0">
                                    @if ($evaluation->est_publie)
                                        <span class="badge bg-success"><i data-feather="eye"></i> Publiée</span>
                                    @else
                                        <span class="badge bg-secondary"><i data-feather="eye-off"></i> Brouillon</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Période</p>
                                <p class="mb-0"><small>{{ $evaluation->periodEvaluation->name ?? 'N/A' }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Info -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0"><i data-feather="calendar"></i> Informations d'examen</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Date d'examen</p>
                                <p class="mb-0">
                                    @if ($evaluation->date_examen)
                                        <strong>{{ \Carbon\Carbon::parse($evaluation->date_examen)->format('d/m/Y') }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Heure de début</p>
                                <p class="mb-0">
                                    @if ($evaluation->heure_debut)
                                        <strong>{{ $evaluation->heure_debut }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Durée</p>
                                <p class="mb-0">
                                    @if ($evaluation->duree)
                                        <strong>{{ $evaluation->duree }} minutes</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Salle</p>
                                <p class="mb-0">
                                    @if ($evaluation->salle)
                                        <strong>{{ $evaluation->salle }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Note maximale</p>
                                <p class="mb-0">
                                    @if ($evaluation->note_max)
                                        <strong>{{ $evaluation->note_max }}/20</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Coefficient</p>
                                <p class="mb-0">
                                    @if ($evaluation->coefficient)
                                        <strong>{{ $evaluation->coefficient }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if ($evaluation->description)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            {{ $evaluation->description }}
                        </div>
                    </div>
                @endif

                <!-- Instructions -->
                @if ($evaluation->instructions)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Instructions</h5>
                        </div>
                        <div class="card-body">
                            {{ $evaluation->instructions }}
                        </div>
                    </div>
                @endif

                <!-- Grades -->
                @if ($notes->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i data-feather="award"></i> Notes ({{ $notes->count() }})</h5>
                        </div>
                        <div class="table-responsive p-3">
                            <table class="table table-hover table-sm table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Nom</th>
                                        <th>Note</th>
                                        <th>Appréciation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notes as $note)
                                        <tr>
                                            <td>{{ $note->eleve->registration_number ?? 'N/A' }}</td>
                                            <td>
                                                <strong>{{ $note->eleve->prenom ?? 'N/A' }}
                                                    {{ $note->eleve->nom ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                @if ($note->score !== null)
                                                    <strong
                                                        class="badge bg-light text-dark">{{ $note->score }}/{{ $evaluation->max_score ?? '20' }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($note->percentage >= 90)
                                                    <span class="badge bg-success">Excellent</span>
                                                @elseif($note->percentage >= 80)
                                                    <span class="badge bg-success">Très bien</span>
                                                @elseif($note->percentage >= 70)
                                                    <span class="badge bg-info">Bien</span>
                                                @elseif($note->percentage >= 60)
                                                    <span class="badge bg-primary">Assez bien</span>
                                                @elseif($note->percentage >= 50)
                                                    <span class="badge bg-warning">Passable</span>
                                                @else
                                                    <span class="badge bg-danger">Insuffisant</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('gestion_notes.show', $note->id) }}"
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
                            @if ($evaluation->est_publie)
                                <i data-feather="eye" style="width: 3rem; height: 3rem; color: #28a745;"></i>
                            @else
                                <i data-feather="eye-off" style="width: 3rem; height: 3rem; color: #6c757d;"></i>
                            @endif
                        </div>
                        <p class="text-muted mb-2">
                            @if ($evaluation->est_publie)
                                Évaluation publiée
                            @else
                                Évaluation en brouillon
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Informations rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1"><i data-feather="book"></i> Type</p>
                            <p class="mb-2"><strong>{{ ucfirst(str_replace('_', ' ', $evaluation->type)) }}</strong></p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p class="text-muted small mb-1"><i data-feather="award"></i> Note max</p>
                            <p class="mb-2"><strong>{{ $evaluation->note_max ?? '20' }}/20</strong></p>
                        </div>
                        <hr>
                        <div>
                            <p class="text-muted small mb-1"><i data-feather="list"></i> Notes saisies</p>
                            <p class="mb-0">
                                <strong>{{ $notes->whereNotNull('valeur_note')->count() }}/{{ $notes->count() }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="#">
                            <button class="btn btn-dark btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                <i data-feather="plus"></i>&nbsp; Ajouter une note
                            </button>
                        </a>
                        <a href="{{ route('gestion_evaluations.edit', $evaluation->id) }}"
                            class="btn btn-1 btn-sm w-100 mb-2">
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette évaluation?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $evaluation->titre }}</strong><br>
                        <small>{{ $evaluation->enseignementMatiereClasse->classe->nom ?? 'N/A' }} -
                            {{ $evaluation->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_evaluations.destroy', $evaluation->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">Supprimer</button>
                    </form>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">Ajouter une note &mdash; {{ $evaluation->titre }}</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('gestion_notes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                    <input type="hidden" name="max_score" value="{{ $evaluation->note_max ?? 20 }}">
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
                            <!-- Score -->
                            <div class="col-md-6 mb-3">
                                <label for="score" class="form-label fw-semibold">
                                    Note <span class="text-muted">(/ {{ $evaluation->note_max ?? 20 }})</span>
                                </label>
                                <input type="number" step="0.01" min="0" max="{{ $evaluation->note_max ?? 20 }}"
                                    class="form-control @error('score') is-invalid @enderror"
                                    name="score" id="score" value="{{ old('score') }}"
                                    placeholder="Ex: 15">
                                @error('score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Saisi par -->
                            <div class="col-md-6 mb-3">
                                <label for="entered_by" class="form-label fw-semibold">Saisi par</label>
                                <select class="form-select @error('entered_by') is-invalid @enderror" name="entered_by" id="entered_by">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($enseignants as $ens)
                                        <option value="{{ $ens->id }}"
                                            {{ old('entered_by', $evaluation->enseignementMatiereClasse?->enseignant?->id) == $ens->id ? 'selected' : '' }}>
                                            {{ $ens->prenom }} {{ $ens->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('entered_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Absent -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_absent" id="is_absent"
                                        {{ old('is_absent') ? 'checked' : '' }}
                                        onchange="document.getElementById('score').disabled = this.checked; document.getElementById('absenceJustified').style.display = this.checked ? 'block' : 'none';">
                                    <label class="form-check-label" for="is_absent">Élève absent</label>
                                </div>
                            </div>
                            <div class="col-md-6" id="absenceJustified" style="display: {{ old('is_absent') ? 'block' : 'none' }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="absence_justified" id="absence_justified"
                                        {{ old('absence_justified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="absence_justified">Absence justifiée</label>
                                </div>
                            </div>
                        </div>

                        <!-- Commentaire -->
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-semibold">Commentaire</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror"
                                name="comment" id="comment" rows="2"
                                placeholder="Observations sur la note...">{{ old('comment') }}</textarea>
                            @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-1">
                            <i data-feather="save"></i>&nbsp; Enregistrer la note
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
