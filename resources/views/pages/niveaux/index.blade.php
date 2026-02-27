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
                            <div class="page-header-icon"><i data-feather="layers"></i></div>
                            Niveaux scolaires
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
                            Ajouter un niveau
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gestion_niveaux.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="small">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" name="code" placeholder="Ex: 6EME" value="{{ old('code') }}"
                                    required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nom" class="small">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom"
                                    name="nom" placeholder="Ex: 6ème" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="order_index" class="small">Ordre d'affichage <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror"
                                    id="order_index" name="order_index" placeholder="Ex: 1" value="{{ old('order_index') }}"
                                    required>
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="etablissement_id" class="small">Établissement</label>
                                <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                    id="etablissement_id" name="etablissement_id">
                                    <option value="" selected>Tous les établissements</option>
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
                            Liste des niveaux
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($niveaux->count() > 0)
                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Code</th>
                                            <th>Nom</th>
                                            <th>Ordre</th>
                                            <th>Établissement</th>
                                            <th>Classes</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($niveaux as $niveau)
                                            <tr>
                                                <td>
                                                    <strong>{{ $niveau->code }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ $niveau->nom }}</strong>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-light text-dark">{{ $niveau->order_index }}</span>
                                                </td>
                                                <td>
                                                    <small>{{ $niveau->etablissement->nom ?? 'Tous' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $niveau->classes->count() }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('gestion_niveaux.show', $niveau->id) }}"
                                                            class="btn btn-1" title="Voir">
                                                            <i data-feather="eye"
                                                                style="width: 14px; height: 14px;"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger"
                                                            onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?')) document.getElementById('delete-form-{{ $niveau->id }}').submit();"
                                                            title="Supprimer">
                                                            <i data-feather="trash-2"
                                                                style="width: 14px; height: 14px;"></i>
                                                        </a>
                                                    </div>
                                                    <form id="delete-form-{{ $niveau->id }}"
                                                        action="{{ route('gestion_niveaux.destroy', $niveau->id) }}"
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
                                <strong>Aucun niveau.</strong> Utilisez le formulaire à gauche pour en ajouter un.
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
