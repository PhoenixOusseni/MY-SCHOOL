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
                            Gestion des Inscriptions
                        </h1>
                        <p class="mt-2 mb-0 text-white-75">
                            Gérez les inscriptions des élèves aux classes
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_inscriptions.create') }}" class="btn btn-light btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter une inscription
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10 mb-5">
        <!-- Messages d'alerte -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i data-feather="check-circle" class="me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading mb-0">Succès</h6>
                        {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i data-feather="alert-circle" class="me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading mb-0">Erreur</h6>
                        {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-lg-3 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-1 text-muted small">Total Inscriptions</p>
                                <h4 class="mb-0">{{ $inscriptions->count() }}</h4>
                            </div>
                            <div>
                                <i data-feather="book-open" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-1 text-muted small">Élèves Inscrits</p>
                                <h4 class="mb-0">{{ $inscriptions->pluck('eleve_id')->unique()->count() }}</h4>
                            </div>
                            <div>
                                <i data-feather="users" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-1 text-muted small">Classes</p>
                                <h4 class="mb-0">{{ $inscriptions->pluck('classe_id')->unique()->count() }}</h4>
                            </div>
                            <div>
                                <i data-feather="layers" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-1 text-muted small">Années Scolaires</p>
                                <h4 class="mb-0">{{ $inscriptions->pluck('annee_scolaire_id')->unique()->count() }}</h4>
                            </div>
                            <div>
                                <i data-feather="calendar" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des inscriptions -->
        <div class="card border-0 shadow">
            <div class="m-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">
                        <i data-feather="list" class="me-2"></i> Liste des Inscriptions
                    </h6>
                    <span class="badge bg-light text-primary">{{ $inscriptions->count() }}</span>
                </div>
            </div>
            <hr>
            <div class="card-body">
                @if ($inscriptions->count() > 0)
                    <div class="table-responsive">
                        <table id="datatablesSimple">
                            <thead class="table-light">
                                <tr>
                                    <th>Élève</th>
                                    <th>N° Matricule</th>
                                    <th>Classe</th>
                                    <th>Année Scolaire</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inscriptions as $inscription)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                                    style="width: 40px; height: 40px; font-weight: bold;">
                                                    {{ substr($inscription->eleve->prenom, 0, 1) }}{{ substr($inscription->eleve->nom, 0, 1) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $inscription->eleve->prenom }}
                                                        {{ $inscription->eleve->nom }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $inscription->eleve->registration_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $inscription->eleve->registration_number }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">
                                                {{ $inscription->classe->nom ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $inscription->anneeScolaire->libelle ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            {{ $inscription->created_at ? $inscription->created_at->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('gestion_inscriptions.show', $inscription->id) }}"
                                                class="text-danger" title="Voir l'inscription">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0" role="alert">
                        <div class="d-flex align-items-start">
                            <i data-feather="info" class="me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Aucune inscription</h6>
                                <p class="mb-0">Il n'y a pas encore d'inscription enregistrée.
                                    <a href="{{ route('gestion_inscriptions.create') }}" class="alert-link">Créer une
                                        inscription</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
