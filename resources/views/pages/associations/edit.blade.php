@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit-2"></i></div>
                            Modifier l'Association
                        </h1>
                        <p class="page-header-subtitle">
                            Modifiez les détails de l'association entre l'élève et son tuteur, y compris les rôles et les
                            permissions.
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_associations.show', $eleveParent->id) }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Messages -->
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-3">
                        <h6 class="mb-0">
                            <i data-feather="edit-2" class="me-2"></i>Modification de l'Association
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form id="associationForm" action="{{ route('gestion_associations.update', $eleveParent->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Élève(s) Actuellement Associé(s) -->
                            <div class="mb-4">
                                <label class="form-label">Élève(s) Associé(s) au Tuteur</label>
                                @if ($elevesAssocies->count() > 0)
                                    <div class="card border-light bg-light">
                                        <div class="list-group list-group-flush">
                                            @foreach ($elevesAssocies as $eleve)
                                                <a href="{{ route('gestion_eleves.show', $eleve->id) }}"
                                                    class="list-group-item list-group-item-action">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-primary fw-bold">
                                                                {{ $eleve->prenom . ' ' . strtoupper($eleve->nom) }}
                                                                @if ($eleve->id == $eleveParent->eleve_id)
                                                                    <span class="badge bg-success">Actuellement édité</span>
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted d-block">
                                                                Matricule: {{ $eleve->registration_number }}
                                                            </small>
                                                            <small class="text-muted d-block">
                                                                Naissance:
                                                                {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end ms-2">
                                                            <span class="badge bg-white text-dark d-block mb-2">
                                                                {{ $eleve->inscriptions()->first()?->classe?->libelle ?? 'Sans classe' }}
                                                            </span>
                                                            <span
                                                                class="badge {{ $eleve->genre == 'M' ? 'bg-info' : 'bg-success' }} d-block">
                                                                {{ $eleve->genre == 'M' ? 'M' : 'F' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info" role="alert">
                                        <i data-feather="inbox" style="width: 16px; height: 16px; display: inline;"
                                            class="me-2"></i>
                                        Aucun élève associé
                                    </div>
                                @endif
                            </div>

                            <!-- Élève à Modifier -->
                            <div class="mb-4">
                                <label for="eleve_id" class="form-label">Élève à Modifier <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="eleve_id" name="eleve_id" required
                                    onchange="updateEleveInfo()">
                                    <option value="" disabled>-- Sélectionner un élève --</option>
                                    @foreach ($eleves as $eleve)
                                        <option value="{{ $eleve->id }}"
                                            data-classe="{{ $eleve->inscriptions()->first()?->classe?->libelle ?? 'N/A' }}"
                                            data-matricule="{{ $eleve->registration_number }}"
                                            {{ old('eleve_id', $eleveParent->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                            {{ $eleve->prenom . ' ' . strtoupper($eleve->nom) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    Classe: <span id="eleveClasse">—</span> | Matricule: <span id="eleveMatricule">—</span>
                                </small>
                            </div>

                            <!-- Tuteur -->
                            <div class="mb-4">
                                <label for="tuteur_id" class="form-label">Tuteur <span class="text-danger">*</span></label>
                                <select class="form-select" id="tuteur_id" name="tuteur_id" required
                                    onchange="updateTuteurInfo()">
                                    <option value="" disabled>-- Sélectionner un tuteur --</option>
                                    @foreach ($tuteurs as $tuteur)
                                        <option value="{{ $tuteur->id }}"
                                            data-relationship="{{ $tuteur->relationship }}"
                                            data-telephone="{{ $tuteur->telephone }}" data-email="{{ $tuteur->email }}"
                                            {{ old('tuteur_id', $eleveParent->tuteur_id) == $tuteur->id ? 'selected' : '' }}>
                                            {{ $tuteur->prenom . ' ' . strtoupper($tuteur->nom) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    Lien: <span id="tuteurRelationship">—</span> | Téléphone: <span
                                        id="tuteurTelephone">—</span>
                                </small>
                            </div>

                            <!-- Options -->
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i data-feather="settings" class="me-2"></i>Paramètres
                                    </h6>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_primary" name="is_primary"
                                            {{ old('is_primary', $eleveParent->is_primary) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_primary">
                                            <i data-feather="star" style="width: 16px; height: 16px; display: inline;"></i>
                                            <strong>Tuteur Principal</strong>
                                            <small class="d-block text-muted">Ce tuteur est le contact principal pour la
                                                scolarité</small>
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="can_pickup" name="can_pickup"
                                            {{ old('can_pickup', $eleveParent->can_pickup) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_pickup">
                                            <i data-feather="check-circle"
                                                style="width: 16px; height: 16px; display: inline;"></i>
                                            <strong>Peut venir chercher l'élève</strong>
                                            <small class="d-block text-muted">Autoriser ce tuteur à venir récupérer
                                                l'élève</small>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="emergency_contact"
                                            name="emergency_contact"
                                            {{ old('emergency_contact', $eleveParent->emergency_contact) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emergency_contact">
                                            <i data-feather="alert-circle"
                                                style="width: 16px; height: 16px; display: inline;"></i>
                                            <strong>Contact d'urgence</strong>
                                            <small class="d-block text-muted">Contacter ce tuteur en cas d'urgence</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <div class="row text-sm">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Créée le</small>
                                            <small
                                                class="fw-bold">{{ $eleveParent->created_at->format('d/m/Y à H:i:s') }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Modifiée le</small>
                                            <small
                                                class="fw-bold">{{ $eleveParent->updated_at->format('d/m/Y à H:i:s') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>Enregistrer les Modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-circle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">À propos des modifications</h6>
                            <hr>
                            <ul class="mb-0">
                                <li>Vous pouvez modifier les paramètres de l'association</li>
                                <li>Vous ne pouvez pas créer un doublon (même élève + même tuteur)</li>
                                <li>Les modifications sont enregistrées automatiquement</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @php
            $relationshipLabel = [
                'pere' => 'Père',
                'mere' => 'Mère',
                'tuteur' => 'Tuteur',
                'autre' => 'Autre',
            ];
        @endphp

        function updateEleveInfo() {
            const select = document.getElementById('eleve_id');
            const option = select.options[select.selectedIndex];
            document.getElementById('eleveClasse').textContent = option.dataset.classe || '—';
            document.getElementById('eleveMatricule').textContent = option.dataset.matricule || '—';
        }

        function updateTuteurInfo() {
            const select = document.getElementById('tuteur_id');
            const option = select.options[select.selectedIndex];

            const relationshipLabel = @json($relationshipLabel);
            const relationship = option.dataset.relationship;

            document.getElementById('tuteurRelationship').textContent = relationshipLabel[relationship] || '—';
            document.getElementById('tuteurTelephone').textContent = option.dataset.telephone || '—';
        }

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Validation du formulaire
            document.getElementById('associationForm').addEventListener('submit', function(e) {
                const eleveId = document.getElementById('eleve_id').value;
                const tuteurId = document.getElementById('tuteur_id').value;

                if (!eleveId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un élève');
                    return false;
                }

                if (!tuteurId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un tuteur');
                    return false;
                }
            });

            // Mettre à jour les infos au chargement
            updateEleveInfo();
            updateTuteurInfo();
        });
    </script>
@endsection
