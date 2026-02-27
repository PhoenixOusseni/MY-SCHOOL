@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Gestion des Notes
                        </h1>
                        <p class="text-muted">Créez et gérez les notes des élèves</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_notes.create') }}" class="btn btn-dark btn-sm">
                            <i data-feather="plus"></i>&nbsp; Ajouter une note
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
                            <p class="card-text mb-0">Notes total</p>
                            <h3 class="mb-0">{{ $notes->total() }}</h3>
                        </div>
                        <i data-feather="edit" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Notes saisies</p>
                            <h3 class="mb-0">{{ $notes->where('score', '!=', null)->count() }}</h3>
                        </div>
                        <i data-feather="check" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Absences</p>
                            <h3 class="mb-0">{{ $notes->where('is_absent', true)->count() }}</h3>
                        </div>
                        <i data-feather="user-x" style="width: 2rem; height: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text mb-0">Page actuelle</p>
                            <h3 class="mb-0">{{ $notes->count() }}</h3>
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
            <div class="table-responsive p-3">
                <table id="datatablesSimple">
                    <thead class="table-light">
                        <tr>
                            <th>Élève</th>
                            <th>Évaluation</th>
                            <th>Note</th>
                            <th>Note Max</th>
                            <th>Pourcentage</th>
                            <th>Absent</th>
                            <th>Saisi par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                            <tr>
                                <td>
                                    <strong>{{ $note->eleve->nom }} {{ $note->eleve->prenom }}</strong>
                                </td>
                                <td>
                                    {{ $note->evaluation->titre ?? 'N/A' }}
                                </td>
                                <td>
                                    @if($note->is_absent)
                                        <span class="badge bg-warning">Absent</span>
                                    @else
                                        <strong>{{ $note->score ?? 'N/A' }}</strong>
                                    @endif
                                </td>
                                <td>{{ $note->max_score }}</td>
                                <td>
                                    @if($note->percentage)
                                        <span class="badge bg-{{ $note->percentage >= 50 ? 'success' : 'danger' }}">
                                            {{ number_format($note->percentage, 2) }}%
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($note->is_absent)
                                        @if($note->absence_justified)
                                            <span class="badge bg-info">Absent justifié</span>
                                        @else
                                            <span class="badge bg-warning">Absent non justifié</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Présent</span>
                                    @endif
                                </td>
                                <td>
                                    @if($note->enteredBy)
                                        {{ $note->enteredBy->nom }} {{ $note->enteredBy->prenom }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-center">
                                        <a href="{{ route('gestion_notes.show', $note->id) }}" class="text-danger" title="Voir">
                                            <i data-feather="eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i data-feather="inbox" style="width: 3rem; height: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-2">Aucune note disponible</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-center">
                {{ $notes->links() }}
            </div>
        </div>
    </div>

@endsection
