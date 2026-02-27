@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit-2"></i></div>
                            Modifier l'Absence
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_absences.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations de l'Absence
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form id="absenceForm" action="{{ route('gestion_absences.update', $absence->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ old('date', $absence->date ? $absence->date->format('Y-m-d') : '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="periode" class="form-label">Période</label>
                                    <select name="periode" id="periode" class="form-select">
                                        <option value="" selected disabled>-- Sélectionner --</option>
                                        <option value="Matin" {{ old('periode', $absence->periode) == 'Matin' ? 'selected' : '' }}>Matin</option>
                                        <option value="Après-midi" {{ old('periode', $absence->periode) == 'Après-midi' ? 'selected' : '' }}>Après-midi</option>
                                        <option value="Cours 1-2" {{ old('periode', $absence->periode) == 'Cours 1-2' ? 'selected' : '' }}>Cours 1-2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                    <select class="form-select" id="eleve_id" name="eleve_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($eleves as $eleve)
                                            <option value="{{ $eleve->id }}" {{ old('eleve_id', $absence->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                                {{ $eleve->prenom }} {{ $eleve->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                    <select class="form-select" id="classe_id" name="classe_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ old('classe_id', $absence->classe_id) == $classe->id ? 'selected' : '' }}>
                                                {{ $classe->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="matiere_id" class="form-label">Matière</label>
                                    <select class="form-select" id="matiere_id" name="matiere_id">
                                        <option value="">-- Aucune --</option>
                                        @foreach ($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" {{ old('matiere_id', $absence->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                                {{ $matiere->intitule }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reported_by" class="form-label">Signalée par</label>
                                    <select class="form-select" id="reported_by" name="reported_by">
                                        <option value="">-- Utilisateur connecté --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('reported_by', $absence->reported_by) == $user->id ? 'selected' : '' }}>
                                                {{ $user->prenom }} {{ $user->nom }} ({{ $user->login }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reported_at" class="form-label">Date/Heure de signalement</label>
                                    <input type="datetime-local" class="form-control" id="reported_at" name="reported_at"
                                        value="{{ old('reported_at', $absence->reported_at ? $absence->reported_at->format('Y-m-d\\TH:i') : '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="justification_document" class="form-label">Document justificatif</label>
                                    <input type="file" class="form-control" id="justification_document" name="justification_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    @if ($absence->justification_document)
                                        <small class="text-muted d-block mt-2">
                                            Document actuel:
                                            <a href="{{ asset('storage/' . $absence->justification_document) }}" target="_blank">Consulter</a>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="raison" class="form-label">Raison</label>
                                <textarea class="form-control" id="raison" name="raison" rows="4" placeholder="Motif de l'absence...">{{ old('raison', $absence->raison) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_justified" name="is_justified" value="1"
                                        {{ old('is_justified', $absence->is_justified) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_justified">
                                        <strong>Absence justifiée</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>&nbsp; Enregistrer les modifications
                                        </button>
                                        <a href="{{ route('gestion_absences.index') }}" class="btn btn-dark">
                                            <i data-feather="x" class="me-2"></i>&nbsp; Annuler
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="clock" class="me-2"></i>Historique
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Créé le</p>
                                <p class="h6">{{ $absence->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Modifié le</p>
                                <p class="h6">{{ $absence->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-danger" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-triangle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Zone de Danger</h6>
                            <p class="mb-0 small">
                                <button type="button" class="btn btn-link btn-sm p-0 text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    Supprimer cette absence
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">
                        <i data-feather="alert-triangle" class="me-2"></i>Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette absence ?</p>
                    <p class="text-muted">
                        <strong>{{ $absence->eleve->prenom ?? '' }} {{ $absence->eleve->nom ?? '' }}</strong>
                        - {{ $absence->date ? $absence->date->format('d/m/Y') : '-' }}
                    </p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_absences.destroy', $absence->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">Supprimer</button>
                    </form>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            document.getElementById('absenceForm').addEventListener('submit', function(e) {
                const date = document.getElementById('date').value;
                const eleveId = document.getElementById('eleve_id').value;
                const classeId = document.getElementById('classe_id').value;

                if (!date || !eleveId || !classeId) {
                    e.preventDefault();
                    alert('Veuillez renseigner les champs obligatoires');
                    return false;
                }
            });
        });
    </script>
@endsection
