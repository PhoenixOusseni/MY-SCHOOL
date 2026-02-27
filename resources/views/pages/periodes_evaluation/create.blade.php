@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                            Ajouter une période d'évaluation
                        </h1>
                        <p class="text-muted">Créez une nouvelle période de notation et d'évaluation</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_periodes_evaluation.index') }}" class="btn btn-dark">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container-xl px-4 mt-n10">
        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_periodes_evaluation.store') }}" method="POST" id="periodeForm">
                            @csrf

                            <!-- Libellé -->
                            <div class="mb-3">
                                <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror"
                                    id="libelle" name="libelle" value="{{ old('libelle') }}"
                                    placeholder="ex: Trimestre 1, Semestre 1" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    @foreach ($types as $value => $label)
                                        <option value="{{ $value }}" @selected(old('type') == $value)>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Année scolaire -->
                            <div class="mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('annee_scolaire_id') is-invalid @enderror"
                                    id="annee_scolaire_id" name="annee_scolaire_id" required>
                                    <option value="">Sélectionner une année</option>
                                    @foreach ($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}" @selected(old('annee_scolaire_id') == $annee->id)>
                                            {{ $annee->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_scolaire_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date début -->
                            <div class="mb-3">
                                <label for="date_debut" class="form-label">Date de début <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                    id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date fin -->
                            <div class="mb-3">
                                <label for="date_fin" class="form-label">Date de fin <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                    id="date_fin" name="date_fin" value="{{ old('date_fin') }}" required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ordre d'affichage -->
                            <div class="mb-3">
                                <label for="order_index" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror"
                                    id="order_index" name="order_index" value="{{ old('order_index') }}"
                                    placeholder="ex: 1, 2, 3..." min="1">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Optionnel - utilisé pour trier les périodes</small>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&nbsp; Enregistrer
                                </button>
                                <a href="{{ route('gestion_periodes_evaluation.index') }}" class="btn btn-dark">
                                    <i data-feather="x"></i>&nbsp; Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <strong>Erreurs de validation:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Information</h5>
                        <p class="card-text small">
                            Cette page permet de créer une nouvelle période d'évaluation pour une année scolaire.
                        </p>
                        <hr>
                        <p class="card-text small mb-0">
                            <strong>Types disponibles:</strong>
                        <ul class="mb-0 ps-3">
                            <li><strong>Trimestre:</strong> Période de 3 mois</li>
                            <li><strong>Semestre:</strong> Période de 6 mois</li>
                            <li><strong>Quart:</strong> Période de 3 mois</li>
                        </ul>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Form validation
        document.getElementById('periodeForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>
@endsection
