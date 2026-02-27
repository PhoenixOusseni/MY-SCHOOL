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
                            <div class="page-header-icon"><i data-feather="book-open"></i></div>
                            Gestion des classes
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
                            Ajouter une classe
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gestion_classes.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom"
                                    name="nom" placeholder="Ex: 6ème A" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="capacite" class="form-label">Capacité</label>
                                <input type="number" class="form-control @error('capacite') is-invalid @enderror"
                                    id="capacite" name="capacite" placeholder="Ex: 50" value="{{ old('capacite', 50) }}"
                                    min="1" max="500">
                                @error('capacite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="salle" class="form-label">Salle</label>
                                <input type="text" class="form-control @error('salle') is-invalid @enderror"
                                    id="salle" name="salle" placeholder="Ex: A101" value="{{ old('salle') }}">
                                @error('salle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="niveau_id" class="form-label">Niveau</label>
                                <select class="form-select @error('niveau_id') is-invalid @enderror" id="niveau_id"
                                    name="niveau_id">
                                    <option value="">Tous les niveaux</option>
                                    @forelse($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}"
                                            {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                            {{ $niveau->nom }}
                                        </option>
                                    @empty
                                        <option disabled>Aucun niveau disponible</option>
                                    @endforelse
                                </select>
                                @error('niveau_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="etablissement_id" class="form-label">Établissement</label>
                                <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                    id="etablissement_id" name="etablissement_id">
                                    <option value="">Tous les établissements</option>
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
                                <label for="annee_scolaire_id" class="form-label">Année scolaire</label>
                                <select class="form-select @error('annee_scolaire_id') is-invalid @enderror"
                                    id="annee_scolaire_id" name="annee_scolaire_id">
                                    <option value="">Toutes les années</option>
                                    @forelse($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}"
                                            {{ old('annee_scolaire_id') == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->libelle }}
                                        </option>
                                    @empty
                                        <option disabled>Aucune année disponible</option>
                                    @endforelse
                                </select>
                                @error('annee_scolaire_id')
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
                    <div class="card-header bg-dark">
                        <h5 class="mb-0 text-white">
                            <i data-feather="list"
                                style="width: 18px; height: 18px; display: inline; margin-right: 8px;"></i>
                            Liste des classes
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($classes->count() > 0)
                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Classe</th>
                                            <th>Niveau</th>
                                            <th>Année</th>
                                            <th>Établissement</th>
                                            <th>Capacité</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $classe)
                                            <tr>
                                                <td>
                                                    <strong>{{ $classe->nom }}</strong>
                                                    @if ($classe->salle)
                                                        <br><small class="text-muted">Salle: {{ $classe->salle }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $classe->niveau->nom ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $classe->anneeScolaire->libelle ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $classe->etablissement->nom ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $classe->capacite }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('gestion_classes.show', $classe->id) }}"
                                                            class="btn btn-1" title="Voir">
                                                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                                        </a>
                                                    </div>
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
                                <strong>Aucune classe.</strong> Utilisez le formulaire à gauche pour en ajouter une.
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
        });
    </script>
@endsection
