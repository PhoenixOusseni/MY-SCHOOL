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
                            <div class="page-header-icon"><i class="fas fa-plus-circle"></i></div>
                            Ajouter un Frais de Scolarité
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_frais_scolarite.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="m-2">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations du Frais
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form action="{{ route('gestion_frais_scolarite.store') }}" method="POST">
                            @csrf

                            <!-- Libellé -->
                            <div class="mb-3">
                                <label for="libelle" class="form-label fw-semibold">
                                    Libellé <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('libelle') is-invalid @enderror"
                                    id="libelle" name="libelle"
                                    placeholder="Ex: Inscription, Scolarité, Cantine..."
                                    value="{{ old('libelle') }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Montant -->
                                <div class="col-md-6 mb-3">
                                    <label for="montant" class="form-label fw-semibold">
                                        Montant <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('montant') is-invalid @enderror"
                                        id="montant" name="montant"
                                        placeholder="Ex: 50000"
                                        value="{{ old('montant') }}" required>
                                    @error('montant')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Devise -->
                                <div class="col-md-6 mb-3">
                                    <label for="devise" class="form-label fw-semibold">Devise</label>
                                    <select class="form-select @error('devise') is-invalid @enderror" id="devise" name="devise">
                                        <option value="XOF" {{ old('devise', 'XOF') == 'XOF' ? 'selected' : '' }}>XOF (Franc CFA)</option>
                                        <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                        <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                        <option value="GNF" {{ old('devise') == 'GNF' ? 'selected' : '' }}>GNF (Franc Guinéen)</option>
                                        <option value="MAD" {{ old('devise') == 'MAD' ? 'selected' : '' }}>MAD (Dirham)</option>
                                    </select>
                                    @error('devise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Fréquence -->
                                <div class="col-md-6 mb-3">
                                    <label for="frequence" class="form-label fw-semibold">
                                        Fréquence <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('frequence') is-invalid @enderror" id="frequence" name="frequence" required>
                                        <option value="" disabled selected>-- Sélectionner --</option>
                                        <option value="unique" {{ old('frequence') == 'unique' ? 'selected' : '' }}>Unique</option>
                                        <option value="mensuelle" {{ old('frequence') == 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                                        <option value="trimestrielle" {{ old('frequence') == 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                                        <option value="annuelle" {{ old('frequence') == 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                                    </select>
                                    @error('frequence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Établissement -->
                                <div class="col-md-6 mb-3">
                                    <label for="etablissement_id" class="form-label fw-semibold">
                                        Établissement <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('etablissement_id') is-invalid @enderror" id="etablissement_id" name="etablissement_id" required>
                                        <option value="" disabled selected>-- Sélectionner --</option>
                                        @foreach ($etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}"
                                                {{ old('etablissement_id') == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('etablissement_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Obligatoire -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="est_obligatoire" name="est_obligatoire"
                                        {{ old('est_obligatoire', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="est_obligatoire">
                                        Frais <strong>obligatoire</strong>
                                    </label>
                                </div>
                                <small class="text-muted">Si décoché, ce frais sera considéré comme facultatif.</small>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i class="fas fa-save me-1"></i> Enregistrer
                                </button>
                                <a href="{{ route('gestion_frais_scolarite.index') }}" class="btn btn-dark">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
