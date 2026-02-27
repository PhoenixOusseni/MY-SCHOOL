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
                            <div class="page-header-icon"><i data-feather="calendar"></i></div>
                            Années scolaires
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
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
                <i data-feather="check-circle" style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
            <!-- Bloc de formulaire -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header bg-dark">
                        <h5 class="mb-0 text-white">
                            <i data-feather="plus-circle"
                                style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Ajouter une année
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gestion_annees_scolaires.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="libelle" class="small">Libellé <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror"
                                    id="libelle" name="libelle" placeholder="Ex: 2025-2026" value="{{ old('libelle') }}"
                                    required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_debut" class="small">Date de début <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                    id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_fin" class="small">Date de fin <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                    id="date_fin" name="date_fin" value="{{ old('date_fin') }}" required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="etablissement_id" class="small">Établissement <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                    id="etablissement_id" name="etablissement_id" required>
                                    <option value="" disabled selected>Sélectionner un établissement...</option>
                                    @forelse($etablissements as $etablissement)
                                        <option value="{{ $etablissement->id }}"
                                            {{ old('etablissement_id') == $etablissement->id ? 'selected' : '' }}>
                                            {{ $etablissement->nom }}
                                        </option>
                                    @empty
                                        <option disabled>Aucun établissement disponible</option>
                                    @endforelse
                                </select>
                                @error('etablissement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_current" name="is_current"
                                        value="1" {{ old('is_current') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_current">
                                        Année scolaire en cours
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-1 w-100">
                                <i data-feather="save"
                                    style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bloc de liste -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0 text-white">
                            <i data-feather="list"
                                style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Liste des années scolaires
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($annees->count() > 0)
                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Année</th>
                                            <th>Début</th>
                                            <th>Fin</th>
                                            <th>Établissement</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($annees as $annee)
                                            <tr>
                                                <td>
                                                    <strong>{{ $annee->libelle }}</strong>
                                                </td>
                                                <td>
                                                    <small
                                                        class="text-muted">{{ $annee->date_debut->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    <small
                                                        class="text-muted">{{ $annee->date_fin->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $annee->etablissement->nom ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @if ($annee->is_current)
                                                        <span class="badge bg-success">
                                                            <i data-feather="check"
                                                                style="width: 12px; height: 12px; display: inline;"></i>
                                                            En cours
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Archivée</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('gestion_annees_scolaires.show', $annee->id) }}"
                                                            class="btn btn-success" title="Voir">
                                                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger"
                                                            onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cette année ?')) document.getElementById('delete-form-{{ $annee->id }}').submit();"
                                                            title="Supprimer">
                                                            <i data-feather="trash-2"
                                                                style="width: 14px; height: 14px;"></i>
                                                        </a>
                                                    </div>
                                                    <form id="delete-form-{{ $annee->id }}"
                                                        action="{{ route('gestion_annees_scolaires.destroy', $annee->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                <i data-feather="info"
                                    style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                                <strong>Aucune année scolaire.</strong> Utilisez le formulaire à gauche pour en ajouter une.
                            </div>
                        @endif
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
