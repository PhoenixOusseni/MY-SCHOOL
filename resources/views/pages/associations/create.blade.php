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
                            <div class="page-header-icon"><i data-feather="link-2"></i></div>
                            Ajouter une Association
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_associations.index') }}" class="btn btn-light btn-sm">
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
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0">
                            <i data-feather="link-2" class="me-2"></i>Informations de l'Association
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form id="associationForm" action="{{ route('gestion_associations.store') }}" method="POST">
                            @csrf

                            <!-- Élèves (Multiple) -->
                            <div class="mb-4">
                                <label for="eleve_ids" class="form-label">Élèves <span class="text-danger">*</span></label>
                                <select class="form-select" id="eleve_ids" name="eleve_ids[]" multiple required
                                    size="8">
                                    @foreach ($eleves as $eleve)
                                        <option value="{{ $eleve->id }}"
                                            data-classe="{{ $eleve->inscriptions()->first()?->classe?->libelle ?? 'N/A' }}"
                                            data-matricule="{{ $eleve->registration_number }}"
                                            {{ in_array($eleve->id, (array) old('eleve_ids', [])) ? 'selected' : '' }}>
                                            {{ $eleve->prenom . ' ' . strtoupper($eleve->nom) }} -
                                            {{ $eleve->inscriptions()->first()?->classe?->libelle ?? 'Sans classe' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i data-feather="info" style="width: 14px; height: 14px; display: inline;"></i>
                                    Utilisez Ctrl+Clic (ou Cmd+Clic) pour sélectionner plusieurs élèves
                                </small>
                            </div>

                            <!-- Tuteur -->
                            <div class="mb-4">
                                <label for="tuteur_id" class="form-label">Tuteur <span class="text-danger">*</span></label>
                                @if ($tuteur)
                                    <input type="hidden" name="tuteur_id" value="{{ $tuteur->id }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            value="{{ $tuteur->prenom . ' ' . strtoupper($tuteur->nom) }}" disabled>
                                        <span class="input-group-text">
                                            <span
                                                class="badge bg-primary">{{ $relationshipLabel[$tuteur->relationship] ?? 'N/A' }}</span>
                                        </span>
                                    </div>
                                @else
                                    <select class="form-select" id="tuteur_id" name="tuteur_id" required
                                        onchange="updateTuteurInfo()">
                                        <option value="" selected disabled>-- Sélectionner un tuteur --</option>
                                        @foreach ($tuteurs as $t)
                                            <option value="{{ $t->id }}" data-relationship="{{ $t->relationship }}"
                                                data-telephone="{{ $t->telephone }}" data-email="{{ $t->email }}"
                                                {{ old('tuteur_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->prenom . ' ' . strtoupper($t->nom) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-2">
                                        Lien: <span id="tuteurRelationship">—</span> | Téléphone: <span
                                            id="tuteurTelephone">—</span>
                                    </small>
                                @endif
                            </div>

                            <!-- Options -->
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i data-feather="settings" class="me-2"></i>Paramètres
                                    </h6>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_primary" name="is_primary"
                                            {{ old('is_primary') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_primary">
                                            <i data-feather="star" style="width: 16px; height: 16px; display: inline;"></i>
                                            <strong>Tuteur Principal</strong>
                                            <small class="d-block text-muted">Ce tuteur est le contact principal pour la
                                                scolarité</small>
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="can_pickup" name="can_pickup"
                                            {{ old('can_pickup') ? 'checked' : '' }}>
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
                                            name="emergency_contact" {{ old('emergency_contact') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emergency_contact">
                                            <i data-feather="alert-circle"
                                                style="width: 16px; height: 16px; display: inline;"></i>
                                            <strong>Contact d'urgence</strong>
                                            <small class="d-block text-muted">Contacter ce tuteur en cas d'urgence</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="check" class="me-2"></i>&nbsp; Créer l'Association
                                        </button>
                                        <a href="{{ route('gestion_associations.index') }}" class="btn btn-dark">
                                            <i data-feather="x" class="me-2"></i>&nbsp; Annuler
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if ($tuteurId)
                                <input type="hidden" name="tuteur_id" value="{{ $tuteurId }}">
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="alert alert-info" role="alert">
                    <div class="d-flex">
                        <i data-feather="info" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">À propos des associations</h6>
                            <ul class="mb-0">
                                <li>Une association lie un élève à un tuteur</li>
                                <li>Vous ne pouvez pas créer deux associations identiques</li>
                                <li>Un élève peut avoir plusieurs tuteurs avec des rôles différents</li>
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
                const eleveSelect = document.getElementById('eleve_ids');
                const selectedEleves = Array.from(eleveSelect.selectedOptions).map(o => o.value);
                const tuteurId = document.getElementById('tuteur_id')?.value || '{{ $tuteurId ?? '' }}';

                if (selectedEleves.length === 0) {
                    e.preventDefault();
                    alert('Veuillez sélectionner au moins un élève');
                    return false;
                }

                if (!tuteurId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un tuteur');
                    return false;
                }
            });

            // Mettre à jour les infos du tuteur au chargement
            if (document.getElementById('tuteur_id') && document.getElementById('tuteur_id').value) {
                updateTuteurInfo();
            }
        });
    </script>
@endsection
