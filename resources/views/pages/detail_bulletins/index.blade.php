@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="layers"></i></div>
                            Détails des bulletins
                        </h1>
                        <p class="text-muted">Gestion des résultats par matière dans chaque bulletin</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_detail_bulletins.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Nouveau détail
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
                            <th>Période</th>
                            <th>Matière</th>
                            <th>Moyenne</th>
                            <th>Coeff.</th>
                            <th>Rang</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $detail)
                            <tr>
                                <td>{{ $detail->bulletin->eleve->nom ?? 'N/A' }} {{ $detail->bulletin->eleve->prenom ?? '' }}</td>
                                <td>{{ $detail->bulletin->periodEvaluation->libelle ?? 'N/A' }}</td>
                                <td>{{ $detail->matiere->intitule ?? 'N/A' }}</td>
                                <td>{{ $detail->moyenne ?? '-' }}</td>
                                <td>{{ $detail->coefficient ?? '-' }}</td>
                                <td>{{ $detail->rang ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('gestion_detail_bulletins.show', $detail->id) }}" class="text-danger" title="Voir">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucun détail de bulletin</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $details->links() }}
            </div>
        </div>
    </div>
@endsection
