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
                            Modifier la classe
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
                        <form action="{{ route('gestion_classes.update', $classe->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       placeholder="Ex: 6ème A" 
                                       value="{{ old('nom', $classe->nom) }}"
                                       required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="capacite" class="form-label">Capacité</label>
                                    <input type="number" 
                                           class="form-control @error('capacite') is-invalid @enderror" 
                                           id="capacite" 
                                           name="capacite" 
                                           placeholder="Ex: 50" 
                                           value="{{ old('capacite', $classe->capacite) }}"
                                           min="1"
                                           max="500">
                                    @error('capacite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="salle" class="form-label">Salle</label>
                                    <input type="text" 
                                           class="form-control @error('salle') is-invalid @enderror" 
                                           id="salle" 
                                           name="salle" 
                                           placeholder="Ex: A101" 
                                           value="{{ old('salle', $classe->salle) }}">
                                    @error('salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="niveau_id" class="form-label">Niveau</label>
                                    <select class="form-select @error('niveau_id') is-invalid @enderror" 
                                            id="niveau_id" 
                                            name="niveau_id">
                                        <option value="">Tous les niveaux</option>
                                        @foreach($niveaux as $niveau)
                                            <option value="{{ $niveau->id }}" 
                                                    {{ old('niveau_id', $classe->niveau_id) == $niveau->id ? 'selected' : '' }}>
                                                {{ $niveau->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('niveau_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="etablissement_id" class="form-label">Établissement</label>
                                    <select class="form-select @error('etablissement_id') is-invalid @enderror" 
                                            id="etablissement_id" 
                                            name="etablissement_id">
                                        <option value="">Tous les établissements</option>
                                        @foreach($etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}" 
                                                    {{ old('etablissement_id', $classe->etablissement_id) == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('etablissement_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire</label>
                                <select class="form-select @error('annee_scolaire_id') is-invalid @enderror" 
                                        id="annee_scolaire_id" 
                                        name="annee_scolaire_id">
                                    <option value="">Toutes les années</option>
                                    @foreach($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}" 
                                                {{ old('annee_scolaire_id', $classe->annee_scolaire_id) == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_scolaire_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
                                    Mettre à jour
                                </button>
                                <a href="{{ route('gestion_classes.index') }}" class="btn btn-secondary">
                                    <i data-feather="x" style="width: 16px; height: 16px; display: inline; margin-right: 8px;"></i>
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
        });
    </script>
@endsection
