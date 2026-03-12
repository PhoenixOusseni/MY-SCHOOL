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
                            <div class="page-header-icon"><i class="fas fa-edit"></i></div>
                            Modifier un Paiement
                        </h1>
                        <p class="text-muted">
                            {{ $paiement->eleve->prenom ?? '' }} {{ $paiement->eleve->nom ?? '' }} —
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_paiements.show', $paiement->id) }}" class="btn btn-dark btn-sm">
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
                            <i class="fas fa-info-circle me-2"></i>Modifier les Informations
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <form action="{{ route('gestion_paiements.update', $paiement->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Élève -->
                            <div class="mb-3">
                                <label for="eleve_id" class="form-label fw-semibold">
                                    Élève <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('eleve_id') is-invalid @enderror" id="eleve_id" name="eleve_id" required>
                                    <option value="" disabled>-- Sélectionner un élève --</option>
                                    @foreach ($eleves as $eleve)
                                        <option value="{{ $eleve->id }}" {{ old('eleve_id', $paiement->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                            {{ $eleve->prenom }} {{ strtoupper($eleve->nom) }}
                                            ({{ $eleve->registration_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('eleve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <!-- Frais de scolarité -->
                                <div class="col-md-6 mb-3">
                                    <label for="frai_scolarite_id" class="form-label fw-semibold">
                                        Type de frais <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('frai_scolarite_id') is-invalid @enderror" id="frai_scolarite_id" name="frai_scolarite_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($frais as $frai)
                                            <option value="{{ $frai->id }}" {{ old('frai_scolarite_id', $paiement->frai_scolarite_id) == $frai->id ? 'selected' : '' }}>
                                                {{ $frai->libelle }} — {{ number_format($frai->montant, 0, ',', ' ') }} {{ $frai->devise }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('frai_scolarite_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Année scolaire -->
                                <div class="col-md-6 mb-3">
                                    <label for="annee_scolaire_id" class="form-label fw-semibold">
                                        Année scolaire <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('annee_scolaire_id') is-invalid @enderror" id="annee_scolaire_id" name="annee_scolaire_id" required>
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach ($annees as $annee)
                                            <option value="{{ $annee->id }}" {{ old('annee_scolaire_id', $paiement->annee_scolaire_id) == $annee->id ? 'selected' : '' }}>
                                                {{ $annee->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('annee_scolaire_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
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
                                        value="{{ old('montant', $paiement->montant) }}" required>
                                    @error('montant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Date de paiement -->
                                <div class="col-md-6 mb-3">
                                    <label for="date_paiement" class="form-label fw-semibold">
                                        Date de paiement <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('date_paiement') is-invalid @enderror"
                                        id="date_paiement" name="date_paiement"
                                        value="{{ old('date_paiement', $paiement->date_paiement->format('Y-m-d')) }}" required>
                                    @error('date_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Méthode -->
                                <div class="col-md-6 mb-3">
                                    <label for="methode_paiement" class="form-label fw-semibold">
                                        Méthode <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('methode_paiement') is-invalid @enderror" id="methode_paiement" name="methode_paiement" required>
                                        @foreach ($methodes as $key => $label)
                                            <option value="{{ $key }}" {{ old('methode_paiement', $paiement->methode_paiement) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('methode_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        Statut <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ old('status', $paiement->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Référence -->
                                <div class="col-md-6 mb-3">
                                    <label for="reference" class="form-label fw-semibold">Référence / N° reçu</label>
                                    <input type="text"
                                        class="form-control @error('reference') is-invalid @enderror"
                                        id="reference" name="reference"
                                        value="{{ old('reference', $paiement->reference) }}">
                                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Reçu par -->
                                <div class="col-md-6 mb-3">
                                    <label for="received_by" class="form-label fw-semibold">Reçu par</label>
                                    <select class="form-select @error('received_by') is-invalid @enderror" id="received_by" name="received_by">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('received_by', $paiement->received_by) == $user->id ? 'selected' : '' }}>
                                                {{ $user->prenom }} {{ $user->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('received_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-semibold">Notes / Observations</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                    id="notes" name="notes" rows="3">{{ old('notes', $paiement->notes) }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i class="fas fa-save me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
