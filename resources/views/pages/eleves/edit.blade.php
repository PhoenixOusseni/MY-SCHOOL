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
                            Modifier un élève
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            {{ $eleve->prenom }} {{ $eleve->nom }} ({{ $eleve->registration_number }})
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_eleves.show', $eleve->id) }}" class="btn btn-light btn-sm">
                            <i data-feather="eye"></i>&nbsp; Voir détails
                        </a>
                        <a href="{{ route('gestion_eleves.index') }}" class="btn btn-dark btn-sm">
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
            <div class="col-lg-12">
                <form action="{{ route('gestion_eleves.update', $eleve->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section: Informations Personnelles -->
                    <div class="card border-0 shadow mb-4">
                        <div class="m-3">
                            <h6 class="mb-0">
                                <i data-feather="user" class="me-2"></i> Informations Personnelles
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                        id="nom" name="nom" placeholder="Ex: Dupont"
                                        value="{{ old('nom', $eleve->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                        id="prenom" name="prenom" placeholder="Ex: Jean"
                                        value="{{ old('prenom', $eleve->prenom) }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="genre" class="form-label">Genre</label>
                                    <select class="form-select @error('genre') is-invalid @enderror" id="genre"
                                        name="genre">
                                        <option value="">Sélectionner...</option>
                                        <option value="M" {{ old('genre', $eleve->genre) == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('genre', $eleve->genre) == 'F' ? 'selected' : '' }}>Féminin</option>
                                        <option value="Autres" {{ old('genre', $eleve->genre) == 'Autres' ? 'selected' : '' }}>Autres</option>
                                    </select>
                                    @error('genre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_naissance" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror"
                                        id="date_naissance" name="date_naissance"
                                        value="{{ old('date_naissance', $eleve->date_naissance ? $eleve->date_naissance : '') }}">
                                    @error('date_naissance')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror"
                                        id="lieu_naissance" name="lieu_naissance" placeholder="Ex: Paris"
                                        value="{{ old('lieu_naissance', $eleve->lieu_naissance) }}">
                                    @error('lieu_naissance')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="nationalite" class="form-label">Nationalité</label>
                                    <input type="text" class="form-control @error('nationalite') is-invalid @enderror"
                                        id="nationalite" name="nationalite" placeholder="Ex: Française"
                                        value="{{ old('nationalite', $eleve->nationalite) }}">
                                    @error('nationalite')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                        id="adresse" name="adresse" placeholder="Ex: 123 rue de la République"
                                        value="{{ old('adresse', $eleve->adresse) }}">
                                    @error('adresse')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Informations de Contact -->
                    <div class="card border-0 shadow mb-4">
                        <div class="m-3">
                            <h6 class="mb-0">
                                <i data-feather="phone" class="me-2"></i> Informations de Contact
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                        id="telephone" name="telephone" placeholder="Ex: +33 1 23 45 67 89"
                                        value="{{ old('telephone', $eleve->telephone) }}">
                                    @error('telephone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email"
                                        value="{{ $eleve->user->email ?? 'N/A' }}" disabled>
                                    <small class="text-muted d-block mt-1">
                                        <i data-feather="info" style="width: 14px; height: 14px;"></i> Géré via le compte utilisateur
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Informations de Santé -->
                    <div class="card border-0 shadow mb-4">
                        <div class="m-3">
                            <h6 class="mb-0">
                                <i data-feather="heart" class="me-2"></i> Informations de Santé
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                                    <select class="form-select @error('groupe_sanguin') is-invalid @enderror"
                                        id="groupe_sanguin" name="groupe_sanguin">
                                        <option value="">Sélectionner...</option>
                                        <option value="O+" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'O-' ? 'selected' : '' }}>O-</option>
                                        <option value="A+" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('groupe_sanguin', $eleve->groupe_sanguin) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    </select>
                                    @error('groupe_sanguin')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-0">
                                <label for="notes_medicales" class="form-label">Notes médicales</label>
                                <textarea class="form-control @error('notes_medicales') is-invalid @enderror"
                                    id="notes_medicales" name="notes_medicales" rows="3"
                                    placeholder="Ex: Allergies, problèmes de santé, médicaments spécialisés...">{{ old('notes_medicales', $eleve->notes_medicales) }}</textarea>
                                @error('notes_medicales')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Informations Scolaires -->
                    <div class="card border-0 shadow mb-4">
                        <div class="m-3">
                            <h6 class="mb-0">
                                <i data-feather="bookmark" class="me-2"></i> Informations Scolaires
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="etablissement_id" class="form-label">Établissement</label>
                                    <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                        id="etablissement_id" name="etablissement_id">
                                        <option value="">-- Sélectionner un établissement --</option>
                                        @foreach ($etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}"
                                                {{ old('etablissement_id', $eleve->etablissement_id) == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('etablissement_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select @error('statut') is-invalid @enderror" id="statut"
                                        name="statut">
                                        <option value="">-- Sélectionner un statut --</option>
                                        <option value="actif" {{ old('statut', $eleve->statut) == 'actif' ? 'selected' : '' }}>
                                            Actif
                                        </option>
                                        <option value="suspendu" {{ old('statut', $eleve->statut) == 'suspendu' ? 'selected' : '' }}>
                                            Suspendu
                                        </option>
                                        <option value="diplome" {{ old('statut', $eleve->statut) == 'diplome' ? 'selected' : '' }}>
                                            Diplômé
                                        </option>
                                        <option value="abandonne" {{ old('statut', $eleve->statut) == 'abandonne' ? 'selected' : '' }}>
                                            Abandonné
                                        </option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <label for="date_inscription" class="form-label">Date d'inscription</label>
                                    <input type="date" class="form-control @error('date_inscription') is-invalid @enderror"
                                        id="date_inscription" name="date_inscription"
                                        value="{{ old('date_inscription', $eleve->date_inscription ? $eleve->date_inscription : '') }}">
                                    @error('date_inscription')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Numéro d'immatriculation</label>
                                    <input type="text" class="form-control" value="{{ $eleve->registration_number }}"
                                        disabled>
                                    <small class="text-muted d-block mt-1">
                                        <i data-feather="lock" style="width: 14px; height: 14px;"></i> Ce numéro ne peut pas être modifié
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Résumé -->
                    <div class="card border-0 shadow mb-4 bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div>
                                        <i data-feather="user" class="me-2" style="width: 18px; height: 18px;"></i>
                                        <strong>Compte Utilisateur</strong>
                                    </div>
                                    <p class="mb-0 ms-4 text-muted">{{ $eleve->user->login ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <i data-feather="calendar" class="me-2" style="width: 18px; height: 18px;"></i>
                                        <strong>Inscriptions</strong>
                                    </div>
                                    <p class="mb-0 ms-4 text-muted">{{ $eleve->inscriptions->count() }} classe(s)</p>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <i data-feather="clock" class="me-2" style="width: 18px; height: 18px;"></i>
                                        <strong>Dernière modification</strong>
                                    </div>
                                    <p class="mb-0 ms-4 text-muted">{{ $eleve->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mb-5">
                        <button type="submit" class="btn btn-1">
                            <i data-feather="save" class="me-2"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('gestion_eleves.show', $eleve->id) }}" class="btn btn-dark">
                            <i data-feather="x" class="me-2"></i> Annuler
                        </a>
                    </div>
                </form>
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
        });
    </script>
@endsection
