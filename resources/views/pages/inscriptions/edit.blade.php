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
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Modifier une Inscription
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            Inscription de {{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_inscriptions.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10 mb-5">
        <!-- Messages d'alerte -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i data-feather="alert-circle" class="me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading mb-2">Erreurs de validation</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i data-feather="check-circle" class="me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading mb-0">Succès</h6>
                        {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Formulaire de modification -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0">
                            <i data-feather="file-edit" class="me-2"></i> Formulaire de Modification
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="inscriptionForm" action="{{ route('gestion_inscriptions.update', $inscription->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Sélection de l'élève -->
                            <div class="mb-4">
                                <label for="eleve_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                <select class="form-select @error('eleve_id') is-invalid @enderror" id="eleve_id"
                                    name="eleve_id" required onchange="updateEleveInfo()">
                                    <option value="">-- Sélectionner un élève --</option>
                                    @foreach ($eleves as $eleve)
                                        <option value="{{ $eleve->id }}"
                                            {{ old('eleve_id', $inscription->eleve_id) == $eleve->id ? 'selected' : '' }}
                                            data-registration="{{ $eleve->registration_number }}"
                                            data-email="{{ $eleve->user->email ?? '' }}" data-genre="{{ $eleve->genre }}"
                                            data-age="{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->age : 'N/A' }}">
                                            {{ $eleve->prenom }} {{ $eleve->nom }}
                                            <small class="text-muted">({{ $eleve->registration_number }})</small>
                                        </option>
                                    @endforeach
                                </select>
                                @error('eleve_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informations condensées de l'élève -->
                            <div id="eleveInfo" class="mb-4">
                                <div class="alert alert-light border" role="alert">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Immatriculation</small>
                                            <strong
                                                id="infoRegistration">{{ $inscription->eleve->registration_number }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Genre</small>
                                            <strong id="infoGenre">
                                                {{ $inscription->eleve->genre === 'M' ? 'Masculin' : ($inscription->eleve->genre === 'F' ? 'Féminin' : 'Autres') }}
                                            </strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Âge</small>
                                            <strong id="infoAge">
                                                {{ $inscription->eleve->date_naissance ? \Carbon\Carbon::parse($inscription->eleve->date_naissance)->age : 'N/A' }}
                                            </strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Email</small>
                                            <strong id="infoEmail">{{ $inscription->eleve->user->email ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sélection de la classe -->
                            <div class="mb-4">
                                <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id"
                                    name="classe_id" required onchange="updateClasseInfo()">
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach ($classes as $classe)
                                        <option value="{{ $classe->id }}"
                                            {{ old('classe_id', $inscription->classe_id) == $classe->id ? 'selected' : '' }}
                                            data-niveau="{{ $classe->niveau->nom ?? 'N/A' }}"
                                            data-capacite="{{ $classe->capacite ?? 'N/A' }}">
                                            {{ $classe->nom }}
                                            <small class="text-muted">({{ $classe->niveau->nom ?? 'N/A' }})</small>
                                        </option>
                                    @endforeach
                                </select>
                                @error('classe_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informations condensées de la classe -->
                            <div id="classeInfo" class="mb-4">
                                <div class="alert alert-light border" role="alert">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Niveau</small>
                                            <strong
                                                id="infoNiveau">{{ $inscription->classe->niveau->nom ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Capacité</small>
                                            <strong
                                                id="infoCapacite">{{ $inscription->classe->capacite ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sélection de l'année scolaire -->
                            <div class="mb-4">
                                <label for="annee_scolaire_id" class="form-label">Année Scolaire <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('annee_scolaire_id') is-invalid @enderror"
                                    id="annee_scolaire_id" name="annee_scolaire_id" required>
                                    <option value="">-- Sélectionner une année scolaire --</option>
                                    @foreach ($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}"
                                            {{ old('annee_scolaire_id', $inscription->annee_scolaire_id) == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_scolaire_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date d'inscription -->
                            <div class="mb-4">
                                <label for="date_inscription" class="form-label">Date d'Inscription <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_inscription') is-invalid @enderror"
                                    id="date_inscription" name="date_inscription"
                                    value="{{ old('date_inscription', $inscription->date_inscription) }}" required>
                                @error('date_inscription')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="mb-4">
                                <label for="statut" class="form-label">Statut <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('statut') is-invalid @enderror" id="statut"
                                    name="statut" required>
                                    <option value="">-- Sélectionner un statut --</option>
                                    <option value="inscrit"
                                        {{ old('statut', $inscription->statut) == 'inscrit' ? 'selected' : '' }}>
                                        Inscrit
                                    </option>
                                    <option value="transfere"
                                        {{ old('statut', $inscription->statut) == 'transfere' ? 'selected' : '' }}>
                                        Transféré
                                    </option>
                                    <option value="termine"
                                        {{ old('statut', $inscription->statut) == 'termine' ? 'selected' : '' }}>
                                        Terminé
                                    </option>
                                    <option value="abandonne"
                                        {{ old('statut', $inscription->statut) == 'abandonne' ? 'selected' : '' }}>
                                        Abandonné
                                    </option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informations de suivi -->
                            <div class="mb-4">
                                <div class="alert alert-info" role="alert">
                                    <h6 class="alert-heading mb-2">
                                        <i data-feather="info" class="me-2"></i> Informations de suivi
                                    </h6>
                                    <div class="row small">
                                        <div class="col-md-6">
                                            <p class="mb-1">
                                                <strong>Créée le:</strong><br>
                                                {{ $inscription->created_at->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-0">
                                                <strong>Modifiée le:</strong><br>
                                                {{ $inscription->updated_at->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex gap-2">
                                <button type="submit" form="inscriptionForm" class="btn btn-1">
                                    <i class="fas fa-save me-2"></i> Enregistrer les modifications
                                </button>
                                <a href="{{ route('gestion_inscriptions.show', $inscription->id) }}"
                                    class="btn btn-dark">
                                    <i class="fas fa-times me-2"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mx-auto">
                <!-- Card informations utiles -->
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i data-feather="help-circle" class="me-2"></i> Informations importantes
                        </h6>
                        <ul class="small mb-0">
                            <li>Les modifications seront enregistrées immédiatement</li>
                            <li>Vérifiez que toutes les données saisies sont correctes</li>
                            <li>Un élève ne peut être inscrit qu'une seule fois par classe par année scolaire</li>
                            <li>Date d'inscription récente signifie un changement d'inscription</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Gestion de la soumission du formulaire
            document.getElementById('inscriptionForm').addEventListener('submit', function(e) {
                // Valider les champs requis
                const eleveId = document.getElementById('eleve_id').value;
                const classeId = document.getElementById('classe_id').value;
                const anneeId = document.getElementById('annee_scolaire_id').value;
                const dateInscription = document.getElementById('date_inscription').value;
                const statut = document.getElementById('statut').value;

                if (!eleveId || !classeId || !anneeId || !dateInscription || !statut) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs requis');
                    return false;
                }
            });
        });

        // Mettre à jour les informations de l'élève
        function updateEleveInfo() {
            const select = document.getElementById('eleve_id');
            const option = select.options[select.selectedIndex];

            if (option.value) {
                document.getElementById('infoRegistration').textContent = option.dataset.registration;
                document.getElementById('infoGenre').textContent =
                    option.dataset.genre === 'M' ? 'Masculin' :
                    (option.dataset.genre === 'F' ? 'Féminin' : 'Autres');
                document.getElementById('infoAge').textContent = option.dataset.age;
                document.getElementById('infoEmail').textContent = option.dataset.email || 'N/A';
            }
        }

        // Mettre à jour les informations de la classe
        function updateClasseInfo() {
            const select = document.getElementById('classe_id');
            const option = select.options[select.selectedIndex];

            if (option.value) {
                document.getElementById('infoNiveau').textContent = option.dataset.niveau;
                document.getElementById('infoCapacite').textContent = option.dataset.capacite;
            }
        }
    </script>
@endsection
