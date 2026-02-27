@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Gestion des Enseignements
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_enseignement_matieres.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter un enseignement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Enseignements total</p>
                            <h3 class="mb-0">{{ $enseignementMatiereClasses->total() }}</h3>
                        </div>
                        <i data-feather="book-open" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Années actives</p>
                            <h3 class="mb-0">{{ $anneesScolaires->count() }}</h3>
                        </div>
                        <i data-feather="calendar" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Classes</p>
                            <h3 class="mb-0">{{ $classes->count() }}</h3>
                        </div>
                        <i data-feather="users" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Enseignements page</p>
                            <h3 class="mb-0">{{ $enseignementMatiereClasses->count() }}</h3>
                        </div>
                        <i data-feather="list" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                @if ($enseignementMatiereClasses->count() > 0)
                    <div class="table-responsive">
                        <table id="datatablesSimple">
                            <thead class="table-light">
                                <tr>
                                    <th>Professeur</th>
                                    <th>Matière</th>
                                    <th>Classe</th>
                                    <th>Année</th>
                                    <th>Heures/Semaine</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enseignementMatiereClasses as $enseignement)
                                    <tr>
                                        <td>
                                            <strong>{{ $enseignement->enseignant->prenom }}
                                                {{ $enseignement->enseignant->nom }}</strong>
                                            <br>
                                            <small
                                                class="text-muted">{{ $enseignement->enseignant->numero_employe }}</small>
                                        </td>
                                        <td>
                                            <span class="badge"
                                                style="background-color: {{ $enseignement->matiere->color ?? '#007bff' }}; color: #fff;">
                                                {{ $enseignement->matiere->intitule }}
                                            </span>
                                        </td>
                                        <td>{{ $enseignement->classe->nom }}</td>
                                        <td>{{ $enseignement->anneeScolaire->libelle }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-light text-dark">{{ $enseignement->heure_par_semaine }}h</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('gestion_enseignement_matieres.show', $enseignement->id) }}"
                                                class="btn btn-sm btn-info" title="Voir">
                                                <i data-feather="eye" style="width: 1rem; height: 1rem;"></i>
                                            </a>
                                            <a href="{{ route('gestion_enseignement_matieres.edit', $enseignement->id) }}"
                                                class="btn btn-sm btn-warning" title="Modifier">
                                                <i data-feather="edit" style="width: 1rem; height: 1rem;"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $enseignement->id }}" title="Supprimer">
                                                <i data-feather="trash-2" style="width: 1rem; height: 1rem;"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $enseignement->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Confirmation de suppression</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer cet enseignement?</p>
                                                    <div class="alert alert-info">
                                                        <strong>{{ $enseignement->enseignant->prenom }}
                                                            {{ $enseignement->enseignant->nom }}</strong> -
                                                        <strong>{{ $enseignement->matiere->intitule }}</strong> -
                                                        <strong>{{ $enseignement->classe->nom }}</strong> -
                                                        <strong>{{ $enseignement->anneeScolaire->annee }}</strong>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <form
                                                        action="{{ route('gestion_enseignement_matieres.destroy', $enseignement->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $enseignementMatiereClasses->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i data-feather="inbox" style="width: 3rem; height: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p class="mb-0">Aucun enseignement trouvé</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
