@extends('layouts.master')

@section('title', 'Modifier un Tuteur')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="h3 text-white">
                            <i data-feather="user-check" class="me-2"></i>Modifier:
                            {{ $tuteur->prenom . ' ' . strtoupper($tuteur->nom) }}
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            Mettez à jour les informations du tuteur et gérez les détails de contact.
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_tuteurs.show', $tuteur->id) }}" class="btn btn-dark btn-sm me-2">
                            <i data-feather="arrow-left" class="me-2"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-md-8">
                <!-- Informations Générales -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations du Tuteur
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form id="tuteurForm" action="{{ route('gestion_tuteurs.update', $tuteur->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nom et Prénom -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nom" name="nom"
                                        value="{{ old('nom', $tuteur->nom) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="prenom" name="prenom"
                                        value="{{ old('prenom', $tuteur->prenom) }}" required>
                                </div>
                            </div>

                            <!-- Relationship -->
                            <div class="mb-3">
                                <label for="relationship" class="form-label">Lien de Parenté <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="relationship" name="relationship" required>
                                    <option value="" disabled>-- Sélectionner --</option>
                                    <option value="pere"
                                        {{ old('relationship', $tuteur->relationship) == 'pere' ? 'selected' : '' }}>Père
                                    </option>
                                    <option value="mere"
                                        {{ old('relationship', $tuteur->relationship) == 'mere' ? 'selected' : '' }}>Mère
                                    </option>
                                    <option value="tuteur"
                                        {{ old('relationship', $tuteur->relationship) == 'tuteur' ? 'selected' : '' }}>
                                        Tuteur</option>
                                    <option value="autre"
                                        {{ old('relationship', $tuteur->relationship) == 'autre' ? 'selected' : '' }}>Autre
                                    </option>
                                </select>
                            </div>

                            <!-- Téléphone et Email -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone"
                                        value="{{ old('telephone', $tuteur->telephone) }}">
                                    <small class="text-muted">Format : +XXX XXXXXXXXX</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $tuteur->email) }}">
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-control" id="adresse" name="adresse" rows="2">{{ old('adresse', $tuteur->adresse) }}</textarea>
                            </div>

                            <!-- Profession et Lieu de Travail -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profession" class="form-label">Profession</label>
                                    <input type="text" class="form-control" id="profession" name="profession"
                                        value="{{ old('profession', $tuteur->profession) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lieu_travail" class="form-label">Lieu de Travail</label>
                                    <input type="text" class="form-control" id="lieu_travail" name="lieu_travail"
                                        value="{{ old('lieu_travail', $tuteur->lieu_travail) }}">
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-1 btn-sm">
                                            <i data-feather="save" class="me-2"></i>Enregistrer les Modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Dates de Création/Modification -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i data-feather="calendar" class="me-2"></i>Historique
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Créé le</p>
                                <p class="fw-bold">{{ $tuteur->created_at->format('d/m/Y à H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Dernière modification</p>
                                <p class="fw-bold">{{ $tuteur->updated_at->format('d/m/Y à H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Note Importante -->
                <div class="alert alert-warning mt-4" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-circle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Note Importante</h6>
                            <p class="mb-0">Les modifications apportées à ce tuteur affecteront tous les élèves qui lui
                                sont rattachés. Assurez-vous que les informations sont correctes avant de les enregistrer.
                            </p>
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
