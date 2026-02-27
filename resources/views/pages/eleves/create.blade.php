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
                            Gestion des élèves - Ajouter un nouvel élève
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_eleves.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10">
        <!-- Messages d'alerte -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
                <strong>Erreurs:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('gestion_eleves.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="mb-0">
                                <i data-feather="user"></i>
                                Informations Personnelles
                            </h5>
                            <hr>
                            <!-- Section: Identité -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                        id="nom" name="nom" placeholder="Ex: Dupont" value="{{ old('nom') }}"
                                        required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                        id="prenom" name="prenom" placeholder="Ex: Jean" value="{{ old('prenom') }}"
                                        required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="genre" class="form-label">Genre</label>
                                    <select class="form-select @error('genre') is-invalid @enderror" id="genre"
                                        name="genre">
                                        <option value="">Sélectionner...</option>
                                        <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                                        <option value="Autres" {{ old('genre') == 'Autres' ? 'selected' : '' }}>Autres
                                        </option>
                                    </select>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_naissance" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror"
                                        id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}">
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror"
                                        id="lieu_naissance" name="lieu_naissance" placeholder="Ex: Paris"
                                        value="{{ old('lieu_naissance') }}">
                                    @error('lieu_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="nationalite" class="form-label">Nationalité</label>
                                    <input type="text" class="form-control @error('nationalite') is-invalid @enderror"
                                        id="nationalite" name="nationalite" placeholder="Ex: Française"
                                        value="{{ old('nationalite') }}">
                                    @error('nationalite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                        id="adresse" name="adresse" placeholder="Ex: 123 rue de la République"
                                        value="{{ old('adresse') }}">
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                                    <select class="form-select @error('groupe_sanguin') is-invalid @enderror"
                                        id="groupe_sanguin" name="groupe_sanguin">
                                        <option value="">Sélectionner...</option>
                                        <option value="O+" {{ old('groupe_sanguin') == 'O+' ? 'selected' : '' }}>O+
                                        </option>
                                        <option value="O-" {{ old('groupe_sanguin') == 'O-' ? 'selected' : '' }}>O-
                                        </option>
                                        <option value="A+" {{ old('groupe_sanguin') == 'A+' ? 'selected' : '' }}>A+
                                        </option>
                                        <option value="A-" {{ old('groupe_sanguin') == 'A-' ? 'selected' : '' }}>A-
                                        </option>
                                        <option value="B+" {{ old('groupe_sanguin') == 'B+' ? 'selected' : '' }}>B+
                                        </option>
                                        <option value="B-" {{ old('groupe_sanguin') == 'B-' ? 'selected' : '' }}>B-
                                        </option>
                                        <option value="AB+" {{ old('groupe_sanguin') == 'AB+' ? 'selected' : '' }}>AB+
                                        </option>
                                        <option value="AB-" {{ old('groupe_sanguin') == 'AB-' ? 'selected' : '' }}>AB-
                                        </option>
                                    </select>
                                    @error('groupe_sanguin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                        id="telephone" name="telephone" placeholder="Ex: +33 1 23 45 67 89"
                                        value="{{ old('telephone') }}">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes_medicales" class="form-label">Notes médicales</label>
                                <textarea class="form-control @error('notes_medicales') is-invalid @enderror" id="notes_medicales"
                                    name="notes_medicales" rows="2" placeholder="Ex: Allergies, problèmes de santé...">{{ old('notes_medicales') }}</textarea>
                                @error('notes_medicales')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <!-- Section: Compte utilisateur -->
                            <h6 class="mb-3">
                                <i data-feather="lock"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                Compte Utilisateur
                            </h6>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="exemple@domaine.com"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="login" class="form-label">Identifiant de connexion <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('login') is-invalid @enderror"
                                        id="login" name="login" placeholder="Ex: jean.dupont"
                                        value="{{ old('login') }}" required>
                                    @error('login')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mot de passe <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Minimum 8 caractères" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation"
                                        placeholder="Confirmez le mot de passe" required>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <!-- Section: Informations scolaires -->
                            <h6 class="mb-3">
                                <i data-feather="book-open"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                Informations Scolaires
                            </h6>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="date_inscription" class="form-label">Date d'inscription</label>
                                    <input type="date"
                                        class="form-control @error('date_inscription') is-invalid @enderror"
                                        id="date_inscription" name="date_inscription"
                                        value="{{ old('date_inscription', today()->format('Y-m-d')) }}">
                                    @error('date_inscription')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-info mb-0" role="alert">
                                        <small>
                                            <i data-feather="info" style="width: 14px; height: 14px; display: inline; margin-right: 4px;"></i>
                                            <strong>Numéro d'immatriculation:</strong> Généré automatiquement au format EL2026/00001
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select @error('statut') is-invalid @enderror" id="statut"
                                        name="statut">
                                        <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>
                                            Actif</option>
                                        <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>
                                            Suspendu</option>
                                        <option value="diplome" {{ old('statut') == 'diplome' ? 'selected' : '' }}>Diplômé
                                        </option>
                                        <option value="abandonne" {{ old('statut') == 'abandonne' ? 'selected' : '' }}>
                                            Abandonné</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="etablissement_id" class="form-label">Établissement</label>
                                    <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                        id="etablissement_id" name="etablissement_id">
                                        <option value="">Sélectionner un établissement</option>
                                        @if (isset($etablissements))
                                            @foreach ($etablissements as $etablissement)
                                                <option value="{{ $etablissement->id }}"
                                                    {{ old('etablissement_id') == $etablissement->id ? 'selected' : '' }}>
                                                    {{ $etablissement->nom }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('etablissement_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save"
                                            style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                        Enregistrer l'élève
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Réinitialiser les icônes Feather
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
