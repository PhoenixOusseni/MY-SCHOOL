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
                            <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                            Ajouter une Matière
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_matieres.index') }}" class="btn btn-dark btn-sm">
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations de la Matière
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form id="matiereForm" action="{{ route('gestion_matieres.store') }}" method="POST">
                            @csrf

                            <!-- Intitulé et Code -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="intitule" class="form-label">Intitulé <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="intitule" name="intitule"
                                        value="{{ old('intitule') }}" required placeholder="Ex: Mathématiques">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Code</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        value="{{ old('code') }}" placeholder="Ex: MATH101">
                                </div>
                            </div>

                            <!-- Établissement et Couleur -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="etablissement_id" class="form-label">Établissement <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="etablissement_id" name="etablissement_id" required>
                                        <option value="" selected disabled>-- Sélectionner --</option>
                                        @foreach ($etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}"
                                                {{ old('etablissement_id') == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="color" class="form-label">Couleur</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="color"
                                            name="color" value="{{ old('color', '#007bff') }}" style="max-width: 60px;">
                                        <input type="text" class="form-control" id="color_text" name="color_text"
                                            value="{{ old('color', '#007bff') }}" placeholder="#007bff" readonly>
                                    </div>
                                    <small class="text-muted">Couleur pour l'affichage</small>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Décrivez cette matière...">{{ old('description') }}</textarea>
                            </div>

                            <!-- Statut -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active') ? 'checked' : '' }} checked>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Matière active</strong>
                                    </label>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>&nbsp; Enregistrer
                                        </button>
                                        <a href="{{ route('gestion_matieres.index') }}" class="btn btn-dark">
                                            <i data-feather="x" class="me-2"></i>&nbsp; Annuler
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Information -->
                <div class="alert alert-info" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-circle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Informations</h6>
                            <ul class="mb-0 ps-3">
                                <li>L'intitulé est obligatoire</li>
                                <li>Le code doit être unique pour chaque établissement</li>
                                <li>La couleur par défaut est le bleu (#007bff)</li>
                                <li>Une matière inactive ne s'affichera pas dans les listes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Sync color picker with text input
            const colorPicker = document.getElementById('color');
            const colorText = document.getElementById('color_text');

            colorPicker.addEventListener('change', function() {
                colorText.value = this.value;
            });

            // Validation du formulaire
            document.getElementById('matiereForm').addEventListener('submit', function(e) {
                const intitule = document.getElementById('intitule').value.trim();
                const etablissementId = document.getElementById('etablissement_id').value;

                if (!intitule) {
                    e.preventDefault();
                    alert('Veuillez saisir l\'intitulé de la matière');
                    return false;
                }

                if (!etablissementId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un établissement');
                    return false;
                }
            });
        });
    </script>
@endsection
