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
                            Gestion des Soumissions de Devoirs
                        </h1>
                        <p class="text-muted">Suivi et évaluation des devoirs soumis par les élèves</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_soumissions.create') }}" class="btn btn-dark">
                            <i data-feather="plus"></i> Ajouter une soumission
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
                            <p class="card-text mb-0">Soumissions totales</p>
                            <h3 class="mb-0">{{ $soumissions->total() }}</h3>
                        </div>
                        <i data-feather="send" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Notées</p>
                            <h3 class="mb-0">{{ $soumissions->where('status', 'noté')->count() }}</h3>
                        </div>
                        <i data-feather="check-circle" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">En retard</p>
                            <h3 class="mb-0">{{ $soumissions->where('status', 'en retard')->count() }}</h3>
                        </div>
                        <i data-feather="alert-circle" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Page actuelle</p>
                            <h3 class="mb-0">{{ $soumissions->count() }}</h3>
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
                @if ($soumissions->count() > 0)
                    <div class="table-responsive">
                        <table id="datatablesSimple">
                            <thead class="table-light">
                                <tr>
                                    <th>Élève</th>
                                    <th>Devoir</th>
                                    <th>Date soumission</th>
                                    <th>Statut</th>
                                    <th>Note</th>
                                    <th>Feedback</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($soumissions as $soumission)
                                    <tr>
                                        <td>
                                            <strong>{{ $soumission->eleve->prenom ?? 'N/A' }}
                                                {{ $soumission->eleve->nom ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            @if ($soumission->devoir)
                                                <div class="small">
                                                    <strong>{{ $soumission->devoir->title ?? 'N/A' }}</strong>
                                                    @if ($soumission->devoir->enseignementMatiereClasse)
                                                        <br>
                                                        <span
                                                            class="badge bg-light text-dark">{{ $soumission->devoir->enseignementMatiereClasse->classe->nom ?? 'N/A' }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($soumission->date_submission)
                                                {{ \Carbon\Carbon::parse($soumission->date_submission)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($soumission->status === 'noté')
                                                <span class="badge bg-success">Noté</span>
                                            @elseif($soumission->status === 'soumis')
                                                <span class="badge bg-info">Soumis</span>
                                            @elseif($soumission->status === 'en retard')
                                                <span class="badge bg-warning text-dark">En retard</span>
                                            @else
                                                <span class="badge bg-secondary">En cours</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($soumission->score !== null)
                                                <strong
                                                    class="badge bg-light text-dark">{{ $soumission->score }}/{{ $soumission->devoir->note_max ?? '20' }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($soumission->feedback)
                                                <a href="#" class="badge bg-info" data-bs-toggle="tooltip"
                                                    title="{{ $soumission->feedback }}">
                                                    <i data-feather="message-square"
                                                        style="width: 0.9rem; height: 0.9rem;"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('gestion_soumissions.show', $soumission->id) }}"
                                                class="text-danger" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $soumissions->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i data-feather="inbox" style="width: 3rem; height: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p class="mb-0">Aucune soumission trouvée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
