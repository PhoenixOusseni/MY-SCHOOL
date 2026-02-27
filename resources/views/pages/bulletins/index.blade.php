@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Gestion des Bulletins
                        </h1>
                        <p class="text-muted">Consultez et gérez les bulletins des élèves</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_bulletins.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Nouveau bulletin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
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

        <div class="card">
            <div class="table-responsive p-3">
                <table id="datatablesSimple">
                    <thead class="table-light">
                        <tr>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Période</th>
                            <th>Moyenne</th>
                            <th>Rang</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulletins as $bulletin)
                            <tr>
                                <td>{{ $bulletin->eleve->nom ?? 'N/A' }} {{ $bulletin->eleve->prenom ?? '' }}</td>
                                <td>{{ $bulletin->classe->nom ?? 'N/A' }}</td>
                                <td>{{ $bulletin->periodEvaluation->libelle ?? 'N/A' }}</td>
                                <td>{{ $bulletin->moyenne_globale ?? '-' }}</td>
                                <td>
                                    @if($bulletin->rang && $bulletin->total_eleves)
                                        {{ $bulletin->rang }}/{{ $bulletin->total_eleves }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($bulletin->status === 'publie')
                                        <span class="badge bg-success">Publié</span>
                                    @elseif($bulletin->status === 'envoye')
                                        <span class="badge bg-info">Envoyé</span>
                                    @else
                                        <span class="badge bg-warning">Brouillon</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('gestion_bulletins.show', $bulletin->id) }}" class="text-danger" title="Voir">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucun bulletin disponible</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $bulletins->links() }}
            </div>
        </div>
    </div>
@endsection
