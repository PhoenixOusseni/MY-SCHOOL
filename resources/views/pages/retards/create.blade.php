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
                            Ajouter un Retard
                        </h1>
                        <p class="text-muted">Enregistrez un nouveau retard pour un élève</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_retards.index') }}" class="btn btn-light btn-sm">
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
                            <i data-feather="info" class="me-2"></i>Informations du Retard
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form id="retardForm" action="{{ route('gestion_retards.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                                    <input type="time" class="form-control" id="heure_arrivee" name="heure_arrivee" value="{{ old('heure_arrivee') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="duree" class="form-label">Durée du retard (min)</label>
                                    <input type="number" min="0" class="form-control" id="duree" name="duree" value="{{ old('duree') }}" placeholder="Ex: 15">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="reported_at" class="form-label">Date/Heure de signalement</label>
                                    <input type="datetime-local" class="form-control" id="reported_at" name="reported_at"
                                        value="{{ old('reported_at') ? \Carbon\Carbon::parse(old('reported_at'))->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>

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
                                    <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                    <select class="form-select" id="classe_id" name="classe_id" required>
                                        <option value="" selected disabled>-- Sélectionner --</option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
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
                                            <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                                {{ $matiere->intitule }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reported_by" class="form-label">Signalé par</label>
                                    <select class="form-select" id="reported_by" name="reported_by">
                                        <option value="">-- Utilisateur connecté --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('reported_by') == $user->id ? 'selected' : '' }}>
                                                {{ $user->prenom }} {{ $user->nom }} ({{ $user->login }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="raison" class="form-label">Raison</label>
                                <textarea class="form-control" id="raison" name="raison" rows="4" placeholder="Motif du retard...">{{ old('raison') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_justified" name="is_justified" value="1" {{ old('is_justified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_justified">
                                        <strong>Retard justifié</strong>
                                    </label>
                                </div>
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

                <div class="alert alert-info" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-circle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Informations</h6>
                            <ul class="mb-0 ps-3">
                                <li>La date, l'élève et la classe sont obligatoires</li>
                                <li>La durée est exprimée en minutes</li>
                                <li>Vous pouvez associer une matière si nécessaire</li>
                            </ul>
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
                        <p class="mb-2">Saisissez les informations du retard pour l'élève concerné.</p>
                        <p class="mb-2">Si vous connaissez l'heure d'arrivée et la durée, renseignez-les pour faciliter les rapports.</p>
                        <p class="mb-0">Le statut justifié peut être coché si une justification est fournie.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            document.getElementById('retardForm').addEventListener('submit', function(e) {
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
