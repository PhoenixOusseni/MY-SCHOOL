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
                            Modifier l'Incident Disciplinaire
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_incidents.index') }}" class="btn btn-light btn-sm">
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
                            <i data-feather="info" class="me-2"></i>Informations de l'Incident
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form id="incidentForm" action="{{ route('gestion_incidents.update', $incident->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_incident" class="form-label">Date de l'incident <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_incident" name="date_incident"
                                        value="{{ old('date_incident', $incident->date_incident ? $incident->date_incident->format('Y-m-d') : '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="heure_incident" class="form-label">Heure de l'incident</label>
                                    <input type="time" class="form-control" id="heure_incident" name="heure_incident"
                                        value="{{ old('heure_incident', $incident->heure_incident) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                    <select class="form-select" id="eleve_id" name="eleve_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($eleves as $eleve)
                                            <option value="{{ $eleve->id }}" {{ old('eleve_id', $incident->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                                {{ $eleve->prenom }} {{ $eleve->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reported_by" class="form-label">Signalé par</label>
                                    <select class="form-select" id="reported_by" name="reported_by">
                                        <option value="">-- Sélectionner un enseignant --</option>
                                        @foreach ($enseignants as $enseignant)
                                            <option value="{{ $enseignant->id }}" {{ old('reported_by', $incident->reported_by) == $enseignant->id ? 'selected' : '' }}>
                                                {{ $enseignant->prenom }} {{ $enseignant->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @php $currentGravite = $incident->{'gravité'}; @endphp
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type', $incident->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gravite" class="form-label">Gravité <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gravite" name="gravite" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($gravites as $value => $label)
                                            <option value="{{ $value }}" {{ old('gravite', $currentGravite) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" id="statut" name="statut" required>
                                        @foreach ($statuts as $value => $label)
                                            <option value="{{ $value }}" {{ old('statut', $incident->statut) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Décrivez l'incident...">{{ old('description', $incident->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emplacement" class="form-label">Emplacement</label>
                                    <input type="text" class="form-control" id="emplacement" name="emplacement"
                                        value="{{ old('emplacement', $incident->emplacement) }}" placeholder="Ex: Salle 2, Cour">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_notification" class="form-label">Date de notification parent</label>
                                    <input type="date" class="form-control" id="date_notification" name="date_notification"
                                        value="{{ old('date_notification', $incident->date_notification ? $incident->date_notification->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="temoins" class="form-label">Témoins</label>
                                <textarea class="form-control" id="temoins" name="temoins" rows="2" placeholder="Noms ou descriptions des témoins...">{{ old('temoins', $incident->temoins) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="action_pris" class="form-label">Actions prises</label>
                                <textarea class="form-control" id="action_pris" name="action_pris" rows="3" placeholder="Actions engagées suite à l'incident...">{{ old('action_pris', $incident->action_pris) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="parent_notifie" name="parent_notifie" value="1"
                                        {{ old('parent_notifie', $incident->parent_notifie) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="parent_notifie">
                                        <strong>Parent notifié</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>&nbsp; Enregistrer les modifications
                                        </button>
                                        <a href="{{ route('gestion_incidents.index') }}" class="btn btn-dark">
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
                                <p class="h6">{{ $incident->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Modifié le</p>
                                <p class="h6">{{ $incident->updated_at->format('d/m/Y H:i') }}</p>
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
                                    Supprimer cet incident
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
                        <p class="mb-2">Mettez à jour les informations de l'incident puis enregistrez.</p>
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
                    <p>Êtes-vous sûr de vouloir supprimer cet incident ?</p>
                    <p class="text-muted">
                        <strong>{{ $incident->eleve->prenom ?? '' }} {{ $incident->eleve->nom ?? '' }}</strong>
                        - {{ $incident->date_incident ? $incident->date_incident->format('d/m/Y') : '-' }}
                    </p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_incidents.destroy', $incident->id) }}" method="POST" class="d-inline">
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

            document.getElementById('incidentForm').addEventListener('submit', function(e) {
                const dateIncident = document.getElementById('date_incident').value;
                const eleveId = document.getElementById('eleve_id').value;
                const type = document.getElementById('type').value;
                const gravite = document.getElementById('gravite').value;
                const description = document.getElementById('description').value.trim();

                if (!dateIncident || !eleveId || !type || !gravite || !description) {
                    e.preventDefault();
                    alert('Veuillez renseigner les champs obligatoires');
                    return false;
                }
            });
        });
    </script>
@endsection
