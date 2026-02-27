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
                            <div class="page-header-icon"><i class="fas fa-user-plus"></i></div>
                            Nouvel Utilisateur
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_utilisateurs.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Erreurs de validation :</strong>
                <ul class="mb-0 mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations personnelles</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('gestion_utilisateurs.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom') }}" placeholder="Ex: DUPONT" required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                        value="{{ old('prenom') }}" placeholder="Ex: Jean" required>
                                    @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="email@exemple.com" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                        value="{{ old('telephone') }}" placeholder="Ex: +225 07 00 00 00">
                                    @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Rôle</label>
                                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                        <option value="">-- Aucun rôle --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1"
                                            {{ old('actif', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="actif">Compte actif</label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3"><i class="fas fa-lock me-2 text-muted"></i>Mot de passe</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Minimum 8 caractères" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control" placeholder="Répéter le mot de passe" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Notes / Remarques</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                    rows="3" placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i class="fas fa-save me-1"></i> Créer l'utilisateur
                                </button>
                                <a href="{{ route('gestion_utilisateurs.index') }}" class="btn btn-outline-secondary">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm border-start-5 border-start-info">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Informations</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="fas fa-key me-2 text-primary"></i>Le <strong>login</strong> est généré automatiquement (1ère lettre prénom + nom).</li>
                            <li class="mb-2"><i class="fas fa-shield-alt me-2 text-success"></i>Le mot de passe doit contenir au moins <strong>8 caractères</strong>.</li>
                            <li class="mb-2"><i class="fas fa-tag me-2 text-info"></i>Le rôle détermine les <strong>permissions</strong> de l'utilisateur.</li>
                            <li><i class="fas fa-toggle-on me-2 text-warning"></i>Un compte <strong>inactif</strong> ne peut pas se connecter.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
