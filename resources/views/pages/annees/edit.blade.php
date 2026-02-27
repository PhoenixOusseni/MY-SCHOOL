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
                            <div class="page-header-icon"><i data-feather="edit-2"></i></div>
                            Modifier l'année scolaire
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10">
        <!-- Messages d'alerte -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreurs:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_annees_scolaires.update', $anneeScolaire->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror"
                                    id="libelle" name="libelle" placeholder="Ex: 2025-2026"
                                    value="{{ old('libelle', $anneeScolaire->libelle) }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_debut" class="form-label">Date de début <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                        id="date_debut" name="date_debut"
                                        value="{{ old('date_debut', $anneeScolaire->date_debut->format('Y-m-d')) }}"
                                        required>
                                    @error('date_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_fin" class="form-label">Date de fin <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                        id="date_fin" name="date_fin"
                                        value="{{ old('date_fin', $anneeScolaire->date_fin->format('Y-m-d')) }}" required>
                                    @error('date_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="etablissement_id" class="form-label">Établissement <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                    id="etablissement_id" name="etablissement_id" required>
                                    <option value="">Sélectionner un établissement...</option>
                                    @foreach ($etablissements as $etablissement)
                                        <option value="{{ $etablissement->id }}"
                                            {{ old('etablissement_id', $anneeScolaire->etablissement_id) == $etablissement->id ? 'selected' : '' }}>
                                            {{ $etablissement->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('etablissement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_current" name="is_current"
                                        value="1"
                                        {{ old('is_current', $anneeScolaire->is_current) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_current">
                                        Année scolaire en cours
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"
                                        style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                    Mettre à jour
                                </button>
                                <a href="{{ route('gestion_annees_scolaires.show', $anneeScolaire->id) }}"
                                    class="btn btn-dark">
                                    <i data-feather="x"
                                        style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                    Annuler
                                </a>
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

            // Validation des dates
            $('form').on('submit', function(e) {
                const dateDebut = new Date($('#date_debut').val());
                const dateFin = new Date($('#date_fin').val());

                if (dateDebut >= dateFin) {
                    e.preventDefault();
                    alert('La date de fin doit être postérieure à la date de début');
                    return false;
                }
            });
        });
    </script>
@endsection
