@extends('layouts.master')

@section('title', 'Ajouter un Tuteur')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book-open"></i></div>
                            Gestion des parents / Tuteurs
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            Gérez les tuteurs / parents
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_tuteurs.index') }}" class="btn btn-dark btn-sm me-2">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-xl px-4 mt-n10 mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations du Tuteur
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form id="tuteurForm" action="{{ route('gestion_tuteurs.store') }}" method="POST">
                            @csrf

                            <!-- Nom et Prénom -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nom" name="nom"
                                        value="{{ old('nom') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="prenom" name="prenom"
                                        value="{{ old('prenom') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="relationship" class="form-label">Lien de parenté <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="relationship" name="relationship" required>
                                        <option value="" disabled selected>-- Sélectionnez --</option>
                                        <option value="Père" {{ old('relationship') == 'Père' ? 'selected' : '' }}>Père
                                        </option>
                                        <option value="Mère" {{ old('relationship') == 'Mère' ? 'selected' : '' }}>Mère
                                        </option>
                                        <option value="Tuteur" {{ old('relationship') == 'Tuteur' ? 'selected' : '' }}>
                                            Tuteur</option>
                                        <option value="Autre" {{ old('relationship') == 'Autre' ? 'selected' : '' }}>Autre
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse"
                                        value="{{ old('adresse') }}">
                                </div>
                            </div>

                            <!-- Téléphone et Email -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone"
                                        value="{{ old('telephone') }}">
                                    <small class="text-muted">Format : +XXX XXXXXXXXX</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}">
                                </div>
                            </div>

                            <!-- Profession et Lieu de Travail -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profession" class="form-label">Profession</label>
                                    <input type="text" class="form-control" id="profession" name="profession"
                                        value="{{ old('profession') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lieu_travail" class="form-label">Lieu de Travail</label>
                                    <input type="text" class="form-control" id="lieu_travail" name="lieu_travail"
                                        value="{{ old('lieu_travail') }}">
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1">
                                            <i data-feather="save" class="me-2"></i>Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Notes importantes -->
                <div class="alert alert-info" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-circle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Informations importantes</h6>
                            <hr>
                            <ul class="mb-0 ps-3">
                                <li>Le nom et prénom sont obligatoires</li>
                                <li>Le lien de parenté permet de distinguer le type de tuteur</li>
                                <li>Le téléphone et l'email facilitent la communication</li>
                                <li>Les autres champs sont optionnels</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser les icônes Feather
            feather.replace();

            // Validation du formulaire
            document.getElementById('tuteurForm').addEventListener('submit', function(e) {
                const nom = document.getElementById('nom').value.trim();
                const prenom = document.getElementById('prenom').value.trim();
                const relationship = document.getElementById('relationship').value;

                if (!nom) {
                    e.preventDefault();
                    alert('Veuillez saisir le nom du tuteur');
                    return false;
                }

                if (!prenom) {
                    e.preventDefault();
                    alert('Veuillez saisir le prénom du tuteur');
                    return false;
                }

                if (!relationship) {
                    e.preventDefault();
                    alert('Veuillez sélectionner le lien de parenté');
                    return false;
                }
            });
        });
    </script>
@endsection
