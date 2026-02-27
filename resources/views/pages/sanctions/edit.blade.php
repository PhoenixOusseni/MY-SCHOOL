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
                            Modifier la Sanction
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_sanctions.index') }}" class="btn btn-light">
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
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations de la Sanction
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form id="sanctionForm" action="{{ route('gestion_sanctions.update', $sanction->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                    <select class="form-select" id="eleve_id" name="eleve_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($eleves as $eleve)
                                            <option value="{{ $eleve->id }}" {{ old('eleve_id', $sanction->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                                {{ $eleve->prenom }} {{ $eleve->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="incident_disciplinaire_id" class="form-label">Incident lié <span class="text-danger">*</span></label>
                                    <select class="form-select" id="incident_disciplinaire_id" name="incident_disciplinaire_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($incidents as $incident)
                                            <option value="{{ $incident->id }}" {{ old('incident_disciplinaire_id', $sanction->incident_disciplinaire_id) == $incident->id ? 'selected' : '' }}>
                                                #{{ $incident->id }} - {{ $incident->eleve->prenom ?? '' }} {{ $incident->eleve->nom ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type', $sanction->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $sanction->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="duree" class="form-label">Durée (jours)</label>
                                    <input type="number" min="0" class="form-control" id="duree" name="duree" value="{{ old('duree', $sanction->duree) }}" placeholder="Ex: 3">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_debut" class="form-label">Date début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ old('date_debut', $sanction->date_debut ? $sanction->date_debut->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_fin" class="form-label">Date fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ old('date_fin', $sanction->date_fin ? $sanction->date_fin->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imposed_by" class="form-label">Imposée par</label>
                                <select class="form-select" id="imposed_by" name="imposed_by">
                                    <option value="">-- Utilisateur connecté --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('imposed_by', $sanction->imposed_by) == $user->id ? 'selected' : '' }}>
                                            {{ $user->prenom }} {{ $user->nom }} ({{ $user->login }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Décrivez la sanction...">{{ old('description', $sanction->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>&nbsp; Enregistrer les modifications
                                        </button>
                                        <a href="{{ route('gestion_sanctions.index') }}" class="btn btn-dark">
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
                                <p class="h6">{{ $sanction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Modifié le</p>
                                <p class="h6">{{ $sanction->updated_at->format('d/m/Y H:i') }}</p>
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
                                    Supprimer cette sanction
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="help-circle" class="me-2"></i>Aide
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <p class="mb-2">Modifiez les informations puis sauvegardez vos changements.</p>
                        <p class="mb-0">La suppression est définitive, utilisez-la avec précaution.</p>
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
                    <p>Êtes-vous sûr de vouloir supprimer cette sanction ?</p>
                    <p class="text-muted">
                        <strong>{{ $sanction->eleve->prenom ?? '' }} {{ $sanction->eleve->nom ?? '' }}</strong>
                    </p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_sanctions.destroy', $sanction->id) }}" method="POST" class="d-inline">
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

            document.getElementById('sanctionForm').addEventListener('submit', function(e) {
                const eleveId = document.getElementById('eleve_id').value;
                const incidentId = document.getElementById('incident_disciplinaire_id').value;
                const type = document.getElementById('type').value;
                const status = document.getElementById('status').value;
                const description = document.getElementById('description').value.trim();

                if (!eleveId || !incidentId || !type || !status || !description) {
                    e.preventDefault();
                    alert('Veuillez renseigner les champs obligatoires');
                    return false;
                }
            });
        });
    </script>
@endsection
