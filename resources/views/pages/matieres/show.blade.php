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
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Détails de la Matière
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_matieres.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Messages -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Colonne gauche: Informations -->
            <div class="col-lg-8 mb-4">
                <!-- Informations Générales -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                         <h6 class="mb-0">
                            <i data-feather="info" class="me-2"></i>Informations Générales
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <p class="text-muted mb-1">Intitulé</p>
                                <p class="h5">{{ $matiere->intitule }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Couleur</p>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 40px; height: 40px; background-color: {{ $matiere->color ?? '#007bff' }}; border-radius: 6px; border: 2px solid #ddd;"></div>
                                    <span class="small">{{ $matiere->color ?? '#007bff' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Code</p>
                                <p class="h6">
                                    @if ($matiere->code)
                                        <span class="badge bg-light text-dark">{{ $matiere->code }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Établissement</p>
                                <p class="h6">{{ $matiere->etablissement->nom ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if ($matiere->description)
                            <div class="mb-3">
                                <p class="text-muted mb-1">Description</p>
                                <p class="h6">{{ $matiere->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Enseignements -->
                @if ($matiere->enseignementMatiereClasses->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="m-2">
                            <h6 class="mb-0">
                                <i data-feather="users" class="me-2"></i>Enseignements
                                <span class="badge bg-light text-dark ms-2">{{ $matiere->enseignementMatiereClasses->count() }}</span>
                            </h6>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Classe</th>
                                            <th>Enseignant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($matiere->enseignementMatiereClasses as $enseignement)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-info">{{ $enseignement->classe->nom ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    @if ($enseignement->enseignant)
                                                        {{ $enseignement->enseignant->prenom }} {{ $enseignement->enseignant->nom }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne droite: Actions -->
            <div class="col-lg-4 mb-4">
                <!-- Statut -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                         <h6 class="mb-0">
                            <i data-feather="activity" class="me-2"></i>Statut
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($matiere->is_active)
                            <div class="alert alert-success mb-0">
                                <i data-feather="check-circle" class="me-2"></i>
                                <strong>Matière active</strong>
                            </div>
                        @else
                            <div class="alert alert-danger mb-0"></div>
                                <i data-feather="x-circle" class="me-2"></i>
                                <strong>Matière inactive</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                         <h6 class="mb-0">
                            <i data-feather="settings" class="me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_matieres.edit', $matiere->id) }}" class="btn btn-dark btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i data-feather="trash-2" class="me-2"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Historique -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                         <h6 class="mb-0">
                            <i data-feather="clock" class="me-2"></i>Historique
                        </h6>
                    </div>
                    <div class="card-body small">
                        <div class="mb-2">
                            <p class="text-muted mb-1">Créé le</p>
                            <p class="h6">{{ $matiere->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Modifié le</p>
                            <p class="h6">{{ $matiere->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i data-feather="alert-triangle" class="me-2"></i>Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette matière ?</p>
                    <p class="text-muted"><strong>{{ $matiere->intitule }}</strong></p>
                    <p class="text-danger small">Cette action ne peut pas être annulée.</p>
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_matieres.destroy', $matiere->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-1">
                            <i data-feather="trash-2" class="me-2"></i>&nbsp; Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
