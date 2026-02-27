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
                            <div class="page-header-icon"><i class="fas fa-user-edit"></i></div>
                            Modifier l'Utilisateur
                        </h1>
                        <p class="page-header-subtitle text-white-50">
                            {{ $user->prenom }} {{ strtoupper($user->nom) }} — <code class="text-white-50">{{ $user->login }}</code>
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_utilisateurs.show', $user->id) }}" class="btn btn-light btn-sm">
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
                <i class="fas fa-exclamation-circle me-2"></i><strong>Erreurs :</strong>
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
                        <form action="{{ route('gestion_utilisateurs.update', $user->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom', $user->nom) }}" required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                        value="{{ old('prenom', $user->prenom) }}" required>
                                    @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control"
                                        value="{{ old('telephone', $user->telephone) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Rôle</label>
                                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                        <option value="">-- Aucun rôle --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                {{ $role->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end pb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1"
                                            {{ old('actif', $user->actif) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="actif">Compte actif</label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-1"><i class="fas fa-lock me-2 text-muted"></i>Changer le mot de passe</h6>
                            <p class="text-muted small mb-3">Laisser vide pour conserver le mot de passe actuel.</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nouveau mot de passe</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Minimum 8 caractères">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Répéter le nouveau mot de passe">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Notes / Remarques</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $user->notes) }}</textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i class="fas fa-save me-1"></i>&nbsp; Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fas fa-id-card me-2 text-muted"></i>Login système</h6>
                        <div class="bg-light rounded p-3 text-center">
                            <code class="fs-5">{{ $user->login }}</code>
                            <p class="text-muted small mb-0 mt-1">Le login ne peut pas être modifié</p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm border-start-5 border-start-warning">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Attention</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">Désactiver un compte empêche la connexion immédiatement.</li>
                            <li>Changer le rôle modifie instantanément les permissions de l'utilisateur.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
