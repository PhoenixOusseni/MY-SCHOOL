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
                            <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                            Ajouter une Sanction
                        </h1>
                        <p class="text-muted">Enregistrez une nouvelle sanction disciplinaire pour un élève</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_sanctions.index') }}" class="btn btn-dark">
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
                        <form id="sanctionForm" action="{{ route('gestion_sanctions.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                    <select class="form-select" id="eleve_id" name="eleve_id" required>
                                        <option value="" selected disabled>-- Sélectionner --</option>
                                        @foreach ($eleves as $eleve)
                                            <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                                {{ $eleve->prenom }} {{ $eleve->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="incident_disciplinaire_id" class="form-label">Incident lié <span class="text-danger">*</span></label>
                                    <select class="form-select" id="incident_disciplinaire_id" name="incident_disciplinaire_id" required>
                                        <option value="" selected disabled>-- Sélectionner --</option>
                                        @foreach ($incidents as $incident)
                                            <option value="{{ $incident->id }}" {{ old('incident_disciplinaire_id') == $incident->id ? 'selected' : '' }}>
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
                                        <option value="" selected disabled>-- Sélectionner --</option>
                                        @foreach ($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', 'pending') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="duree" class="form-label">Durée (jours)</label>
                                    <input type="number" min="0" class="form-control" id="duree" name="duree" value="{{ old('duree') }}" placeholder="Ex: 3">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_debut" class="form-label">Date début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ old('date_debut') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_fin" class="form-label">Date fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ old('date_fin') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imposed_by" class="form-label">Imposée par</label>
                                <select class="form-select" id="imposed_by" name="imposed_by">
                                    <option value="">-- Utilisateur connecté --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('imposed_by') == $user->id ? 'selected' : '' }}>
                                            {{ $user->prenom }} {{ $user->nom }} ({{ $user->login }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Décrivez la sanction...">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>&nbsp; Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                        <p class="mb-2">Associez la sanction à un incident disciplinaire existant.</p>
                        <p class="mb-2">La durée est exprimée en nombre de jours.</p>
                        <p class="mb-0">Les dates de début et fin sont facultatives.</p>
                    </div>
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
