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
                            Gestion des Devoirs
                        </h1>
                        <p class="text-muted">Créez et gérez les devoirs des classes</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_devoirs.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter un devoir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Devoirs total</p>
                            <h3 class="mb-0">{{ $devoirs->total() }}</h3>
                        </div>
                        <i data-feather="book-open" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">À noter</p>
                            <h3 class="mb-0">{{ $devoirs->where('est_note', true)->count() }}</h3>
                        </div>
                        <i data-feather="star" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Avec pièce jointe</p>
                            <h3 class="mb-0">{{ $devoirs->whereNotNull('attachment')->count() }}</h3>
                        </div>
                        <i data-feather="paperclip" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Page actuelle</p>
                            <h3 class="mb-0">{{ $devoirs->count() }}</h3>
                        </div>
                        <i data-feather="list" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                @if ($devoirs->count() > 0)
                    <div class="table-responsive">
                        <table id="datatablesSimple">
                            <thead class="table-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Classe/Matière</th>
                                    <th>Type</th>
                                    <th>Échéance</th>
                                    <th>Noté</th>
                                    <th>Pièce jointe</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($devoirs as $devoir)
                                    <tr>
                                        <td>
                                            <strong>{{ $devoir->title }}</strong>
                                        </td>
                                        <td>
                                            @if ($devoir->enseignementMatiereClasse)
                                                <div class="small">
                                                    <span
                                                        class="badge bg-light text-dark">{{ $devoir->enseignementMatiereClasse->classe->nom ?? 'N/A' }}</span>
                                                    <span class="badge"
                                                        style="background-color: {{ $devoir->enseignementMatiereClasse->matiere->color ?? '#007bff' }}; color: #fff;">
                                                        {{ $devoir->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $devoir->type }}</span>
                                        </td>
                                        <td>
                                            @if ($devoir->date_echeance)
                                                <strong>{{ \Carbon\Carbon::parse($devoir->date_echeance)->format('d/m/Y') }}</strong>
                                                @if (\Carbon\Carbon::parse($devoir->date_echeance)->isPast())
                                                    <br>
                                                    <small class="text-danger">Expiré</small>
                                                @elseif(\Carbon\Carbon::parse($devoir->date_echeance)->diffInDays() <= 3)
                                                    <br>
                                                    <small class="text-warning">Urgent</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($devoir->est_note)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($devoir->attachment)
                                                <a href="{{ asset('storage/' . $devoir->attachment) }}" target="_blank"
                                                    class="badge bg-warning text-dark" title="Télécharger">
                                                    <i data-feather="download" style="width: 0.9rem; height: 0.9rem;"></i>
                                                    Fichier
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('gestion_devoirs.show', $devoir->id) }}" class="text-danger" title="Voir les détails">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $devoir->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Confirmation de suppression</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer ce devoir?</p>
                                                    <div class="alert alert-info">
                                                        <strong>{{ $devoir->title }}</strong>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('gestion_devoirs.destroy', $devoir->id) }}"
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
                        {{ $devoirs->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i data-feather="inbox" style="width: 3rem; height: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p class="mb-0">Aucun devoir trouvé</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
