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
                            <div class="page-header-icon"><i data-feather="user-plus"></i></div>
                            Ajouter un Enseignant
                        </h1>
                        <p class="text-muted">Remplissez le formulaire ci-dessous pour ajouter un nouvel enseignant à l'établissement</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignants.index') }}" class="btn btn-dark btn-sm">
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
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations de l'Enseignant
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form id="enseignantForm" action="{{ route('gestion_enseignants.store') }}" method="POST">
                            @csrf

                            <!-- Établissement -->
                            <div class="mb-3">
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

                            <!-- Genre et Date de naissance -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sexe" class="form-label">Genre</label>
                                    <select class="form-select" id="sexe" name="sexe">
                                        <option value="" selected>-- Sélectionner --</option>
                                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                        <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_naissance" class="form-label">Date de Naissance</label>
                                    <input type="date" class="form-control" id="date_naissance" name="date_naissance"
                                        value="{{ old('date_naissance') }}">
                                </div>
                            </div>

                            <!-- Téléphone et Email -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone"
                                        value="{{ old('telephone') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}">
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-control" id="adresse" name="adresse" rows="2">{{ old('adresse') }}</textarea>
                            </div>

                            <!-- Qualification et Spécialisation -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="qualification" class="form-label">Qualification/Diplômes</label>
                                    <input type="text" class="form-control" id="qualification" name="qualification"
                                        value="{{ old('qualification') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="specialisation" class="form-label">Spécialisation</label>
                                    <input type="text" class="form-control" id="specialisation" name="specialisation"
                                        value="{{ old('specialisation') }}">
                                </div>
                            </div>

                            <!-- Date d'embauche -->
                            <div class="mb-3">
                                <label for="date_embauche" class="form-label">Date d'Embauche</label>
                                <input type="date" class="form-control" id="date_embauche" name="date_embauche"
                                    value="{{ old('date_embauche') }}">
                            </div>

                            <!-- Statut -->
                            <div class="mb-4">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select" id="statut" name="statut">
                                    <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>Actif
                                    </option>
                                    <option value="en_conge" {{ old('statut') == 'en_conge' ? 'selected' : '' }}>En congé
                                    </option>
                                    <option value="retraite" {{ old('statut') == 'retraite' ? 'selected' : '' }}>Retraite
                                    </option>
                                    <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>Terminé
                                    </option>
                                </select>
                            </div>

                            <!-- Section Création de Compte Utilisateur -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="create_account" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create_account">
                                        <strong>Créer un compte utilisateur pour cet enseignant</strong>
                                    </label>
                                </div>
                            </div>

                            <!-- Account Fields -->
                            <div id="accountSection" style="display: {{ old('create_account') ? 'block' : 'none' }};">
                                <div class="alert alert-info mb-3">
                                    <i data-feather="info" class="me-2"></i>
                                    Créez un compte pour permettre à cet enseignant de se connecter au système
                                </div>

                                <!-- Email et Rôle -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="account_email" class="form-label">Email du Compte <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="account_email" name="account_email"
                                            value="{{ old('account_email') }}" placeholder="exemple@ecole.com">
                                        <small class="text-muted">Sera utilisé pour la connexion</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="role_id" class="form-label">Rôle <span class="text-danger">*</span></label>
                                        <select class="form-select" id="role_id" name="role_id">
                                            <option value="" selected disabled>-- Sélectionner un rôle --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="login" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="login" name="login" placeholder="Nom d'utilisateur">
                                        <small class="text-muted">Le nom d'utilisateur sera utilisé pour la connexion</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="account_password" class="form-label">Mot de Passe <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="account_password" name="account_password" placeholder="Minimum 8 caractères">
                                        <small class="text-muted">Le mot de passe doit contenir au moins 8 caractères</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
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
                <!-- Notes importantes -->
                <div class="alert alert-info" role="alert">
                    <div class="d-flex">
                        <i data-feather="alert-circle" class="me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Informations importantes</h6>
                            <ul class="mb-0 ps-3">
                                <li>Le numéro d'emploi est généré automatiquement</li>
                                <li>Nom et prénom sont obligatoires</li>
                                <li>L'établissement est obligatoire</li>
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
            feather.replace();

            // Toggle account section visibility
            const createAccountCheckbox = document.getElementById('create_account');
            const accountSection = document.getElementById('accountSection');
            const accountEmailInput = document.getElementById('account_email');
            const roleIdSelect = document.getElementById('role_id');
            const accountPasswordInput = document.getElementById('account_password');

            createAccountCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    accountSection.style.display = 'block';
                    accountEmailInput.required = true;
                    roleIdSelect.required = true;
                    accountPasswordInput.required = true;
                } else {
                    accountSection.style.display = 'none';
                    accountEmailInput.required = false;
                    roleIdSelect.required = false;
                    accountPasswordInput.required = false;
                }
            });

            // Validation du formulaire
            document.getElementById('enseignantForm').addEventListener('submit', function(e) {
                const nom = document.getElementById('nom').value.trim();
                const prenom = document.getElementById('prenom').value.trim();
                const etablissementId = document.getElementById('etablissement_id').value;
                const createAccount = document.getElementById('create_account').checked;

                if (!nom) {
                    e.preventDefault();
                    alert('Veuillez saisir le nom');
                    return false;
                }

                if (!prenom) {
                    e.preventDefault();
                    alert('Veuillez saisir le prénom');
                    return false;
                }

                if (!etablissementId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un établissement');
                    return false;
                }

                if (createAccount) {
                    const email = document.getElementById('account_email').value.trim();
                    const password = document.getElementById('account_password').value.trim();
                    const roleId = document.getElementById('role_id').value;

                    if (!email) {
                        e.preventDefault();
                        alert('Veuillez saisir l\'email du compte');
                        return false;
                    }

                    if (!password || password.length < 8) {
                        e.preventDefault();
                        alert('Le mot de passe doit contenir minimum 8 caractères');
                        return false;
                    }

                    if (!roleId) {
                        e.preventDefault();
                        alert('Veuillez sélectionner un rôle');
                        return false;
                    }
                }
            });
        });
    </script>
@endsection
